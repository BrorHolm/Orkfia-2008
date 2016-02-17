<?php
//******************************************************************************
// Functions forums.php                                        ORKFiA, 2001-2007
//
// History:
// Made the forum code a little more dynamic, constants.php now
// controls the number of posts that fit in a thread, not the lazyness of
// the coder                                                       - AI 30/10/06
// Adding back the ingame world forums, shouldn't be too much work
//                                                                 - AI 31/10/06
// Major over-haul and rewriting most functions with the server move
//                                                       October 15, 2007 Martel
//******************************************************************************

/*
    Level: 0 = Player
    Level: 1 = Player
    Level: 2 = Elder / WTB Member / Co-elder(?)
    Level: 3 = Resort Member
    Level: 5 = Head of a Resort
    Level: 6 = Head of Orkfia

    "Non-dynamic / Fixed" Forums
    Posttype/Set: 0/alliance = Alliance
    Posttype/Set: 1/staff101 = Staff
    Posttype/Set: 2/world    = World
    Posttype/Set: 3/news     = Announcements
    Posttype/Set: 4/lno      = Law & Order
    Posttype/Set: 5/ops      = Operations
    Posttype/Set: 6/dev      = DEV
    Posttype/Set: 7/game     = Game Talk
    Posttype/Set: 8/dragon   = Dragon Lair

    Available functions:
    read_acess($type)          Check the reading rights for a forum
    mod_access($type)          Checks or someone has access to the mod powers of a forum
    forum_moderation($ids)     The mod functions
    select_forum()             Selects the forum
    show_threads()             Shows the topics
    show_posts()               Shows the posts of a topic
    record_a_thread_post()     Create a new topic
    record_a_post()            Create a new post in a topic
    show_edit_form()           'mode' for editing own posts
    record_an_edit()           'action' to handle input from the edit-form
    safeHTML()                 Secures the input
    cleanHTML()                Cleans the msg from trash
    tab2space()                Formats tabbed (tables) and posts in fixed-width
    make_post()                Attempt to write a 'proper' function
    get_coloured_name()        Unfinished? Not used in the forums anyway..

*/

//==============================================================================
// Checks who has reading rights to a forum-type         Martel October 15, 2007
//==============================================================================
function has_read_access($type)
{
    // Allow people without a stored session to read
    $iUserLevel   = (int) 0;
    $iStaffResort = (int) 0;
    $blnSponsor   = FALSE;
    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser   = &$GLOBALS["objSrcUser"];
        $iUserLevel   = $objSrcUser->get_stat(LEVEL);
        $iStaffResort = $objSrcUser->get_stat(ALLIANCE);
        if ($iStaffResort > 10)
            $iStaffResort = (int) 0;
        // Dragon Lair
        if (get_sponsor_badge($objSrcUser->get_userid()) != '')
            $blnSponsor = TRUE;
    }

    switch($type)
    {
        case 1: // staff101
            $iReqLevel = 2;
            $iReqResort = 0;
        break;
        case 4: // lno
            $iReqLevel = 3;
            $iReqResort = 2;
        break;
        case 5: // ops
            $iReqLevel = 3;
            $iReqResort = 3;
        break;
        case 6: // dev
            $iReqLevel = 3;
            $iReqResort = 1;
        break;
        default:
            $iReqLevel = 0;
            $iReqResort = 0;
        break;
    }

    // 1: allow admins,
    // 2: allow players+mods,
    // 3: Allow staff in their own forum,
    // 4: Allow sponsors in dragon forum
    if ($iUserLevel >= 5 ||
        ($iUserLevel >= $iReqLevel && $iReqResort == 0 && $type != 8) ||
        ($iUserLevel >= $iReqLevel  && $iStaffResort == $iReqResort  && $type != 8) ||
        ($blnSponsor == TRUE && $type == 8))
        return TRUE;
    else
        return FALSE;
}

//==============================================================================
// Checks or someone has access to the mod powers of a forum              ORKFiA
//==============================================================================
function mod_access($type)
{
    global  $cat;

    // Allow people without a stored session to read
    $arrStats[LEVEL] = 0;
    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];
        $arrStats   = $objSrcUser->get_stats();
    }

    switch($type)
    {
        case 0:
            // Give elders moderator powers
            if ($arrStats[TYPE] == 'elder' || $arrStats[TYPE] == 'coelder')
                $needed = 0;
            else
                $needed = 2;
        break;
        case 1: // staff101
            $needed = 5;
        break;
        case 3: // news
            $needed = 6;
        break;
        case 8: // dragon
            $needed = 6;
        break;
        default:
            $needed = 2;
    }

    if ($arrStats[LEVEL] >= $needed)
        return TRUE;
    else
        return FALSE;
}

//==============================================================================
// The mod functions                                                      ORKFiA
//==============================================================================
function forum_moderation(&$objSrcUser, $posttype, $ids)
{
    $arrStats   = $objSrcUser->get_stats();

    // M: Strict moderation of alliance forums... only members of the alliance
    if ($posttype != 0)
        $strAlliance = '';
    else
         $strAlliance = "poster_kd = {$arrStats[ALLIANCE]} AND";

    if (isset($_POST['sticky']))
    {
        $sql ="UPDATE forum SET sticky = 1 WHERE $strAlliance post_id IN ($ids)";
        echo '<div class="center"><br />' . "The selected topics are now sticky topics.<br /></div>";
    }
    elseif (isset($_POST['unsticky']))
    {
        $sql ="UPDATE forum SET sticky = 0 WHERE $strAlliance post_id IN ($ids)";
        echo '<div class="center"><br />' . "The selected topics aren't sticky anymore.<br /></div>";
    }
    elseif (isset($_POST['close']))
    {
        $sql ="UPDATE forum SET close_option = 1 WHERE $strAlliance post_id IN ($ids)";
        echo '<div class="center"><br />' . "The selected topics are now closed for posting.<br /></div>";
    }
    elseif (isset($_POST['open']))
    {
        $sql ="UPDATE forum SET close_option = 0 WHERE $strAlliance post_id IN ($ids)";
        echo '<div class="center"><br />' . "The selected topics are open again.<br /></div>";
    }
    elseif (isset($_POST['delete']))
    {
        $sql = "DELETE FROM forum WHERE $strAlliance ( post_id IN ($ids) OR parent_id IN ($ids) )";
        echo '<div class="center"><br />' . "The selected topics or posts were deleted.<br /></div>";
    }

    mysql_query($sql);
}

//==============================================================================
// Selects the forum                                                      ORKFiA
// Returns the $posttype variable
//==============================================================================
function select_forum($set, $bSwitch = FALSE)
{
    global  $cat;

    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];

        switch ($set)
        {
            case "alliance":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_A, 0); // FALSE didn't work..
                return (int) 0;
            break;
            case "staff101":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_S, 0);
                return 1;
            break;
            case "world":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_W, 0);
                return 2;
            break;
            case "news":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_N, 0);
                return 3;
            break;
            case "lno":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_L, 0);
                return 4;
            break;
            case "ops":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_O, 0);
                return 5;
            break;
            case "dev":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_D, 0);
                return 6;
            break;
            case "game":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_G, 0);
                return 7;
            break;
            case "dragon":
                if ($bSwitch)
                    $objSrcUser->set_preference(NEW_DR, 0);
                return 8;
            break;
        }
    }
    elseif ($cat == 'main')
    {
        switch ($set)
        {
            case "world":
                return 2;
            break;
            case "news":
                return 3;
            break;
            case "game":
                return 7;
            break;
            default:
                return 6; // default value to return 'false' when echoing links
        }
    }
    else
    {
        header('location: main.php?cat=game&page=logout');
    }
}

//==============================================================================
// Shows the topics                                                       ORKFiA
//==============================================================================
function show_threads($posttype, $set)
{
    global  $Host, $cat;

    if (! has_read_access($posttype))
    {
        echo '<div id="textSmall">' .
                 "<p>Sorry, you don't have access to read this forum.</p>" .
             '</div>';
        include_game_down();
        exit;
    }

    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];
        $userid     = $objSrcUser->get_userid();
        $arrStats   = $objSrcUser->get_stats();
    }

    switch ($posttype)
    {
        case 0:

            $objSrcAlli = $objSrcUser->get_alliance();
            $forumname = "Alliance Forum - " . stripslashes($objSrcAlli->get_alliance_info(NAME)) . " (#" . $objSrcAlli->get_allianceid() . ")";

            // Eldermessage from tribepage
            include_once('inc/functions/tribe.php');
            echo get_eldermessage_text($objSrcAlli) . "<br />";

            $post_count = mysql_num_rows(mysql_query("SELECT * from forum where type = $posttype and poster_kd = {$arrStats[ALLIANCE]}"));
            $result_2  = mysql_query("SELECT * FROM forum WHERE type = $posttype AND poster_kd = {$arrStats[ALLIANCE]} AND parent_id = 0 ORDER BY sticky DESC, updated DESC") or die(mysql_error());

        break;
        default:

            echo '<br />';
            $post_count = mysql_num_rows(mysql_query("SELECT * FROM forum WHERE type = $posttype"));
            $result_2  = mysql_query("SELECT * FROM forum WHERE type = $posttype AND parent_id = 0 ORDER BY sticky DESC, updated DESC") or die(mysql_error());

        break;
    }

    switch ($posttype)
    {
        case 1:
            $forumname = "Staff Forum";
        break;
        case 2:
            $forumname = "World Forum";
        break;
        case 3:
            $forumname = "Announcements";
        break;
        case 4:
            $forumname = "Law & Order Reports";
        break;
        case 5:
            $forumname = "Operations Reports";
        break;
        case 6:
            $forumname = "ORKFiA Development";
        break;
        case 7:
            $forumname = "Game Talk";
        break;
        case 8:
            $forumname = "The Dragon Lair";
        break;
    }

    $counter    = 0;
    if ($post_count <= 0)
    {
        echo
            '<div id="textMedium">' .
                '<p>' . 'This forum is empty. Shall we change that?' . '</p>' .
            '</div>';
    }
    else
    {
        $last_id = 0;

        if (mod_access($posttype))
            $colspan = "7";
        elseif ($posttype == 0)
            $colspan = "6";
        else
            $colspan = "5";

        if (mod_access($posttype))
            echo "<form id='center' name='forum' action='main.php?cat=game&amp;page=forums&amp;set=$set&amp;mode=threads&amp;action=moderation' method='post'>";
        else
            echo "<br />";
        echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"big\">";
        echo "<tr class=\"header\"><th colspan='$colspan'>$forumname</th></tr>";
        echo '<tr class="subheader">' .
                 '<th width="30">&nbsp;</th>' .
                 '<th>Topic</th>' .
                 "<th class=\"center\">Replies</th>" .
                 "<th class=\"center\">Name</th>";
        // Only show tribe in alliance forums, else ppl will make stuff personal - AI 31/10/06
        if ($posttype == 0 || (mod_access($posttype) && isset($arrStats) && $arrStats[LEVEL] >= 5))
        {
            echo "<th width='20%' class=\"center\">Tribe</th>";
        }
        echo "<th width='15%' class=\"center\">Last Post</th>";
        if (mod_access($posttype))
        {
            echo "<th width=\"45\" class=\"center\">Select</th>";
        }
        echo "</tr>";

        while ($forum = mysql_fetch_array($result_2))
        {
            $counter = $counter + 1;
            $class = "";
            if ($counter > 1)
            {
                $class = "bsup";
            }

            if (trim($forum['title']) != '')
            {
                $forum['title'] = cleanHTML($forum['title']);
            }
            else
            {
                $forum['title'] = "No subject";
            }

            if ($forum['close_option'] != 1)
                $image = "<img src='".$Host."icon_forum.gif' alt='' />";
            else
                $image = "<img src='".$Host."icon_forum_c.gif' alt='' />";

            $sticky = '';
            if ($forum['sticky'] > 0)
            {
                $sticky = 'Sticky: ';
            }

            $count  = mysql_query("SELECT post_id FROM forum WHERE type = $posttype AND parent_id = $forum[post_id]");
            $num    = mysql_num_rows($count);

            $link = "<a class='forum' href='main.php?cat=$cat&amp;page=forums";
            $link .= "&amp;set=$set";
            $link .= "&amp;postid=$forum[post_id]&amp;mode=posts&amp;start=0";
            $link .= "&amp;finish=" . min($num, FORUM_POSTS_PER_PAGE) . "'>$forum[title]</a>";

            //this should make stuff more dynamic - AI 30/10/06
            $postcounter = FORUM_POSTS_PER_PAGE;
            while ($postcounter < $num) {
                $link .= "<a class='forum' href='main.php?cat=$cat&amp;page=forums";
                $link .= "&amp;set=$set";
                $link .= "&amp;postid=$forum[post_id]&amp;mode=posts&amp;start=$postcounter";
                $postcounter += FORUM_POSTS_PER_PAGE;
                $link .= "&amp;finish=" . min($num, $postcounter) . "'> [" . $postcounter/FORUM_POSTS_PER_PAGE . "]</a>";
            }

            echo "<tr class=\"data\">";
            echo "<th class='$class' width=\"30\">$image</th>";
            echo "<th class='$class'>$sticky $link</th>";
            echo "<td class='center $class'>$num</td>";
            echo "<td class='center $class'>$forum[poster_name]</td>";
            //identity hiding on WF - AI 31/10/06
            if ($posttype == 0 || (mod_access($posttype) && isset($arrStats) && $arrStats[LEVEL] >= 5))
            {
                echo "<td class='center $class'>$forum[poster_tribe]</td>";
            }
            echo "<td class='center $class'>$forum[updated]</td>";
            if (mod_access($posttype))
            {
                echo "<td class='center $class'><input name='posts[]' type='checkbox' value='$forum[post_id]' /></td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        if ($counter > 1)
            $plural = "s";
        else
            $plural = "";

        if ($post_count > 1)
            $plural2 = "s";
        else
            $plural2 = "";

        echo '<div class="center" style="font-size: 0.8em;">This forum has ' .
                 '<strong>'. $post_count .' post'.$plural2.'</strong>' .
                 ' in <strong>'.$counter.' topic'.$plural.'</strong>.' .
             '</div>';

        if (mod_access($posttype))
        {
            echo "<br /><br />";
            echo "| <a href='#' onclick=\"var posts=document.getElementsByName('forum')[0]['posts[]']; for(var i=0,len=posts.length;i<=len;i++) posts[i].checked=true;\">Check All</a>";
            echo " | <a href='#' onclick=\"var posts=document.getElementsByName('forum')[0]['posts[]']; for(var i=0,len=posts.length;i<=len;i++) posts[i].checked=false;\">Uncheck All</a> |";
            echo "<br /><br />";
            echo "<input type='submit' name='sticky' value='Sticky' /> ";
            echo "<input type='submit' name='unsticky' value='Unsticky' /> ";
            echo "<input type='submit' name='close' value='Close' /> ";
            echo "<input type='submit' name='open' value='Open' /> ";
            echo "<input type='submit' name='delete' value='Delete' /> ";
        }
        echo "</form>";
    }

    if ($counter >= 30 && $posttype == 0)
    {
        echo '<div class="center">' . "Your alliance has reached its maximum of 30 topics, to create a new topic you must clean out your forums." . '</div>';
    }
    elseif ($cat == 'game')
    {
        // Only allow heads to create announcement topics
        if ($posttype == 3 && $arrStats[LEVEL] < 5)
        {
            return;
        }
        $strCreateTopicForm =
        '<div id="textMedium">' .

            '<h2>Start New Topic</h2>' .
            '<form action="main.php?cat=game&amp;page=forums&amp;set=' . $set .
            '&amp;mode=threads&amp;action=post" method="post">' .

                '<label for="1">Subject</label>: ' .
                '<br /><input type="text" name="title" size="40" maxlength="30" id="1" />' .
                '<br /><br />' .
                '<label for="2">Your Message</label>: ' .
                '<br /><textarea name="text" rows="8" cols="44" id="2"></textarea>' .
                '<br /><br />' .
                '<input type="submit" value="Post new topic" />' .
                '<input type="hidden" name="postid" value="0" />' .
            '</form>' .
        '</div>';

        echo $strCreateTopicForm;
    }
}

//==============================================================================
// Shows the posts of a topic                                             ORKFiA
//==============================================================================
function show_posts($posttype, $set, $postid, $start, $finish)
{
    global  $cat;

    if (! has_read_access($posttype))
    {
        echo '<div id="textSmall">' .
                 "<p>Sorry, you don't have access to read this topic.</p>" .
             '</div>';
        include_game_down();
        exit;
    }

    $start  = max(0, $start);
    $finish = max(FORUM_POSTS_PER_PAGE, $finish);

    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];
        $userid     = $objSrcUser->get_userid();
        $arrStats   = $objSrcUser->get_stats();
    }

    // dynamic stuff is sooooooo much cooler, isn't that what loops and
    // functions were invented for?
    $strSQL      = "SELECT post_id FROM forum WHERE type = $posttype AND parent_id = $postid";
    $num         = mysql_num_rows(mysql_query($strSQL));
    $strPages    = 'Page ';
    $postcounter = 0;
    do
    {
        $counternext = $postcounter + FORUM_POSTS_PER_PAGE;
        $page = $postcounter/FORUM_POSTS_PER_PAGE + 1;
        if ($start != $postcounter)
        {
            $strPages .= "<a class='forum' href='main.php?cat=$cat&amp;page=forums&amp;set=$set&amp;postid=$postid&amp;mode=posts&amp;start=$postcounter&amp;finish=$counternext'>";
        }
        $strPages .= "$page";
        if ($start != $postcounter)
            $strPages .= "</a>";
        $strPages .= "&nbsp;";
        $postcounter = $counternext;
    }
    while ($postcounter < $num);

    $posts  = mysql_query("SELECT * FROM forum WHERE type = $posttype AND post_id = $postid") or die(mysql_error());
    $posts = mysql_fetch_array($posts);

    if (trim($posts['title']) != '')
        $posts['title'] = cleanHTML($posts['title']);
    else
        $posts['title'] = "No subject";

    // Species 5618, 30-3-2004
    // Checks to see wether the tribe is in the same alli as the post belongs to
    // This was an exploit before i fixed, by manipulating the url, players could read any post in the game.
    if ($posttype == 0 && ($arrStats[ALLIANCE] != $posts['poster_kd']))
    {
        if (isset($posts['poster_kd']))
        {
            echo '<div class="center">' . 'The topic/post you requested can\'t be accessed.' . '</div>';
            include_game_down();
            exit;
        }
        else
        {
            echo '<div class="center">' . "The topic/post you requested doesn't exist anymore.</div>";
            include_game_down();
            exit;
        }
    }

    echo "<br />";
    // Back button table
    echo "<div class='tableLinkBig'><a name='top' href='#bottom'>To The Bottom</a> :: ";
    echo "<a href=\"main.php?cat=$cat&amp;page=forums&amp;set=$set&amp;mode=threads\">Back To Overview</a>";
    echo "</div>";

    $colspan = '2';
    if (((mod_access($posttype) && isset($arrStats) && $arrStats[LEVEL] >= 5) || $posttype == 0 || $posttype == 4 || $posttype == 5) && $cat != 'main')
        $colspan = '3';

    // Table Header
    echo
    '<table cellpadding="0" cellspacing="0" class="big">' .

        '<form id="center" name="forum" action="main.php?cat=' . $cat .
        '&amp;page=forums&amp;set=' . $set .
        '&amp;mode=threads&amp;action=moderation" method="post">' .

        '<tr class="header">' .
            '<th colspan="' . $colspan . '">Topic: ' . $posts['title'] . '</th>' .
        '</tr>' .

        '<tr class="subheader">' .
            '<th colspan="' . $colspan . '" class="right">' . $strPages . '</th>' .
        '</tr>';

    if ($start == 0)
    {
        // FIRST POST OF A THREAD
        // NO DELETION ALLOWED
        $posts['post'] = cleanHTML($posts['post']);
        echo "<tr class=\"data\">";
        echo "<td class='left'><br />";
        // Hide id on WF - AI 31/10/06 M: enable on alliance, lno, ops, + admins
        if (((mod_access($posttype) && isset($arrStats) && $arrStats[LEVEL] >= 5) || $posttype == 0 || $posttype == 4 || $posttype == 5) && $cat != 'main')
        {
            $alliance = mysql_query("SELECT " . ALLIANCE . " FROM stats WHERE id = $posts[poster_id]");
            $alliance = mysql_fetch_array($alliance);
            echo "<a href=\"main.php?cat=game&amp;page=mail&amp;set=compose&amp;replyid=$posts[poster_id]&amp;kingdom=$alliance[kingdom]\" title=\"$posts[poster_tribe] (#" . $alliance[ALLIANCE] . ")\">$posts[poster_name]</a> ";
        }
        else
        {
            echo "<strong>{$posts['poster_name']}</strong> ";
        }
        echo $posts['date_time'] . " ";

        if ($posts['date_time'] != $posts['updated'])
        {
            echo " edited ";
        }

        if ($cat == 'game' && $posts['poster_id'] == $userid && $posts['close_option'] != 1)
        {
            echo "<a href=\"main.php?cat=$cat&amp;page=forums&amp;set=$set&amp;postid=$postid&amp;edit_id=$posts[post_id]&amp;mode=edit\">edit</a>";
        }
        echo "<br /><br /></td>";

        $strSponsorBadge = get_sponsor_badge($posts['poster_id']);
        echo '<td rowspan="2" class="center bsdown" width="45">' .
                 '<a href="main.php?cat=' . $cat .
                 '&amp;page=sponsors" title="Dragon Sponsor">' .
                 $strSponsorBadge . '</a>' .
             '</td>';

        if (mod_access($posttype))
        {
            echo "<td rowspan='2' align='center' class=\"center bsdown bsleft\" width=\"45\"> X </td>";
        }

        echo "</tr><tr class='data'><td colspan='1' valign='top' class=\"left bsdown\" style='padding-left: 1em;'><div>".$posts['post']."<br /><br /></div></td></tr>";
    }

    @$result = mysql_query ("SELECT * from forum where type ='$posttype' and parent_id='$postid' ORDER BY date_time ASC ") or die(mysql_error());
    $i = 0;
    while ($forum = mysql_fetch_array($result))
    {
        if ($i >= $start && $i < $finish)
        {
            if ($forum['date_time'])
            {
                $forum['post'] = cleanHTML($forum['post']);

                echo "<tr class=\"data\">";
                echo "<td class='left'><br />";

                // Hide ID on WF - AI 31/10/06
                if (((mod_access($posttype) && isset($arrStats) && $arrStats[LEVEL] >= 5) || $posttype == 0 || $posttype == 4 || $posttype == 5) && $cat != 'main')
                {
                    $alliance = mysql_query("SELECT " . ALLIANCE . " FROM stats WHERE id = $forum[poster_id]");
                    $alliance = mysql_fetch_array($alliance);
                    echo "<a href=\"main.php?cat=game&amp;page=mail&amp;set=compose&amp;replyid=$forum[poster_id]&amp;kingdom=$alliance[kingdom]\" title=\"$forum[poster_tribe] (#" . $alliance[ALLIANCE] . ")\">$forum[poster_name]</a> ";
                }
                else
                {
                    echo "<strong>{$forum['poster_name']}</strong> ";
                }
                echo $forum['date_time'] . " ";

                if ($forum['date_time'] != $forum['updated'])
                {
                    echo " edited ";
                }

                if ($cat == 'game' && $forum['poster_id'] == $userid && $posts['close_option'] != 1)
                {
                    echo "<a href=\"main.php?cat=$cat&amp;page=forums&amp;set=$set&amp;postid=$postid&amp;edit_id=$forum[post_id]&amp;mode=edit\">edit</a> ";
                }
                echo "<br /><br /></td>";

                $strSponsorBadge = get_sponsor_badge($forum['poster_id']);
                echo '<td rowspan="2" class="center bsdown" width="45">' .
                         '<a href="main.php?cat=' . $cat .
                         '&amp;page=sponsors" title="Dragon Sponsor">' .
                         $strSponsorBadge . '</a>' .
                     '</td>';

                if (mod_access($posttype))
                {
                    echo "<td rowspan='2' align='center' class=\"center bsdown bsleft\"><input name='posts[]' type='checkbox' value='$forum[post_id]' /></td>";
                }

                echo "</tr><tr class=\"data\"><td colspan='1' class=\"left bsdown\" valign='top' style='padding-left: 1em;'>";
                echo "<div>".$forum['post']."<br /><br /></div></td>";
                echo "</tr>";
            }
        }
        $i++;
    }
    echo "<tr class='subheader'><th colspan='$colspan' class='right' style='border: 0;'>$strPages</th></tr>";
    echo "</table>";

    echo '<div class="center"><br />';

    if (mod_access($posttype))
    {
        echo "| <a href='#' onclick=\"var posts=document.getElementsByName('forum')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=true;\">Check All</a>";
        echo " | <a href='#' onclick=\"var posts=document.getElementsByName('forum')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=false;\">Uncheck All</a> ";
    }
    echo "| <a href=\"main.php?cat=$cat&amp;page=forums&amp;set=$set&amp;mode=threads\">Back To Overview</a> |";
    echo " <a name=\"bottom\" href='#top'>To The Top</a> |";

    if (mod_access($posttype))
    {
        echo "<br /><br />";
        echo "<input type='submit' name='delete' value='Delete' /> ";
    }
    echo '</div>';

    echo "</form>";

    // Adding in open close option for threads

    //close at 120 posts
    if ($num >= FORUM_MAX_PAGES * FORUM_POSTS_PER_PAGE && $arrStats[ALLIANCE] > 10)
    {
        $posts['close_option'] = 2;
    }

    if ($posts['close_option'] == 0 && $cat != 'main')
    {
        $strReplyToTopicForm =
        '<div id="textMedium">' .

            '<h2>Reply To Topic</h2>' .
            '<form action="main.php?cat=game&amp;page=forums&amp;set=' . $set .
            '&amp;mode=posts&amp;action=post" method="post">' .

                '<label for="1">Your Reply</label>: ' .
                '<br /><textarea name="text" rows="8" cols="44" id="1"></textarea>' .
                '<br /><br />' .
                '<input type="submit" value="Post your reply" />' .
                '<br /><br />' .
                '<input type="checkbox" name="formatted" value="yes" id="2" /> ' .
                '<label for="2">With Tabs</label> (Sometimes this can make a copied table look great.)' .
                '<input type="hidden" name="postid" value="' . $postid . '" />' .
            '</form>' .
        '</div>';

        echo $strReplyToTopicForm;
    }
    elseif ($posts['close_option'] == 2 && $arrStats[ALLIANCE] > 10)
    {
        echo '<div class="center"><br />' . "It seems this was a very popular topic! It is now full, but you may create a new one.</div>";
    }
    elseif ($posts['close_option'] == 1)
    {
        echo '<div class="center"><br />' . "This topic has been closed, you can't reply to it.</div>";
    }
}

//==============================================================================
// Create a new topic                                                     ORKFiA
//==============================================================================
function record_a_thread_post($posttype, $set, $title, $text)
{
    global  $orkTime, $ip;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $arrStats   = $objSrcUser->get_stats();

    // Only allow heads to create announcement topics
    if ($posttype == 3 && $arrStats[LEVEL] < 5)
    {
        include_game_down();
        exit;
    }

    // Secure user-input
    $title          = substr($title, 0, 30);
    $title          = safeHTML($title);
    $text           = safeHTML($text);

    // Colored names :p
    $strSpan = get_coloured_name($objSrcUser->get_userid(), $posttype);

    // Write to DB
    include_once('inc/functions/generic.php');
    $strSQL ="INSERT INTO " . 'forum' . " " .
                     "SET " . "poster_id = {$arrStats[ID]}, " .
                                   "type = $posttype, " .
                              "poster_kd = {$arrStats[ALLIANCE]}, " .
                              "parent_id = 0, " .
                                  "title = '$title', " .
                                   "post = '$text', " .
                              "date_time = '$orkTime', " .
                                "updated = '$orkTime', " .
                            "poster_name = '$strSpan', " .
                           "poster_tribe = '{$arrStats[TRIBE]}', " .
                                  "level = {$arrStats[LEVEL]}, " .
                                     "ip = " . quote_smart($ip);
    mysql_query($strSQL) or die("Creating new topic: " . mysql_error());

    // Highlight users
    notify_forum_users($objSrcUser, $posttype);

    echo '<br /><div class="center">' . "You have succesfully posted a new topic.";
    echo "<br /><a href=\"main.php?cat=game&amp;page=forums&amp;set=$set&amp;mode=threads\">Return To Forum</a></div>";

    include_game_down();
    exit;
}

//==============================================================================
// Create a new post in a topic                                           ORKFiA
//==============================================================================
function record_a_post($posttype, $set, $postid, $text)
{
    global  $orkTime, $ip;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $arrStats   = $objSrcUser->get_stats();

    $title = ''; //safeHTML($title);

    // M: Add formatting wrappers *M: updated with AI's fixes*
    if (isset($_POST['formatted']) && $_POST['formatted'] == "yes")
    {
        if (get_magic_quotes_gpc())
            $text = stripslashes($text);
        $text = tab2space($text);
//         $text = htmlentities($text, ENT_QUOTES);
        $text = htmlspecialchars($text);
        $text = mysql_real_escape_string($text);
        $text = "<code>" . $text . "</code>";
    }
    else
    {
        $text = safeHTML($text);
    }

    // M: Colored names :p
    $strSpan = get_coloured_name($objSrcUser->get_userid(), $posttype);

    // M: Write to DB
    include_once('inc/functions/generic.php');
    $strSQL ="INSERT INTO " . 'forum' . " " .
                     "SET " . "poster_id = {$arrStats[ID]}, " .
                                   "type = $posttype, " .
                              "poster_kd = {$arrStats[ALLIANCE]}, " .
                              "parent_id = $postid, " . // diff to thread_post
                                  "title = '$title', " .
                                   "post = '$text', " .
                              "date_time = '$orkTime', " .
                                "updated = '$orkTime', " .
                            "poster_name = '$strSpan', " .
                           "poster_tribe = '{$arrStats[TRIBE]}', " .
                                  "level = {$arrStats[LEVEL]}, " .
                                     "ip = " . quote_smart($ip);
    mysql_query($strSQL) or die("Creating new topic: " . mysql_error());

    // M: Update parent's last updated value
    mysql_query("UPDATE forum SET updated='$orkTime' WHERE post_id = $postid");

    // M: Highlight users
    notify_forum_users($objSrcUser, $posttype);

    //this should make stuff more dynamic - AI 30/10/06
    // Reused: it will only store the last page. works wonderfully - Martel
    $count  = mysql_query("SELECT post_id FROM forum WHERE type = $posttype AND parent_id = $postid");
    $num    = mysql_num_rows($count);
    $postcounter = 0;
    while ($postcounter <= $num) {
        $link = "<a href='main.php?cat=game&amp;page=forums";
        $link .= "&amp;set=$set";
        $link .= "&amp;postid=$postid&amp;mode=posts&amp;start=$postcounter";
        $postcounter += FORUM_POSTS_PER_PAGE;
        $link .= "&amp;finish=" . min($num, $postcounter) . "#bottom'>Return To Topic</a>";
    }

    echo '<br /><div class="center">' . "You have succesfully posted a reply.";
    echo "<br /><br />$link</div>";

    include_game_down();
    exit;
}

//==============================================================================
// Highlighting different users in different forums      October 17, 2007 Martel
//==============================================================================
function notify_forum_users(&$objSrcUser, $posttype)
{
    switch ($posttype)
    {
        case 0: // Alliance

            // One way to highlight a whole alliance -Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            $objSrcAlliance = $objSrcUser->get_alliance();
            $arrIds         = $objSrcAlliance->get_userids();
            foreach ($arrIds as $iUserId)
            {
                $objTmpUser->set_userid($iUserId);
                $objTmpUser->set_preference(NEW_A, 1);
            }

        break;
        case 1: // Staff

            // A way to highlight several alliances... There must be something
            // better, but now it works. Maybe add a method in alliance class
            // to affect all members. Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            $strSQL = "SELECT id FROM " . TBL_STAT . " WHERE level > 1";
            $resSQL = mysql_query($strSQL) or die(mysql_error());
            while ($arrPrefs = mysql_fetch_array($resSQL))
            {
                $objTmpUser->set_userid($arrPrefs[ID]);
                $objTmpUser->set_preference(NEW_S, 1);
            }

        break;
        case 2: // adding world entry - AI 31/10/06

            // Note: This is bad, because we ignore user objects for performance
            mysql_query("UPDATE " . TBL_PREFERENCES . " SET " .
            NEW_W . " = 1 WHERE " . NEW_W . " = 0") or die(mysql_error());

        break;
        case 3: // Announcements

            // Note: This is bad, because we ignore user objects for performance
            mysql_query("UPDATE " . TBL_PREFERENCES . " SET " .
            NEW_N . " = 1 WHERE " . NEW_N . " = 0") or die(mysql_error());

        break;
        case 4: // Law & Order

            // One way to highlight a whole alliance -Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            include_once('inc/classes/clsAlliance.php');
            $objSrcAlliance = new clsAlliance(2); // Hardcoded alliance id
            $arrIds         = (array) $objSrcAlliance->get_userids();
            foreach ($arrIds as $iUserId)
            {
                $objTmpUser->set_userid($iUserId);
                $objTmpUser->set_preference(NEW_L, 1);
            }

        break;
        case 5: // Operations

            // One way to highlight a whole alliance -Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            include_once('inc/classes/clsAlliance.php');
            $objSrcAlliance = new clsAlliance(3); // Hardcoded alliance id
            $arrIds         = (array) $objSrcAlliance->get_userids();
            foreach ($arrIds as $iUserId)
            {
                $objTmpUser->set_userid($iUserId);
                $objTmpUser->set_preference(NEW_O, 1);
            }

        break;
        case 6: // Development

            // One way to highlight a whole alliance -Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            include_once('inc/classes/clsAlliance.php');
            $objSrcAlliance = new clsAlliance(1); // Hardcoded alliance id
            $arrIds         = (array) $objSrcAlliance->get_userids();
            foreach ($arrIds as $iUserId)
            {
                $objTmpUser->set_userid($iUserId);
                $objTmpUser->set_preference(NEW_D, 1);
            }

        break;
        case 7: // Game Talk

            // Note: This is bad, because we ignore user objects for performance
            mysql_query("UPDATE " . TBL_PREFERENCES . " SET " .
            NEW_G . " = 1 WHERE " . NEW_G . " = 0") or die(mysql_error());

        break;
        case 8: // Dragon Lair (game sponsors)

            $strLogins = "'martel'"; // hardcoded for sponsor notify SQL string
            $strSQL3 = "SELECT option_selection1 FROM phpsuppo_3.paypal WHERE option_selection1 != 'NULL' GROUP BY option_selection1";
            $resSQL3 = mysql_query($strSQL3);
            while ($arrSQL3 = mysql_fetch_row($resSQL3))
                $strLogins .= ", " . quote_smart($arrSQL3[0]);

            // A way to highlight several alliances... There must be something
            // better, but now it works. Maybe add a method in alliance class
            // to affect all members. Martel, September 13, 2007
            include_once('inc/classes/clsUser.php');
            $objTmpUser = new clsUser(1); //M:small performance increaser, reuse
            $strSQL = "SELECT id FROM " . TBL_USER . " WHERE username IN ($strLogins)";
            $resSQL = mysql_query($strSQL) or die(mysql_error());
            while ($arrPrefs = mysql_fetch_array($resSQL))
            {
                $objTmpUser->set_userid($arrPrefs[ID]);
                $objTmpUser->set_preference(NEW_DR, 1);
            }

        break;
    }
}

//==============================================================================
// New 'mode' for editing own posts                                       Martel
//==============================================================================
function show_edit_form($posttype, $set, $postid, $edit_id)
{
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $userid     = $objSrcUser->get_userid();

    $sql   = "SELECT poster_id,poster_kd,post,title,parent_id FROM forum ";
    $sql  .= "WHERE post_id = $edit_id";
    $edit  = mysql_fetch_array(mysql_query($sql));
    if ($userid != $edit['poster_id'])
    {
        echo '<div class="center">' .
                "Sorry, you can't edit this post." .
             '</div>';

        include_game_down();
        exit;
    }

    $post = cleanHTML($edit['post']);
    $post = str_replace("<br />", "", $post);
    $post = strip_tags($post); // remove <code></code>
    $post = rtrim($post); // to remove odd break-rows at end of a post

    $title = cleanHTML($edit['title']);

    if ($edit_id != $postid)
    {
        $strTitleInput = '<input type="hidden" name="title" value="' . $title .
                         '" />';
    }
    else
    {
        $strTitleInput = '<label for="i1">Topic:</label>' .
                         '<br /><input type="text" name="title" size="40" ' .
                         'maxlength="30" value="' . $title . '" id="i1" /><br /><br />';
    }

    $strEditPostForm =
    '<div id="textMedium">' .

        '<h2>Edit post</h2>' .
        '<form action="main.php?cat=game&amp;page=forums&amp;set=' . $set .
        '&amp;action=edit" method="post">' .

            $strTitleInput .
            '<label for="i2">Your Message</label>: ' .
            '<br /><textarea name="text" rows="8" cols="44" id="i2">' . $post . '</textarea>' .
            '<br /><br />' .
            '<input type="submit" value="Save changes" />' .
            '<br /><br />' .
            '<input type="checkbox" name="formatted" value="yes" id="i3" /> ' .
            '<label for="i3">With Tabs</label> (Sometimes this can make a copied table look great.)' .
            '<input type="hidden" name="edit_id" value="' . $edit_id . '" />' .
            '<input type="hidden" name="postid" value="' . $postid . '" />' .
        '</form>' .
    '</div>';
    echo $strEditPostForm;
}

//==============================================================================
// New 'action' to handle input from the edit-form                        Martel
//==============================================================================
function record_an_edit($posttype, $set, $postid, $edit_id, $orkTime)
{
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $userid     = $objSrcUser->get_userid();

    $sql   = "SELECT poster_id,poster_kd,post,title FROM forum ";
    $sql  .= "WHERE post_id = $edit_id";
    $edit  = mysql_fetch_array(mysql_query($sql));

    if ($userid != $edit['poster_id'])
    {
        echo '<div class="center">' .
                "Sorry, you can't edit this post." .
             '</div>';

        include_game_down();
        exit;
    }

    // To prevent script kiddies and accidents from editing closed posts
    $seek = mysql_query("SELECT close_option FROM forum WHERE post_id=$postid");
    $seek = mysql_fetch_array($seek);
    if ($seek['close_option'] == 1)
    {
        echo '<div class="center">' . "...Topic closed, no editing..." . '</div>';
        include_game_down();
        exit;
    }

    // secure input
    $title = substr($_POST['title'], 0, 30);
    $title = safeHTML($title);
    $text  = $_POST['text']; // Not yet secured

    // M: Add formatting wrappers + securing *M: updated with AI's fixes*
    if (isset($_POST['formatted']) && $_POST['formatted'] == "yes")
    {
        if (get_magic_quotes_gpc())
            $text = stripslashes($text);
        $text = tab2space($text);
        $text = htmlspecialchars($text);
        $text = mysql_real_escape_string($text);
        $text = "<code>" . $text . "</code>";
    }
    else
    {
        $text = safeHTML($text); // Secured
    }

    // update post
    $sql = "UPDATE forum SET title='$title', post='$text', updated='$orkTime' WHERE post_id=$edit_id";
    $result = mysql_query($sql);

    echo '<div class="center"><br />Your post was edited successfully.' .
             '<br /><br />' .
             "<a href=\"main.php?cat=game&amp;page=forums&amp;set=$set&amp;postid=$postid&amp;mode=posts&amp;start=0&amp;finish=" . FORUM_POSTS_PER_PAGE . "\">" .
             "Changes saved, click here to return</a>" .
         '</div>';

    include_game_down();
    exit;
}

//==============================================================================
// Changed the two functions below to function correctly,
//  not addslashes but mysql_real_escape_string should be used
//                                                    - AI 30/09/06
//==============================================================================
function safeHTML($inputBody)
{
    //$inputBody = addslashes($inputBody);
    if (get_magic_quotes_gpc())
        $inputBody = stripslashes($inputBody);
    $inputBody = htmlentities($inputBody, ENT_QUOTES);
    $inputBody = htmlspecialchars($inputBody );
    //$inputBody = escapeshellcmd($inputBody );
    $inputBody = mysql_real_escape_string($inputBody);

    return $inputBody;
}

function cleanHTML($inputBody)
{
    $inputBody = html_entity_decode($inputBody);
    //moved line to before stripslashes, else \r\n would turn into rn instead of <br>
    $inputBody = nl2br($inputBody);
    //with mysql_real_escape_string mysql should return everything as orignally inputted
    //$inputBody = stripslashes($inputBody);
    // next line left for legacy data (that's still escaped with addslashes)
    $inputBody = stripslashes($inputBody);


    return $inputBody;
}

// M: function that will take a tab separated table (used in Firefox for ctrl+c)
//    and convert its tabs into non-breaking spaces.
//    In IE this will not make table columns since IE doesn't use tabs.
function tab2space($text, $spaces = 4)
{
    $spaces = str_repeat(" ", $spaces);
    $text = preg_split("/\r\n|\r|\n/", $text);
    $word_lengths = array();
    $w_array = array();

    // Store word lengths
    foreach ($text as $line)
    {
        $words = preg_split("/(\t+)/", $line, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach (array_keys($words) as $i)
        {
            $strlen = strlen($words[$i]);
            $add = isset($word_lengths[$i]) && ($word_lengths[$i] < $strlen);
            if ($add || !isset($word_lengths[$i]))
            {
                $word_lengths[$i] = $strlen;
            }
        }
        $w_array[] = $words;
    }

    // Clear $text
    $text = '';

    // Apply padding when appropriate and rebuild the string
    foreach (array_keys($w_array) as $i)
    {
        foreach (array_keys($w_array[$i]) as $ii)
        {
            if (preg_match("/^\t+$/", $w_array[$i][$ii]))
            {
                $w_array[$i][$ii] = str_pad($w_array[$i][$ii], $word_lengths[$ii], "\t");
            }
            else
            {
                $w_array[$i][$ii] = str_pad($w_array[$i][$ii], $word_lengths[$ii]);
            }
        }

        // M: added rtrim to remove unnecessary spaces on the right side
        $text .= rtrim(str_replace("\t", $spaces, implode("", $w_array[$i]))) . "\n";
    }

    // M: mod that will make spaces into non-breaking spaces (for DB-storage)
    $text = str_replace(" ", "&nbsp;", $text);

    // Finished
    return $text;
}

//==============================================================================
//  Attempt to write a 'proper' function to make posts           - AI 13/01/2006
//==============================================================================
function make_post($poster_id, $thread, $alli, $type, $post)
{
    $objTmpUser = new clsUser($poster_id);
    $arrStats = $objTmpUser->get_stats();
    $post = safeHTML($post);
    $orkTime = $GLOBALS['orkTime'];
    mysql_query("INSERT INTO forum (poster_id,type,poster_kd,parent_id,post,date_time,updated,poster_name,poster_tribe,level) VALUES ($poster_id,$type,$alli,$thread,'$post','$orkTime','$orkTime','" . get_coloured_name($poster_id,$type) . "','{$arrStats['tribe']}',{$arrStats['level']})") or die('mysql error: ' . mysql_error());
    mysql_query("UPDATE forum SET updated = '$orkTime' WHERE post_id = $thread") or die('mysql error: ' . mysql_error());
    mysql_query("UPDATE user,stats SET allianceforum = allianceforum + 1 WHERE user.id = stats.id AND kingdom = $alli") or die('mysql error: ' . mysql_error());
}

function get_coloured_name($id, $type)
{
    $objTmpUser = new clsUser($id);
    $arrStats   = $objTmpUser->get_stats();

    if ($arrStats[LEVEL] == 6)
        $strSpan = '<span class="admin">' . $arrStats[NAME] . "</span>";
    elseif ($arrStats[LEVEL] == 5)
        $strSpan = '<span class="head">' . $arrStats[NAME] . "</span>";
    elseif ($arrStats[LEVEL] > 2)// && $arrStats[ALLIANCE] < 11)
        $strSpan = '<span class="staff">' . $arrStats[NAME] . "</span>";
    elseif ($arrStats[LEVEL] > 1)
        $strSpan = '<span class="mod">' . $arrStats[NAME] . "</span>";
    else
        $strSpan = '<span class="player">' . $arrStats[NAME] . "</span>";

    if ($type == '0') // Alliance
    {
        if ($arrStats[LEVEL] == 6)
            $strSpan = '<span class="admin">' . $arrStats[NAME] . "</span>";
        elseif ($arrStats[TYPE] == 'elder')
            $strSpan = '<span class="elder">' . $arrStats[NAME] . '</span>';
        elseif ($arrStats[TYPE] == 'coelder')
            $strSpan = '<span class="coelder">' . $arrStats[NAME] . '</span>';
        else
            $strSpan = '<span class="player">' . $arrStats[NAME] . "</span>";
    }

    return $strSpan;
}

function get_sponsor_badge($iUserid)
{
    $strImg = '';

    // Check if user still exists in the DB
    $strSQL = "SELECT * FROM " . TBL_USER . " WHERE id = $iUserid";
    if (mysql_num_rows(mysql_query($strSQL)) > 0)
    {
        // Sponsor Dragons
        $objTmpUser  = new clsUser($iUserid);
        $strUsername = $objTmpUser->get_user_info(USERNAME);

        $resSQL      = mysql_query("SELECT item_number as rank, quantity, option_selection1 as username, unix_timestamp, payment_gross as money FROM phpsuppo_3.paypal WHERE (item_name = 'One Week Sponsorship' OR item_name = 'Three Months Sponsorship') AND option_selection1 = '$strUsername' AND payment_status = 'Completed' ORDER BY unix_timestamp DESC LIMIT 1");
        $arrPayPal   = mysql_fetch_array($resSQL);
        $iRows       = mysql_num_rows($resSQL);

        if ($iRows == 1)
        {
            $strRank = $arrPayPal['rank'];
            include_once('inc/pages/sponsors.inc.php');
            if (check_valid_sponsor($arrPayPal))
            {
                $strPic = explode(" ", $strRank);
                $strPic = $strPic[1] . "_" . $strPic[0];
                $strImg = HOST_PICS . strtolower($strPic) . '.gif';
                $strImg = '<img src="'.$strImg.'" alt="'.$strRank.'" border="0">';
            }
        }
    }
    return $strImg;
}

?>
