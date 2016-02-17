<?php
//******************************************************************************
// pages invade2.php                                        Martel, May 31, 2006
//
// This page can only be reached using the POST method. (eg forms)
// History:
// 13/04/2002 thalura     corrected hit and run casualties, now offense/defense
//******************************************************************************
include_once('inc/functions/races.php');
include_once('inc/functions/invade.php');

//added blocking system - AI 11/02/2007
require_once('inc/classes/clsBlock.php');

function include_invade2_text()
{
    global  $ip;
    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $arrSrcUser  = $objSrcUser->get_user_infos();
    $arrSrcStats = $objSrcUser->get_stats();

    //==========================================================================
    // Secure user input from the invasion form
    //==========================================================================
    if(isset($_POST['TrgPlayer']) && ! empty($_POST['TrgPlayer']) && $_POST['TrgPlayer'] != 'spacer')
    {
        // Selected Target
        $iTrgUserId   = abs(intval($_POST['TrgPlayer']));
    }
    else
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Your army walk back to their homes, you should give them a " .
            "target next time!" .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    $arrWhite = array('1', '2', '3', '4', '5');
    $arrWhite2 = array(1 => 'standard', 'raid', 'barren', 'hitnrun', 'bc');

    // Selected Target
    if(isset($_POST['invade_type']) && in_array($_POST['invade_type'], $arrWhite))
    {
        $iAttack   = intval($_POST['invade_type']);
    }
    else
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Your army walk back to their homes, you should give them a " .
            "target next time!" .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    // Army Sent
    $arrUnitVars    = getUnitVariables($arrSrcStats[RACE]);
    $arrUnitOffence = $arrUnitVars['offence'];
    $arrUnitVar     = $arrUnitVars['variables'];

    if (isset($_POST["arrArmySent"]))
    {
        $arrArmySent = $_POST["arrArmySent"];

        foreach ($arrUnitOffence as $i => $iUnitOffence)
        {
            if ($iUnitOffence > 0)
                $arrArmySent[$arrUnitVar[$i]] = max(0, intval($arrArmySent[$arrUnitVar[$i]]));
            else
                $arrArmySent[$arrUnitVar[$i]] = 0;
        }
    }

    //==========================================================================
    // Verify attacker's status
    //==========================================================================
    obj_check_protection($objSrcUser, "invade");

    $iTotalSentArmy = array_sum($arrArmySent);
    if ($iTotalSentArmy < 1)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Sorry, but you did not send any units to battle.<br />' .
            'This attack has been aborted.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    if (verifyArmyAvailable($objSrcUser, $arrArmySent) == 1)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Sorry, you do not have that many units to send.<br />' .
            'This attack has been aborted.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($arrSrcStats[RACE] == "Oleg Hai" && verifyArmyAvailableOleg($objSrcUser, $arrArmySent) == 1)
    {
        $mercsTrainedThisHour = $objSrcUser->get_army_merc(MERC_T3);

        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Sorry, but you did only train $mercsTrainedThisHour " .
            "mercs this update and that's the maximum you may use for an " .
            "attack.<br />" .
            "This attack has been aborted." .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($arrSrcUser[NEXT_ATTACK] > 0)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Sorry, but you cannot attack for at least ' .
            $arrSrcUser[NEXT_ATTACK] . ' more updates.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($arrSrcUser[HOURS] < PROTECTION_HOURS)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'You are not allowed to attack while in protection!' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    //==========================================================================
    // Verify defender's status
    //==========================================================================
    include_once('inc/functions/update.php');
    check_to_update($iTrgUserId);
    $objTrgUser  = new clsUser($iTrgUserId);
    $arrTrgStats = $objTrgUser->get_stats();

    if ($arrTrgStats[ALLIANCE] == $objSrcUser->get_stat(ALLIANCE))
    {
        echo $strDiv =
            '<div id="textMedium"><p>' .
                'Sorry, honor before might. Do not attack into your own alliance.' .
                '</p><p>' .
                '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
            '</p></div>';

        include_game_down();
        exit;
    }
    elseif ($objTrgUser->get_stat(ALLIANCE) < 11)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'I hope you did not think that you would get away with ' .
            'attacking into a staff alliance, did you?' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    // Frost: Added a global protection mode
    include_once('inc/classes/clsGame.php');
    $objGame = new clsGame();
    if ($objGame->get_game_switch(GLOBAL_PROTECTION) == 'on')
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Because of a global event all tribes in ORKFiA are under ' .
            'protection. Please check the community forum for an announcement.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    $iTrgHours = $objTrgUser->get_user_info(HOURS);
    if ($iTrgHours < PROTECTION_HOURS)
    {
        $iTrgHoursRemaining = PROTECTION_HOURS - $iTrgHours;

        echo $strDiv =
        '<div id="textMedium"><p>' .
            'It appears that the tribe you wish to target is still ' .
            'materializing. Our general estimates that it will ' .
            'take another ' . $iTrgHoursRemaining . ' updates for the area ' .
            'to become a stable part of reality.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($objTrgUser->get_stat(ALLIANCE) == 0)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This player has either been deleted or suspended.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    // Frost: added jan -04
    // Martel: updated July 08, 2006
    if ($objTrgUser->get_stat(KILLED) == 1 || $objTrgUser->get_stat(RESET_OPTION) == 'yes')
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This tribe is dead.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    // Paused account                                      Martel, July 13, 2006
    if ($objTrgUser->isPaused())
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This tribe is paused.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=invade">' . 'Return' . '</a>' .
        '</p></div>';

        return;
    }
    // Blocking system                          - AI 11/02/2007
    if (! clsBlock::isOpAllowed($objSrcUser, $objTrgUser))
    {
        echo '<div id="textMedium"><p>' .
            'Someone else from the same IP has already opped this tribe during the last 8 hours.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=invade">Return</a>' .
            '</p></div>';
        clsBlock::reportOp($objSrcUser, $objTrgUser, 'Attack: ' . $arrWhite2[$iAttack], false);
        return;
    }

    $iTrgLand = $objTrgUser->get_build(LAND);
    $iSrcLand = $objSrcUser->get_build(LAND);
    $breakoff = round($iSrcLand * 0.7);
    // Barren attack has 70% bottom feed limit if smaller than 2000 acres
    if ($iAttack == ATTACK_BARREN && $iTrgLand <= $breakoff && $iTrgLand < 2000)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Sorry but you can not bash the small ones. Barren grabs are not ' .
            'allowed against smaller tribes unless they are within 70% ' .
            'of your own size.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Try Again ?' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($iAttack == ATTACK_HNR && $iTrgLand < $iSrcLand)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'What kind of coward are you who try to make a Hit \'n\' Run ' .
            'attack against a smaller tribe?' .
            '</p><p>' .
            'Shame on you!' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Return' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }

    $quick_check = mysql_query("Select * from user where id = 1");
    $quick_check = mysql_fetch_array($quick_check);
    if ($quick_check[STOPGAMETRIGGER] == 99)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'The game has been paused by staff, possibly due to technical ' .
            'maintenance. Please look in the announcements ' .
            'forum or the admin game message for more information.' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . 'Return' . '</a>' .
        '</p></div>';

        include_game_down();
        exit;
    }
    else
    {
        //======================================================================
        // Do Battle
        //======================================================================
       /* include the code for the attack about to be done */
       include_once("inc/attacks/" . $arrWhite2[$iAttack] . ".php");

        // Damadm00 19-03-2004, check here the result from the viking check
        // Martel: Notify thieves about attack only if not viking+25% luck
        if ($arrSrcStats[RACE] == 'Viking' && getVikingCheck() == 1)
        {
            $viking = 1;
        }
        else
        {
            $viking   = 0;
            getMonitoringCheck($objSrcUser);
        }

        include_once('inc/functions/military.php');
        $defence  = getArmyDefence($objTrgUser, $iAttack);
        $defence  = $defence['total_home'];
        $offence  = getSentOffence($objSrcUser, $arrArmySent);

    //Attacking Templars kills mystics, not thieves - AI
    $thieves = 'thieves';
    if($objTrgUser->get_stat(RACE) == 'Templar')
    {
        $thieves = 'mystics';
    }

        // Eagle 30% auto force retreat feature
        if ($objTrgUser->get_stat(RACE) == "Eagle" && getEagleCheck() == 1)
        {
            doRetreat($objSrcUser, $objTrgUser, $arrArmySent, 1, 0);
            return;
        }
        elseif (($offence < $defence) && $iAttack != ATTACK_HNR)
        {
            doRetreat($objSrcUser, $objTrgUser, $arrArmySent, 0, $viking);
            return;
        }
        elseif ($offence < $defence && $iAttack == ATTACK_HNR)
        {
            if ($offence > 0.5 * $defence)
            {
                if ($defence == 0)
                    $defence = 1;

                $arrSrcArmyLost = getSrcLosses($objSrcUser, $arrArmySent, $offence/$defence, $arrWhite2[$iAttack]);
                $arrTrgArmyLost = getTrgLosses($objTrgUser, $objSrcUser, $offence/$defence, $arrWhite2[$iAttack]);

                $arrReport = doAttack($objSrcUser, $objTrgUser, $arrArmySent);
            }
            else
            {
                doRetreat($objSrcUser, $objTrgUser, $arrArmySent, 0, $viking);
                return;
            }
        }
        else
        {
            if ($defence == 0)
                $defence = 1;

            $arrSrcArmyLost = getSrcLosses($objSrcUser, $arrArmySent, $offence/$defence, $arrWhite2[$iAttack]);
            $arrTrgArmyLost = getTrgLosses($objTrgUser, $objSrcUser, $offence/$defence, $arrWhite2[$iAttack]);

            $arrReport = doAttack($objSrcUser, $objTrgUser, $arrArmySent);
        }

        // Spread Pestilence
        $arrSpreadPest = checkPestilence($objSrcUser, $objTrgUser);
        $pestSrc = $arrSpreadPest['attacker'];
        $pestTrg = $arrSpreadPest['defender'];

        //======================================================================
        // Begin creating the invade report
        //======================================================================
        $strTribe = stripslashes($objTrgUser->get_rankings_personal(TRIBE_NAME));
        $iAlliance = $arrTrgStats[ALLIANCE];

        $strReport =
            '<div id="textMedium">' .
                '<h2>' . "Invade Report" . '</h2>' .
                '<p>' .
                    'Your invasion of <strong>' . $strTribe . ' (#' . $iAlliance . ')</strong> was successful, ' .
                    'below follows a report from your general.' .
                '</p>';

        //======================================================================
        // Report: Gains
        //======================================================================
        // Acres
        if (isset($arrReport['gained_acres']) && $arrReport['gained_acres'] != 0)
        {
            $strReport .=
                '<p>' .
                    "Our army has gained control over " .
                    "<strong>" . $arrReport['gained_acres']. " acres</strong>. ";

            // Explored Acres
            if (isset($arrReport['explored_acres']) && $arrReport['explored_acres'] != 0)
            {
                $strReport .=
                    "Also <strong>" . $arrReport['explored_acres'] . " acres</strong> was " .
                    "explored and can be used to build on now.";
            }
            $strReport .=
                '</p>';
        }

        // Citizens Killed (raid or hnr)
        if (isset($arrReport['killed_citizens']) && $arrReport['killed_citizens'] != 0)
        {
            $strReport .=
                '<p>' .
                    "Our army storm into their lands, killing <strong>" .
                    $arrReport['killed_citizens'] . "</strong> of their citizens." .
                '</p>';
        }

        // Money (raid)
        if (isset($arrReport['gained_crowns']) && $arrReport['gained_crowns'] != 0)
        {
            $strReport .=
                '<p>' .
                    "The army report having stolen <strong class=\"indicator\">" .
                    number_format($arrReport['gained_crowns']) . " crowns</strong>." .
                '</p>';
        }

        // Buildings Razed (blasphemy crusade)
        if (isset($arrReport['damaged_total']) && $arrReport['damaged_total'] != 0)
        {
            $strReport .=
                '<p>' .
                    "Your loyal army charges into their lands, destroying <strong>" .
                    $arrReport['damaged_total'] . " buildings</strong> of witchcraft " .
                    "and deception. (";

            // Academies
            if (isset($arrReport['damaged_academies']) && $arrReport['damaged_academies'] != 0)
            {
                $strReport .=
                    " <strong>" . $arrReport['damaged_academies'] . " academies</strong>";
            }

            // Guilds
            if (isset($arrReport['damaged_guilds']) && $arrReport['damaged_guilds'] != 0)
            {
                $strReport .=
                    " <strong>" . $arrReport['damaged_guilds'] . " guilds</strong>";
            }

            // Hideouts
            if (isset($arrReport['damaged_hideouts']) && $arrReport['damaged_hideouts'] != 0)
            {
                $strReport .=
                    " <strong>" . $arrReport['damaged_hideouts'] . " hideouts</strong>";
            }
            $strReport .=
                ').</p>';
        }

        // Thieves Killed (blasphemy crusade)
        if (isset($arrReport['killed_thieves']) && $arrReport['killed_thieves'] != 0)
        {
            $strReport .=
                '<p>' .
                    "Your general reports having killed <strong>" .
                    $arrReport['killed_thieves'] . " $thieves of the enemy</strong>." .
                '</p>';
        }

        // Fame Gained
        if (isset($arrReport['gained_fame']) && $arrReport['gained_fame'] != 0)
        {
            $strReport .=
                '<p>' .
                    "This invasion gave our tribe <strong class=\"positive\">" .
                    $arrReport['gained_fame'] . " fame</strong>." .
                '</p>';
        }

        //======================================================================
        // Begin enemy defence estimation (report)
        //======================================================================

        if ($offence > ($defence * 2))
        {
            $strReport .=
                '<p>' .
                    "Our army is more than double the power of the " .
                    "defending army, causing the enemy to run in fear. (Victory " .
                    "by more than 100%)" .
                '</p>';
        }
        elseif ($offence > ($defence * 1.8))
        {
            $strReport .=
                '<p>' .
                    "Our army is almost double the power of the defending " .
                    "army. (Victory by more than 80%)" .
                '</p>';
        }
        elseif ($offence > ($defence * 1.6))
        {
            $strReport .=
                '<p>' .
                    "Our army has easily broken the enemies defences, " .
                    "overpowering them by more than 3 to 2. (Victory by more " .
                    "than 60%)" .
                '</p>';
        }
        elseif ($offence > ($defence * 1.4))
        {
            $strReport .=
                '<p>' .
                    "Our army has broken the enemies defences, overpowering " .
                    "them by around 3 to 2. (Victory by more than 40%)" .
                '</p>';
        }
        elseif ($offence > ($defence * 1.2))
        {
            $strReport .=
                '<p>' .
                    "Our army has broken through the line of defence, but it was " .
                    "a hard battle. (Victory by more than 20%)" .
                '</p>';
        }
        elseif ($offence > ($defence * 1.1))
        {
            $strReport .=
                '<p>' .
                    "Our army has broken through the line of defence, but it was " .
                    "a very tough battle. (Victory by more than 10%)" .
                '</p>';
        }
        elseif ($offence >= $defence)
        {
            $strReport .=
                '<p>' .
                    "Our army fought hard, winning only after a lengthy and very " .
                    "difficult battle. (Victory by less than 10%)" .
                '</p>';
        }

        //======================================================================
        // Report: Army Losses
        //======================================================================
        $arrUnitVars  = getUnitVariables($objSrcUser->get_stat(RACE));
        $arrUnitNames = $arrUnitVars['output'];
        $strPlural    = 's';

        $strReport .=
            '<p>' .
                "The captains report that we have lost ";

        if($arrSrcArmyLost[UNIT1] > 0)
        {
            $strReport .=
                $arrSrcArmyLost[UNIT1] . " " . $arrUnitNames[2] . $strPlural . ", ";
        }

        if($arrSrcArmyLost[UNIT2] > 0)
        {
            if ($arrUnitNames[3] == 'Swordmen')    { $strPlural = '';}
            elseif ($arrUnitNames[3] == 'Pikemen')     { $strPlural = '';}

            $strReport .=
                $arrSrcArmyLost[UNIT2] . " " . $arrUnitNames[3] . $strPlural . ", ";
        }

        if($arrSrcArmyLost[UNIT3] > 0)
        {
            if ($arrUnitNames[4] == 'Crossbowmen') { $strPlural = '';}
            elseif ($arrUnitNames[4] == 'Longbowmen')  { $strPlural = '';}
            elseif ($arrUnitNames[4] == 'Mummy') { $arrUnitNames[4] = 'Mummie';}

            $strReport .=
                $arrSrcArmyLost[UNIT3] . " " . $arrUnitNames[4] . $strPlural . ", ";
        }

        if($arrSrcArmyLost[UNIT4] > 0)
        {
            if ($arrUnitNames[5] == 'Priestess') { $strPlural = '';}

            $strReport .=
                $arrSrcArmyLost[UNIT4] . " " . $arrUnitNames[5] . $strPlural . ", ";
        }

        if($arrSrcArmyLost[UNIT5] > 0)
        {
            if ($arrUnitNames[6] == 'Thief') {$arrUnitNames[6] = 'Thieve';}

            $strReport .=
                $arrSrcArmyLost[UNIT5] . " " . $arrUnitNames[6] . $strPlural . ", ";
        }

        if(array_sum($arrSrcArmyLost) < 1)
        {
            $strReport .=
                " no military at all, ";
        }

        $totalkilled  = round(array_sum($arrTrgArmyLost), -2);
        if ($totalkilled > 0)
        {
            $strReport .=
                " and they estimate the enemy's losses to be $totalkilled units";
            if ($objSrcUser->get_stat(RACE) == "Undead")
                $strReport .= ", who joined our cursed army as soldiers.";
        }
        else
        {
            $strReport .=
                " and they estimate the enemy's losses to be near zero";
        }

        $strReport .=
            '.</p>';

        //======================================================================
        // Report: Army available again
        //======================================================================
        $wait = $objSrcUser->get_user_info(NEXT_ATTACK);

        $strReport .=
            '<p>' .
                "Our generals report our army will be able to attack again " .
                "in " . $wait . " updates. ";

        if ($wait > 4)
        {
            // Assuming that an attack with more than 4 hours is a BC or Hitnrun
            $wait -= 2;
            $strReport .=
                "However, our army will be home to defend our lands after " .
                $wait . " updates.";
        }

        $strReport .=
            '</p>';

        //======================================================================
        // Report: Pestilence
        //======================================================================
        if($pestSrc == "yes")
        {
            $strReport .=
                '<p>' .
                    "<strong class=\"negative\">During your invasion your military " .
                    "got infected with pestilence.</strong>" .
                '</p>';
        }

        //======================================================================
        // frost: added suicide detection | modified for age 18. only one update
        // loss of 25% citizens
        //======================================================================
        $suicide = getSuicideCheck($objSrcUser, $arrArmySent);
        if ($arrSrcStats[RACE] != "Raven" && $suicide == 1)
        {
            $citz = $objSrcUser->get_pop(CITIZENS);
            $leavingCitz = floor($citz * 0.25);
            $strReport .=
                '<p>' .
                    "<strong class=\"negative\">Your citizens are getting tired of " .
                    "their tax money going to far-away military campaigns " .
                    "instead of defending their homes. " .
                    number_format($leavingCitz) .
                    " citizens have left your lands.</strong>" .
                '</p>';
            $objSrcUser->set_pop(CITIZENS, $citz - $leavingCitz);
        }

        //======================================================================
        // Report: Viking Stealth Attack
        //======================================================================
        if($viking == 1)
        {
            $strReport .=
                '<p>' .
                    "<strong class=\"positive\">You got lucky. Your location " .
                    "doesn't end up in the news.</strong>" .
                '</p>';
        }

        //======================================================================
        // Report: War effects
        //======================================================================
        require_once('inc/functions/war.php');
        $objSrcAlliance = $objSrcUser->get_alliance();
        if (checkWarBetween($objSrcAlliance, $objTrgUser->get_stat(ALLIANCE)))
        {
            $objTrgAlliance = $objTrgUser->get_alliance();
            if (($arrGains = testWarVictory($objSrcAlliance, $objTrgAlliance)))
            {
                // Append war-win message
                require_once('inc/pages/war_room2.inc.php');
                $strReport .=
                '<p><strong class="positive">Your alliance has won the war!</strong></p>' .
                getVictoryReport($arrGains);
            }
        }

        $strReport .=
                '<p>' .
                    '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
                '</p>' .
            '</div>';
        echo $strReport;

        //======================================================================
        // Defender tribe news (damage report)
        //======================================================================
        $srcDisplay = $arrSrcStats[TRIBE] . " (#" . $arrSrcStats[ALLIANCE] . ")";
        $trgDisplay = $arrTrgStats[TRIBE] . " (#" . $arrTrgStats[ALLIANCE] . ")";
        $strStrategy = $arrWhite2[$iAttack];
        switch($strStrategy)
        {
            case "standard":

                if ($viking == 0)
                {
                    $strTrgTribe =
                        "<span class=\"positive\">$srcDisplay has successfully marched into our lands and conquered " . $arrReport['gained_acres'] . " acres</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">$srcDisplay has successfully marched into the lands of $trgDisplay and conquered " . $arrReport['gained_acres'] . " acres</span>";
                }
                else
                {
                    $strTrgTribe =
                        "<span class=\"positive\">An unidentified tribe of vikings has successfully marched into our lands and conquered " . $arrReport['gained_acres'] . " acres</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has successfully marched into the lands of $trgDisplay and conquered " . $arrReport['gained_acres'] . " acres</span>";
                }

            break;
            case "raid":

                if ($viking == 0)
                {
                    $strTrgTribe =
                        "<span class=\"positive\">$srcDisplay has successfully stormed into our lands and conquered " . $arrReport['gained_acres'] . " acres, stolen " . $arrReport['gained_crowns'] . " crowns and slaughtered " . $arrReport['killed_citizens'] . " citizens</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">$srcDisplay has successfully stormed into the lands of $trgDisplay and conquered " . $arrReport['gained_acres'] . " acres, stolen " . $arrReport['gained_crowns'] . " crowns and slaughtered " . $arrReport['killed_citizens'] . " citizens</span>";
                }
                else
                {
                    $strTrgTribe =
                        "<span class=\"positive\">An unidentified tribe of vikings has successfully stormed into our lands and conquered " . $arrReport['gained_acres'] . " acres, stolen " . $arrReport['gained_crowns'] . " crowns and slaughtered " . $arrReport['killed_citizens'] . " citizens</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has Successfully stormed into the lands of $trgDisplay and conquered " . $arrReport['gained_acres'] . " acres, stolen " . $arrReport['gained_crowns'] . " crowns and slaughtered " . $arrReport['killed_citizens'] . " citizens</span>";
                }

            break;
            case "barren":

                if ($viking == 0)
                {
                    $strTrgTribe =
                        "<span class=\"newsattack\">$srcDisplay has successfully sneaked into our lands and claimed " . $arrReport['gained_acres'] . " acres</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">$srcDisplay has successfully sneaked into the lands of $trgDisplay and claimed " . $arrReport['gained_acres'] . " acres</span>";
                }
                else
                {
                    $strTrgTribe =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has successfully sneaked into our lands and claimed " . $arrReport['gained_acres'] . " acres</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has successfully sneaked into the lands of $trgDisplay and claimed " . $arrReport['gained_acres'] . " acres</span>";
                }

            break;
            case "bc":

                if ($viking == 0)
                {
                    $strTrgTribe =
                        "<span class=\"newsbc\">$srcDisplay has successfully charged into our lands and destroyed " . $arrReport['damaged_total'] . " buildings and killed " . $arrReport['killed_thieves'] . " $thieves. Our lands will be avaliable for building again after they have been cleared</span>";
                    $strTrgAlliance =
                        "<span class=\"newsbc\">$srcDisplay has successfully charged into the lands of $trgDisplay and destroyed " . $arrReport['damaged_total'] . " buildings and slaughtered " . $arrReport['killed_thieves'] . " $thieves</span>";
                }
                else
                {
                    $strTrgTribe =
                        "<span class=\"newsbc\">An unidentified tribe of vikings has successfully charged into our lands and destroyed " . $arrReport['damaged_total'] . " buildings and killed " . $arrReport['killed_thieves'] . " $thieves. Our lands will be avaliable for building again after they have been cleared</span>";
                    $strTrgAlliance =
                        "<span class=\"newsbc\">An unidentified tribe of vikings has successfully charged into the lands of $trgDisplay and destroyed " . $arrReport['damaged_total'] . " buildings and killed " . $arrReport['killed_thieves'] . " $thieves</span>";
                }

            break;
            case "hitnrun":

                $strAdd = "";
                if ($offence >= $defence)
                    $strAdd = " and " . $arrReport['killed_citizens'] . " citizens";

                if ($viking == 0)
                {
                    $strTrgTribe =
                        "<span class=\"newsattack\">$srcDisplay has cowardly ambushed our lands, killing many troops" . $strAdd . "</span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">$srcDisplay has cowardly attacked and rained arrows over $trgDisplay, killing many troops" . $strAdd . "</span>";
                }
                else
                {
                    $strTrgTribe =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has cowardly ambushed our lands, killing many troops" . $strAdd . "</span></span>";
                    $strTrgAlliance =
                        "<span class=\"newsattack\">An unidentified tribe of vikings has cowardly attacked and rained arrows over $trgDisplay, killing many troops" . $strAdd . "</span>";
                }

            break;
        }

        $trgId = $objTrgUser->get_userid();
        $srcId = $objSrcUser->get_userid();
        $trgKd = $objTrgUser->get_stat(ALLIANCE);
        $srcKd = $objSrcUser->get_stat(ALLIANCE);
        if ($viking == 0)
        {
            $result = "INSERT INTO `news` VALUES ('', NOW(), '$ip', '$strStrategy', '$trgId', '$srcId', '1', " . quote_smart($strTrgTribe) . ", " . quote_smart($strTrgAlliance) . ",'$trgKd','$srcKd',1)";
        }
        else
        {
            $result = "INSERT INTO `news` VALUES ('', NOW(), '$ip', '$strStrategy', '$trgId', '$srcId', '1', " . quote_smart($strTrgTribe) . ", " . quote_smart($strTrgAlliance) . ",'$trgKd',0,1)";
        }
        mysql_query($result);

        $arrUnitVars    = getUnitVariables($objTrgUser->get_stat(RACE));
        $arrUnitNames   = $arrUnitVars['output'];
        $strPlural      = 's';
        $strPlural2     = 's';
        $strPlural3     = 's';
        $strPlural4     = 's';
        $strPlural5     = 's';

        if ($arrUnitNames[3] == 'Swordmen') { $strPlural2 = '';}
        elseif ($arrUnitNames[3] == 'Pikemen') { $strPlural2 = '';}
        if ($arrUnitNames[4] == 'Crossbowmen') { $strPlural3 = '';}
        elseif ($arrUnitNames[4] == 'Longbowmen') { $strPlural3 = '';}
        elseif ($arrUnitNames[4] == 'Mummy') { $arrUnitNames[4] = 'Mummie';}
        if ($arrUnitNames[5] == 'Priestess') { $strPlural4 = 'es';}
        if ($arrUnitNames[6] == 'Thief') {$arrUnitNames[6] = 'Thieve';}

        $strTrgNews =
            "A report has been collected, our losses are listed as follows: " .
            "<br />" .
            $arrUnitNames[2] . $strPlural . " killed: " . "<span class=\"negative\">" .
            $arrTrgArmyLost[UNIT1] . "</span>," .
            "<br />" .
            $arrUnitNames[3] . $strPlural2 . " killed: " . "<span class=\"negative\">" .
            $arrTrgArmyLost[UNIT2] . "</span>," .
            "<br />" .
            $arrUnitNames[4] . $strPlural3 . " killed: " . "<span class=\"negative\">" .
            $arrTrgArmyLost[UNIT3] . "</span>," .
            "<br />" .
            $arrUnitNames[5] . $strPlural4 . " killed: " . "<span class=\"negative\">" .
            $arrTrgArmyLost[UNIT4] . "</span>.";
        //Add mystic losses for templars - AI 24/04/2007
        if ($arrTrgArmyLost[UNIT5] > 0) {
            $strTrgNews .= "<br />" .
                $arrUnitNames[6] . $strPlural5 . " killed: " . "<span class=\"negative\">" .
                $arrTrgArmyLost[UNIT5] . "</span>.";
        }

        if ($pestTrg == "yes")
        {
            $strTrgNews .=
                "<br />" .
                "<strong class=\"negative\">" . "During this invasion pestilence " .
                "was spread into our lands for 12 updates" . "</strong>.";
        }

        // create tribe news for attack
        mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', NOW(), '$ip', 'invade report', '$arrTrgStats[id]', '$arrSrcStats[id]', 1, '$strTrgNews', '')");
        $orkTime = date(TIMESTAMP_FORMAT);
        $objTrgUser->set_user_info(LAST_NEWS, $orkTime);

        // Update target rankings
        include_once('inc/functions/update_ranking.php');
        doUpdateRankings($objTrgUser, 'yes');

        // Log the op for blocking system  - AI 11/02/2007
        clsBlock::logOp($objSrcUser, $objTrgUser, 'Attack: ' . $arrWhite2[$iAttack]);

    }
}

?>

