<?php

/*
ARSON - Destroys enemy homes
Last update: Object recoding by Gotland
7-5-2004, by Species 5618
*/

function get_op_type()
{
    return "aggressive";
}

function get_op_chance()
{
    return 70;
}

function get_op_name()
{
    return "Arson";
}

function do_op(&$objSrcUser, &$objTrgUser, $cnt, $thieves, $mod)
{
    include_once('inc/functions/bonuses.php');

    $kingdom                = $objTrgUser->get_stat(ALLIANCE);
//     $alliance               = $objTrgUser->get_alliance();
//     $alliance_size          = $alliance->get_alliance_size() * 80;
//     $arrSci                 = getSciences($kingdom);
//     $science_defence_bonus  = $arrSci['def'];

    $percentage             = 0.01;
//     $percentage            -= ($science_defence_bonus / 100);
    if ($percentage < 0.0055)
        $percentage = 0.0055;

    $homes       = $objTrgUser->get_build(HOMES);
    $land        = $objTrgUser->get_build(LAND);
    $totalburned = 0;

    for ($x = 1; $x <= $cnt; $x++)
    {
        $max_burn = floor($homes * $percentage * $mod);
        if ($thieves <= 50 || $max_burn <= 0)
            $max_burn = 0;

        $burnable = floor(($thieves / $land) * $max_burn);
        if ($burnable >= $max_burn)
            $burnable  = $max_burn;

        // Record stuff. Since no randomness is involved in dmg, first arson is
        // always the largest.
        if ($x == 1)
            $first = $burnable;

        $homes        -= $burnable;
        $totalburned  += $burnable;
    }

    $objTrgUser->set_build(HOMES, $homes);
    unset($max_burn);

    //==========================================================================
    // Record stuff
    //==========================================================================
    $fetch_record = mysql_query("SELECT * FROM records WHERE id = 1");
    $record       = mysql_fetch_assoc($fetch_record);
    if ($first > intval($record['arson']))
    {
        $user = $objSrcUser->get_userid();
        $update = mysql_query("UPDATE records SET arson = $first, arson_id = $user WHERE id = 1") or die("p00p!");
    }
    //==========================================================================

    $result["fame"] = floor($totalburned * 3);
    $result["text_screen"] = "Arson has destroyed <span class=\"negative\">$totalburned</span> homes of the enemy!";
    $result["text_news"] = "Unfortunately we have found <span class=\"negative\">$totalburned</span> homes burned to the ground.";

    return $result;
}


//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
    global  $local_build,
        $local_stats,
        $connection;

    mysql_grab($d_user[id],local,build,stats,user);
        $percentage = 0.01;

        include_once("inc/functions/get.php");
        $kingdom = get_kingdom_nonarray($d_user[id]);
        $alliance_size = (get_alliance_size($kingdom)) * 80;
        $science_update_bonus = get_science_update_bonus($kingdom);
        $science_defence_bonus = round((1.98*$science_update_bonus[defence_bonus])/($alliance_size+$science_update_bonus[defence_bonus]),3);
        if ($science_defence_bonus > 1) {$science_defence_bonus = 1;}

        $percentage = $percentage - ($science_defence_bonus/100);
        if ($percentage < 0.0055) { $percentage = 0.0055; }

    $homes = $local_build[homes];
    $totalburned = 0;

    for ($x=1; $x<=$cnt; $x++)
    {
            $max_burn = floor($homes * $percentage * $mod);
            if ($thieves <= 50 || $max_burn <= "0") { $max_burn = "0"; }
        $burnable = floor( ($thieves / $local_build[land]) * $max_burn);
            if ($burnable >= $max_burn) { $burnable = $max_burn; }
//record stuff... since no randomness is involved in dmg, first arson is always the largest.
        if ($x == 1) { $first = $burnable; }
        $homes -= $burnable;
        $totalburned += $burnable;
        }

    $update = mysql_query ("UPDATE build SET homes ='$homes' WHERE id ='$d_user[id]'");
    $update = mysql_query($update, $connection);
    unset($max_burn);
    unset($local_build);
    unset($local_stats);
    $local_stats = mysql_query ("SELECT * FROM stats WHERE id = '$userid' ");
    $local_stats = mysql_fetch_array($local_stats);

//record stuff
        $fetch_record = mysql_query("SELECT * FROM records WHERE id = 1");
//At age-start, new thingie will have to be created
    if (mysql_num_rows($fetch_record) == 0) { mysql_query("INSERT INTO records (id) VALUES (1)"); }
        $record = mysql_fetch_assoc($fetch_record);
        if ($first > intval($record[arson])) { $update = mysql_query("UPDATE records SET arson = $first, arson_id = $o_user[id] WHERE id = 1") or die("p00p!"); }

    $result["fame"] = floor($totalburned * 3);
    $result["text_screen"] = "Arson has destroyed $totalburned homes of the enemy!";
    $result["text_news"] = "Unfortunately we have found $totalburned homes burned to the ground.";

    return $result;
}
*/
?>
