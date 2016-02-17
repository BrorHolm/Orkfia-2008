<?php

function include_elder_text()
{
    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $arrSrcStats = $objSrcUser->get_stats();

    if ($arrSrcStats[TYPE] == "elder" || $arrSrcStats[TYPE] == "coelder")
    {
        $arrSrcUserInfos = $objSrcUser->get_user_infos();
        $arrSrcGoods     = $objSrcUser->get_goods();
        $arrSrcBuilds    = $objSrcUser->get_builds();
        $iUserId         = $objSrcUser->get_userid();
        $objSrcAlliance  = $objSrcUser->get_alliance();
        $arrSrcAlliance  = $objSrcAlliance->get_alliance_infos();

        include_once('inc/classes/clsGame.php');
        $objGame         = new clsGame();

        // Navigational Links
        echo $topLinks =
            '<div class="center">' .
            '| <a href="main.php?cat=game&page=preferences">Options</a> | ' .
            '<b>Elder Options</b> |' .
            '</div>';

        echo
             '<div id="textBig">' .
             "<h2>Elder Options</h2>" .
             '<p>';

        if ($arrSrcStats['type'] == "elder")
            echo " <a href=\"main.php?cat=game&amp;page=elder&amp;task=rename_alliance\">Rename Alliance</a> ::";

        echo
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=redesc_alliance\">Change Description</a> ::" .
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=elder_defect\">Defect A Tribe</a> :: " .
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=alliance_type\">Change Alliance Type</a>" .
             '</p><p>' .
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=elder_message\">Elder Message</a> ::" .
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=banner\">Banner</a> ::" .
             " <a href=\"main.php?cat=game&amp;page=elder&amp;task=alliance_pass\">Alliance Password</a>" .
             "</p>" .

             "</div><br />" .
             '<div id="textBig">';

        //======================================================================
        // Below you will find the options for elders
        //======================================================================
        $task = '';
        if (isset($_GET['task'])) { $task = $_GET['task']; }
        switch ($task)
        {
            case "rename_alliance":

                if (isset($_POST['name']) && !empty($_POST['name']) && $arrSrcStats['type'] == "elder")
                {
                    $strName = strip_tags($_POST['name']);
                    $strName = substr($strName, 0, 16);
                    $strName = trim($strName);
                    $strName = ucfirst($strName);

                    // Check history names (only allow reclaiming an old name)
                    $resSQL = mysql_query("SELECT * FROM " . TBL_GAME_HISTORY .
                              " WHERE alli_name = " . quote_smart($strName) .
                              " AND alli_id != " . $objSrcAlliance->get_allianceid());
                    $iNumRows = mysql_num_rows($resSQL);

                    // Check "today" names
                    $resSQL2 = mysql_query("SELECT * FROM " . TBL_ALLIANCE . " WHERE name = " . quote_smart($strName));
                    $iNumRows2 = mysql_num_rows($resSQL2);

                    if ($strName != '' && $iNumRows == 0)
                    {
                        $objSrcAlliance->set_alliance_info('name', $strName);
                        $objSrcAlliance->set_rankings_alliance('alli_name', $strName);
                        echo $strDiv =
                        '<p>' .
                            "You have successfully changed your alliance name." .
                        '</p><p>' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=rename_alliance">' .
                            'Continue' . '</a>' .
                        '</p>';
                    }
                    else
                    {
                        echo $strDiv =
                        '<p>' .
                            "There will be no change unless you provide me with a valid name, leader. (You may not use the name of an alliance who has been recorded in the infinity rankings or an invalid name.)" .
                        '</p><p>' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=rename_alliance">' .
                            'Try Again ?' . '</a>' .
                        '</p>';
                    }
                }
                elseif ($arrSrcStats['type'] == "elder")
                {

                    echo '<h2>Change Alliance Name</h2>';

                    // some words of advice to elder-persons
                    echo
                        '<div id="textMedium">' .
                            '<p>' .
                                'Read the CoC before you change the name ' .
                                'or description of your alliance. Offensive ' .
                                'names will be changed and the elder punished ' .
                                'accordingly.' .
                            '</p>' .
                            '<p>' .
                                'You may only change into a name of an ' .
                                'alliance that has been recorded in the ' .
                                'History rankings if it belonged to you.' .
                            '</p>' .
                        '</div>';

                    // initialize form
                    echo "<br /><form method=\"post\" action=\"main.php?cat=game&amp;page=elder&amp;task=rename_alliance\">";

                    // alliance name
                    $arrSrcAlliance['name'] = stripslashes($arrSrcAlliance['name']);
                    echo "Alliance Name: <input type='text' name='name' value=\"$arrSrcAlliance[name]\" maxlength='15' size='15'><br /><br />";

                    // submit form
                    echo '<input type=submit value="Change Name">';
                    echo "</form>";
                }

            break;
            case "redesc_alliance":

                if (isset($_POST['do']) && $_POST['do'] == "yes" && $arrSrcStats['type'] == "elder")
                {
                    $desc = strip_tags($_POST['desc']);
                    $desc = substr($desc,0,31);
                    $desc = trim($desc);
                    $desc = ucfirst($desc);
                    $desc = addslashes($desc);

                    mysql_query("UPDATE kingdom SET description = '$desc' WHERE id = " . $arrSrcStats[ALLIANCE]);
                    mysql_query("UPDATE rankings_alliance SET description = '$desc' WHERE id = " . $arrSrcStats[ALLIANCE]);

                    echo $strDiv =
                        '<div id="textSmall">' .
                            '<p>' .
                            "You have successfully changed your alliance description." .
                            '</p><p>' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=redesc_alliance">' .
                            'Continue' . '</a>' .
                            '</p>' .
                        '</div>';
                }
                else
                {
                    echo '<h2>' . 'Change Alliance Description' . '</h2>';

                    // some words of advice to elder-persons
                    echo
                        '<div id="textMedium">' .
                            '<p>' .
                            'You should re-read the CoC before you change name ' .
                            'or description of your alliance, names that ' .
                            'conflict with it will be changed and the elder may ' .
                            'be removed. Because of this, only the elected elder ' .
                            'can change it. ' .
                            '</p>' .
                            '<p>' .
                            'Use the alliance description to state a message ' .
                            'to other ORKFiAns, such as if you are recruiting ' .
                            'or in a particularly bad mood...' .
                            '</p>' .
                        '</div>';

                    // initialize form
                    echo "<br /><form method=\"post\" action=\"main.php?cat=game&amp;page=elder&amp;task=redesc_alliance\">";

                    // alliance description
                    $arrSrcAlliance['description'] = stripslashes($arrSrcAlliance['description']);
                    echo "Alliance Description: <input type=text name=desc value=\"$arrSrcAlliance[description]\" maxlength='30' size='30'><br /><br /> ";

                    // submit form
                    echo "<input type=hidden name=do value=yes>";
                    echo "<input type=submit value='Change Description'>";
                    echo "</form>";
                }

            break;
            case "elder_defect":

                if ($arrSrcStats[TYPE] == "elder")
                {
                    if (isset($_POST['defectId']))
                    {
                        $iDefect      = intval($_POST['defectId']);
                        $objTrgUser   = new clsUser($iDefect);

                        //======================================================
                        // Update of tribe + rankings (non forced)
                        //======================================================
                        include_once('inc/functions/update_ranking.php');
                        doUpdateRankings($objTrgUser);

                        $arrTrgStats  = $objTrgUser->get_stats();
                        $alliance_num = $arrTrgStats[ALLIANCE]; // for random

                        if ($arrTrgStats[ALLIANCE] != $arrSrcStats[ALLIANCE])
                        {
                            echo $strDiv =
                                '<div id="textSmall">' .
                                    '<p>' .
                                    'That tribe is not in your alliance.' .
                                    '</p><p>' .
                                    '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                    'Try Again ?' . '</a>' .
                                    '</p>' .
                                '</div>';

                            echo "</div>"; return;
                        }

                        include_once("inc/functions/war.php");
                        $warTarget = war_target($arrSrcStats[ALLIANCE]);
                        if ($warTarget != 0)
                        {
                            echo $strDiv =
                                '<div id="textSmall">' .
                                    '<p>' .
                                    'You are not allowed to defect anyone during war.' .
                                    '</p><p>' .
                                    '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                    'Try Again ?' . '</a>' .
                                    '</p>' .
                                '</div>';

                            echo "</div>"; return;
                        }

                        $arrTrgArmysOut = $objTrgUser->get_armys_out();
                        if (array_sum($arrTrgArmysOut) > 0)
                        {
                            echo $strDiv =
                                '<div id="textSmall">' .
                                    '<p>' .
                                    'Sorry, you cannot defect this tribe since they still have troops out.' .
                                    '</p><p>' .
                                    '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                    'Try Again ?' . '</a>' .
                                    '</p>' .
                                '</div>';

                            echo "</div>"; return;
                        }

                        // Undo private
                        if ((count($objSrcAlliance->get_userids()) - 1) < floor(MAX_ALLIANCE_SIZE * .25))
                            $objSrcAlliance->set_alliance_info(PRIVATE, 'no');

                        // Martel: New inactivity check.    The Graveyard in #10
                        // Works with both month changes and leap years ;)
                        $inactive = date(TIMESTAMP_FORMAT, strtotime('-2 days'));
                        $arrOnline = $objTrgUser->get_onlines();
                        if (($arrTrgStats[KILLED] == 2 || $arrTrgStats[RESET_OPTION] == 'yes') && $arrOnline['time'] < $inactive)
                        {
                            // Update User Stats
                            $arrTrgStats = array
                            (
                                ALLIANCE => 10,
                                VOTE => 0,
                                TYPE => 'player',
                                INVESTED => 0
                            );
                            $objTrgUser->set_stats($arrTrgStats);

                            // Update User Goods (Martel, December 08, 2006)
                            $arrTrgGoods = array
                            (
                                MARKET_WOOD => 0,
                                MARKET_SOLDIERS => 0,
                                MARKET_FOOD => 0,
                                MARKET_MONEY => 0,
                                CREDITS => 0
                            );
                            $objTrgUser->set_goods($arrTrgGoods);

                            // Set voted for to 0 for all tribes that voted for
                            // the defected tribe (Martel, December 08, 2006)
                            mysql_query("UPDATE user SET vote = 0 WHERE vote = $iDefect");

                            // Update target rankings (Forced)
                            include_once('inc/functions/update_ranking.php');
                            doUpdateRankings($objTrgUser, 'yes');

                            echo $strDiv =
                                '<div id="textSmall">' .
                                    '<p>' .
                                    'You abandoned ' . stripslashes($arrTrgStats[NAME]) .
                                    'to the graveyard in #10. ' .
                                    '</p><p>' .
                                    '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                    'Continue' . '</a>' .
                                    '</p>' .
                                '</div>';

                            // Alliance News
                            $orkTime = date("YmdHis");
                            $strSQL = "INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('','$orkTime', '', 'Defection', 0, 0, 1, 'tribe got moved', '" . $arrTrgStats[TRIBE] . " was defected from (#" . $arrTrgStats[ALLIANCE] . ") to (#10)!', " . $arrTrgStats[ALLIANCE] . ", 10)";
                            mysql_query($strSQL);

                            echo "</div>";
                            return;
                        }

                        //======================================================
                        // Copy of random join routine found in register.php
                        //======================================================
                        $LOWER_LIMIT = 11;
                        $CUR_ALLIANCES = mysql_query("SELECT COUNT(*) as alliances FROM " . TBL_ALLIANCE . " WHERE id >= $LOWER_LIMIT");
                        $CUR_ALLIANCES = mysql_fetch_array($CUR_ALLIANCES);
                        $CUR_ALLIANCES = $CUR_ALLIANCES['alliances'];
                        $blnPrivateCheck = FALSE;

                        $strQuery = mysql_query("SELECT id FROM " . TBL_ALLIANCE);
                        while ($resQuery = mysql_fetch_array($strQuery))
                        {
                            if ($resQuery[0] >= $LOWER_LIMIT)
                                $arrAlliances[] = $resQuery[0];
                        }

                        $i = 0;
                        $loop_stop = 0;
                        $lowest_found = MAX_ALLIANCE_SIZE;
                        $true = FALSE;
                        $break = FALSE;
                        while ($true == FALSE && $break == FALSE)
                        {
                            $dice = rand(1, 6);

                            // CHANGED FROM REGISTER.PHP
                            $strQuery = "SELECT COUNT(*) AS tribes" .
                                         " FROM " . TBL_STAT .
                                        " WHERE " . ALLIANCE . " = $arrAlliances[$i]";
                            $resQuery = mysql_fetch_array(mysql_query($strQuery));

                            // Remember # tribes and # alliances
                            if ($resQuery['tribes'] < $lowest_found)
                                $lowest_found = $resQuery['tribes'];

                            // join random only if there is room left, dice
                            // shows 6, alli != origin alli and != private
                            // CHANGED FROM REGISTER.PHP
                            if ($resQuery['tribes'] < MAX_ALLIANCE_SIZE &&
                                $dice == 6 &&
                                $arrAlliances[$i] != $arrTrgStats[ALLIANCE])
                            {
                                include_once('inc/classes/clsAlliance.php');
                                $objTmpAlliance   = new clsAlliance($arrAlliances[$i]);
                                $arrAllianceInfos = $objTmpAlliance->get_alliance_infos();
                                // don't join any allis that are in war  - AI 01/10/06
                                if ($arrAllianceInfos[PRIVATE] != 'yes' && $objTmpAlliance->get_war(TARGET) == 0)
                                {
                                    // CHANGED FROM REGISTER.PHP
                                    echo $strDiv =
                                    '<div id="textSmall">' .
                                        '<p>' .
                                        "You have abandoned " .
                                         stripslashes($arrTrgStats[TRIBE]) .
                                         " to alliance (#" . $arrAlliances[$i] . ")." .
                                        '</p><p>' .
                                        '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                        'Continue' . '</a>' .
                                        '</p>' .
                                    '</div>';

                                    $alliance_num = $arrAlliances[$i];
                                    $true = TRUE;
                                }
                                else
                                    $blnPrivateCheck = TRUE;
                            }

                            // Stop 'emergency' (no space or simply too many loops)
                            if ($loop_stop > 60)
                            {
                                $break = TRUE;

                                // CHANGED FROM REGISTER.PHP
                                $strDiv =
                                    '<div id="textSmall">' .
                                        '<p>' .
                                        "We couldn't find them a spot.. ";

                                if ($lowest_found == MAX_ALLIANCE_SIZE || $blnPrivateCheck)
                                    $strDiv .= "And our sources indicate there is no room at all! The game seems to be full or the alliances with room are private :(";
                                else
                                    $strDiv .= "But there is room left, so please try your luck and defect again!";

                                $strDiv .=
                                        '</p><p>' .
                                        '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                                        'Try Again ?' . '</a>' .
                                        '</p>' .
                                    '</div>';
                                echo $strDiv;
                            }

                            $i++;

                            // Start over again if we didn't find a spot
                            if ($i >= $CUR_ALLIANCES)
                            {
                                $loop_stop++;
                                $i = 0;
                            }
                        }

                        //======================================================
                        // UPDATE STUFF
                        //======================================================
                        if ($true)
                        {
                            // Alliance News
                            $orkTime = date("YmdHis");
                            $strSQL = "INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('','$orkTime', '', 'Defection', 0, 0, 1, 'tribe got moved', '" . $arrTrgStats[TRIBE] . " was defected from (#" . $arrTrgStats[ALLIANCE] . ") to (#$alliance_num)!', " . $arrTrgStats[ALLIANCE] . ", $alliance_num)";
                            mysql_query($strSQL);

                            // Remove invested research
                            //include_once("inc/functions/research.php");
                            //delete_my_rps($iDefect);
                            // No more - AI 07/05/07

                            // Update User Stats
                            $arrTrgStats = array
                            (
                                ALLIANCE => $alliance_num,
                                VOTE => 0,
                                TYPE => "'player'",
                                INVESTED => 0
                            );
                            $objTrgUser->set_stats($arrTrgStats);

                            // Update User Goods (Martel, December 08, 2006)
                            $arrTrgGoods = array
                            (
                                MARKET_WOOD => 0,
                                MARKET_SOLDIERS => 0,
                                MARKET_FOOD => 0,
                                MARKET_MONEY => 0,
                                CREDITS => 0
                            );
                            $objTrgUser->set_goods($arrTrgGoods);

                            // Set voted for to 0 for all tribes that voted for
                            // the defected tribe (Martel, December 08, 2006)
                            mysql_query("UPDATE user SET vote = 0 WHERE vote = $iDefect");

                            // Update target rankings
                            include_once('inc/functions/update_ranking.php');
                            doUpdateRankings($objTrgUser, 'yes');
                        }

                        echo "</div>"; return;
                    }


                    echo
                        '<h2>' . 'Defect Tribe' . '</h2>';

                    echo
                        '<div id="textMedium">' .
                            '<p>' .
                            'Defecting a tribe will force them to randomly ' .
                            'join another alliance. Their invested research ' .
                            'will be lost with them.' .
                            '</p>' .
                        '</div>' .
                        '<br />';

                    // select everyone in this alliance
                    $strSQL =
                        "SELECT * " .
                        "  FROM stats " .
                        " WHERE kingdom = " . $arrSrcStats[ALLIANCE] .
                        " ORDER BY tribe ASC";
                    $result = mysql_query ($strSQL) or die("Elder Defect:" . mysql_error());
                    include('inc/functions/vote.php');

                    echo
                        '<form method="post" action="main.php?cat=game&amp;page=elder&amp;task=elder_defect">' .
                            "Choose Tribe: " .
                            '<select name="defectId" size="1">' .
                                render_option_list($result, TRIBE, ID, 0) .
                            '</select>' .
                            '<br /><br />' .
                            '<input type="hidden" name="do" value="yes">' .
                            '<input type="submit" value="Defect Tribe">' .
                        '</form>';
                }
                else
                {
                    echo "<p>This is only available to the elected elder of your alliance.</p>";
                }

            break;
            case "alliance_type":

                echo
                    '<h2>' . 'Change Alliance Type' . '</h2>';

                if ($arrSrcStats[TYPE] == 'elder' && isset($_GET['do']) && ($_GET['do'] == 'yes' || $_GET['do'] == 'no'))
                {
                    if (count($objSrcAlliance->get_userids()) < floor(MAX_ALLIANCE_SIZE / 4) && $_GET['do'] == 'yes')
                    {
                        $_GET['do'] = 'no';

                        echo $strDiv =
                            '<div id="textSmall">' .
                                '<p>' .
                                'You must have a minimum of ' .
                                floor(MAX_ALLIANCE_SIZE / 4) . ' tribes before ' .
                                'your alliance can become private.' .
                                '</p><p>' .
                                '<a href="main.php?cat=game&amp;page=elder&amp;task=alliance_type">' .
                                'Try Again ?' . '</a>' .
                                '</p>' .
                            '</div>';
                    }
                    else
                    {
                        echo $strDiv =
                            '<div id="textSmall">' .
                                '<p>' .
                                'Your alliance status has changed.' .
                                '</p><p>' .
                                'Good luck!' .
                                '</p><p>' .
                                '<a href="main.php?cat=game&amp;page=elder">' .
                                'Continue' . '</a>' .
                                '</p>' .
                            '</div>';
                    }
                    $objSrcAlliance->set_alliance_info(PRIVATE, $_GET['do']);
                }

                if ($arrSrcStats[TYPE] == 'elder')
                {
                    echo
                        '<p>' .
                        'Your alliance is currently set to: <strong>Private: ' .
                        $objSrcAlliance->get_alliance_info(PRIVATE) . '</strong>' .
                        '</p>' .
                        '<p>' .
                        '<a href="main.php?cat=game&amp;page=elder&amp;task=alliance_type&amp;do=yes">Make Private</a>' .
                        " | " .
                        ' <a href="main.php?cat=game&amp;page=elder&amp;task=alliance_type&amp;do=no">Make Public</a>' .
                        '</p>';
                }
                else
                {
                    echo
                            '<p>' .
                            'Your alliance is currently set to: <strong>Private - ' .
                            $objSrcAlliance->get_alliance_info(PRIVATE) . '</strong>' .
                            '</p>' .
                            '<p>' .
                            'This option is available only to the elected ' .
                            'elder of your alliance.' .
                            '</p>';
                }

            break;
            case "alliance_pass":

                if (isset($_GET['do']) && isset($_POST['password1']) && strlen(trim($_POST['password1'])) > 0)
                {
                    $strPassword   = trim(strip_tags($_POST['password1']));
                    $objSrcAlliance->set_alliance_info(PASSWORD, "'$strPassword'");

                    echo $strDiv =
                        '<div id="textSmall">' .
                            '<p>' .
                            'Your alliance password is now: ' .
                            $strPassword .
                            '</p><p>' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=alliance_pass">' .
                            'Continue' . '</a>' .
                            '</p>' .
                        '</div>';
                }
                else
                {
                    $strPassword = $objSrcAlliance->get_alliance_info(PASSWORD);

                    echo
                        '<h2>' . 'Change Alliance Password' . '</h2>' .
                        '<div id="textMedium">' .
                            '<p>' .
                            "Your current alliance password is: $strPassword. " .
                            'Feel free to share this and your alliance number ' .
                            'to invite friends to the alliance.' .
                            '</p>' .
                        '</div>' .
                        '<br />';
                    echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=elder&amp;task=alliance_pass&amp;do=yes\">";
                    echo "New Password: <input type=text name=password1 ><br /><br /> ";
                    echo "<input type=submit value='Change Password'>";
                    echo "</form>";
                }

            break;
            case "elder_message":

                if (isset($_POST['strNew']))
                {
                    $strNew = addslashes($_POST['strNew']);
                    $strNew = htmlentities($strNew, ENT_QUOTES);
                    $strNew = htmlspecialchars($strNew);
                    $strNew = escapeshellcmd($strNew);

                    $objSrcAlliance->set_alliance_info(ELDER_MESSAGE, "'$strNew'");

                    echo $strDiv =
                        '<div id="textSmall">' .
                            '<p>' .
                            'Your elder message has been updated!' .
                            '<br /><br />' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=elder_message">' .
                            'Continue' . '</a>' .
                            '</p>' .
                        '</div>';
                }
                else
                {
                    $strElderMsg = $objSrcAlliance->get_alliance_info(ELDER_MESSAGE);

                    echo $strForm =
                        '<form method="post" action="main.php?cat=game&amp;page=elder&amp;task=elder_message">' .
                            '<h2>' . 'Elder Message' . '</h2>' .
                            '<textarea name="strNew" rows="10" cols="45">' .
                            stripslashes(stripslashes(html_entity_decode($strElderMsg))) .
                            '</textarea>' .
                            '<br />' .
                            '<input type="submit" value="Update Elder Message">' .
                        '</form>';
                }

            break;
            case "banner":

                if (isset($_POST['submitaddy']))
                {
                    $submitaddy   = addslashes($_POST['submitaddy']);
                    $submitaddy   = htmlentities($submitaddy, ENT_QUOTES);
                    $submitaddy   = htmlspecialchars($submitaddy);
                    $submitaddy   = escapeshellcmd($submitaddy);
                    $submitwidth  = intval($_POST['submitwidth']);
                    $submitheight = intval($_POST['submitheight']);

                    if ($submitwidth > 500)
                        $submitwidth = 500;
                    elseif ($submitwidth < 0)
                        $submitwidth = 0;

                    if ($submitheight > 200)
                        $submitheight = 200;
                    elseif ($submitheight < 0)
                        $submitheight = 0;

                    $arrSrcAlliance = array
                    (
                        IMAGE => "'$submitaddy'",
                        IMAGEWIDTH => $submitwidth,
                        IMAGEHEIGHT => $submitheight
                    );
                    $objSrcAlliance->set_alliance_infos($arrSrcAlliance);

                    echo $strDiv =
                        '<div id="textSmall">' .
                            '<p>' .
                            'Your alliance banner has been updated.' .
                            '<br /><br />' .
                            '<a href="main.php?cat=game&amp;page=elder&amp;task=banner">' .
                            'Continue' . '</a>' .
                            '</p>' .
                        '</div>';
                }
                else
                {
                    // Vay: added commas after elder and nudity

                    echo $strForm =
                        '<h2>' . 'Banner' . '</h2>' .
                        '<div id="textMedium">' .
                            '<p>' .
                            'As elder, you are responsible for this picture! If ' .
                            'it is judged as obscene it may result in your deletion ' .
                            'or banning from ORKFiA. Do NOT post anything racist' .
                            ', sexist, something containing nudity, or offensive ' .
                            'images. Dimension limits are 500 by 200.' .
                            '</p>' .
                        '</div>' .
                        '<br />' .
                        '<form method="post" action="main.php?cat=game&amp;page=elder&amp;task=banner">' .
                            'New banner address: ' .
                            '<input type="text" name="submitaddy" maxlength="75" ' .
                            'size="50" value="' . $arrSrcAlliance[IMAGE] . '">' .
                            '<br /><br />' .
                            'Width: ' .
                            '<input type="text" name="submitwidth" maxlength="3" ' .
                            'size="3" value="' . $arrSrcAlliance[IMAGEWIDTH] . '">' .
                            '<br /><br />' .
                            'Height: ' .
                            '<input type="text" name="submitheight" maxlength="3" ' .
                            'size="3" value="' . $arrSrcAlliance[IMAGEHEIGHT] . '">' .
                            '<br /><br />' .
                            '<input type="submit" value="Update Banner">' .
                        '</form>';
                }

            break;
        }
        echo "</div>";
    }
    else
    {
        echo '<div id="textMedium"><p>' .
                 'Only Elders May Enter Here.' .
                 '<br /><br />' .
                 '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
             '</p></div>';
    }
}
