<?php
//******************************************************************************
// New war interface for elders                           August 05, 2005 Martel
// History:
//  Recoded to properly use objects and no globals.     February 28, 2008 Martel
//
//  frost: partially new war routine
//  other sources of the war system: /pages/alliance.inc.php,
//                                   /functions/war.php
//******************************************************************************
require_once('inc/functions/war.php');

function include_war_room_text()
{
    // User object
    $objSrcUser  = $GLOBALS['objSrcUser'];
    $arrSrcStats = $objSrcUser->get_stats();

    // Alliance object
    $objSrcAlli  = $objSrcUser->get_alliance();
    $arrSrcWar   = $objSrcAlli->get_wars();

    // Include war functions

    // Show declare war page is default unless the alliance is at war
    $strShow = "declare_war";
    if ($arrSrcWar['target'] != 0)
        $strShow = "current_war";

    // Show page
    switch ($strShow)
    {
        case "declare_war":

            // M: Show statistics (if we have been in war)
            $strStatistics = '';
            if ($arrSrcWar['last_target'] != 0)
            {
                // M: Show statistics, with correct grammar
                $arrS = array('ies', 's', 's', 's');
                if ($arrSrcWar['victory'] == 1) $arrS[0] = 'y';
                if ($arrSrcWar['defeat'] == 1) $arrS[1] = '';
                if ($arrSrcWar['surrender'] == 1) $arrS[2] = '';
                if ($arrSrcWar['truce'] == 1) $arrS[3] = '';
                $strStatistics .=
                '<p>' .
                    '<strong class="positive">' . $arrSrcWar['victory'] .
                    " war victor$arrS[0]</strong>&#8212;defeated " .
                    $arrSrcWar['defeat'] . " time$arrS[1].<br />" .
                    "We have surrendered " . $arrSrcWar['surrender'] .
                    " time$arrS[2] and truced " . $arrSrcWar['truce'] .
                    " time$arrS[3].</strong>" .
                '</p><p>';

                // Info about who the last wartarget was                - AI
                $objTrgAlli = new clsAlliance($arrSrcWar['last_target']);
                $arrTrgAlli = $objTrgAlli->get_alliance_infos();

                // M: Show continued statistics, with correct grammar
                if ($arrSrcWar['last_outgoing'] == 'surrender')
                    $strF = 'Our last war ended as a %s to %s (#%d).';
                elseif ($arrSrcWar['last_outgoing'] == 'victory')
                    $strF = 'Our last war ended as a %s over %s (#%d).';
                elseif ($arrSrcWar['last_outgoing'] == 'defeat')
                    $strF = 'Our last war ended as a %s against %s (#%d).';
                elseif ($arrSrcWar['last_outgoing'] == 'truce')
                    $strF = 'Our last war ended in a %s with %s (#%d).';

                // M: Echo continued statistics
                $strStatistics .= sprintf($strF, $arrSrcWar['last_outgoing'],
                                          stripslashes($arrTrgAlli['name']),
                                          $arrSrcWar['last_target']);
                $strStatistics .= '</p>';
            }
            else
            {
                 $strStatistics .= '<p>We have not been in a war yet.</p>';
            }

            // M: Find alliances in range
            $iAlliSize    = $objSrcAlli->get_alliance_size('land');
            $iUpwardRange = floor($iAlliSize * WAR_UPWARD_MOD);
            $iBottomRange = ceil($iAlliSize * WAR_BOTTOM_MOD);

            $strSQL       = "SELECT id FROM rankings_alliance ";
            $strSQL      .= "WHERE land BETWEEN $iBottomRange and $iUpwardRange ";
            $strSQL      .= "ORDER BY land DESC";

            $resSQL       = mysql_query($strSQL);

            $count        = 1;
            $objTmpAlli   = new clsAlliance(1);
            $strTableRow  = '';
            $strInputList = '<option selected="selected"></option>';
            while ($row = mysql_fetch_assoc($resSQL))
            {
                // M: Get temporary alliance object
                $objTmpAlli->set_allianceid($row['id']);

                // M: Check if war is possible
                $arrWarPossible = testWarPossible($objSrcAlli, $objTmpAlli);
                $bWarPossible   = $arrWarPossible[0];
                if ($bWarPossible)
                {
                    // M: Fetch alliance rankings
                    $arrTmpRanks     = $objTmpAlli->get_rankings_alliances();
                    $strAllianceLink = "<a href=\"main.php?cat=game&amp;page=" .
                                     "alliance&amp;aid=$row[id]\">$row[id]</a>";

                    // M: Create drop-down list with targets in range (for form)
                    $strInputList .=
                    '<option value="' . $row['id'] . '">' .
                        stripslashes($arrTmpRanks['alli_name']) .
                    '</option>';

                    // M: Create "Alliances in range" table data
                    $strTableRow .=
                    "<tr class=data>" .
                        '<th width="22">' . $count . '.</th>' .
                        "<th>" . stripslashes($arrTmpRanks['alli_name']) .
                                 " (#$strAllianceLink)" . "</th>" .
                        "<td>" . number_format($arrTmpRanks['land']) . "</td>" .
                        "<td>" . number_format($arrTmpRanks[STRENGTH]) . "</td>" .
                        "<td>" . number_format($arrTmpRanks['fame']) . "</td>" .
                    "</tr>";

                    $count++;
                }
            }

            if ($count == 1)
            {
                $strTableRow .=
                '<tr class="data">' .
                    '<th class="center" colspan="5">' .
                    "There are no alliances that we can declare against." .
                    '</th>'.
                "</tr>";

                $strForm = "<p>There are no alliances that we can declare against.</p>";
            }
            elseif ($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME || $arrSrcStats['type'] == "elder" || $arrSrcStats['type'] == "coelder")
            {
                $strForm =
                '<form method="post" action="main.php?cat=game&amp;page=war_room2" id="center">' .
                    '<label>Alliance: ' .
                        '<select name="target">' . $strInputList . '</select>' .
                    '</label> ' .
                    '<input type="submit" name="declare" value="Declare War!" />' .
                '</form><br />';
            }
            else
            {
                $strForm = '<p>Only alliance elders can declare war.</p>';
            }

            // M: Show page contents
            echo $strPage =
                 '<div id="textMedium" style="margin-top: 0;">' .

                 '<h2>Declare War</h2>' .

                 '<p>' .
                    "In a war, knowing the enemy is very important. " .
                    "Decipher the enemies' weaknesses and avoid " .
                    "confronting their strengths. Gather information about " .
                    "their tribes and research, and then choose targets for " .
                    "your attackers, thieves and mages." .
                 '</p>' .

                 '<h3>Statistics</h3>' .

                 $strStatistics .

                 '<h3>New war target</h3>' .

                 $strForm .

                 '</div><br />' .

                 '<table class="medium" cellpadding="0" cellspacing="0">' .

                     '<tr class="header">' .
                        '<th colspan="5">Alliances in Range</th>' .
                     '</tr>' .

                     '<tr class="subheader"><th colspan="2">Alliance</th>' .
                         '<th>Acres</th>' .
                         '<th class="right">Strength</th>' .
                         '<th class="right">Fame</th>' .
                     '</tr>' .

                     $strTableRow .

                 '</table>';

        break;
        case "current_war":

            // We are in war
            $objTrgAlli = new clsAlliance($arrSrcWar['target']);
            $arrTrgAlli = $objTrgAlli->get_alliance_infos();
            $arrTrgWar  = $objTrgAlli->get_wars();

            // Time of war start

            // Get game hours
            require_once('inc/classes/clsGame.php');
            require_once('inc/functions/orktime.php');
            $objGame     = new clsGame();
            $iGameHours  = $objGame->get_game_time('hour_counter');
            $arrOE       = hoursToYears(WAR_LENGTH - ($iGameHours - $arrSrcWar['war_started']));
            $strOrkDate  = "$arrOE[years] years and $arrOE[months] months";

            // Option: Claim Victory (tho should be automatic like "final blow")
            $strDiplomacy = '';
            if ($arrSrcWar['land_needed'] <= 0)
            {
                $strDiplomacy .=
                '<p>We have reached our war goal. Do you wish to claim this victory?</p>' .

                '<form method="post" action="main.php?cat=game&page=war_room2" id="center">' .
                    '<input type="submit" name="victory" value="Claim Victory!">' .
                "</form><br />";
            }
            // Option: Offer truce
            elseif (($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME || $arrSrcStats['type'] == "elder" || $arrSrcStats['type'] == "coelder") && $arrSrcWar['truce_offer'] == 1 && $arrTrgWar['truce_offer'] == 0)
            {
                $strDiplomacy .=
                '<p>' .
                    "Alliance #" . $objTrgAlli->get_allianceid() . " has " .
                    "not accepted our generous offer to truce yet. " .
                '</p>' .

                '<p>' .
                    "As a last resort and at a greater cost we may " .
                    "surrender. (+40% war losses). The only valid reason " .
                    "to do this is to save our alliance from further damage." .
                '</p>' .

                '<p>Do you wish to surrender?</p>' .

                '<form method="post" action="main.php?cat=game&page=war_room2" id="center">' .
                    '<input type="submit" name="ctruce" value="Withdraw Truce" /> ' .
                    '<input type="submit" name="surrender" value="Surrender War" />' .
                '</form><br />';
            }
            // Option: Offer truce
            elseif (($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME || $arrSrcStats['type'] == "elder" || $arrSrcStats['type'] == "coelder") && $arrSrcWar['truce_offer'] == 0 && $arrTrgWar['truce_offer'] == 0)
            {
                $strDiplomacy .=
                '<p>We have the option to offer a truce to the enemy.</p>' .

                '<form method="post" action="main.php?cat=game&page=war_room2" id="center">' .
                    '<input type="submit" name="otruce" value="Offer truce">' .
                "</form><br />";
            }
            // Option: Accept truce
            elseif (($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME || $arrSrcStats['type'] == "elder" || $arrSrcStats['type'] == "coelder") && $arrSrcWar['truce_offer'] == 0 && $arrTrgWar['truce_offer'] == 1)
            {
                $strDiplomacy .=
                '<p>' .
                    "Leader, #<strong>$arrTrgWar[id]</strong> has " .
                    "offered us a truce." .
                '</p>' .

                '<form method="post" action="main.php?cat=game&page=war_room2" id="center">' .
                    '<input type="submit" name="atruce" value="Accept Truce">' .
                '</form><br />';
            }

            echo $strPage =
            '<div class="tableLinkMedium">' .
                 '<a href="main.php?cat=game&amp;page=global_news">Global News</a>' .
                 ' | <a href="main.php?cat=game&amp;page=alliance&amp;aid=' .
                 $arrTrgAlli['id'] . '">Alliance #' . $arrTrgAlli['id'] .
                 '</a>' .
            '</div>' .

            '<div id=textMedium style="margin-top: 0;">' .

            '<h2>Victory conditions</h2>' .

            '<p>' .
                '<strong class="positive">Take ' .
                number_format($arrSrcWar['land_needed']) .
                ' acres to win</strong>&#8212;lose ' .
                number_format($arrTrgWar['land_needed']) .
                " acres for defeat." .
            '</p>' .

            "<p><em>$strOrkDate left until automatic draw.</em></p>" .

            '<h3>Diplomacy</h3>' .

            $strDiplomacy .

            '</div>';

        break;
    }

    // M: Show guide link + advice
    include_once('inc/pages/advisors.inc.php');
    echo get_guide_link($objSrcUser, 'war_room', 'textMedium');
}

?>
