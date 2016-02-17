<?php



/////////////////////////////////////
/// DragonApprentice - destroys homes
/////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgBuild = $objTrgUser->get_builds();
    $arrSrcBuild = $objSrcUser->get_builds();
    $homes = $arrTrgBuild[HOMES];





    $destroyable = 0.004;
    require_once("inc/functions/get.php");
    $kingdom = get_kingdom_nonarray($arrTrgStats['id']);
    //$kingdom = $arrTrgStats[ALLIANCE];
    //SCIENCE
    $alliance_size = (get_alliance_size($kingdom)) * 80;
    $science_update_bonus = get_science_update_bonus($kingdom);
    $science_defence_bonus = round((1.98*$science_update_bonus['defence_bonus'])/($alliance_size+$science_update_bonus['defence_bonus']),3);
    if ($science_defence_bonus > 1) {$science_defence_bonus = 1;}
    //$science_defence_bonus = min(1,$science_defence_bonus);
    $destroyable = $destroyable - ($science_defence_bonus/500);
    //$intMaxHomeDamage = ceil($dmg * $arrSrcBuild[ACADEMIES] * 0.015);
    $intMaxHomeDamage = ceil($dmg * $arrSrcBuild[LAND] * get_mage_level($objSrcUser) * 0.00015);
    //changed to work with magelevel so Templars won't get max damages of zero - AI 21/02/2007
    //damage caps here, see dragonmage for info
    $totalHomeDamage = 0;




    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage = ceil($dmg*$homes*$destroyable);
        $intDamage = min($intDamage,$intMaxHomeDamage);
        $totalHomeDamage += $intDamage;
        $homes -= $intDamage;
    }

    if ($amount > 1) { $plural1 = "s"; $plural2 = ""; }
    else { $plural1 = ""; $plural2 = "s"; }

    
    $result["text_news"] = "<font class=\"negative\">$amount Dragon$plural1</font> rage$plural2 through your tribe destroying <b><font class=\"negative\">$totalHomeDamage</font></b> acres of 
                homes";
    $result["text_screen"] = "With the aid of dragons, $totalHomeDamage of ". $objTrgUser->get_stat(TRIBE) . "(#". $objTrgUser->get_stat(KINGDOM) .")'s homes were destroyed.";
    $result["damage"] = 1;
    $result["casted"] = $amount;
    
    $objTrgUser->set_build(HOMES, $homes);

    return $result;
}
?>
