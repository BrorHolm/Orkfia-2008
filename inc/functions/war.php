<?php
//******************************************************************************
// Functions War.php                            Recoded February 28, 2008 Martel
// History
// frost declaration of some constant values placed here for easier modification
// Martel: New -actual- constants and complete rewrite       - February 28, 2008
//******************************************************************************
define('WAR_LENGTH', 48);             // 48 updates
define('WAR_SRC_COOLDOWN', 12);       // 12 updates
define('WAR_TRG_REST', 6);            // 6 updates
define('WAR_SURRENDER_REST', 48);     // 48 updates
define('WAR_SURRENDER_LOSSES', 0.07); // 7% purchased research loss
define('WAR_SURRENDER_FAME', 700);    // 700 fame
define('WAR_UPWARD_MOD', 1.5);        // 150% up
define('WAR_BOTTOM_MOD', 0.8);        // 80% down
define('WAR_VICTORY_GAINS', 0.05);    // 5% purchased research gain
define('WAR_VICTORY_FAME', 500);      // 500 fame gains for defeating
define('WAR_VICTORY_GOODS', 0.15);    // 15% goods from loosers market

//==============================================================================
// New Function check war state - between two alliances    March 06, 2008 Martel
//==============================================================================
function checkWarBetween(&$objSrcAlli, $iTrgAlli)
{
    // Handle both alliance objects and ids
    if (is_object($iTrgAlli))
        $iTrgAlli = $iTrgAlli->get_allianceid();

    // They are at war
    if ($objSrcAlli->get_war('target') == $iTrgAlli)
        return TRUE;
    else
        return FALSE;
}

// Old Function check war between
function war_alli($source_alli, $target_alli)
{
    require_once('inc/classes/clsAlliance.php');
    $objSrcAlli = new clsAlliance($source_alli);
    if (checkWarBetween($objSrcAlli, $target_alli))
        return 2;
    else
        return 0;
}

// Old Function get war target
function war_target($IAllianceid)
{
    require_once('inc/classes/clsAlliance.php');
    $objAlli = new clsAlliance($IAllianceid);
    return $objAlli->get_war('target');
}

//==============================================================================
// Function test war victory/defeat                        March 03, 2008 Martel
// (History) frost: defeat / victory detection ... part of new war system
//==============================================================================
function testWarVictory(&$objSrcAlli, &$objTrgAlli)
{
    $arrSrcWar = $objSrcAlli->get_wars();
    $arrTrgWar = $objTrgAlli->get_wars();

    if ($arrSrcWar['land_needed'] <= 0)
        return doWarVictory($objSrcAlli, $objTrgAlli);
    elseif ($arrTrgWar['land_needed'] <= 0)
        return doWarVictory($objTrgAlli, $objSrcAlli);
    else
        return FALSE;
}

//==============================================================================
// Function check if war is possible                    February 27, 2008 Martel
//==============================================================================
function testWarPossible(&$objSrcAlli, &$objTrgAlli)
{
    // Get game hours
    require_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $arrGameTime = $objGame->get_game_times();

    // Get alliance sizes
    $iSrcLand    = $objSrcAlli->get_rankings_alliance('land');
    $iTrgLand    = $objTrgAlli->get_rankings_alliance('land');

    // M: Count tribes - The clever way
    $iSrcTribes = count($objSrcAlli->get_userids());
    $iTrgTribes = count($objTrgAlli->get_userids());

    // Get war table arrays
    $arrSrcWar = $objSrcAlli->get_wars();
    $arrTrgWar = $objTrgAlli->get_wars();

    // Check if war is possible
    if ($objTrgAlli->get_allianceid() == $objSrcAlli->get_allianceid())
        return array(FALSE, "Sorry, you can't declare against your own alliance.");
    elseif ($objTrgAlli->get_allianceid() < 11 || $objSrcAlli->get_allianceid() < 11)
        return array(FALSE, "Sorry, you can't declare against staff alliances.");
    elseif ($arrTrgWar['target'] != 0)
        return array(FALSE, "Sorry, you can't declare against an alliance already at war.");
    elseif ($iTrgTribes < 1)
        return array(FALSE, "Sorry, you can't declare against an empty alliance.");
    elseif ($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME)
        return array(TRUE, "");
    elseif (($iSrcLand * WAR_BOTTOM_MOD) > $iTrgLand)
        return array(FALSE, "Sorry, you can't declare against a so much smaller alliance.");
    elseif (($iSrcLand * WAR_UPWARD_MOD) < $iTrgLand)
        return array(FALSE, "Sorry, you can't declare against a so much larger alliance.");
    elseif ($iSrcTribes < ceil(MAX_ALLIANCE_SIZE * .25))
        return array(FALSE, "Sorry, you can't declare with less than " . ceil(MAX_ALLIANCE_SIZE * .25) . " tribes in your own alliance.");
    elseif ($iTrgTribes < ceil(MAX_ALLIANCE_SIZE * .25))
        return array(FALSE, "Sorry, you can't declare war against an alliance with less than " . ceil(MAX_ALLIANCE_SIZE * .25) . " tribes.");
    elseif ((round($iSrcTribes * WAR_BOTTOM_MOD)) > $iTrgTribes)
        return array(FALSE, "Sorry, you can't declare war against alliances with so many less tribes than us.");
    elseif ((round($iSrcTribes * WAR_UPWARD_MOD)) < $iTrgTribes)
        return array(FALSE, "Sorry, you can't declare war against alliances with so many more tribes than us.");
    elseif (($arrGameTime['hour_counter'] - WAR_SURRENDER_REST) < $arrTrgWar['last_end'] && $arrTrgWar['last_outgoing'] == 'surrender' )
        return array(FALSE, "Sorry, you can't declare on an alliance which surrendered less than " . WAR_SURRENDER_REST . " months ago.");
    elseif (($arrSrcWar['last_end'] + WAR_SRC_COOLDOWN) > $arrGameTime['hour_counter'])
        return array(FALSE, "Sorry, it haven't passed " . WAR_SRC_COOLDOWN . " months yet.");
    elseif (($arrTrgWar['last_end'] + WAR_TRG_REST) > $arrGameTime['hour_counter'])
        return array(FALSE, "Sorry, you can't declare on an alliance which was in a war less than " . WAR_TRG_REST . " months ago.");
    else
        return array(TRUE, "");
}

//==============================================================================
// Function declare war           Rewritten February 29 - March 6th, 2008 Martel
//==============================================================================
function doWarDeclare(&$objSrcAlli, &$objTrgAlli)
{
    global $orkTime;

    // M: Get game hours
    require_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $arrGameTime = $objGame->get_game_times();
    $iGameHours  = $arrGameTime['hour_counter'];

    // M: Get war table arrays
    $arrSrcWar = $objSrcAlli->get_wars();
    $arrTrgWar = $objTrgAlli->get_wars();

    // M: Get alliance sizes
    $iSrcLand = $objSrcAlli->get_rankings_alliance('land');
    $iTrgLand = $objTrgAlli->get_rankings_alliance('land');

    // M: Get alliance ids
    $iSrcAllianceid = $objSrcAlli->get_allianceid();
    $iTrgAllianceid = $objTrgAlli->get_allianceid();

    // M: Event handler
    $iEventid = mysql_query("SELECT event_counter FROM " . TBL_GAME_TIME);
    $iEventid = mysql_fetch_row($iEventid);

    // M: Create stored events for auto truce
    $iEventHour = $arrGameTime['hour_counter'] + WAR_LENGTH;
    $iEventid1  = $iEventid[0]+1;
    $iEventid2  = $iEventid[0]+2;
    $iEventid3  = $iEventid[0]+3;
    $arrSQL[]   = "UPDATE war SET target=0,truce=truce+1,last_target=$iTrgAllianceid,last_outgoing=\'truce\',last_end=$iEventHour,event_id=0 WHERE id=$iSrcAllianceid;";
    $arrSQL[]   = "UPDATE war SET target=0,truce=truce+1,last_target=$iSrcAllianceid,last_outgoing=\'truce\',last_end=$iEventHour,event_id=0 WHERE id=$iTrgAllianceid;";
    $arrSQL[]   = "INSERT INTO news SET time=NOW(), type=\'global\', kingdom_text=\'<strong class=\"positive\">Alliance #$iSrcAllianceid and #$iTrgAllianceid have truced.</strong>\';";

    // M: Store events
    foreach ($arrSQL as $strSQL)
    {
        $iEventid[0]++;
        mysql_query("INSERT INTO auto_event SET event_id = $iEventid[0], " .
                    "query = '$strSQL', execution_hour = $iEventHour;");
    }

    // M: Update event counter
    $objGame->set_game_time('event_counter', $iEventid[0]);

    // M: Update source alli
    $arrNewSrcWar = array(
        'target'                => $iTrgAllianceid,
        'start_land'            => $iSrcLand,
        'war_started'           => $iGameHours,
        'truce_offer'           => 0,
        'last_end'              => 0,
        'last_target'           => 0,
        'last_outgoing'         => 0,
        'land_needed'           => round($iTrgLand * (($iSrcLand / $iTrgLand) * .35)),
        'event_id'              => $iEventid1
    );
    $objSrcAlli->set_wars($arrNewSrcWar);

    // M: Update target alli
    $arrNewTrgWar = array(
        'target'                => $iSrcAllianceid,
        'start_land'            => $iTrgLand,
        'war_started'           => $iGameHours,
        'truce_offer'           => 0,
        'last_end'              => 0,
        'last_target'           => 0,
        'last_outgoing'         => 0,
        'land_needed'           => round($iSrcLand * (($iTrgLand / $iSrcLand) * .35)),
        'event_id'              => $iEventid2
    );
    $objTrgAlli->set_wars($arrNewTrgWar);

    // M: News for winner, looser and global
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'War', '0', '0', '1', '', '<strong class=\"positive\">We have declared war on alliance #$iTrgAllianceid!</strong>', '$iSrcAllianceid', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'War', '0', '0', '1', '', '<strong class=\"negative\">Alliance #$iSrcAllianceid has declared war on us!</strong>', '$iTrgAllianceid', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'global', '0', '0', '1', '', '<strong class=\"positive\">Alliance #$iSrcAllianceid has declared war on #$iTrgAllianceid!</strong>', '0', '')");
}

//==============================================================================
// Function win war               Rewritten February 29 - March 6th, 2008 Martel
//==============================================================================
function doWarVictory(&$objWinAlli, &$objSuxAlli)
{
    global $orkTime;

    $winner = $objWinAlli->get_allianceid();
    $looser = $objSuxAlli->get_allianceid();

    // Clear events
    $iEventid1 = $objWinAlli->get_war('event_id');
    $iEventid2 = $objSuxAlli->get_war('event_id');
    clearEvents($iEventid1, $iEventid2);

    // Get game hours
    require_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $arrGameTime = $objGame->get_game_times();

    // M: Update winner alli
    $arrWinWar = $objWinAlli->get_wars();
    $arrNewWinWar = array(
        'target'        => 0,
        'last_target'   => $looser,
        'last_outgoing' => 'victory',
        'last_end'      => $arrGameTime['hour_counter'],
        'victory'       => $arrWinWar['victory'] + 1,
        'event_id'      => ''
    );
    $objWinAlli->set_wars($arrNewWinWar);

    // M: Update looser alli
    $arrSuxWar = $objSuxAlli->get_wars();
    $arrNewSuxWar = array(
        'target'        => 0,
        'last_target'   => $winner,
        'last_outgoing' => 'defeat',
        'last_end'      => $arrGameTime['hour_counter'],
        'defeat'        => $arrSuxWar['defeat'] + 1,
        'event_id'      => ''
    );
    $objSuxAlli->set_wars($arrNewSuxWar);

    // Temporary user object for alliance fame-update iterations
    $objTmpUser = new clsUser(1);

    // M: Update winner alli
    $arrUserId = $objWinAlli->get_userids();
    foreach ($arrUserId as $iUserid)
    {
        $objTmpUser->set_userid($iUserid);
        $iNewFame = $objTmpUser->get_stat('fame') + WAR_VICTORY_FAME;
        $objTmpUser->set_stat('fame', $iNewFame);
    }

    // M: Update looser alli
    $arrUserId = $objSuxAlli->get_userids();
    foreach ($arrUserId as $iUserid)
    {
        $objTmpUser->set_userid($iUserid);
        $iNewFame = max(0, $objTmpUser->get_stat('fame') - WAR_VICTORY_FAME);
        $objTmpUser->set_stat('fame', $iNewFame);
    }

    // M: Transfer research
    $arrResearch = moveResearch($objSuxAlli, $objWinAlli, WAR_VICTORY_GAINS);

    // M: Transfer market goods
    $arrMarket   = moveMarketGoods($objSuxAlli, $objWinAlli, WAR_VICTORY_GOODS);

    // News for winner, looser and global
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"positive\">We have been victorious in the war against alliance #$looser!</strong>', '$winner', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"negative\">We have been defeated in the war against alliance #$winner!</strong>', '$looser', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'global', '0', '0', '1', '', '<strong class=\"positive\">Alliance #$winner has won the war!</strong>', '0', '')");

    return array($arrResearch, $arrMarket);
}

//==============================================================================
// Function accept truce          Rewritten February 29 - March 6th, 2008 Martel
//==============================================================================
function doWarTruce(&$objWinAlli, &$objSuxAlli)
{
    global $orkTime;

    $winner = $objWinAlli->get_allianceid();
    $looser = $objSuxAlli->get_allianceid();

    // Clear events
    $iEventid1 = $objWinAlli->get_war('event_id');
    $iEventid2 = $objSuxAlli->get_war('event_id');
    clearEvents($iEventid1, $iEventid2);

    // Get game hours
    require_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $arrGameTime = $objGame->get_game_times();

    // M: Update winner alli
    $arrWinWar = $objWinAlli->get_wars();
    $arrNewWinWar = array(
        'target'        => 0,
        'last_target'   => $looser,
        'last_outgoing' => 'truce',
        'last_end'      => $arrGameTime['hour_counter'],
        'truce'         => $arrWinWar['truce'] + 1,
        'event_id'      => ''
    );
    $objWinAlli->set_wars($arrNewWinWar);

    // M: Update looser alli
    $arrSuxWar = $objSuxAlli->get_wars();
    $arrNewSuxWar = array(
        'target'        => 0,
        'last_target'   => $winner,
        'last_outgoing' => 'truce',
        'last_end'      => $arrGameTime['hour_counter'],
        'truce'         => $arrSuxWar['truce'] + 1,
        'event_id'      => ''
    );
    $objSuxAlli->set_wars($arrNewSuxWar);

    // News for winner, looser and global
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"positive\">We have truced with alliance #$looser.</strong>', '$winner', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"positive\">We have truced with alliance #$winner.</strong>', '$looser', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'global', '0', '0', '1', '', '<strong>Alliance #$winner and #$looser ended their war with a truce.</strong>', '0', '')");
}

//==============================================================================
// Function surrender war         Rewritten February 29 - March 6th, 2008 Martel
//==============================================================================
function doWarSurrender(&$objSuxAlli, &$objWinAlli)
{
    global  $orkTime;

    $winner = $objWinAlli->get_allianceid();
    $looser = $objSuxAlli->get_allianceid();

    // Clear events
    $iEventid1 = $objWinAlli->get_war('event_id');
    $iEventid2 = $objSuxAlli->get_war('event_id');
    clearEvents($iEventid1, $iEventid2);

    // Get game hours
    require_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $arrGameTime = $objGame->get_game_times();

    // M: Update winner alli
    $arrWinWar = $objWinAlli->get_wars();
    $arrNewWinWar = array(
        'target'        => 0,
        'last_target'   => $looser,
        'last_outgoing' => 'victory',
        'last_end'      => $arrGameTime['hour_counter'],
        'victory'       => $arrWinWar['victory'] + 1,
        'event_id'      => ''
    );
    $objWinAlli->set_wars($arrNewWinWar);

    // M: Update looser alli
    $arrSuxWar = $objSuxAlli->get_wars();
    $arrNewSuxWar = array(
        'target'        => 0,
        'last_target'   => $winner,
        'last_outgoing' => 'surrender',
        'last_end'      => $arrGameTime['hour_counter'],
        'surrender'     => $arrSuxWar['surrender'] + 1,
        'event_id'      => ''
    );
    $objSuxAlli->set_wars($arrNewSuxWar);

    // M: Update winner alli fame
    $objTmpUser = new clsUser(0);
    $arrUserId  = $objWinAlli->get_userids();
    foreach ($arrUserId as $iUserid)
    {
        $objTmpUser->set_userid($iUserid);
        $iNewFame = $objTmpUser->get_stat('fame') + WAR_SURRENDER_FAME;
        $objTmpUser->set_stat('fame', $iNewFame);
    }

    // M: Update looser alli fame
    $arrUserId = $objSuxAlli->get_userids();
    foreach ($arrUserId as $iUserid)
    {
        $objTmpUser->set_userid($iUserid);
        $iNewFame = max(0, $objTmpUser->get_stat('fame') - WAR_SURRENDER_FAME);
        $objTmpUser->set_stat('fame', $iNewFame);
    }

    // M: Transfer research
    $arrResearch = moveResearch($objSuxAlli, $objWinAlli, WAR_SURRENDER_LOSSES);

    // M: Transfer market goods (Atm this is the same % as regular victory)
    $arrMarket   = moveMarketGoods($objSuxAlli, $objWinAlli, WAR_VICTORY_GOODS);

    // News for winner, looser and global
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"positive\">We were victorious in the war with alliance #$looser!</strong>', '$winner', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'war-end', '0', '0', '1', '', '<strong class=\"negative\">We have surrendered in the war with #$winner!</strong>', '$looser', '')");
    mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'global', '0', '0', '1', '', '<strong class=\"positive\">Alliance #$looser have surrendered! #$winner wins!</strong>', '0', '')");

    return (array($arrResearch, $arrMarket));
}

//==============================================================================
// Move research from one alliance to another           February 29, 2008 Martel
// $iModifier is a number from 0-1, 0.0=0% and 1.0=100%
//==============================================================================
function moveResearch(&$objSrcAlli, &$objTrgAlli, $iModifier = 1)
{
    $arrSrcAlli = $objSrcAlli->get_alliance_infos();
    $arrTrgAlli = $objTrgAlli->get_alliance_infos();
    $arrNames   =
          array('income_bonus', 'home_bonus', 'defence_bonus', 'offence_bonus');

    // Calculate and move research
    foreach ($arrNames as $str)
    {
        $arrDiffResearch[$str]   = floor($arrSrcAlli[$str] * $iModifier);
        $arrNewSrcResearch[$str] = floor($arrSrcAlli[$str] - $arrDiffResearch[$str]);
        $arrNewTrgResearch[$str] = floor($arrTrgAlli[$str] + $arrDiffResearch[$str]);
    }

    // Save to DB
    $objSrcAlli->set_alliance_infos($arrNewSrcResearch);
    $objTrgAlli->set_alliance_infos($arrNewTrgResearch);

    // Use to write report
    return $arrDiffResearch;
}

//==============================================================================
// Move market goods from one alliance to another       February 29, 2008 Martel
// $iModifier is a number from 0-1, 0.0=0% and 1.0=100%
//==============================================================================
function moveMarketGoods(&$objSrcAlli, &$objTrgAlli, $iModifier = 1)
{
    $arrSrcAlli = $objSrcAlli->get_alliance_infos();
    $arrTrgAlli = $objTrgAlli->get_alliance_infos();
    $arrNames   = array('food', 'money', 'wood', 'soldiers');

    // Calculate and move research
    foreach ($arrNames as $str)
    {
        $arrDiffMarket[$str]   = floor($arrSrcAlli[$str] * $iModifier);
        $arrNewSrcMarket[$str] = floor($arrSrcAlli[$str] - $arrDiffMarket[$str]);
        $arrNewTrgMarket[$str] = floor($arrTrgAlli[$str] + $arrDiffMarket[$str]);
    }

    // Save to DB
    $objSrcAlli->set_alliance_infos($arrNewSrcMarket);
    $objTrgAlli->set_alliance_infos($arrNewTrgMarket);

    // Use to write report
    return $arrDiffMarket;
}

function clearEvents($iEventid1, $iEventid2) {
    $iEventid3 = 1 + max($iEventid1, $iEventid2);
    mysql_query("DELETE FROM auto_event WHERE event_id = $iEventid1 OR " .
                "event_id = $iEventid2 OR event_id = $iEventid3");
}

?>
