<?php
//******************************************************************************
// Pages: preferences.inc.php                           November 06, 2007 Martel
// History: November 06, 2007 M: Recoded plenty of options for the new server
//******************************************************************************

function include_preferences_text()
{
    global  $orkTime;

    include_once('inc/classes/clsGame.php');

    $objSrcUser       = &$GLOBALS["objSrcUser"];
    $arrSrcUserInfos  = $objSrcUser->get_user_infos();
    $arrSrcStats      = $objSrcUser->get_stats();
    $iUserId          = $objSrcUser->get_userid();
    $objGame          = new clsGame();

    // Navigational Links
    if ($arrSrcStats[TYPE] == 'elder' || $arrSrcStats[TYPE] == 'coelder')
    {
        echo $topLinks =
            '<div class="center">| <b>Options</b> ' .
            '| <a href="main.php?cat=game&page=elder">Elder Options</a> ' .
            '|' .
            '</div>';
    }

    echo '<div id="textBig">';

    echo '<h2>Account Settings</h2>' .
         '<p>' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=layout">Interface Settings</a> :: ' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=profile">Profile Settings</a> :: ' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=request_name">Request Name Change</a>' .
         '</p>';

    echo '<h2>Account Options</h2>' .
         '<p>' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=request_merge">Request Merge</a> :: ' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=pause_account">Pause Account</a> :: ' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=reset_account">Reset Account</a> :: ' .
             '<a href="main.php?cat=game&amp;page=preferences&amp;task=delete_account">Delete Account</a>' .
         '</p>';

    if ($arrSrcStats[LEVEL] > 1)
    {
        echo "<h2>Staff Options</h2>" .
             '<p>' .
                 '<a href="main.php?cat=game&amp;page=resort_tools">Resort Tools</a>' .
             '</p>';
    }
    echo '</div>';

    //==========================================================================
    // Below you will find the options for people to manage their accounts
    //==========================================================================
    echo '<div id="textBig">';

    $task = '';
    if (isset($_GET['task'])) { $task = $_GET['task']; }
    switch ($task)
    {
        case "layout":

?>
            <h2>Interface Settings</h2>

<?php
            // XSS secure May 21, 2006 -Martel & Damadm00
            $widths     = array( 1 => 750, 1060);
            $alignments = array( 1 => 'left', 'center');
//             $designs    = array( 1 => 'blue', 'ice', 'red');
            $designs    = array( 1 => 'forest_v1', 'black_v2', 'ork_v2');
//             $output     = array( 1 => 'Trade Winds', 'Castle Walls', 'Uruk Hatred');
            $output     = array( 1 => 'Forest Greens', 'Cursed Nights', 'Old Orkfia');

            // Handle POST Requests
            if (isset($_POST['width']) && isset($_POST['alignment']) && isset($_POST['design']))
            {
                if (in_array($_POST['width'], $widths) && in_array($_POST['alignment'], $alignments) && in_array($_POST['design'], $designs))
                {
                    $arrSrcDesignSafe[WIDTH]     = $_POST['width'];
                    $arrSrcDesignSafe[ALIGNMENT] = $_POST['alignment'];
                    $arrSrcDesignSafe[COLOR]     = $_POST['design'];
                    $objSrcUser->set_designs($arrSrcDesignSafe);

                    // A gift to you Damadm00 ^^ -Martel
                    header("Location: main.php?cat=game&page=preferences&task=layout");
                }
            }
            elseif (isset($_POST['guide_links']))
            {
                if (isset($_POST['check']))
                {
                    echo '<p><strong>Saved!</strong> Guide links will now show.</p>';
                    $objSrcUser->set_preference(GUIDE_LINKS, ON);
                }
                else
                {
                    echo '<p><strong>Saved!</strong> Guide links will no longer show.</p>';
                    $objSrcUser->set_preference(GUIDE_LINKS, OFF);
                }
            }

            // GUIDE LINKS OPTION
            $strChecked = '';
            if ($objSrcUser->get_preference(GUIDE_LINKS) == ON)
                $strChecked = ' CHECKED';

            // INTERFACE OPTIONS
            $arrSrcDesign  = $objSrcUser->get_designs();

?>
            <h3>Interface Control</h3>

            <form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=layout">

                <label for="skin">Skin:</label><br />
                <select name="design" id="skin">
<?php
                    foreach ($designs as $key=>$color) {
                        if($color == $arrSrcDesign['color']) {
                            echo "<option value=\"".$color."\" selected=\"selected\">".$output[$key]."</option>";
                        } else {
                            echo "<option value=\"".$color."\">".$output[$key]."</option>";
                        }
                    }
?>
                </select><br /><br />

                <label for="screen width">Width:</label><br />
                <select name="width" id="screen width">
<?php
                    foreach ($widths as $width) {
                        if($width == $arrSrcDesign['width']) {
                            echo "<option value=\"".$width."\" selected=\"selected\">".$width."</option>";
                        } else {
                            echo "<option value=\"".$width."\">".$width."</option>";
                        }
                    }
?>
                </select><br /><br />

                <label for="layout alignment">Alignment:</label><br />
                <select name="alignment" id = "layout alignment">
<?php
                    foreach ($alignments as $alignment) {
                        if($alignment == $arrSrcDesign['alignment']) {
                            echo "<option value=\"".$alignment."\" selected=\"selected\">".$alignment."</option>";
                        } else {
                            echo "<option value=\"".$alignment."\">".$alignment."</option>";
                        }
                    }
?>
                </select>

                <input type="submit" value="Update interface" />
            </form>

            <h3>Ingame Help System</h3>

            <form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=layout">
                <label for="1">Show Guide Links: </label>
                <input type="checkbox" id="1" name="check" <?php echo $strChecked; ?>>
                <input type="hidden" name="guide_links" />
                <input type="submit" value="Update interface" />
            </form>
<?php

        break;
        case "profile":

            //==================================================================
            // Action handler part of Profile            October 28, 2007 Martel
            //==================================================================
            if (isset($_POST['realname']) && !empty($_POST['realname']))
            {
                // Enforce maxlength in form
                $strSafe = substr($_POST['realname'], 0, 24);

                // Same as in registration, important to keep the order right
                $strSafe = addslashes(strip_tags(trim($strSafe)));

                if (!empty($strSafe))
                {
                    $objSrcUser->set_user_info(REALNAME, $strSafe);
                    echo '<p>Thank you, your name has been updated.</p>';
                }
            }
            elseif (isset($_POST['name']) && !empty($_POST['name']))
            {
                // Enforce maxlength in form
                $strSafe = substr($_POST['name'], 0, 16);

                // Same as in registration, important to keep the order right
                $strSafe = addslashes(strip_tags(trim($strSafe)));

                $resSQL = mysql_query("SELECT * FROM stats WHERE name = '$strSafe'");
                $arrSQL = mysql_fetch_array($resSQL);
                if ($arrSQL)
                {
                    echo "<p>Sorry, that leader name is taken.</p>";
                    $strSafe = '';
                }

                if (!empty($strSafe))
                {
                    $objSrcUser->set_stat(NAME, $strSafe);
                    echo '<p>Thank you, your name has been updated.</p>';
                }
            }
            elseif (isset($_POST['password1']) && !empty($_POST['password1']))
            {
                $password1  = $_POST['password1'];
                $password2  = $_POST['password2'];
                $password   = $_POST['password'];

                $check = mysql_query("SELECT * FROM user WHERE password = sha1('$password') AND id = $iUserId") or die("Error Retrieving Password From DB.");
                if (mysql_num_rows($check) == 1 && $password1 == $password2)
                {
                    $strPw1Safe = sha1($password1);
                    $objSrcUser->set_user_info(PASSWORD, $strPw1Safe);
                    echo "<p>Your password has been changed.</p>";

                    $email    = stripslashes($objSrcUser->get_preference(EMAIL));
                    $username = stripslashes($arrSrcUserInfos[USERNAME]);
                    mail("$email","ORKFiA New Password","Hello, \nYour password has been updated with the one you requested =) \n\nUsername: $username \nPassword: $password1" . SIGNED_ORKFIA, "From: ORKFiA <" . EMAIL_REGISTRATION . ">\r\nX-Mailer: PHP/" . phpversion() . "\r\nX-Priority: Normal");
                }
                else
                {
                    echo "<p>Either you entered the wrong current password, or your new password wasn't matching in both entries.</p>";
                }
            }
            elseif (isset($_POST['email']) && !empty($_POST['email']))
            {
                $strEmailSafe = $_POST['email'];

                // This check is used on the registration and verification pages
                if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
                    '@'.
                    '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
                    '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $strEmailSafe))
                {
                    echo "<p>Sorry, invalid e-mail address.</p>";
                }
                else
                {
                    $pref_search = mysql_query("SELECT * FROM preferences WHERE email = " . quote_smart($strEmailSafe));
                    $pref_search = mysql_fetch_array($pref_search);
                    if ($pref_search)
                    {
                        echo "<p>Sorry, that email is in use.</p>";
                    }
                    else
                    {
                        $strCode = '';
                        for($i = 0; $i < 12; $i++)
                        {
                            $strCode .= substr("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", rand(0, 61), 1);
                        }

                        // Activation email sent to all new players
                        mail($strEmailSafe, "ORKFiA Verification", "Thank you for updating your profile =) \n\nHere is your new verification code: $strCode \n\nWe hope you enjoy your stay in ORKFiA =)" . SIGNED_ORKFIA, "From: ORKFiA <" . EMAIL_REGISTRATION . ">\r\nX-Mailer: PHP/" . phpversion() . "\r\nX-Priority: Normal");

                        $arrNewPrefs = array (
                            EMAIL_ACTIVATION => $strCode,
                            EMAIL => $strEmailSafe );
                        $objSrcUser->set_preferences($arrNewPrefs);

                        echo '<p>Thank you, your e-mail has been updated and ' .
                        'a new verification code has been sent to it.</p>';
                    }
                }
            }

            $arrUsers = $objSrcUser->get_user_infos();
            $arrStats = $objSrcUser->get_stats();
            $arrUsers[REALNAME] = stripslashes($arrUsers[REALNAME]);
            $arrStats[NAME]     = stripslashes($arrStats[NAME]);
            $strEmail           = stripslashes($objSrcUser->get_preference(EMAIL));

            //==================================================================
            // Output part of Profile                    October 28, 2007 Martel
            //==================================================================
            echo '<h2>Profile Settings</h2>';

            $strRealName =
                '<h3>Real Name</h3>' .

                '<p>About your privacy: Only admins and Law & Order ' .
                'can see your real name, and only when enforcing the Code ' .
                'of Conduct (to help judge that you are a unique individual). ' .
                'We believe it is your right to remain anonymous, and you may ' .
                'rest assured this will never be shown to anybody else.</p>' .

                '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=profile">' .

                '<label for="real_name">Your Real Name:</label>' . '<br />' .

                '<input type="text" name="realname" id="real_name" value="' .
                $arrUsers[REALNAME] . '" maxlength="24" size="24" /> ' .
                '<input type="submit" value="Update my profile" />' .

                "</form>" .

                '<blockquote>"You also agree that by creating your account, ' .
                'all details you have supplied are correct, persons who ' .
                'create accounts with false information may be removed from ' .
                'ORKFiA."</blockquote>';
            echo $strRealName;

            $strLeaderName =
                '<h3>Leader Name</h3>' .

                '<p>Your current nick name with us is <em>' . $arrStats[NAME] .
                '</em>. This is used in the forums, when sending mail using ' .
                'the Orkfia Mail and in various alliance contexts. If someone ' .
                'called out "<em>Hey ' . $arrStats[NAME] .  '!</em>" in the ' .
                'street, would you react to it and think it must be another ' .
                'ORKFiAN?</p>' .

                '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=profile">' .

                '<label for="new_leader_name">Your Leader Name:</label><br />' .

                '<input type="text" name="name" id="new_leader_name" value="' .
                $arrStats[NAME] . '" maxlength="16" size="16" /> ' .

                "<input type=\"submit\" value=\"Update my profile\" />" .

                "</form>";
            echo $strLeaderName;

            $strPassword =
                '<h3>Password</h3>' .

                '<p>For your own safety, make sure your password consists of ' .
                'minimum 8 characters including numbers and capital letters.' .
                '</p>' .

                '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=profile">' .

                '<label for="current_pw">Current Password:</label><br />' .
                '<input type="password" name="password" id="current_pw" />' .

                '<br /><br />' .

                '<label for="new_pw">New Password:</label><br />' .
                '<input type="password" name="password1" id="new_pw" ' .
                'maxlength="30" />' .

                '<br /><br />' .

                '<label for="repeat_pw">Confirm Password:</label><br />' .
                '<input type="password" name="password2" id="repeat_pw" ' .
                'maxlength="30" /> ' .

                '<input type="submit" value="Update my profile" />' .

                "</form>";
            echo $strPassword;

            $strEmail =
                '<h3>E-mail</h3>' .

                '<p>It is important that you get this right, if you would ' .
                'forget your password or if our ORKFiA Staff Team need to ' .
                'contact you this e-mail will be used. We will never share ' .
                'this with anyone else, privacy is a major concern to us.</p>' .

                '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=profile">' .

                '<label for="new_email">' . "Your e-mail:" . '</label><br />' .
                '<input type="text" name="email" id="new_email" value="' .
                $strEmail . '" maxlength="30" size="30" />' .
                ' <input type="submit" value="Update my profile" />' .

                "</form>";
            echo $strEmail;


        break;
        case "request_name":

            $strSwitch = $objGame->get_game_switch(NAME_CHANGE_PROGRAM);
            if ($strSwitch == 'on')
            {
                echo "<h2> Request Name Change </h2>";
                echo "<p>Here you may request that the ORKFiA Staff Team " .
                     "changes your tribe name. Normally you would only change " .
                     "your tribe name if you reset your account, or if " .
                     "your tribe dies.</p>";

                if (isset($_POST['action']) && $_POST['action'] == "yes")
                {
                    if (!empty($_POST['newtribename']) && !empty($_POST['reason']))
                    {
                        $strTribe = $_POST['newtribename'];
                        $strTribe = quote_smart(strip_tags(trim($strTribe)));
                        $reason = quote_smart(str_replace("'", "", strip_tags(trim($_POST['reason']))));
                        $check = mysql_query("SELECT * FROM stats WHERE tribe = $strTribe");
                        if (mysql_num_rows($check) != 0){
                            echo "<p>Sorry, someone is already using that tribe name.</p>";
                        } else {

                            echo "<p>Thank you, your request has been sent to staff. You will soon recieve a confirmation note.</p>";
                            mysql_query("INSERT INTO namechanges (id, tribe, requestedname, reason, mod_id, reason_declined, request_status, oldname) VALUES ( '', '$arrSrcStats[id]', $strTribe, $reason, '0', '0', 'ready', '$arrSrcStats[tribe]')");
                        }
                    }
                    else
                    {
                        echo '<p>' .
                                 "You need to fill in a new tribe name and a reason." .
                             '</p><p>' .
                                 '<a href="main.php?cat=game&amp;page=preferences&amp;task=request_name">Return</a>' .
                             '</p>';
                    }
                }
                elseif (isset($_POST['action']) && $_POST['action'] == "cancel")
                {
                    echo "<p>Your request to change your tribe name has been deleted.</p>";
                    mysql_query("UPDATE namechanges SET request_status = 'cancelled' WHERE tribe = $arrSrcStats[id] AND request_status = 'ready' ");
                }
                else
                {
                    $strSQL = "SELECT requestedname FROM namechanges WHERE tribe = $arrSrcStats[id] AND request_status = 'ready'";
                    $result2 = mysql_query($strSQL);
                    if ($result = mysql_fetch_array($result2))
                    {
                        echo "<p>You have a pending name request.<br /> Your requested name is: " . stripslashes($result['requestedname']) . ".</p><p>If you wish to cancel your name request you may use the button below.</p>" .
                             "<form method=\"post\" action=\"main.php?cat=game&amp;page=preferences&amp;task=request_name\">" .
                             "<input type=hidden value='cancel' name='action'>" .
                             "<input type=submit value='Cancel Request'>" .
                             "</form>";
                    }
                    else
                    {
?>
            <form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=request_name">

                <label for="new tribe name">New Tribe Name:</label><br />
                <input type="text" size="30" name="newtribename"
                maxlength="30" id="new tribe name"
                value="<?=stripslashes($arrSrcStats[TRIBE]);?>" /><br /><br />

                <label for="reason">Reason For Changing:</label><br />
                <input type="text" size="48" name="reason" maxlength="100" id="reason" />
                <input type="hidden" name="action" value="yes" />

                <input type="submit" value="Send request to Operations staff" />
            </form>
<?php
                    }
                }
            }
            else
            {
                echo '<p class="positive">Currently name changes are closed. ' .
                     'It is not possible to request any more name changes.</p>';
            }

        break;
        case "request_merge":

            echo '<h2>Request Merge</h2>';

            $mergeswitch = $objGame->get_game_switch(MERGER_PROGRAM);
            if ($mergeswitch == "on")
            {
                if (isset($_POST['do']) && $_POST['do'] == "cancel")
                {
                    $bleh = mysql_query("SELECT * FROM mergers WHERE tribe = $arrSrcStats[id] AND ( request_status = 'not ready' OR request_status = 'ready')");
                    $bleh = mysql_fetch_array($bleh);
                    $bleh2 = mysql_query("SELECT * FROM stats WHERE type='elder' AND kingdom='$bleh[target]'");
                    $bleh2 = mysql_fetch_array($bleh2);
                    $message = "The merge request done by " .
                    $arrSrcStats[TRIBE] . " (#" . $arrSrcStats[ALLIANCE].
                    ") has been cancelled by the player.";
                    mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', $bleh2[id], 0, $orkTime, 'Merge Request Cancelled', ".quote_smart($message).", 'new', 'received')");
                    mysql_query("DELETE FROM mergers WHERE tribe = $arrSrcStats[id] AND ( request_status = 'not ready' OR request_status = 'ready')");
                    echo "<p>Your merge request has been deleted.</p>";

                    echo '</div>';
                    return;
                }

                $result2 = mysql_query("SELECT * FROM mergers WHERE tribe = $arrSrcStats[id] AND ( request_status = 'not ready' OR request_status = 'ready')");
                $result2 = mysql_fetch_array($result2);

                if (isset($result2['target']))
                {
                    echo "<p>You have a pending merge request to alliance (#" .
                    $result2['target'] . ").<br />If you want to cancel this " .
                    "merge request click on the button below.</p>";

                    echo $strCancelForm =
                        '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=request_merge">' .
                            '<input type="hidden" name="do" value="cancel" />' .
                            '<input type="submit" value="Cancel merge request" />' .
                        '</form>';

                    echo '</div>';
                    return;
                }

                // Action on sent confirmation from target elder (by PM link)
                if (isset($_GET['checker']) && $_GET['checker'] > 0)
                {
                    $checker  = intval($_GET['checker']);
                    $mergerid = intval($_GET['mergerid']);

                    $result = mysql_query("SELECT * FROM mergers WHERE tribe = $mergerid ORDER BY id DESC LIMIT 1");

                    if (mysql_num_rows($result) > 0)
                    {
                        $result = mysql_fetch_array($result);

                        if ($checker == 1 && $result['target'] == $arrSrcStats[ALLIANCE])
                        {
                            mysql_query("UPDATE mergers SET auth = 1, request_status = 'ready' WHERE tribe = $mergerid AND request_status = 'not ready'") or die("<p>Error while accepting tribe.</p>");

                            $message = "Your request to merge to alliance $result[target] has been accepted by their elder.<br /><br />A member of the Operations Resort will merge you as soon as possible =)";
                            $sqlquery = "INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', $result[tribe], 0, $orkTime, 'Merge Request Accepted', " . quote_smart($message) . ", 'new', 'received')";
                            mysql_query($sqlquery);

                            echo "<p>Thank you, we have now accepted a new member to the alliance.</p>";
                        }
                        elseif ($checker == 2 && $result['target'] == $arrSrcStats[ALLIANCE])
                        {
                            mysql_query("DELETE FROM mergers WHERE tribe = $result[tribe] AND ( request_status = 'not ready' OR request_status = 'ready') ") or die("<br />Error while rejecting tribe.<br />");

                            $message = "Your request to merge to alliance $result[target] has been rejected by their elder.<br /><br />";
                            $sqlquery = "INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', $result[tribe], 0, $orkTime, 'Merge Request Rejected', " . quote_smart($message) . ", 'new', 'received')";
                            mysql_query($sqlquery);

                            echo "<p>Thank you for answering the request.</p>";
                        }
                        else
                        {
                            echo "<p>This tribe is no longer applying for a merge into your alliance.</p>";
                        }
                    }
                    else
                    {
                        echo "<p>The merge request no longer exists.</p>";
                    }
                }
                // Action on sent merge request by player
                elseif (isset($_POST['do']) && $_POST['do'] == "yes")
                {
                    $iTargetAlli = intval($_POST['target']);
                    if (empty($iTargetAlli))
                    {
                        echo "<p>You have to fill in an alliance number that " .
                             "you wish to merge into. Please try again. </p>";

                        unset($_POST['do']);
                        echo '</div>';
                        return;
                    }
                    elseif ($iTargetAlli == $arrSrcStats[ALLIANCE])
                    {
                        echo "<p>You can not merge into your own alliance. " .
                             "Please fill in another alliance number.</p>";

                        echo '</div>';
                        return;
                    }
                    elseif ($iTargetAlli <= 10)
                    {
                        echo "<p>You can not request to merge into a staff " .
                             "alliance. Please fill in another alliance " .
                             "number.</p>";

                        echo '</div>';
                        return;
                    }

                    $strSQL = "SELECT * FROM " . TBL_ALLIANCE . " WHERE id = " .
                              $iTargetAlli;
                    if (mysql_num_rows(mysql_query($strSQL)) != 1)
                    {
                        echo "<p>We're sorry, this alliance does not exist.</p>";

                        echo '</div>';
                        return;
                    }

                    $objTrgAlliance = new clsAlliance($iTargetAlli);
                    $arrUserids = $objTrgAlliance->get_userids();
                    if (count($arrUserids) >= MAX_ALLIANCE_SIZE)
                    {
                        echo "<p>We're sorry, this alliance is full.</p>";

                        echo '</div>';
                        return;
                    }
                    else
                    {
                        // Default: no name change requested along with merger
                        $newtribename = $arrSrcStats[TRIBE];
                        $safetribename = quote_smart(stripslashes($newtribename));
                        if (isset($_POST['newtribename']) && !empty($_POST['newtribename']))
                        {
                            $safetribename = quote_smart(strip_tags(trim($_POST['newtribename'])));
                            $id = $objSrcUser->get_userid();
                            $check = mysql_query("SELECT * FROM stats WHERE tribe = $safetribename AND id != $id");
                            if (mysql_num_rows($check) != 0)
                            {
                                echo "<p>Someone is already using that tribename.</p>";

                                echo '</div>';
                                return;
                            }
                            $message = "You have an awaiting merge request.<br /><br />" . $arrSrcStats[TRIBE] . " (#" . $arrSrcStats[ALLIANCE] . ") wishes to join your alliance.<br />They did also request a new name.<br /><br />Do you <a href= \"main.php?cat=game&amp;page=preferences&amp;task=request_merge&amp;checker=1&amp;mergerid=" . $arrSrcStats['id'] . "\">accept their request</a> or <a href= \"main.php?cat=game&amp;page=preferences&amp;task=request_merge&amp;checker=2&amp;mergerid=" . $arrSrcStats['id'] . "\">deny their request</a>?<br />";
                        }
                        else
                        {
                            $message = "You have an awaiting merge request.<br /><br />" . $arrSrcStats[TRIBE] . " (#" . $arrSrcStats[ALLIANCE] . ") wishes to join your alliance.<br /><br />Do you <a href=\"main.php?cat=game&amp;page=preferences&amp;task=request_merge&amp;checker=1&amp;mergerid=" . $arrSrcStats['id'] . "\">accept</a> or <a href=\"main.php?cat=game&amp;page=preferences&amp;task=request_merge&amp;checker=2&amp;mergerid=" . $arrSrcStats['id'] . "\">deny</a> their request?";
                        }

                        $iElderId = mysql_query("SELECT id FROM stats WHERE type = 'elder' AND " . ALLIANCE . " = $iTargetAlli");
                        $iElderId = mysql_fetch_array($iElderId);
                        $iElderId = $iElderId['id'];
                        if (!empty($iElderId))
                            mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', $iElderId, 0, $orkTime, 'Merge Request', " . quote_smart($message) . ", 'new', 'received')");
                        else
                        {
                            echo "<p>There's no elder in this alliance!</p>";

                            echo '</div>';
                            return;
                        }

                        // Insert into mergers
                        mysql_query("INSERT INTO mergers (id, tribe, target, origin, auth, tribe_status, request_status, mtype, merge_time, newname, oldname, mod_id, declined_reason) VALUES ('', $id, $iTargetAlli, {$arrSrcStats[ALLIANCE]}, 0, " . quote_smart($arrSrcStats[TYPE]) . ", 'not ready', 1, $orkTime, $safetribename, " . quote_smart($arrSrcStats[TRIBE]) . ", '0', '0')")or die("Merger error");
                        echo "<p>The request was sent to the elder of (#" .
                             $iTargetAlli . ")!</p>";
                    }
                }
                // there is no action (no link activation or form sent)
                else
                {
                    include_once("inc/functions/war.php");
                    $warTarget = war_target($arrSrcStats[ALLIANCE]);
                    $strDate = $objSrcUser->get_gamestat(SIGNUP_TIME);
                    $iHours = $objSrcUser->get_user_info(HOURS);

                    $bAllow = TRUE;
                    if ($warTarget != 0)
                    {
                        echo "<p>The option to merge is not available during war.</p>";

                        $bAllow = FALSE;
                    }
                    elseif (strtotime("-6 weeks") < strtotime($strDate))
                    {
                        echo '<p>You may merge for another ' .
                        ceil((strtotime($strDate) - strtotime("-6 weeks")) / 86400).
                        ' days.</p>';

                        $bAllow = TRUE;
                    }
                    elseif ($iHours < 72)
                    {
                        echo '<p>You may merge for another ' .
                        ceil(abs(($iHours - 72) / 12)) . ' orkfian years';

                        $bAllow = TRUE;
                    }

                    if ($bAllow)
                    {
                        echo $strForm =
                        '<h3>Merging into another alliance</h3>' .
                        '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=request_merge">' .
                            'Alliance # you wish to request a merge with: ' .
                            '<input type="text" size="4" name="target" maxlength="4" />' .
                            '<br />' .
                            'New tribe name you want: ' .
                            '<input type="text" size="16" name="newtribename" maxlength="30" /> (optional)' .
                            '<br /><br />' .
                            '<input type="hidden" name="do" value="yes" />' .
                            '<input type="submit" value="Send merge request" />' .
                        '</form>';
                    }
                    elseif (strtotime("-6 weeks") > strtotime($strDate))
                    {
                        echo '<p>Currently your ruler is too old to merge.</p>';
                    }
                }

                echo $strRules =
                    '<h3>Merger Rules</h3>' .
                    '<ol>' .
                        '<li>You may request a merge ' .
                        'anywhere, at any time, within 6 weeks after you ' .
                        'sign up</li>' .

                        '<li>Veteran players may only merge before their ' .
                        'ruler age is 22 (within 3 days after restarting)' .

                        '<li>Exception to rule #2: If you were <em>defected' .
                        '</em> you may request a merges at any ruler age, but ' .
                        'only <em>after</em> 1 week</li>' .

                        '<li>No one may merge during wars</li>' .
                    '</ol>';
            }
            else
            {
                echo "<p>Currently merging is closed. You can not request/accept any more mergers.</p>";
            }

        break;
        case "delete_account":

            echo '<h2>Delete Account</h2>';

            include_once("inc/functions/war.php");
            $warTarget = war_target($arrSrcStats[ALLIANCE]);
            if ($warTarget > 0)
            {
                ECHO "<p>You are not allowed to delete during war.</p>";

                echo '</div>';
                return;
            }

            $arrSrcArmysOut = $objSrcUser->get_armys_out();
            if (array_sum($arrSrcArmysOut) > 0)
            {
                echo "<p>Sorry, you can not delete with troops out.</p>";

                echo '</div>';
                return;
            }

            if (isset($_GET['do']) && $_GET['do'] == "yes" && isset($_POST['checker']))
            {
                echo "<p>It's so sad to see you go.</p>";

                //include_once("inc/functions/research.php");
                //delete_my_rps($iUserId);
                // no more - AI 07/05/07


                $objSrcUser->set_stat(NAME, 'deleted');
                $objSrcUser->set_stat(ALLIANCE, 0);
                mysql_query("DELETE FROM rankings_personal WHERE id = $iUserId");
                setcookie("userid", "");
                echo "<p>...Thank you for playing ORKFiA...</p>";

                // Alliance News
                $iAlliance = $objSrcUser->get_stat(ALLIANCE);
                $orkTime = date("YmdHis");
                $strSQL = "INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('','$orkTime', '', 'Selfdeletion', 0, 0, 1, 'tribe deleted', '" . $arrSrcStats[TRIBE] . " deleted their tribe.', " . $iAlliance . ", $iAlliance)";
                mysql_query($strSQL);

                echo '</div>';
                return;
            }
            else
            {
                $strForm =
                    '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=delete_account&amp;do=yes">' .
                        'Are you absolutely sure you want to <em>delete</em> your account? ' .
                        '<input type="checkbox" name="checker" id="y1"> ' .
                        '<label for="y1">(yes)</label>' .
                        '<br /><br />' .
                        'This action is permanent and cannot be undone.' .
                        '<br /><br />' .
                        '<input type="submit" value="Delete my account">' .
                    '</form>';

                echo $strForm;
            }

        break;
        case "reset_account":

            echo '<h2>Reset Account</h2>';

            include_once("inc/functions/war.php");
            $warTarget = war_target($arrSrcStats[ALLIANCE]);
            if ($warTarget > 0)
            {
                echo '<p>Sorry, you are not allowed to reset during war.</p>';
                echo '</div>';
                return;
            }

            $arrSrcArmysOut = $objSrcUser->get_armys_out();
            if (array_sum($arrSrcArmysOut) > 0)
            {
                echo '<p>Sorry, you cannot reset while you have troops out.</p>';
                echo '</div>';
                return;
            }

            if (isset($_GET['do']) && $_GET['do'] == "yes" && isset($_POST['checker']))
            {
                // Mark tribe for resetting
                $objSrcUser->set_stat(RESET_OPTION, "'yes'");

                // Force update of rankings
                include_once('inc/functions/update_ranking.php');
                doUpdateRankings($objSrcUser, 'yes');

                $strP =
                    '<p>' .
                        'Your account has been reset!' .
                    '</p><p>' .
                        '<a href="main.php?cat=game&amp;page=tribe">' .
                        'Continue' . '</a>' .
                    '</p>';

                echo $strP;
            }
            else
            {
                $strForm =
                    '<form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=reset_account&amp;do=yes">' .
                        'Are you absolutely sure you want to <em>reset</em> your account? ' .
                        '<input type="checkbox" name="checker" id="1"> ' .
                        '<label for="1">(yes)</label>' .
                        '<br /><br />' .
                        'This action is permanent and cannot be undone.' .
                        '<br /><br />' .
                        '<input type="submit" value="Reset my account">' .
                    '</form>';

                echo $strForm;
            }

        break;
        case "pause_account":

            echo '<h2>Pause Account</h2>';

            $PauseModeActive = $objGame->get_game_switch(PAUSE_MODE_ACTIVE);
            if ($PauseModeActive != 'on')
            {
                echo "<p><span class=positive>We're sorry, currently you may " .
                     "not pause your account due to a global event.</span></p>";

                echo '</div>';
                return;
            }

            $arrSrcUserInfos  = $objSrcUser->get_user_infos();
            if ($arrSrcUserInfos[PAUSE_ACCOUNT] == 0 && ($arrSrcUserInfos[NEXT_ATTACK] > 0 && $arrSrcUserInfos[HOURS] > 0))
            {
                echo "<p>" . $arrSrcUserInfos[NEXT_ATTACK] . " hour(s) left before your troops are home.</p>";

                echo '</div>';
                return;
            }

            $arrSrcBuilds = $objSrcUser->get_builds();
            if ($arrSrcUserInfos[PAUSE_ACCOUNT] == 0 && ($arrSrcBuilds[LAND_T1] > 0 || $arrSrcBuilds[LAND_T2] > 0 || $arrSrcBuilds[LAND_T3] > 0 || $arrSrcBuilds[LAND_T4] > 0))
            {
                echo "<p>You can't pause while land is incoming.</p>";

                echo '</div>';
                return;
            }

            if ($arrSrcUserInfos[PAUSE_ACCOUNT] > 49)
            {
                echo
                    "<p>Your account will be paused in " .
                    ($arrSrcUserInfos[PAUSE_ACCOUNT] - 49) .
                    " updates, you will be able to unpause it in " .
                    ($arrSrcUserInfos[PAUSE_ACCOUNT] - 1) . "</p>";
                echo '</div>';
                return;
            }

            if ($arrSrcUserInfos[PAUSE_ACCOUNT] > 1)
            {
                echo
                    "<p>" . ($arrSrcUserInfos[PAUSE_ACCOUNT] - 1) . " updates left " .
                    "until you may un-pause your account and play again.</p>";

                echo '</div>';
                return;
            }

//             if($arrSrcUserInfos[PAUSE_ACCOUNT] == 1 && war_target($arrSrcStats[ALLIANCE]) > 0 )
//             {
//                 echo "<p>You cannot unpause during war.</p>";

//                 echo '</div>';
//                 return;
//             }

            if (isset($_POST['do']) && $_POST['do'] == "yes")
            {
                // Pause Account Routines. Martel July 13, 2006
                $arrSrcUser = $objSrcUser->get_user_infos();

                if ($arrSrcUser[NEXT_ATTACK] <= 1)
                    $arrSrcUser[NEXT_ATTACK] = 1;

                $arrNewSrcUser = array
                (
                    NEXT_ATTACK => $arrSrcUser[NEXT_ATTACK],
                    PAUSE_ACCOUNT => 55
                );
                $objSrcUser->set_user_infos($arrNewSrcUser);

                // Empty Magic and Thievery Points
                $objSrcUser->set_spell(POWER, 0);
                $objSrcUser->set_thievery(CREDITS, 0);

                // Forced Ranking Update
                include_once('inc/functions/update_ranking.php');
                doUpdateRankings($objSrcUser, 'yes');

                // M: Alliance news (March 07, 2008)
                $objSrcAlliance = $objSrcUser->get_alliance();
                $iWarTarget  = $objSrcAlliance->get_war('target'); // 0=default
                $iAllianceid = $objSrcAlliance->get_allianceid();
                mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'vacation', 0, 0, 1, '', '$arrSrcStats[tribe] (#$iAllianceid) is entering vacation mode', $iAllianceid, $iWarTarget)");

                echo "<p>Your account has been paused, see you back in 2 or more days =)</p>";

                echo '</div>';
                return;
            }
            elseif (isset($_POST['do']) && $_POST['do'] == "no")
            {
                // Un-Pause Account Routines. Martel July 13, 2006
                $arrNewSrcUser = array
                (
                    NEXT_ATTACK => 0,
                    PAUSE_ACCOUNT => 0
                );
                $objSrcUser->set_user_infos($arrNewSrcUser);

                // M: Alliance news (March 07, 2008)
                $objSrcAlliance = $objSrcUser->get_alliance();
                $iWarTarget  = $objSrcAlliance->get_war('target'); // 0=default
                $iAllianceid = $objSrcAlliance->get_allianceid();
                mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'vacation', 0, 0, 1, '', '$arrSrcStats[tribe] (#$iAllianceid) is now back from vacation mode', $iAllianceid, $iWarTarget)");

                echo "<p>Your account has been un-paused, we're glad to see you back =)</p>";
            }
            else
            {
                if ($arrSrcUserInfos[PAUSE_ACCOUNT] > 0)
                {
?>
                <p>You are now allowed to un-pause your account.</p>
                <form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=pause_account">
                    <input type="hidden" name="do" value="no">
                    <input type="submit" value="Leave Vacation Mode">
                </form>
<?php
                }
                else
                {
?>
                <h3>Vacation Mode Rules</h3>
                <ul>
                    <li>Entering Vacation Mode will take 6 hours</li>
                    <li>You <i>may</i> enter Vacation Mode during war</li>
                    <li>You may not enter Vacation Mode if you currently have
                    military out on an attack or if you have incoming acres</li>
                    <li>Your ruler will still age, but your account will be
                    protected during Vacation Mode</li>
                </ul>

                <form method="post" action="main.php?cat=game&amp;page=preferences&amp;task=pause_account">
                    <input type="hidden" name="do" value="yes">
                    <input type="submit" value="Enter vacation mode">
                </form>

                <p>Vacation Mode runs for 48 hours minimum. If you cannot enter
                VM with your account due to circumstances beyond your control,
                you may message a staff member in Law & Order requesting your
                account being suspended and a valid reason why you can't pause
                it yourself.</p>
<?php
                }
            }

        break;
    }
    echo '</div>';
}

?>
