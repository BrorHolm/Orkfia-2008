<?php
// Ops.php -- list with available ops, used to have the code for all the ops as well, but
// that has been moved to separate files, to make it more like magic is coded.
// Last update: 13-5-2004, by Species 5618

function set_op_vars()
{
        global $opname, $action, $basecost;

        $action[1] = "recon";
        $opname[1] = "Recon";
        $basecost[1] = 1;

        $action[2] = "sneak";
        $opname[2] = "Sneak";
        $basecost[2] = 1;

        $action[3] = "intercept";
        $opname[3] = "Intercept";
        $basecost[3] = 1;

        $action[4] = "bribe";
        $opname[4] = "Bribe Accountant";
        $basecost[4] = 1;

        $action[5] = "truth";
        $opname[5] = "Truth's Eye";
        $basecost[5] = 1;

        $action[6] = "farm";
        $opname[6] = "Weather's Light";
        $basecost[6] = 0;

        $action[7] = "poison";
        $opname[7] = "Poison Water";
        $basecost[7] = 14;

        $action[8] = "money";
        $opname[8] = "Templu Amplo";
        $basecost[8] = 0;

        $action[9] = "arson";
        $opname[9] = "Arson";
        $basecost[9] = 20;

        $action[10] = "trap";
        $opname[10] = "Thieves Trap (SELF)";
        $basecost[10] = 4;

        $action[11] = "boze";
        $opname[11] = "Boze";
        $basecost[11] = 20;

        $action[12] = "monitoring";
        $opname[12] = "Monitoring";
        $basecost[12] = 12;

        $action[13] = "tunneling";
        $opname[13] = "Tunneling (WAR ONLY)";
        $basecost[13] = 10;

        $action[14] = "rebellion";
        $opname[14] = "Thieves Rebellion";
        $basecost[14] = 10;

        $action[15] = "raid";
        $opname[15] = "Hwighton Raid";
        $basecost[15] = 20;

        $action[16] = "research";
        $opname[16] = "Napanometry";
        $basecost[16] = 0;
        
        $action[17] = "ambush";
        $opname[17] = "Ambush";
        $basecost[17] = 280;

}

function get_op_cost($strOp, $iLand)
{
    global $action, $basecost;

    $id     = array_search($strOp, $action);
    $cost   = ceil($basecost[$id] * intval($iLand) / 1000);

    return  $cost;
}

function obj_thief_op_growth(&$objUser)
{
    include_once("inc/functions/get.php");

    $objAlliance = $objUser->get_alliance();

    // SCIENCE
    $alliance_size = $objAlliance->get_alliance_size() * 80;
    $off_bonus = $objAlliance->get_alliance_info(OFFENCE_BONUS);
    $sci = round((1.98*$off_bonus)/($alliance_size+$off_bonus),3);
    $sci = min($sci,1);

    // Base-growth
    $hideouts = $objUser->get_build(HIDEOUTS);
    $land = $objUser->get_build(LAND);
    $growth = $hideouts*0.2+1;

    // Specialization-bonus
    $growth = $growth * (1 + 2 * $hideouts / $land);

    // Race-bonus
    $race = $objUser->get_stat(RACE);
    if ($race == "Wood Elf")
    {
        $growth = $growth*1.3;
    }
    if ($race == "Mori Hai")
    {
        $homes = $objUser->get_build(HOMES);
        $growth = (($homes/2.5)+$hideouts)*0.2+1;
        $growth = $growth * (1 + 2 * (($homes/2.5)+$hideouts) / $land);
    }
    if ($race == "Meteor Elf")
    {
        $growth *= 1.25;
    }
    $growth = round($growth * ( ($sci/2)+1 ));
    return $growth;
}

// Gotland: rather use obj_thief_op_growth than this one
function thief_op_growth($userid)
{
    include_once("inc/functions/get.php");
    $kingdom = get_kingdom_nonarray($userid);

    //SCIENCE
    $alliance_size = (get_alliance_size($kingdom)) * 80;
    $res = mysql_query("SELECT offence_bonus FROM kingdom WHERE id = $kingdom");
    $line = mysql_fetch_assoc($res);

    $sci = round((1.98*$line["offence_bonus"])/($alliance_size+$line["offence_bonus"]),3);
    $sci = min($sci,1);

    $build = mysql_query ("SELECT homes, hideouts, land FROM build WHERE id = $userid");
    $build = mysql_fetch_assoc($build);
    //Base-growth
    $growth = $build["hideouts"]*0.2+1;

    //Specialization-bonus
    $growth = $growth * (1 + 2 * $build["hideouts"] / ($build["land"]));

    //Race-bonus
    $grab = mysql_query("SELECT race FROM stats WHERE id = $userid");
    $grab = mysql_fetch_array($grab);
    if ($grab['race'] == "Wood Elf")
    {
        $growth = $growth*1.3;
    }
    if ($grab['race'] == "Mori Hai")
    {
        $growth = (($build["homes"]/2.5)+$build["hideouts"])*0.2+1;
        $growth = $growth * (1 + 2 * (($build["homes"]/2.5)+$build["hideouts"]) / ($build["land"]));
    }
    $growth = round($growth * ( ($sci/2)+1 ));
    return $growth;
}

?>
