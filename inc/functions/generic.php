<?php

/* Another generic function gone. Small step for a man, a giant leap for orkfiankind
// Martel, September 15, 2007
function check_new()
{
}*/

//==============================================================================
// New strength calc by Aldorin                             June 26, 2006 Martel
// (Other part of this found in clsUser)
//==============================================================================
function calcUnitStr(&$objUser, $unit_off, $unit_def)
{
    require_once('inc/functions/bonuses.php');
    $arrSci = getSciences($objUser->get_stat(ALLIANCE));

    // possibly + spells + war, but I dont think that part of the mod off is
    // intresting here (same with def)
    $X = $unit_off * (1 + $arrSci['war']);
    $Y = $unit_def * (1 + $arrSci['def']);

    // a fifteenth of a str pt for each off pt beyond half of def, if that is
    // positive.
    $def_str = $Y / 4 + max(( $X - $Y/2 ),0) / 15;

    // 5% more str on the unit for having turtle at all, then 1/20:th of a str
    // pt for each def pt.
    $off_str = $X / 4 * (1 + min( $Y , 0.05 ) ) + $Y / 20;

    // taking the highest of the two above
    $unit_str = max ( $off_str, $def_str );

    return $unit_str;
}

//==============================================================================
function convert_to_percent($item, $formatted) {
    global $local_build;

    $percentage = ($item / $local_build['land']) * 100;
    if ($formatted == "yes")
        $percentage = number_format($percentage);

    return $percentage;
}

//==============================================================================
function obj_check_protection(&$objUser, $strType)
{
    $strReturn = '';
    $objRace=$objUser->get_race();
    if ($objUser->get_user_info(HOURS) < PROTECTION_HOURS)
    {
        $iRemaining = PROTECTION_HOURS - $objUser->get_user_info(HOURS);

        if ($strType == 'status' && $_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME)
        {
            if ($iRemaining == PROTECTION_HOURS)
            {
                $strReturn =
                    'The people of your tribe are performing the rituals ' .
                    'required to inspire themselves with your physical ' .
                    'presence. Your spirit will guard the boundaries of your ' .
                    'new empire for <strong>' . $iRemaining . ' months</strong>, ' .
                    'repelling all those who are not loyal to your cause.' .
                    '</p><p>' .
                    'Alternatively, you can rush the summoning ritual and ' .
                    '<a href="main.php?cat=game&amp;page=startnow">start your tribe now</a>.';
            }
            elseif ($iRemaining > 1 && $iRemaining < PROTECTION_HOURS)
            {
                $strReturn =
                    'The people of your tribe are performing the rituals ' .
                    'required to inspire themselves with your physical ' .
                    'presence. Your spirit will guard the boundaries of your ' .
                    'new empire for <strong>' . $iRemaining . ' months</strong>, ' .
                    'repelling all those who are not loyal to your cause.';
            }
            elseif ($iRemaining == 1)
            {
                $strReturn =
                    'Your spirit shall be fully attached to your new body at ' .
                    'the end of <b>this month</b>. Your followers are ready ' .
                    'to give you their lives in the struggle for domination!';
            }
        }
        elseif ($strType == 'status')
        {
            if ($iRemaining > 0 && $iRemaining < PROTECTION_HOURS)
            {
                $strReturn = "You are currently under protection for $iRemaining updates.<br />";
            }
            elseif ($iRemaining == 1)
            {
                $strReturn = "You are out of protection at the end of this update, be ready leader!<br />";
            }
        }
        elseif ($strType == 'invade')
        {
            echo $strDiv =
            '<div id="textMedium"><p>' .
                'Your army refuses to leave your local borders out of fear ' .
                'for the disruption of our summoning ritual. You will be able ' .
                'to fully command your armies in ' . $iRemaining . ' months.' .
                '</p><p>' .
                '<a href="main.php?cat=game&page=invade">' . "Return to Invasion" . "</a>" .
            '</p></div>';

            include_game_down();
            exit;
        }
        elseif ($strType == 'magic')
        {
            echo $strDiv =
            '<div id="textMedium"><p>' .
                'Your Mage is unable to perform any black magic at this ' .
                'time, glorious leader! He cannot maintain the link between ' .
                'your spirit and himself if you force him to do so. He will ' .
                'perform any spell you ask for in ' . $iRemaining . ' months.' .
                '</p><p>' .
                '<a href="main.php?cat=game&page=mystics">' . "Try Again ?" . "</a>" .
            '</p></div>';

            include_game_down();
            exit;
        }
        elseif ($strType == 'thievery')
        {
            echo $strDiv =
            '<div id="textMedium"><p>' .
                'The head of our thieves thinks it is not wise to draw too ' .
                'much attention to the tribe while the summoning ritual is ' .
                'being performed. He informs you that it will be safe to go ' .
                'out ' . $iRemaining . ' months from now.' .
                '</p><p>' .
                '<a href="main.php?cat=game&page=thievery">' . "Try Again ?" . "</a>" .
            '</p></div>';

            include_game_down();
            exit;
        }
    }
    elseif ($objUser->get_user_info(HOURS) > $objRace->getLifespan() && $strType == 'invade')
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Your general is nowhere in sight and your captains seem to be ' .
            'doubting your capability to judge properly who to invade or not. ' .
            '</p><p>' .
            '<a href="main.php?cat=game&page=invade">' . "Return" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    return $strReturn;
}

//==============================================================================
//                                                         Martel, July 06, 2006
// Improved whole thing after age c#26 war kill bugs - Martel, December 30, 2007
//==============================================================================
function obj_test_for_kill(&$objTrgUser, &$objSrcUser)
{
    global $ip;

    // Minimize multi-kills - Martel December 30, 2007
    //  #1 Query DB for kill status (Don't use cached data) #2 Check citz
    $strSQL = 'SELECT killed FROM stats WHERE id = '.$objTrgUser->get_userid();
    $arrRow = mysql_fetch_row(mysql_query($strSQL));
    if ($arrRow[0] == 0 && $objTrgUser->get_pop(CITIZENS) <= 0)
    {
        // Separate a reset tribe from a killed (1=age, 2=pk)
        $objTrgUser->set_stat(KILLED, 2);

        // Show kill message
        $arrTrgStats = $objTrgUser->get_stats();
        echo $strDiv =
        '<div id="textMedium">' .
            '<p>' .
                "Congratulations! You watch on as the last citizen of " .
                '<strong class="negative">' .
                stripslashes($arrTrgStats[TRIBE]).' (#'.$arrTrgStats[ALLIANCE] .
                ")</strong> dies and their empire crumbles in front of you." .
            '</p>' .
        '</div>' .
        '<br />';

        // Update SrcUser with +1 Kill
        $arrSrcStats = $objSrcUser->get_stats();
        $iKilled = $arrSrcStats[KILLS] + 1;
        $objSrcUser->set_stat(KILLS, $iKilled);

        // Record: Largest Kill
        require_once('inc/classes/clsGame.php');
        $objGame      = new clsGame();
        $arrRecords   = $objGame->get_game_records();
        $arrTrgBuilds = $objTrgUser->get_builds();
        if ($arrTrgBuilds[LAND] > $arrRecords[KILLED])
        {
            $arrRecords = array(
                KILLED => $arrTrgBuilds[LAND],
                KILLED_ID => $objSrcUser->get_userid()
            );
            $objGame->set_game_records($arrRecords);
        }

        // War effects
        require_once("inc/functions/war.php");
        $objSrcAlliance = $objSrcUser->get_alliance();
        if (checkWarBetween($objSrcAlliance, $arrTrgStats[ALLIANCE]))
        {
            $objTrgAlliance = $objTrgUser->get_alliance();

            // Update land counter in new war system       March 06, 2008 Martel
            $iNeeded = $objSrcAlliance->get_war('land_needed');
            $objSrcAlliance->set_war('land_needed', max(0, $iNeeded - $arrTrgBuilds[LAND]));

            // Wait with adding this until it is 100% sure no multi kills exist
//             if (($arrGains = testWarVictory($objSrcAlliance, $objTrgAlliance)))
//             {
//                 require_once('inc/pages/war_room2.inc.php');
//                 $strGains = getVictoryReport($arrGains);
//                 // Show war-win message
//                 echo $strDiv =
//                 '<div id="textMedium">' .
//                     '<p><strong class="positive">Your alliance have won the war!</strong></p>' .
//                     $strGains .
//                 '</div>' .
//                 '<br />';
//             }

            // Add war-kill to global news
            $strGlobalNews =
                '<strong class="negative">Alliance #' . $arrSrcStats[ALLIANCE] .
                " has laid the final blow in " . $arrTrgStats[TRIBE] .
                "\'s ORKFiA career during their war.</strong>";
            mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, " .
                        "result, text, kingdom_text, kingdoma, kingdomb) " .
                        "VALUES ('', NOW(), '---', 'global', 0, 0, 1, '', " .
                        "'$strGlobalNews', 0, '')");

            // War events if a killed tribe is in the biggest half of the alli
            $iLand   = $arrTrgBuilds[LAND];
            $arrRow1 = mysql_fetch_row(mysql_query("SELECT COUNT(id) FROM rankings_personal WHERE land > $iLand AND alli_id = " . $arrTrgStats[ALLIANCE]));
            $arrRow2 = mysql_fetch_row(mysql_query("SELECT COUNT(id) FROM rankings_personal WHERE alli_id = " . $arrTrgStats[ALLIANCE]));
            if ($arrRow1[0] * 2 < $arrRow2[0])
            {
                require_once("inc/functions/update.php");
                //==============================================================
                // Bonus for the killing alliance: MP+TP gain, army
                // moved 1 hours closer to home
                //==============================================================
                // Since we don't seem to want a max to the mps/tps here,
                //  why not just add obj_mage_power_growth twice, same for
                //  thieves ofc                              - AI 25/11/2006
                //==============================================================
                $arrUserId      = $objSrcAlliance->get_userids();
                foreach($arrUserId as $iUserid)
                {
                    check_to_update($iUserid);
                    $objTmpUser   = new clsUser($iUserid);
                    $build        = $objTmpUser->get_builds();

                    // Bonus to Thievery Credits
                    require_once("inc/functions/ops.php");
                    $bonus1       = 2 * obj_thief_op_growth($objTmpUser);
                    $iNewCredits  = $objTmpUser->get_thievery(CREDITS);
                    $iNewCredits += $bonus1;

                    // Bonus to Spell Power
                    require_once("inc/functions/spells.php");
                    $bonus2       = 2 * obj_mage_power_growth($objTmpUser);
                    $iNewPower    = $objTmpUser->get_spell(POWER);
                    $iNewPower   += $bonus2;

                    // Update User
                    $objTmpUser->set_thievery(CREDITS, $iNewCredits);
                    $objTmpUser->set_spell(POWER, $iNewPower);

                    // Update Military Training 1x time (Mori thieves)
                    $arrTmpStats = $objTmpUser->get_stats();
                    if ($arrTmpStats[RACE] == "Mori Hai")
                    {
                        $arrMercs = $objTmpUser->get_army_mercs();
                        $arrNewMercs = array
                        (
                            MERC_T0 => $arrMercs[MERC_T1],
                            MERC_T1 => $arrMercs[MERC_T2],
                            MERC_T2 => $arrMercs[MERC_T3],
                            MERC_T3 => 0
                        );
                        $objTmpUser->set_army_mercs($arrNewMercs);
                    }

                    // Update Military Training 1x time (Everybody)
                    $arrRets = $objTmpUser->get_milreturns();
                    $arrNewRets = array
                    (
                        UNIT1_T1 => $arrRets[UNIT1_T2],
                        UNIT1_T2 => $arrRets[UNIT1_T3],
                        UNIT1_T3 => $arrRets[UNIT1_T4],
                        UNIT1_T4 => 0,
                        UNIT2_T1 => $arrRets[UNIT2_T2],
                        UNIT2_T2 => $arrRets[UNIT2_T3],
                        UNIT2_T3 => $arrRets[UNIT2_T4],
                        UNIT2_T4 => 0,
                        UNIT3_T1 => $arrRets[UNIT3_T2],
                        UNIT3_T2 => $arrRets[UNIT3_T3],
                        UNIT3_T3 => $arrRets[UNIT3_T4],
                        UNIT3_T4 => 0,
                        UNIT4_T1 => $arrRets[UNIT4_T2],
                        UNIT4_T2 => $arrRets[UNIT4_T3],
                        UNIT4_T3 => $arrRets[UNIT4_T4],
                        UNIT4_T4 => 0,
                        UNIT5_T1 => $arrRets[UNIT5_T2],
                        UNIT5_T2 => $arrRets[UNIT5_T3],
                        UNIT5_T3 => $arrRets[UNIT5_T4],
                        UNIT5_T4 => 0,
                        UNIT6_T1 => $arrRets[UNIT6_T2],
                        UNIT6_T2 => $arrRets[UNIT6_T3],
                        UNIT6_T3 => $arrRets[UNIT6_T4],
                        UNIT6_T4 => 0
                    );
                    $objTmpUser->set_milreturns($arrNewRets);

                    // Update Tribe News
                    $strNews =
                        "The death of one of our enemies has given our ".
                        "troops courage! Armies are returning more quickly, " .
                        "thieves are more willing to risk their lives for the ".
                        "tribe and your mage feels extra powerful.";
                    mysql_query("INSERT INTO news (time, ip, type, duser, " .
                                "ouser, result, text, kingdom_text) VALUES " .
                                "(NOW(), '---', 'local_news', $iUserid, '', " .
                                "1, '$strNews','')");
                    $objTmpUser->set_user_info(LAST_NEWS, 1);
                }

                // Defiance for the losing alliance, gives bonuses to off/tm-dmg
                $arrUserId      = $objTrgAlliance->get_userids();
                foreach ($arrUserId as $iUserid)
                {
                    check_to_update($iUserid);
                    $objTmpUser = new clsUser($iUserid);
                    $objTmpUser->set_spell(DEFIANCE, 4);

                    $strNews =
                        "The death of one of our big tribes has filled the " .
                        "hearts of our people with anger! For 4 more months " .
                        "we will strike hard at our enemies!";
                    mysql_query("INSERT INTO news (time, ip, type, duser, " .
                                "ouser, result, text, kingdom_text) VALUES " .
                                "(NOW(), '---', 'local_news', $iUserid, '', " .
                                "1, '$strNews','')");
                    $objTmpUser->set_user_info(LAST_NEWS, 1);
                }
            } //top half
        } //war

        // Create News
        $timestamp = date(TIMESTAMP_FORMAT);

        $d_news    =
            '<strong class="negative">' . $arrSrcStats[TRIBE] . " (#" .
            $arrSrcStats[ALLIANCE] . ") has laid the final blow in " .
            $arrTrgStats[TRIBE] . "\'s ORKFiA career.</strong>";

        $o_news    =
            '<strong class="positive">Our ' . $arrSrcStats[TRIBE] .
            " has laid the final blow in " . $arrTrgStats[TRIBE] . " (#" .
            $arrTrgStats[ALLIANCE] . ")\'s ORKFiA career.</strong>";

        $strNews   =
            "I am sorry leader, upon your return to your tribe your alliance " .
            "has sent this message forth to you." .
            "<br /><br />" .
            $d_news .
            "<br /><br />" .
            "They have also sent us supplies and some citizens to restart " .
            "our tribe." .
            "<br /><br />" .
            "Below this line is news our previous tribe had recieved:";

        $strSQL    =
            "INSERT INTO news (time, ip, type, duser, ouser, result, " .
            "text, kingdom_text, kingdoma, kingdomb) VALUES ('$timestamp', " .
            "'$ip', 'killed', {$arrTrgStats[ID]}, 0, 1, '$strNews', " .
            "'$d_news', '{$arrTrgStats[ALLIANCE]}', '')";
        mysql_query($strSQL);

        $strSQL    =
            "INSERT INTO news (time, ip, type, duser, ouser, result, text, " .
            "kingdom_text, kingdoma, kingdomb) VALUES ('$timestamp', '$ip', " .
            "'killed', 0, 0, 1, '', '$o_news', '{$arrSrcStats[ALLIANCE]}', '')";
        mysql_query($strSQL);

        //======================================================================
        // Kill the tribe
        //======================================================================
        require_once('inc/staff/delete.inc.php');
        doBackupTribe($objTrgUser->get_userid(), 'kill');

        obj_kill_user($objTrgUser);
    }
    else
    {
        return;
    }
}

//==============================================================================
//                                                         Martel, July 06, 2006
//==============================================================================
function obj_kill_user(&$objUser)
{
    // Tag tribe to be restarted
    $objUser->set_stat(RESET_OPTION, 'yes');

    // Remove invested research
    //require_once("inc/functions/research.php");
    //delete_my_rps($objUser->get_userid());
    // no more - AI 07/05/07

    // Mail user to tell them they have been killed
    $strMail =
        "Unfortunately your tribe in ORKFiA has died. It is now ready " .
        "to be restarted." . "\n\n" . HOST . SIGNED_ORKFIA;
    $strEmail = stripslashes($objUser->get_preference(EMAIL));
    mail($strEmail, "Your tribe is no more =(", $strMail, "From: ORKFiA <" .
    EMAIL_REPORTER.">\r\nX-Mailer: PHP/".phpversion()."\r\nX-Priority: Normal");

    // Calculate Re-starting Bonus
    // Species5618 (7-6-04): Setting a flag that marks the tribe as dead, so
    // that they do receive their age-death-bonus ("heritage") when they login
    if ($objUser->get_stat(KILLED) == 1 || $_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
    {
        require_once('inc/functions/population.php');
        $arrPopulation = getPopulation($objUser);

        require_once('inc/functions/races.php');
        $strRace       = $objUser->get_stat(RACE);
        $arrUnitVars   = getUnitVariables($strRace);
        $arrUnitCost   = $arrUnitVars['gold'];

        // Calculate the killed army's worth
        $total_value   = $arrPopulation['basics'] * $arrUnitCost[2];
        $total_value  += $arrPopulation['off_specs'] * $arrUnitCost[3];
        $total_value  += $arrPopulation['def_specs'] * $arrUnitCost[4];
        $total_value  += $arrPopulation['elites'] * $arrUnitCost[5];
        $total_value  += $arrPopulation['thieves'] * $arrUnitCost[6];
        $total_value   = round($total_value * .02);

        // Calculate the killed army in basics
        $total_troops  = round($arrPopulation['total_army'] / 40);

        // Base Housing Capacities
        require_once('inc/functions/build.php');
        $arrBuildVariables = getBuildingVariables($strRace);
        $homes_hold        = $arrBuildVariables['housing'][1];

        // Add kill-bonus
        $iLand    = $objUser->get_build(LAND);
        $fame     = $objUser->get_stat(FAME);
        //Removed - AI 08/05/07
        //$research = $objUser->get_stat(INVESTED);
        $arrKills = array
        (
            LAND => $iLand,
            CASH => $total_value,
            BASICS => $total_troops,
            POP => $homes_hold,
            FAME => $fame,
            //Removed - AI 08/05/07
            //RESEARCH => $research
        );
        $objUser->set_kills($arrKills);
    }

    // Reset Tribe Rankings
    $arrRankingsPersonal = array
    (
        STRENGTH => 3000,
        LAND => STARTING_LAND,
        FAME => 5000,
        HOURS => 0
    );
    $objUser->set_rankings_personals($arrRankingsPersonal);

    // Update Alliance Rankings
    $objAlliance = $objUser->get_alliance();
    $objAlliance->do_update_ranking();

    // Set total land to what would be inherited (fix a science bug),
    //  we're using a hardcoded value here which just happens to be the same
    //  as the one used in inc/pages/reset_account.inc.php,
    //  this is a BAD THING                                      - AI 11/01/2006
    // Age changes coding - new heritage formula
    // Martel, September 16, 2007 (Plus also using constant and not '400')
    $iLand               = $objUser->get_build(LAND);
    $iNewFormulaHeritage = round(pow(max(0, $iLand - STARTING_LAND), 0.80459611995) + STARTING_LAND);
    if (($objUser->get_stat(KILLED) == 1 || $_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME) && $iNewFormulaHeritage > STARTING_LAND)
        $iLand = $iNewFormulaHeritage;

    if ($iLand > 2000)
        $iLand = 2000;
    elseif ($iLand < STARTING_LAND)
        $iLand = STARTING_LAND;

    // Save new land (to fix a science bug)
    $objUser->set_build(LAND, $iLand);

    // Reset other stuff too, so the tribe can be defected         - AI 02/12/06
    $objUser->set_user_info(PAUSE_ACCOUNT, 0);
}

//==============================================================================
// Check wether an array is 'empty' (contains only empty values)
//  this is not the same as empty($array)                          - AI 02/10/06
//==============================================================================
function array_empty($array) {
    if (!is_array($array)) return false;
    foreach ($array as $item) {
        if(!empty($item)) return false;
    }
    return true;
}

//==============================================================================
// A correct quoting function for mysql                            - AI 06/10/06
// Martel: V.2 - December 30, 2007
//==============================================================================
function quote_smart($val) {
    if (is_array($val))
        return array_map('quote_smart', $val);
    else {
        if (get_magic_quotes_gpc()) $val = stripslashes($val);
        if ($val == '') $val = 'NULL';
        if (!is_numeric($val) || $val[0] == '0')
            $val = "'".mysql_real_escape_string($val)."'";
        return $val;
    }
}

//==============================================================================
// M: Legacy functions - try remove the need for all of them
//==============================================================================

function mysql_grab() {
    global  $local_goods,$local_build,$local_stats,$local_pop,$local_army,
            $gamestats,$local_land,$local_milreturn, $local_kingdom,$local_user,
            $local_spells, $result, $userid, $i, $current, $var_name, $d_build, $local_admin,
            $d_stats,$d_pop,$d_army,$d_land,$d_milreturn,$d_kingdom,$d_user,$d_spells,
            $prefix,$d_goods,$local_preferences,$d_thievery,$local_thievery;

    $number = (func_num_args() -1);
    $local_userid = func_get_arg(0);
    $prefix =func_get_arg(1)."_";

    for ($i = 2; $i <= $number; $i++) {
        $current = trim(func_get_arg($i));
        $var_name = $prefix."" .$current;
        $result = mysql_query ("SELECT * FROM $current WHERE id = '$local_userid' ") or die(mysql_error());;
        $$var_name= mysql_fetch_array($result);
    }

    $local_stats['kingdom'] = stripslashes($local_stats['kingdom']);
}

function fmat($var) {
    $var = trim($var);
    $var = strip_tags($var);
    $var = htmlspecialchars($var);
    $var = addslashes($var);

    return $var;
}

function fmat_values() {
    global  $register;

    $number = (func_num_args());
    for ($i =0; $i < $number; $i++)     {
        $current = trim(func_get_arg($i));
        fmat($current);
    }
}

function get_unit_names($i_strRace) {
    $unit_names = "";

    //switch to using clsRace - AI 10/02/2007
    $unit_var = "citizen|unit1|unit2|unit3|unit4|unit5";
    $unit_var = explode("|", $unit_var);
    require_once('inc/races/clsRace.php');
    $objRace = clsRace::getRace($i_strRace);
    $unit_names = $objRace->getUnitNames();
    return (array($unit_var, $unit_names));
}

function unit_names($race) {
    global $unit_var, $unit_names;

    $get_unit_names = get_unit_names($race);
    $unit_var = $get_unit_names[0];
    $unit_names = $get_unit_names[1];
    array_shift($unit_var);
    $unit_names = array_merge($unit_names);
}

?>
