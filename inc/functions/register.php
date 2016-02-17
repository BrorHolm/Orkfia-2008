<?php
//******************************************************************************
// functions register.php                                   Martel, May 30, 2006
//
// History:
// August 06, 2006 - Martel: 8 Hour OOP Concept modifications
//******************************************************************************

//==============================================================================
// Include the classes                                            - AI 07/10/06
// Comment: (Martel) Can't use classes for user or alliance until created
//==============================================================================
include_once('inc/classes/clsAlliance.php');
include_once('inc/classes/clsUser.php');
include_once('inc/classes/clsGame.php');

//==============================================================================
// Make room for a new alliances                        Martel, October 17, 2006
//==============================================================================
function check_empty_alliances($new_k = 0)
{
    for ($i = (MAX_ALLIANCES + 10); $i > 10; $i--)
    {
        // Martel: Complete routines for alliance-cleaning now
        $num_in_alli = mysql_query("SELECT COUNT(id) AS number FROM stats WHERE " . ALLIANCE . " = $i");
        $num_in_alli = mysql_fetch_array($num_in_alli);
        if($num_in_alli['number'] == 0)
        {
            // Delete empty alliance's info
            mysql_query("DELETE FROM " . TBL_ALLIANCE . " WHERE id = $i") or die("empty alliance 1");
            mysql_query("DELETE FROM " . TBL_RANKINGS_ALLIANCE . " WHERE id = $i") or die("empty alliance 2");
            mysql_query("DELETE FROM " . TBL_WAR . " WHERE id = $i") or die("empty alliance 3");
            mysql_query("DELETE FROM " . "forum" . " WHERE poster_kd = $i") or die("empty alliance 4");
            mysql_query("DELETE FROM " . "market_log" . " WHERE alliance = $i") or die("empty alliance 5");
            mysql_query("DELETE FROM " . "news" . " WHERE kingdoma = $i") or die("empty alliance 6");

            // Choose this id for new alliance (will be lowest empty)
            $new_k = $i;
        }
    }

    if ($new_k > 0)
    {
        // Update our #alliances counter... (since we have one)
        $newTotal = mysql_query("SELECT COUNT(id) AS number FROM " . TBL_ALLIANCE) or die("alliances counter");
        $newTotal = mysql_fetch_array($newTotal);
        mysql_query("UPDATE gamestats SET kingdoms = $newTotal[number] WHERE id = 1") or die("alliances counter 2");
    }

    return $new_k;
}

//==============================================================================
// Get bootcamp (quick version)                             Martel, May 06, 2007
// Information: Just modified my other code from inc/pages/register1.php
// Comment: Code stopped working when moving to a slightly older MySQL. Odd. (M)
//==============================================================================
function get_first_bootcamp($new_k = 0)
{
//     $strSQL = "SELECT kingdom, COUNT(*) as num_players FROM stats GROUP BY kingdom HAVING kingdom IN(SELECT id as kingdom FROM kingdom WHERE bootcamp = 'yes') ORDER BY kingdom DESC";
//     $resSQL = mysql_query($strSQL);

//     $iOpenBootcamps = 0;
//     while ($arrRES = mysql_fetch_array($resSQL))
//     {
//         if ($arrRES['num_players'] < MAX_ALLIANCE_SIZE)
//         {
// //             $iOpenBootcamps++;
// //             $iLowestAlliNr = $arrRES['kingdom'];
// //             $iSpotsOpen = MAX_ALLIANCE_SIZE - $arrRES['num_players'];

//             $new_k = $arrRES[ALLIANCE];
//         }
//     }

    $iOpenBootcamps = 0;
    $strSQL = "SELECT id FROM " . ALLIANCE . " WHERE bootcamp = 'yes' ORDER BY id DESC";
    $resSQL = mysql_query($strSQL);
    while ($arrRES = mysql_fetch_array($resSQL))
    {
        $strSQL = "SELECT COUNT(*), kingdom FROM stats WHERE kingdom = $arrRES[id] GROUP BY kingdom";
        $resSQL2 = mysql_query($strSQL);
        $arrRES2 = mysql_fetch_row($resSQL2);

        if ($arrRES2[0] < MAX_ALLIANCE_SIZE)
        {
//             $iOpenBootcamps++;
//             $iLowestAlliNr = $arrRES2[1];
//             $iSpotsOpen = MAX_ALLIANCE_SIZE - $arrRES[0];
            $new_k = $arrRES2[1];
        }
    }

    return $new_k;
}

//==============================================================================
// Added 'active race' check - AI 15/01/2006
//==============================================================================
function check_race($strRace, $cont = '')
{
    include_once('inc/functions/races.php');

    $arrRaces = getRaces();
    $arrActiveRaces = getActiveRaces();
    if(in_array($strRace, $arrActiveRaces))
    {
        // Race is okay!
    }
    else
    {
        if(in_array($strRace, $arrRaces))
        {
            echo "$strRace is currently disabled";
        }
        else
        {
            echo "Please do not try \"edit\" values";
        }
        $cont = "no";
    }

    return $cont;
}

//==============================================================================
// function password_check($arrRegister)
// {
//     if ($register['password'] != $register['verify'])
//     {
//         echo "Your passwords do not match<br />";
//         $cont = 'no';
//     }

//     return $cont;
// }

//==============================================================================
function fmat_kingdom($arrRegister, $cont)
{
    switch($arrRegister['alliance_type'])
    {
        case "new":

            $arrRegister['kingdom_name'] = addslashes(htmlspecialchars(strip_tags(trim($arrRegister['kingdom_name']))));
            $arrRegister['kingdom_pass'] = addslashes(htmlspecialchars(strip_tags(trim($arrRegister['kingdom_pass']))));

            $resSQL = mysql_query("SELECT * FROM " . TBL_GAME_HISTORY .
                                 " WHERE alli_name = '$arrRegister[kingdom_name]'");
            $iNumRows = mysql_num_rows($resSQL);

            if (! $arrRegister['kingdom_name'])
            {
                echo "Please enter an alliance name<br />";
                $cont = "no";
            }
            elseif ($iNumRows > 0)
            {
                echo "You can't use this alliance name<br />";
                $cont = "no";
            }

            if (! $arrRegister['kingdom_pass'])
            {
                echo "Please enter an alliance password<br />";
                $cont= "no";
            }

        break;
        case "existing":

            $arrRegister['ex_id']   = addslashes(htmlspecialchars(strip_tags(trim($arrRegister['ex_id']))));
            $arrRegister['ex_id']   = intval($arrRegister['ex_id']);
            $arrRegister['ex_pass'] = addslashes(htmlspecialchars(strip_tags(trim($arrRegister['ex_pass']))));

            if (! $arrRegister['ex_id'])
            {
                echo "Please enter an alliance #<br />";
                $cont = "no";
            }
            if (! $arrRegister['ex_pass'])
            {
                echo "Please enter an alliance password<br />";
                $cont = "no";
            }

        break;
    }

    return $cont;
}

//==============================================================================
function check_taken($arrRegister, $cont)
{
    $login_search = mysql_query ("SELECT * FROM user WHERE username = '$arrRegister[login]'") ;
    $login_search = mysql_fetch_array($login_search);
    if ($login_search)
    {
        echo "That username is already taken.<br />";
        $cont ='no';
    }

    $stat_search = mysql_query ("SELECT * FROM stats WHERE tribe = '$arrRegister[tribe]'");
    $stat_search = mysql_fetch_array($stat_search);
    if ($stat_search)
    {
        echo "That tribe name is taken.<br />";
        $cont = 'no';
    }

    if ($_SERVER['SERVER_NAME'] != DEV_SERVER_NAME)
    {
        $pref_search = mysql_query ("SELECT * FROM preferences WHERE email = '$arrRegister[email]'");
        $pref_search = mysql_fetch_array($pref_search);
        if ($pref_search)
        {
            echo "That email is in use.<br />";
            $cont = 'no';
        }
    }

    $stat_search2 = mysql_query ("SELECT * FROM stats WHERE name = '$arrRegister[alias]'");
    $stat_search2 = mysql_fetch_array($stat_search2);
    if ($stat_search2)
    {
        $cont = 'no';
        echo "That alias is taken<br /><br />";
    }

    return $cont;
}

//==============================================================================
function alliance_placement($alliance_num, $register)
{
    $register_type = $register['alliance_type'];

    switch ($register_type)
    {
        case "new":

            // Fetch current hour
            // (sci decay fix with "NULL" updates)      Martel, January 27, 2008
            $objGame    = new clsGame();
            $arrTime    = $objGame->get_game_times();
            $iGameHours = $arrTime[HOUR_COUNTER];

            // Delete Tables (should already be done, just in case.. 27-1-2008)
            mysql_query("DELETE FROM " . TBL_ALLIANCE . " WHERE id = $alliance_num");
            mysql_query("DELETE FROM " . TBL_RANKINGS_ALLIANCE . " WHERE id = $alliance_num");
            mysql_query("DELETE FROM " . TBL_WAR . " WHERE id = $alliance_num");

            // Create Tables
            mysql_query("INSERT INTO " . TBL_ALLIANCE . " SET id = $alliance_num, " . NAME . " = '$register[kingdom_name]', " . PASSWORD . " = '$register[kingdom_pass]', research_hour = $iGameHours, market_hour = $iGameHours") or die("alliance table");
            mysql_query("INSERT INTO " . TBL_RANKINGS_ALLIANCE . " SET id = $alliance_num, " . ALLI_NAME . " = '$register[kingdom_name]'") or die("alliance_rankings table");
            mysql_query("INSERT INTO " . TBL_WAR . " SET id = $alliance_num") or die("war table");

            // Update our #alliances counter... (since we have one)
            $newTotal = mysql_query("SELECT COUNT(id) AS number FROM " . TBL_ALLIANCE) or die("alliances counter");
            $newTotal = mysql_fetch_array($newTotal);
            mysql_query("UPDATE gamestats SET kingdoms = $newTotal[number] WHERE id = 1") or die("alliances counter 2");

            // Create Alliance Creation News
            $strSQL =
                "INSERT INTO news " .
                "(id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) " .
                "VALUES ('', UNIX_TIMESTAMP(), '', 'admin', 0, 0, 1, '', '<span class=\"elder\">The alliance of " . $register['kingdom_name'] . " has been formed</span>', $alliance_num, 0)";
            mysql_query($strSQL) or die("Alliance Creation News");

            echo
                "You have formed a new alliance!" .
                "<br /><br />" .

                "This is the info you should pass to any friends " .
                "to join your Alliance:" .
                "<br />" .
                "Alliance #: " . $alliance_num .
                "<br />" .
                "Password: " . $register['kingdom_pass'] .
                "<br />" .
                HOST . // M: too long: 'main.php?cat=main&amp;page=register1&amp;alliance_type=existing' .
                "<br /><br />" .

                "You may log in 3 times before you have to verify your " .
                "email address. You have 48 hours to verify it before the " .
                "account will be automatically deleted.";

        break;
        case "existing":

            $num_search  = mysql_query ("SELECT * FROM stats WHERE kingdom = '$alliance_num'") or die('');
            $num_players = mysql_num_rows($num_search);

            $kingdom_search = mysql_query ("SELECT * FROM kingdom WHERE id = '$register[ex_id]'");
            $kingdom_search = mysql_fetch_array($kingdom_search);


            echo "There are currently $num_players in this Alliance.<br /><br />";


            if ($num_players >= MAX_ALLIANCE_SIZE)
            {
                echo "Sorry, but you cannot create this account because there is a max of ".MAX_ALLIANCE_SIZE." tribes per alliance.</p></div>";

                include_main_down();
                exit;
            }

            // don't join allis that are in war  - AI 01/10/06
            $objAlliance = new clsAlliance($alliance_num);
            if ($objAlliance->get_war(TARGET) != 0)
            {
                echo "Sorry, alliance $alliance_num is currently at war, try joining again when the fighting dies down.</p></div>";
                include_main_down();
                exit;
            }

            if ($register['ex_pass'] == $kingdom_search['password'])
            {
                echo "You have successfully joined Alliance (#" .$register['ex_id']. ")<br /><br /> Please login and start playing :-)<br /><br /> You may log in 3 times until you have to verify your email address.";
            }
            else
            {
                echo "Sorry, you don't have the right password, you cannot join that alliance.</p></div>";
                include_main_down();
                exit;
            }

        break;
        case "random":

            // Bootcamp exception
            if ($register['bootcamp'] == 'yes')
            {
                $alliance_num = get_first_bootcamp();
                echo "<br /><br />Congratulations, your have created an account!<br /><br />You may log in 3 times before you have to verify your email address.";
                break;
            }

            // available constants: MAX_ALLIANCES, MAX_ALLIANCE_SIZE
            $LOWER_LIMIT = 11; // staff alliances 1-10
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
            $smallest_found = MAX_ALLIANCE_SIZE;
            $true = FALSE;
            $break = FALSE;
            while($true == FALSE && $break == FALSE)
            {
                $dice = rand(1, 6);

                $strQuery2 = "SELECT COUNT(*) as tribes FROM stats WHERE " . ALLIANCE . " = $arrAlliances[$i]";
                $resQuery2 = mysql_query($strQuery2) or die("There was an error with counting tribes in #" . $arrAlliances[$i]);
                $resQuery2 = mysql_fetch_array($resQuery2);

                // Remember # tribes and # alliances
                if ($resQuery2['tribes'] < $smallest_found)
                    $smallest_found = $resQuery2['tribes'];

                // join random only if there is room left and not private
                if ($resQuery2['tribes'] < MAX_ALLIANCE_SIZE &&
                    $dice == 6)
                {
                    include_once('inc/classes/clsAlliance.php');
                    $objTmpAlliance   = new clsAlliance($arrAlliances[$i]);
                    $arrAllianceInfos = $objTmpAlliance->get_alliance_infos();
                    // also, don't join if the alliance is at war
                    if ($arrAllianceInfos[PRIVATE] != 'yes' && $objTmpAlliance->get_war(TARGET) == 0)
                    {
                        // do join alli
                        echo "The dice finally hits the ground, staring back at you it shows 6 carved out marks. You decide to join alliance (#".$arrAlliances[$i].").";
                        echo "<br /><br />Congratulations, your have created an account!<br /><br /> You may log in 3 times before you have to verify your email address.";
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
                    echo "We couldn't find you a spot..<br />";
                    if ($smallest_found == MAX_ALLIANCE_SIZE || $blnPrivateCheck)
                        echo "And sadly our sources indicate there is no room at all! The game seems to be full or the alliances with room are private :(";
                    else
                        echo "But there is room left, so please try your luck and join random again!";

                    if ($CUR_ALLIANCES >= MAX_ALLIANCES)
                        echo "<br />If you are considering creating a new alliance we're sorry... There is no room for another alliance :(";
                    else
                        echo "<br />But, good news is that you may also consider creating a new alliance! (There is room available.)";

                    echo "</p></div>";
                    include_main_down();
                    exit;
                }

                $i++;

                // Start over again if we didn't find a spot
                if ($i >= $CUR_ALLIANCES)
                {
                    $loop_stop++;
                    $i = 0;
                }
            }

        break;
    }
    return $alliance_num;
}

//==============================================================================
//==============================================================================
function make_army_data($iUserId, $strRace)
{
    include_once('inc/functions/reset_account.php');
    $arrStartValues = getStartValues($strRace);

    $strSQL = "INSERT INTO " . TBL_ARMY . " " .
                      "SET " . UNIT1 . " = {$arrStartValues[UNIT1]}, " .
                               UNIT2 . " = {$arrStartValues[UNIT2]}, " .
                               UNIT3 . " = {$arrStartValues[UNIT3]}, " .
                               UNIT4 . " = {$arrStartValues[UNIT4]}, " .
                               UNIT5 . " = {$arrStartValues[UNIT5]}, " .
                                  ID . " = $iUserId";
//                                UNIT6 . " = {$arrStartValues[UNIT6]}, " .

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_army_data:" . mysql_error());

    if ($strRace == "Oleg Hai")
    {
        $strSQL2 = "UPDATE " . TBL_ARMY . " " .
                      "SET " . UNIT4 . " = 0 " .
                    "WHERE " . ID . " = $iUserId";

//         echo "<br />".$strSQL."<br />";
        mysql_query($strSQL2) or die("make_army_data2:" . mysql_error());
    }
}

//==============================================================================
//==============================================================================
function make_army_mercs_data($iUserId)
{
    $strSQL = "INSERT INTO " . TBL_ARMY_MERCS . " " .
                      "SET " . ID . " = $iUserId";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_army_mercs_data:" . mysql_error());
}

//==============================================================================
// create a ranking record - Lips
// Martel, May 29, 2006
//==============================================================================
function make_ranking_data($iUserId, $alliance_num, $register)
{
    $strSQL = "INSERT INTO " . TBL_RANKINGS_PERSONAL .
                     " SET " . ID . " = $iUserId, " .
                          ALLI_ID . " = $alliance_num, " .
                       TRIBE_NAME . " = '{$register[TRIBE]}'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_ranking_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_build_data($iUserId, $strRace)
{
    include_once('inc/functions/reset_account.php');
    $arrStartValues = getStartValues($strRace);

//     $land = STARTING_LAND;
//     // Buildings
//     $homes    = floor($land * 0.3);
//     $farms    = floor($land * 0.075);
//     $markets  = floor($land * 0.075);
//     $yards    = floor(1 + ($land / 1000)) * 40;

    $strSQL = "INSERT INTO " . TBL_BUILD . " " .
                      "SET " . LAND . " = {$arrStartValues[LAND]}, " .
                              HOMES . " = {$arrStartValues[HOMES]}, " .
                              FARMS . " = {$arrStartValues[FARMS]}, " .
                            MARKETS . " = {$arrStartValues[MARKETS]}, " .
                              YARDS . " = {$arrStartValues[YARDS]}, " .
                             GUILDS . " = {$arrStartValues[GUILDS]}, " .
                           HIDEOUTS . " = {$arrStartValues[HIDEOUTS]}, " .
                                 ID . " = $iUserId";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_build_data:" . mysql_error());
}

//==============================================================================
//                                                                        Martel
//==============================================================================
function make_goods_data($iUserId, $strRace)
{
    include_once('inc/functions/reset_account.php');
    $arrStartValues = getStartValues($strRace);

//     include_once('inc/functions/build.php');
//     $arrBuildVariables = getBuildingVariables($strRace);
//     $homes_hold        = $arrBuildVariables['housing'][1];
//     $money             = (120 * $homes_hold * STARTING_LAND);
//     $food              = (40 * $homes_hold);
//     $wood              = (25 * STARTING_LAND);

    $strSQL = "INSERT INTO " . TBL_GOODS . " " .
                      "SET " . ID . " = $iUserId, " .
                            MONEY . " = {$arrStartValues[MONEY]}, " .
                             FOOD . " = {$arrStartValues[FOOD]}, " .
                         RESEARCH . " = {$arrStartValues[RESEARCH]}, " .
                             WOOD . " = {$arrStartValues[WOOD]}";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_goods_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_kills_data($iUserId)
{
    $strSQL = "INSERT INTO " . TBL_KILLS . " " .
                      "SET " . ID . " = $iUserId";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_kills_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_milreturn_data($iUserId)
{
    $strSQL = "INSERT INTO " . TBL_MILRETURN . " " .
                      "SET " . ID . " = $iUserId";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_milreturn_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_pop_data($iUserId, $strRace)
{
    include_once('inc/functions/reset_account.php');
    $arrStartValues = getStartValues($strRace);

//     include_once('inc/functions/build.php');
//     $arrBuildVariables = getBuildingVariables($strRace);
//     $homes_hold        = $arrBuildVariables['housing'][1];
//     $pop               = 120 * $homes_hold;

    $strSQL = "INSERT INTO " . TBL_POP . " " .
                      "SET " . ID . " = $iUserId, " .
                         CITIZENS . " = {$arrStartValues[CITIZENS]}";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_pop_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_preferences_data($iUserId, $strActivation, $strEmail)
{
    $strSQL = "INSERT INTO " . TBL_PREFERENCES . " " .
                      "SET " . ID . " = $iUserId, " .
                 EMAIL_ACTIVATION . " = '$strActivation', " .
                            EMAIL . " = '$strEmail'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_preferences_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_spell_data($iUserId, $strRace)
{
    include_once('inc/functions/reset_account.php');
    $arrStartValues = getStartValues($strRace);

    $strSQL = "INSERT INTO " . TBL_SPELL . " " .
                      "SET " . ID . " = $iUserId, " .
                           GROWTH . " = {$arrStartValues[GROWTH]}, " .
                           INCOME . " = {$arrStartValues[INCOME]}, " .
                       POPULATION . " = {$arrStartValues[POPULATION]}, " .
                             FOOD . " = $arrStartValues[matawaska]";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_spell_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_thievery_data($iUserId)
{
    $strSQL = "INSERT INTO " . TBL_THIEVERY . " " .
                      "SET " . ID . " = '$iUserId'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_thievery_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_user_data($iUserId, $md5sum, $register)
{
    $day    = date("d");
    $hour   = date("H");

    $strSQL = "INSERT INTO " . TBL_USER . " " .
                      "SET " . ID . " = '$iUserId', " .
                         USERNAME . " = '$register[login]', " .
                         PASSWORD . " = sha1('$register[password]'), " .
                              MD5 . " = '$md5sum', " .
                  LAST_UPDATE_DAY . " = $day, " .
                 LAST_UPDATE_HOUR . " = $hour + 1, " .
                         REALNAME . " = '$register[realname]', " .
                          COUNTRY . " = '$register[country]'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_user_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_online_data($iUserId, $timestamp)
{
    $strSQL = "INSERT INTO " . TBL_ONLINE . " " .
                      "SET " . ID . " = '$iUserId', " .
                             TIME . " = '$timestamp'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_online_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_stats_data($iUserId, $alliance_num, $register)
{
    $strSQL = "INSERT INTO " . TBL_STAT . " " .
                      "SET " . ID . " = '$iUserId', " .
                            TRIBE . " = '$register[tribe]', " .
                             NAME . " = '$register[alias]', " .
                             RACE . " = '$register[race]', " .
                         ALLIANCE . " = '$alliance_num'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_stats_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_news_data($playerid, $alliance_num, $register)
{
    $create  =
        "INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', NOW(), '', 'admin', '0', '0', '1', ' ', 'The Tribe of  " . $register['tribe'] . " Has Been Created. ', '".$alliance_num."', '0')";
    mysql_query($create) or die("make_news_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_mail_data($iUserId, $register)
{
    $strWelcome = "Hello there $register[alias]," .
    "<br />Welcome to the world of ORKFiA!" .

    "<br /><br />This is an exciting game where heros are made and slain " .
    "everyday. Go for personal glory by making the rankings with your tribe, " .
    "or team up with allies to vanquish your foes. Can you compete? Only " .
    "time will tell..." .

    "<br /><br />It is important that you review the " .
    "<a href=\"main.php?cat=game&amp;page=CoC\">Code of Conduct</a> often. " .
    "There are harsh penalties for those who cheat or misbehave, so read it " .
    "and know what is and is not allowed here in ORKFiA." .

//     "<br /><br />We recommend that you create an account on our " .
//     "<a href=\"http://forum.orkfia.org/\" class=\"newWindowLink\" " .
//     "target=\"_blank\">Community Forum</a>; it is a great place to meet the " .
//     "other players, find answers to your questions, and to keep up to date on " .
//     "game news and age changes. " .

    "<br /><br />ORKFiA also has its own IRC channel, " .
    "<a href=\"irc://irc.netgamers.org/orkfia\" class=\"newWindowLink\">#orkfia</a>, on the " .
    "Netgamers.org servers. Its sole intention is to be a friendly place " .
    "where you can talk about ORKFiA or just hang out with the staff and " .
    "players. It is usually pretty busy and is a great place to learn the " .
    "game and joke around with your friends." .

    "<br /><br />Once again, welcome to the world of ORKFiA, we hope you " .
    "will enjoy this game as much as we do!" .

    "<br /><br />~ The ORKFiA Staff Team<br />";

    $strSQL = "INSERT INTO " . "messages" . " " .
                      "SET " . "for_user" . " = '$iUserId', " .
                              "from_user" . " = '1', " .
                                   "date" . " = NOW(), " .
                                "subject" . " = 'Hello $register[alias]', " .
                                   "text" . " = '$strWelcome', " .
                                 "action" . " = 'received'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_mail_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function make_design($iUserId)
{
    $strSQL = "INSERT INTO " . TBL_DESIGN . " " .
                      "SET " . ID . " = '$iUserId', " .
                            WIDTH . " = '750' , " .
                        ALIGNMENT . " = 'center'";

//     echo "<br />".$strSQL."<br />";
    mysql_query($strSQL) or die("make_online_data:" . mysql_error());
}

//==============================================================================
//==============================================================================
function update_registration($iNew)
{
    mysql_query("UPDATE gamestats SET players = $iNew WHERE id = 1");
}

?>
