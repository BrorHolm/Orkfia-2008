<?php
function include_forums_text()
{
    global  $orkTime, $cat;

    // Get stored session id from a user account
    if (isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];
        $strSession = $objSrcUser->get_user_info(SESSION);
    }
    elseif ($cat == 'main')
    {
        $strSession = 'guest'; // Allow people without a stored session to read
        echo '<h2 style="margin: 15px auto; text-align: center;"><img src="' .
             HOST_PICS . 'first_forum.gif" alt="Forums" /></h2>';
    }

    // Show Links
    $set = 'news';
    if (isset($_GET['set'])) { $set = $_GET['set']; }
    echo get_forum_links($set);


    include_once('inc/functions/forums.php');
    $posttype = select_forum($set, TRUE);

    session_start();
    $session = session_id();

    if ($strSession == $session || $strSession == 'guest')
    {
        $action = '';
        if (isset($_GET['action'])) { $action = $_GET['action']; }
        switch ($action)
        {
            case "moderation":

                if (mod_access($posttype) == 'yes') // Added safety against xss
                {
                    if (isset($_POST['posts']))
                    {
                        $posts      = $_POST['posts'];
                        $postcount  = count($posts);
                        $ids        = "";
                        for ($i = 0; $i < $postcount; $i++)
                        {
                            $ids .= intval($posts[$i]);
                            if ($i != $postcount-1)
                            {
                                $ids .= ",";
                            }
                        }
                        forum_moderation($objSrcUser, $posttype, $ids);
                    }
                    else
                    {
                        echo '<div class="center">' .
                                 'Please select a topic or post first.' .
                             '</div>';
                    }
                }

            break;
            case "edit":

                record_an_edit($posttype, $set, intval($_POST['postid']), intval($_POST['edit_id']), $orkTime);

            break;
        }

        $mode = 'threads';
        if (isset($_GET['mode'])) { $mode = $_GET['mode']; }
        switch ($mode)
        {
            case "threads":

                if(isset($_GET['action']) && $_GET['action'] == "post")
                {
                    record_a_thread_post($posttype, $set, $_POST['title'], $_POST['text']);
                }
                show_threads($posttype, $set);

            break;
            case "posts":

                if(isset($_GET['action']) && $_GET['action'] == "post")
                {
                    record_a_post($posttype, $set, intval($_POST['postid']), $_POST['text']);
                }
                show_posts($posttype, $set, intval($_GET['postid']), intval($_GET['start']), intval($_GET['finish']));

            break;
            case "edit":

                show_edit_form($posttype, $set, intval($_GET['postid']), intval($_GET['edit_id']));

            break;
        }
    }
    else
    {
        echo
            '<div class="center">' .
                'Sorry, we lost your session. You need to log in again to see ' .
                'the forums.' .
            '</div>';
    }
}

//==============================================================================
// Links at top of page                                     Martel, May 24, 2006
// Recoded after getting >40 rows of different arrays  Martel, November 10, 2007
//==============================================================================
function get_forum_links($show = '')
{
    global  $cat;

    if ($cat != 'main' && isset($GLOBALS["objSrcUser"]))
    {
        $objSrcUser = &$GLOBALS["objSrcUser"];
        $arrPrefs   = $objSrcUser->get_preferences();
    }
    else
    {
        $arrPrefs = array (
        NEW_N => 0, NEW_S => 0, NEW_O => 0, NEW_L => 0, NEW_A => 0, NEW_W => 0,
        NEW_G => 0, NEW_DR => 0);
    }

    // M: Base values in array (Starting with Announcements)
    $arrArrLinks[0][] = 'news';            // 0 = value used in URL
    $arrArrLinks[0][] = 'Announcements';   // 1 = name of link
    $arrArrLinks[0][] = $arrPrefs[NEW_N];  // 2 = BOOLEAN: highlight on / off

    include_once('inc/functions/forums.php');
    if (has_read_access(select_forum('staff101')))
        $arrArrLinks[] = array('staff101', 'Staff Forum', $arrPrefs[NEW_S]);

    if (has_read_access(select_forum('alliance')))
        $arrArrLinks[] = array('alliance', 'Alliance Forum', $arrPrefs[NEW_A]);

    if (has_read_access(select_forum('dev')))
        $arrArrLinks[] = array('dev', 'Dev', $arrPrefs[NEW_D]);

    if (has_read_access(select_forum('lno')))
        $arrArrLinks[] = array('lno', 'LnO', $arrPrefs[NEW_L]);

    if (has_read_access(select_forum('ops')))
        $arrArrLinks[] = array('ops', 'Ops', $arrPrefs[NEW_O]);

    // M: All-access forums
    $arrArrLinks[] = array('world', 'World Forum', $arrPrefs[NEW_W]);
    $arrArrLinks[] = array('game', 'Game Talk', $arrPrefs[NEW_G]);

    if (has_read_access(select_forum('dragon')))
        $arrArrLinks[] = array('dragon', 'The Dragon Lair', $arrPrefs[NEW_DR]);

    $str = '';
    for ($key = 0; $key < count($arrArrLinks); $key++)
    {
        $page  = $arrArrLinks[$key][0];
        $title = $arrArrLinks[$key][1];
        $bool  = $arrArrLinks[$key][2];

        $strClass = '';
        if ($show != $page)
        {
            $strClass = '';
            if ($bool == 1)
                $strClass = ' class="check_new"';

            $str .= '<a href="main.php?cat=' . $cat . '&amp;page=forums&amp;set=' .
                   $page . '&amp;mode=threads"' . $strClass . '>' . $title . '</a>';
        }
        else
        {
            $str .= '<strong>' . $title . '</strong>';
        }
        $str .= ' | ';
    }
    return '<div class="center">' . substr($str, 0, -2) . '</div>';
}
?>
