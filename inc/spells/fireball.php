<?php

///////////////////////////////////
/// Fireball - destroy citizens
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    
    $arrSrcBuild = $objSrcUser->get_builds();
    $arrTrgBuild = $objTrgUser->get_builds();
    $arrTrgStats = $objTrgUser->get_stats();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgPops = $objTrgUser->get_pops();
    $citizens = $arrTrgPops[CITIZENS];

// Get target science. Didn't find any object code so I did this is the hard way. -Reaver
    $strSQL = "SELECT home_bonus FROM kingdom WHERE id = $arrTrgStats[kingdom]";
    $mres = mysql_query ($strSQL) or die("cast:". $arrSpell[DISPLAY] . ":" . mysql_error() . " -- ".$strSQL);
    $arrKingdom = mysql_fetch_array($mres);
    $intSciencePop = ($arrKingdom[HOME_BONUS] * 0.01 + 1);
    //changed to work with magelevel so Templars won't get max damages of zero - AI 21/02/2007
    //$intMaxLandDamage = floor($arrSrcBuild[ACADEMIES] * 3 * $intSciencePop);
    $intMaxLandDamage = floor($arrSrcBuild[LAND] * get_mage_level($objSrcUser) * 0.03 * $intSciencePop);

    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage = floor($citizens * 0.01);
        // Takes science into account now. -Reaver

        $intDamage = min($intDamage,$intMaxLandDamage);
        if ($arrTrgBuild[LAND] >= 1000 && $intDamage <= 30) { $intDamage = 30; }
        if ($arrTrgBuild[LAND] >= 300 && $intDamage <= 15) { $intDamage = 15; }
        if ($arrTrgBuild[LAND] <= 300 && $intDamage <= 10) { $intDamage = 10; }

        // min 1 damage
        $intDamage = max(1,$intDamage);
        $intDamage = floor($dmg * $intDamage);

        // First FB will always be the biggest, so in order to check for records, just save the first one.
        if ($x == 1) $firstDamage = $intDamage;
        //frost: age 18, each % walls shelters 2% citizen
        $wallsPercent = ($arrTrgBuild[WALLS]*100)/$arrTrgBuild[LAND];
        if ($wallsPercent > 20) {$wallsPercent = 20;};
        for ($y = 1; $y <= $wallsPercent; $y++)
        {
            $intDamage = round($intDamage*0.98);
        }
        //frost end.
        $citizens -= $intDamage;
        $totalDamage += $intDamage;
    }

    if ($amount > 1) $plural = "s"; else $plural = "";   
    $result["text_news"] = "<font class=negative>$amount storm$plural of fireballs</font> rained from the sky! <font class=negative>$totalDamage</font> citizens were killed.";
    $result["text_screen"] = "Fireballs rain from the sky of " . $objTrgUser->get_stat(TRIBE) . "(#" . $objTrgUser->get_stat(KINGDOM) . ") and kill <b><font class=positive>$totalDamage</font></b> citizens.";
    $result["casted"] = $amount;
    //Nasty trick to get kill-check working in magic.php. If $result["damage"] < -100, the script will check for a kill...
    $result["damage"] = -100+$citizens;

    $objTrgUser->set_pop(CITIZENS, $citizens);

    // record stuff
    $fetch_record = mysql_query("Select * from records where id = 1");
    $fetch_record = mysql_fetch_array($fetch_record);

    if ($firstDamage > $fetch_record['fireball'] || $fetch_record == "") 
    { 
        $update = mysql_query("Update records set fireball = $firstDamage, fireball_id = " . $arrSrcStats["id"] . " where id = 1"); 
    }

    return $result;
}
?>
