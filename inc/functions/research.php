<?php
//******************************************************************************
// Functions: research.php                                Martel and Species5618
//                                                            September 18, 2005
//
// List of functions:
//
// delete_my_rps('user-id') will remove research points invested for a tribe
//******************************************************************************

function research_update($alli_id)
{
    // Classic Exception
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
        return;

    $search = mysql_query("SELECT (hour_counter - research_hour) as updates,home_bonus,income_bonus,offence_bonus,defence_bonus FROM " . TBL_ALLIANCE . ", admin_global_time WHERE id = $alli_id");
    $search = mysql_fetch_assoc($search);
    $updates = $search['updates'];

    $intUpdatesOwed = $updates;
    if ($intUpdatesOwed > 0)
    {
        $eng = $search['home_bonus'];
        $prod = $search['income_bonus'];
        $def = $search['defence_bonus'];
        $war = $search['offence_bonus'];

        while ($intUpdatesOwed-- > 0 )
        {
            $eng *= .996;
            $prod *= .999;
            $def *= .997;
            $war *= .998;
        }
        $eng  = round($eng);
        $prod = round($prod);
        $def  = round($def);
        $war  = round($war);

        $update = mysql_query("UPDATE kingdom SET home_bonus = $eng, income_bonus = $prod, defence_bonus = $def, offence_bonus = $war, research_hour = research_hour + $updates WHERE id = $alli_id");
    }
}

//==============================================================================
// Age change age 21: Deletion and defection (leaving the alliance) will remove
// all science invested and purchased.
// Function will delete a user's invested and purchased RPs from his alliance
//------------------------------------------------------------------------------
// This function will no longer be used, research decay will take it's place
//                                                         - AI 07/05/07
//==============================================================================
/*function delete_my_rps($iUserid)
{
    // grab invested science
    $tribeStats = "SELECT invested, kingdom FROM stats WHERE id='$iUserid'";
    $tribeStats = mysql_query($tribeStats);
    $tribeStats = mysql_fetch_array($tribeStats);

    // grab alli research
    $sql  = "SELECT research as not_purchased, income_bonus, home_bonus, ";
    $sql .= "offence_bonus, defence_bonus, (income_bonus + ";
    $sql .= "home_bonus + offence_bonus + defence_bonus) as total_bonus ";
    $sql .= "FROM kingdom WHERE id=$tribeStats[kingdom] LIMIT 0, 1";
    $alli_RP = mysql_query($sql);
    $alli_RP = mysql_fetch_array($alli_RP);

    // calculate invested RPs after research 'nerf'
    $modified_tribe_RP = 0.6666 * $tribeStats["invested"];

    // calculate total
    $total_alli_RP = max(1, (($alli_RP["not_purchased"] * 0.6666) + $alli_RP["total_bonus"]));

    // calculate fractions
    $fraction_home = $alli_RP["home_bonus"]    / $total_alli_RP;
    $fraction_def  = $alli_RP["defence_bonus"] / $total_alli_RP;
    $fraction_off  = $alli_RP["offence_bonus"] / $total_alli_RP;
    $fraction_prod = $alli_RP["income_bonus"]  / $total_alli_RP;
    $fraction_pool = ($alli_RP["not_purchased"] * 0.6666) / $total_alli_RP;

    // calculate actual rps to be deleted
    $home_RP_lost = round($modified_tribe_RP * $fraction_home);
    $def_RP_lost  = round($modified_tribe_RP * $fraction_def);
    $off_RP_lost  = round($modified_tribe_RP * $fraction_off);
    $prod_RP_lost = round($modified_tribe_RP * $fraction_prod);
    $pool_RP_lost = round(($modified_tribe_RP * $fraction_pool) / 0.6666);

    // update DB
    mysql_query("UPDATE kingdom SET research = research - $pool_RP_lost, home_bonus = home_bonus - $home_RP_lost, defence_bonus = defence_bonus - $def_RP_lost, offence_bonus = offence_bonus - $off_RP_lost, income_bonus = income_bonus - $prod_RP_lost WHERE id = '$tribeStats[kingdom]'");
    mysql_query("UPDATE stats SET invested = 0 WHERE id = $iUserid");
}*/

?>
