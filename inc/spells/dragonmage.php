<?php



///////////////////////////////////
/// DragonMage - destroys Buildings
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgBuild = $objTrgUser->get_builds();
    $arrSrcBuild = $objSrcUser->get_builds();
    $homes = $arrTrgBuild[HOMES];
    $farms = $arrTrgBuild[FARMS];
    $acads = $arrTrgBuild[ACADEMIES];
    $guilds = $arrTrgBuild[GUILDS];
    $hideouts = $arrTrgBuild['hideouts'];

    $destroyable = 0.01;
    include_once("inc/functions/get.php");
    $kingdom = get_kingdom_nonarray($arrTrgStats['id']);
    //SCIENCE
    $alliance_size = (get_alliance_size($kingdom)) * 80;
    $science_update_bonus = get_science_update_bonus($kingdom);
    $science_defence_bonus = round((1.98*$science_update_bonus['defence_bonus'])/($alliance_size+$science_update_bonus['defence_bonus']),3);
    if ($science_defence_bonus > 1) {$science_defence_bonus = 1;}
    $destroyable = $destroyable - ($science_defence_bonus/200);
    //$intMaxHomeDamage = floor($dmg * $arrSrcBuild[ACADEMIES] * 0.015);
    //$intMaxFarmDamage = floor($dmg * $arrSrcBuild[ACADEMIES] * 0.002);
    //$intMaxMagicDamage = floor($dmg * $arrSrcBuild[ACADEMIES] * 0.00225);
    $intMaxHomeDamage = floor($dmg * $arrSrcBuild[LAND] * get_mage_level($objSrcUser) * 0.00015);
    $intMaxFarmDamage = floor($dmg * $arrSrcBuild[LAND] * get_mage_level($objSrcUser) * 0.00002);
    $intMaxMagicDamage = floor($dmg * $arrSrcBuild[LAND] * get_mage_level($objSrcUser) * 0.0000225);
    //changed to work with magelevel so Templars won't get max damages of zero - AI 21/02/2007



// Added the same kind of damage caps that fireball uses. They are based on the assumption
// of an average build with 30% homes, 8% farms, 10% guilds and 10% academies. -Reaver
    $totalHomeDamage = 0;
    $totalFarmDamage = 0;
    $totalAcadDamage = 0;
    $totalGuildDamage = 0;
    $totalHideoutDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage = floor($dmg*$homes*$destroyable);
        $intDamage = min($intDamage,$intMaxHomeDamage);
        $totalHomeDamage += $intDamage;
        $homes -= $intDamage;

        $intDamage = floor($dmg * $farms*$destroyable);
            $intDamage = min($intDamage,$intMaxFarmDamage);
        $totalFarmDamage += $intDamage;
        $farms -= $intDamage;

        $intDamage = floor($dmg * $acads*$destroyable);
            $intDamage = min($intDamage,$intMaxMagicDamage);
        $totalAcadDamage += $intDamage;
        $acads -= $intDamage;

        $intDamage = floor($dmg * $guilds*$destroyable);
            $intDamage = min($intDamage,$intMaxMagicDamage);
        $totalGuildDamage += $intDamage;
        $guilds -= $intDamage;

        $intDamage = floor($dmg * $hideouts*$destroyable);
            $intDamage = min($intDamage,$intMaxMagicDamage);
        $totalHideoutDamage += $intDamage;
        $hideouts -= $intDamage;
    }

    if ($amount > 1) { $plural1 = "S"; $plural2 = ""; }
    else { $plural1 = ""; $plural2 = "s"; }

    $totalDamage = $totalHomeDamage + $totalFarmDamage + $totalAcadDamage + $totalGuildDamage + $totalHideoutDamage;
    $result["text_news"] = "<span class=negative>$amount dragon$plural1</span> rage$plural2 through your tribe destroying <strong class=negative>$totalDamage</strong> acres of residential and magical land";
    $result["text_screen"] = "With the aid of the dragons, $totalHomeDamage homes, $totalFarmDamage farms, $totalAcadDamage academies, $totalGuildDamage guilds and $totalHideoutDamage hideouts were destroyed.<br /><br />".
                 "A total of $totalDamage of ". $objTrgUser->get_stat(TRIBE) . " (#" . $objTrgUser->get_stat(KINGDOM) . ")s buildings were ruined.";
    $result["damage"] = 1;
    $result["casted"] = $amount;

    $objTrgUser->set_build(HOMES, $homes);
    $objTrgUser->set_build(FARMS, $farms);
    $objTrgUser->set_build(GUILDS, $guilds);
    $objTrgUser->set_build(ACADEMIES, $acads);
    $objTrgUser->set_build(HIDEOUTS, $hideouts);

    return $result;
}
?>




