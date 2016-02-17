<?php

/*
    TUNNELING - Destroys enemy academies
    Last update: 7-5-2004, by Species 5618
    No defence sci bonuses January 29, 2008 Martel
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
    return "Tunneling (WAR ONLY)";
}

function do_op(&$objSrcUser, &$objTrgUser, $cnt, $thieves, $mod)
{
    include_once("inc/functions/war.php");
    $modifier = war_alli($objSrcUser->get_stat(ALLIANCE),$objTrgUser->get_stat(ALLIANCE));
    if ($modifier != 2)
    {
        echo "You can only do this thievery operation against a target you are at war with.";
        include_game_down();
        exit;
    }

    $percentage     = 0.0075;
//     $alliance       = $objTrgUser->get_alliance();
//     $alliance_size  = $alliance->get_alliance_size() * 80;

//     include_once('inc/functions/bonuses.php');
//     $arrSci = getSciences($alliance->get_allianceid());
//     $science_defence_bonus = $arrSci['def'];
//     if ($science_defence_bonus > 1)
//         $science_defence_bonus = 1;

//     $percentage -= ($science_defence_bonus / 100);
    if ($percentage < 0.0055)
        $percentage = 0.0055;

    $academies   = $objTrgUser->get_build(ACADEMIES);
    $totalburned = 0;

    for ($x = 1; $x <= $cnt; $x++)
    {
        $max_burn = floor($academies * $percentage * $mod);

        if ($thieves <= 50 || $max_burn <= 0)
            $max_burn = 0;

        $burnable = floor( ($thieves / ( $objTrgUser->get_build(LAND) / 2 ) ) * $max_burn);
        if ($burnable >= $max_burn)
            $burnable = $max_burn;

        $academies   -= $burnable;
        $totalburned += $burnable;
    }

    $objTrgUser->set_build(ACADEMIES, $academies);

    $result["fame"]         = floor($totalburned * 3);
    $result["text_screen"]  = "Tunneling has destroyed $totalburned academies of the enemy!";
    $result["text_news"]    = 'Unfortunately we have found <span class="negative">' . $totalburned . '</negative> academies burned to the ground.';

    return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
    global  $local_build,
        $local_stats,
        $connection;
        include_once("inc/functions/war.php");
        $search = mysql_query("SELECT kingdom FROM stats WHERE id = $o_user[id]");
        $search = mysql_fetch_array($search);
        $search2 = mysql_query("SELECT kingdom FROM stats WHERE id = $d_user[id]");
        $search2 = mysql_fetch_array($search2);
        $modifier = war_alli($search[kingdom],$search2[kingdom]);
        if ($modifier != 2) {
            echo "You can only do this thievery operation against a target you are at war with.";
            include_game_down();
            exit;
        }
        mysql_grab($d_user[id],local,build,stats,user);
        $percentage = 0.0075;
        include_once("inc/functions/get.php");
        $kingdom = get_kingdom_nonarray($d_user[id]);
        $alliance_size = (get_alliance_size($kingdom)) * 80;
        $science_update_bonus = get_science_update_bonus($kingdom);
        $science_defence_bonus = round((1.98*$science_update_bonus[defence_bonus])/($alliance_size+$science_update_bonus[defence_bonus]),3);
        if ($science_defence_bonus > 1) {$science_defence_bonus = 1;}

        $percentage = $percentage - ($science_defence_bonus/100);
        if ($percentage < 0.0055) { $percentage = 0.0055; }

    $academies = $local_build[academies];
    $totalburned = 0;

    for ($x=1; $x<=$cnt; $x++)
    {
            $max_burn = floor($academies * $percentage * $mod);
            if ($thieves <= 50 || $max_burn <= "0") { $max_burn = "0"; }
            $burnable = floor( ($thieves / ( $local_build[land] / 2 ) ) * $max_burn);
            if ($burnable >= $max_burn) { $burnable = $max_burn; }
//record stuff... since no randomness is involved in dmg, first arson is always the largest.
        if ($x == 1) { $first = $burnable; }
        $academies -= $burnable;
        $totalburned += $burnable;
        }

    $update = mysql_query ("UPDATE build SET academies ='$academies' WHERE id ='$d_user[id]'");
    $update = mysql_query($update, $connection);
    unset($max_burn);
    unset($local_build);
    unset($local_stats);
    $local_stats = mysql_query ("SELECT * FROM stats WHERE id = '$userid' ");
    $local_stats = mysql_fetch_array($local_stats);

    $result["fame"] = floor($totalburned * 3);
    $result["text_screen"] = "Tunneling has destroyed $totalburned academies of the enemy!";
    $result["text_news"] = "Unfortunately we have found $totalburned academies burned to the ground.";

    return $result;
}
*/
?>
