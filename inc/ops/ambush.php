<?

/*
Ambush : retal operation after you got attacked
Last update: 18-01-2005, By DamadmOO
Lastest update: September 02, 2005, Martel
*/

function get_op_type()
{
    return "aggressive";
}

function get_op_chance()
{
    return 100;
}

function get_op_name()
{
    return "Ambush";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
    $next_attack = $objTrgUser->get_user_info(NEXT_ATTACK);
    $maxtime = min($next_attack, 4) * 3600;
    $userid = $objSrcUser->get_userid();
    $targetid = $objTrgUser->get_userid();
    $res = mysql_query("SELECT COUNT(*) AS num FROM news WHERE type='invade' AND duser=$userid AND ouser=$targetid AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`time`)) <  $maxtime AND ambush = 1");
    $res = mysql_fetch_array($res);
    $num = $res["num"];

    // Martel:
    // possibilities of num=0: 1) noone has attacked you 2) you have already ambushed all available tribes
    if ($num == "0") {
        $result["text_screen"] = "Your thieves follow your command with pleasure; since no enemy armies are in sight they decide to visit the local taverns instead.";
        $result["text_news"] = "";
        $result["fame"] = 0;

        return $result;
        exit;
    }

    $build = $objTrgUser->get_builds();
    if ($thieves < ($build[LAND]*25)) {
        $result["text_screen"] = "Your thieves try to follow your command, but are too few in numbers and fail to reclaim any of your acres.";
        $result["fame"] = 0;
        $result["text_news"] = "";

        return $result;
        exit;
    }

    $mercs_out = 0;
    $race = $objTrgUser->get_stat(RACE);
    if ($race == "Mori Hai") {
        $army_mercs = $objTrgUser->get_army_mercs();
        $mercs_out = $army_mercs[MERC_T0] + $army_mercs[MERC_T1] + $army_mercs[MERC_T2] && $army_mercs[MERC_T3];
    }

    $milret = $objTrgUser->get_milreturns();
    $total_out = array_sum($milret) + $mercs_out;
    if ($total_out == 0) {
        $result["text_screen"] = "Sorry, you are too late. Your target already has your grabbed acres back in.";
        $result["fame"] = 0;
        $result["text_news"] = "";
        return $result;
        exit;
    }

    if ($num >= 1) {
        $land1 = floor($build[LAND_T1] / 2);
        $land2 = floor($build[LAND_T2] / 2);
        $land3 = floor($build[LAND_T3] / 2);
        $land4 = floor($build[LAND_T4] / 2);
        $unit11 = floor($milret[UNIT1_T1] / 40);
        $unit12 = floor($milret[UNIT1_T2] / 40);
        $unit13 = floor($milret[UNIT1_T3] / 40);
        $unit14 = floor($milret[UNIT1_T4] / 40);
        $unit21 = floor($milret[UNIT2_T1] / 40);
        $unit22 = floor($milret[UNIT2_T2] / 40);
        $unit23 = floor($milret[UNIT2_T3] / 40);
        $unit24 = floor($milret[UNIT2_T4] / 40);
        $unit31 = floor($milret[UNIT3_T1] / 40);
        $unit32 = floor($milret[UNIT3_T2] / 40);
        $unit33 = floor($milret[UNIT3_T3] / 40);
        $unit34 = floor($milret[UNIT3_T4] / 40);
        $unit41 = floor($milret[UNIT4_T1] / 40);
        $unit42 = floor($milret[UNIT4_T2] / 40);
        $unit43 = floor($milret[UNIT4_T3] / 40);
        $unit44 = floor($milret[UNIT4_T4] / 40);
        $unit1 = floor($unit11 + $unit12 + $unit13 + $unit14);
        $unit2 = floor($unit21 + $unit22 + $unit23 + $unit24);
        $unit3 = floor($unit31 + $unit32 + $unit33 + $unit34);
        $unit4 = floor($unit41 + $unit42 + $unit43 + $unit44);
        $totalUnits = $unit1 + $unit2 + $unit3 + $unit4;
        if ($race == "Mori Hai") {
            $unit51 = floor($army_mercs[MERC_T0] / 40);
            $unit52 = floor($army_mercs[MERC_T1] / 40);
            $unit53 = floor($army_mercs[MERC_T2] / 40);
            $unit54 = floor($army_mercs[MERC_T3] / 40);
            $unit5 = floor($unit51 + $unit52 + $unit53 + $unit54);
            $totalUnits += $unit5;
        }
        $acres = floor($build[LAND_T1] + $build[LAND_T2] + $build[LAND_T3] + $build[LAND_T4] - $land1 - $land2 - $land3 - $land4 );
        $famelost = floor($acres * 2);
        $fame = $objTrgUser->get_stat(FAME) - $famelost;
        $objTrgUser->set_stat(FAME, $fame);
        $fame = $objSrcUser->get_stat(FAME) + $famelost;
        $objSrcUser->set_stat(FAME, $fame);
        $land = $objSrcUser->get_build(LAND) + $acres;
        $objSrcUser->set_build(LAND, $land);

        //Those were set on the source tribe in original code. I assume that was wrong /Gotland
        $arrBuild = array(LAND_T1 => $land1, LAND_T2 => $land2, LAND_T3 => $land3, LAND_T4 => $land4);
        $objTrgUser->set_builds($arrBuild);

        $update = mysql_query("UPDATE news SET ambush=0 WHERE type='invade' AND duser=$o_user[id] AND ouser=$d_user[id] AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`time`)) <  $maxtime ");

        $army = $objTrgUser->get_armys();
        $arrArmy = array( UNIT1 => $army[UNIT1] - $unit1, UNIT2 => $army[UNIT2] - $unit2, UNIT3 => $army[UNIT3] - $unit3, UNIT4 => $army[UNIT4] - $unit4 );
        $arrMilReturn = array( UNIT1_T1 => $milret[UNIT1_T1] - $unit11, UNIT1_T2 => $milret[UNIT1_T2] - $unit12, UNIT1_T3 => $milret[UNIT1_T3] - $unit13, UNIT1_T4 => $milret[UNIT1_T4] - $unit14,
                                UNIT2_T1 => $milret[UNIT2_T1] - $unit21, UNIT2_T2 => $milret[UNIT2_T2] - $unit22, UNIT2_T3 => $milret[UNIT2_T3] - $unit23, UNIT2_T4 => $milret[UNIT2_T4] - $unit24,
                                UNIT3_T1 => $milret[UNIT3_T1] - $unit31, UNIT3_T2 => $milret[UNIT3_T2] - $unit32, UNIT3_T3 => $milret[UNIT3_T3] - $unit33, UNIT3_T4 => $milret[UNIT3_T4] - $unit34,
                                UNIT4_T1 => $milret[UNIT4_T1] - $unit41, UNIT4_T2 => $milret[UNIT4_T2] - $unit42, UNIT4_T3 => $milret[UNIT4_T3] - $unit43, UNIT4_T4 => $milret[UNIT4_T4] - $unit44 );
        if ($race == "Mori Hai") {
            $arrArmy += array (UNIT5 => $army[UNIT5] - $unit5);
            $arrMilReturn += array( UNIT5_T1 => $milret[UNIT5_T1] - $unit51, UNIT5_T2 => $milret[UNIT5_T2] - $unit52, UNIT5_T3 => $milret[UNIT5_T3] - $unit53, UNIT5_T4 => $milret[UNIT5_T4] - $unit54 );
            $arrMercs = array ( MERC_T0 => $army_mercs[MERC_T0] - $unit51, MERC_T1 => $army_mercs[MERC_T1] - $unit52, MERC_T2 => $army_mercs[MERC_T2] - $unit53, MERC_T3 => $army_mercs[MERC_T3] - $unit54 );
            $objTrgUser->set_army_mercs($arrMercs);
        }
        $objTrgUser->set_milreturns($arrMilReturn);
        $objTrgUser->set_armys($arrArmy);

        // War effects
        include_once('inc/functions/war.php');
        $objSrcAlliance = $objSrcUser->get_alliance();
        if (checkWarBetween($objSrcAlliance, $objTrgUser->get_stat(ALLIANCE)))
        {
            // Update land counter in new war system       March 06, 2008 Martel
            $iNeeded = $objSrcAlliance->get_war('land_needed');
            $objSrcAlliance->set_war('land_needed', max(0, $iNeeded - $acres));
        }

        $result["text_news"] = "Our walls have been defaced with crude messages, we have also noted that our target has taken " . number_format($acres) . " of his acres back and killed " . number_format($totalUnits) . " of our military units during this.";
        $result["text_screen"] ="Our thieves HAPPILY deface the walls of our enemy. Our thieves took back " . number_format($acres) . " of our acres and killed " . number_format($totalUnits) . " military units.";
        $result["fame"] = $famelost;

        return $result;
    }
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
    global  $local_build,
        $d_stats;
    $next_attack = mysql_query("SELECT next_attack FROM user WHERE id = $d_user[id]");
    $next_attack = mysql_fetch_array($next_attack);
    $maxtime = min($next_attack["next_attack"], 4) * 3600;
    $res = mysql_query("SELECT COUNT(*) AS num FROM news WHERE type='invade' AND duser=$o_user[id] AND ouser=$d_user[id] AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`time`)) <  $maxtime AND ambush = 1");
    $res = mysql_fetch_array($res);
    $num = $res["num"];
    $fetch = mysql_query("SELECT land, land_t1, land_t2, land_t3, land_t4 FROM build WHERE id = $d_user[id]");
    $fetch = mysql_fetch_array($fetch);
    $race = mysql_fetch_array("SELECT race FROM stats WHERE id = $d_user[id]");
    $race = mysql_fetch_array($race);
    $fetch2 = mysql_query("SELECT `unit1_t1`, `unit1_t2`, `unit1_t3`, `unit1_t4`, `unit2_t1`, `unit2_t2`, `unit2_t3`, `unit2_t4`, `unit3_t1`, `unit3_t2`, `unit3_t3`, `unit3_t4`, `unit4_t1`, `unit4_t2`, `unit4_t3`, `unit4_t4` FROM `milreturn` WHERE id = $d_user[id]");
    $fetch2 = mysql_fetch_array($fetch2);
    if ($race["race"] == "Mori Hai") {
        $fetch3 = mysql_query("SELECT * FROM army_mercs WHERE id = $d_user[id]");
        $fetch3 = mysql_fetch_array($fetch3);
    }

    // Martel:
    // possibilities of num=0: 1) noone has attacked you 2) you have already ambushed all available tribes
    if ($num == "0") {
        $result["text_screen"] = "Your thieves follow your command with pleasure; since no enemy armies are in sight they decide to visit the local taverns instead.";
        $result["text_news"] = "";
        $result["fame"] = 0;

        return $result;
        exit;
    }

    if ($thieves < ($fetch[land]*25)) {
        $result["text_screen"] = "Your thieves try to follow your command, but are too few in numbers and fail to reclaim any of your acres.";
        $result["fame"] = 0;
        $result["text_news"] = "";

        return $result;
        exit;
    }

    if ($fetch2["unit1_t1"] == "0" && $fetch2["unit1_t2"] == "0" && $fetch2["unit1_t3"] == "0" && $fetch2["unit1_t4"] == "0" && $fetch2["unit2_t1"] == "0" && $fetch2["unit2_t2"] == "0" && $fetch2["unit2_t3"] == "0" && $fetch2["unit2_t4"] == "0" && $fetch2["unit3_t1"] == "0" && $fetch2["unit3_t2"] == "0" && $fetch2["unit3_t3"] == "0" && $fetch2["unit3_t4"] == "0" && $fetch2["unit4_t1"] == "0" && $fetch2["unit4_t2"] == "0" && $fetch2["unit4_t3"] == "0" && $fetch2["unit4_t4"] == "0" && $fetch3["merc_t0"] == "0" && $fetch3["merc_t1"] == "0" && $fetch3["merc_t2"] == "0" && $fetch3["merc_t3"] == "0") {
        $result["text_screen"] = "Sorry, you are too late. Your target already has your grabbed acres back in.";
        $result["fame"] = 0;
        $result["text_news"] = "";
        return $result;
        exit;
    }
//  if ($res["ambush"] != "1") {
//      echo "<HR>" . $res["ambush"] . "<HR>";
//      echo "You already did a succesfull ambush against this target. It is not allowed to do more then one //succesfull ambush.";
//      include_game_down();
//      exit;
//  }
    if ($num >= 1) {
        // this looks unneccessary
        // $units = mysql_query("SELECT ");
        $land1 = floor($fetch['land_t1'] / 2);
        $land2 = floor($fetch['land_t2'] / 2);
        $land3 = floor($fetch['land_t3'] / 2);
        $land4 = floor($fetch['land_t4'] / 2);
        $unit11 = floor($fetch2['unit1-t1'] / 40);
        $unit12 = floor($fetch2['unit1-t2'] / 40);
        $unit13 = floor($fetch2['unit1-t3'] / 40);
        $unit14 = floor($fetch2['unit1-t4'] / 40);
        $unit21 = floor($fetch2['unit2-t1'] / 40);
        $unit22 = floor($fetch2['unit2-t2'] / 40);
        $unit23 = floor($fetch2['unit2-t3'] / 40);
        $unit24 = floor($fetch2['unit2-t4'] / 40);
        $unit31 = floor($fetch2['unit3-t1'] / 40);
        $unit32 = floor($fetch2['unit3-t2'] / 40);
        $unit33 = floor($fetch2['unit3-t3'] / 40);
        $unit34 = floor($fetch2['unit3-t4'] / 40);
        $unit41 = floor($fetch2['unit4-t1'] / 40);
        $unit42 = floor($fetch2['unit4-t2'] / 40);
        $unit43 = floor($fetch2['unit4-t3'] / 40);
        $unit44 = floor($fetch2['unit4-t4'] / 40);
        $unit1 = floor($unit11 + $unit12 + $unit13 + $unit14);
        $unit2 = floor($unit21 + $unit22 + $unit23 + $unit24);
        $unit3 = floor($unit31 + $unit32 + $unit33 + $unit34);
        $unit4 = floor($unit41 + $unit42 + $unit43 + $unit44);
        $totalUnits = $unit1 + $unit2 + $unit3 + $unit4;
        if ($race["race"] == "Mori Hai") {
            $unit51 = floor($fetch3['merc_t0'] / 40);
            $unit52 = floor($fetch3['merc_t1'] / 40);
            $unit53 = floor($fetch3['merc_t2'] / 40);
            $unit54 = floor($fetch3['merc_t3'] / 40);
            $unit5 = floor($unit51 + $unit52 + $unit53 + $unit54);
            $totalUnits += $unit5;
        }
        $acres = floor($fetch['land_t1'] + $fetch['land_t2'] + $fetch['land_t3'] + $fetch['land_t4'] - $land1 - $land2 - $land3 - $land4 );
        $famelost = floor($acres * 2);
        $update = mysql_query("UPDATE stats SET fame = fame - $famelost WHERE id = $d_user[id]");
        $update = mysql_query("UPDATE stats SET fame = fame + $famelost WHERE id = $o_user[id]");
        $update = mysql_query("UPDATE build SET land = land + $acres WHERE id = $o_user[id]");
        $update = mysql_query("UPDATE build SET land_t1 = $land1, land_t2 = $land2, land_t3 = $land3, land_t4 = $land4, WHERE id = $o_user[id]");
        $update = mysql_query("UPDATE news SET ambush=0 WHERE type='invade' AND duser=$o_user[id] AND ouser=$d_user[id] AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`time`)) <  $maxtime ");
        if ($race["race"] == "Mori Hai") {
            $update = mysql_query("UPDATE milreturn SET unit1_t1 = unit1_t1 - $unit11, unit1_t2 = unit1_t2 - $unit12, unit1_t3 = unit1_t3 - $unit13, unit1_t4 = unit1_t4 - $unit14, unit2_t1 = unit2_t1 - $unit21, unit2_t2 = unit2_t2 - $unit22, unit2_t3 = unit2_t3 - $unit23, unit2_t4 = unit2_t4 - $unit24, unit3_t1 = unit3_t1 - $unit31, unit3_t2 = unit3_t2 - $unit32, unit3_t3 = unit3_t3 - $unit33, unit3_t4 = unit3_t4 - $unit34, unit4_t1 = unit4_t1 - $unit41, unit4_t2 = unit4_t2 - $unit42, unit4_t3 = unit4_t3 - $unit43, unit4_t4 = unit4_t4 - $unit44, unit5_t1 = unit5_t1 - $unit51, unit5_t2 = unit5_t2 - $unit52, unit5_t3 = unit5_t3 - $unit53, unit5_t4 = unit5_t4 - $unit54 WHERE id = $d_user[id]");
            $update = mysql_query("UPDATE army_mercs SET merc_t0 = merctO - $unit51, merc_t1 = merct1 - $unit52, merc_t2 = merct2 - $unit53, merc_t3 = merct3 - $unit54 WHERE id = $d_user[id]");
            $update = mysql_query("UPDATE army SET unit1 = unit1 - $unit1, unit2 = unit2 - $unit2, unit3 = unit3 - $unit3, unit4 = unit4 - $unit4, unit5 = unit5 - $unit5 WHERE id = $d_user[id]");
        } else {
            $update = mysql_query("UPDATE milreturn SET unit1_t1 = unit1_t1 - $unit11, unit1_t2 = unit1_t2 - $unit12, unit1_t3 = unit1_t3 - $unit13, unit1_t4 = unit1_t4 - $unit14, unit2_t1 = unit2_t1 - $unit21, unit2_t2 = unit2_t2 - $unit22, unit2_t3 = unit2_t3 - $unit23, unit2_t4 = unit2_t4 - $unit24, unit3_t1 = unit3_t1 - $unit31, unit3_t2 = unit3_t2 - $unit32, unit3_t3 = unit3_t3 - $unit33, unit3_t4 = unit3_t4 - $unit34, unit4_t1 = unit4_t1 - $unit41, unit4_t2 = unit4_t2 - $unit42, unit4_t3 = unit4_t3 - $unit43, unit4_t4 = unit4_t4 - $unit44 WHERE id = $d_user[id]");
            $update = mysql_query("UPDATE army SET unit1 = unit1 - $unit1, unit2 = unit2 - $unit2, unit3 = unit3 - $unit3, unit4 = unit4 - $unit4 WHERE id = $d_user[id]");
        }
        $result["text_news"] = "Our walls have been defaced with crude messages, we have also noticed that our target has taken " . number_format($acres) . " of his acres back and killed " . number_format($totalUnits) . " of our military units during this.";
        $result["text_screen"] ="Our thieves HAPPILY deface the walls of our enemy. Our thieves took back " . number_format($acres) . " of our acres and killed " . number_format($totalUnits) . " military units.";
        $result["fame"] = $famelost;

        return $result;
    }
}
*/
?>