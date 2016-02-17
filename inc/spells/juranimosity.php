<?php

////////////////////////////////////
/// Juranimosity
////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $targetid = $arrTrgStats["id"];
    $res = mysql_query("SELECT unit5 FROM army WHERE id = $targetid");
    $arrTrgArmys = mysql_fetch_assoc($res);
    $arrSrcStats = $objSrcUser->get_stats();
    $sourceid = $arrSrcStats["id"];
    include_once("inc/functions/war.php");
    $search = mysql_query("SELECT kingdom FROM stats WHERE id = $sourceid");
    $search = mysql_fetch_array($search);
    $search2 = mysql_query("SELECT kingdom FROM stats WHERE id = $targetid");
    $search2 = mysql_fetch_array($search2);
    $modifier = war_alli($search['kingdom'],$search2['kingdom']);
    $arrTrgMilReturns =  $objTrgUser->get_milreturns();
    $thieves = $arrTrgArmys[UNIT5] - ($arrTrgMilReturns["unit5_t1"] + $arrTrgMilReturns["unit5_t2"] + $arrTrgMilReturns["unit5_t3"] + $arrTrgMilReturns["unit5_t4"]);

    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $rndDamage = rand(20,60)*0.001;
        $intDamage = floor($dmg * $thieves * $rndDamage);
        $totalDamage += $intDamage;
        $thieves -= $intDamage;
    }

    if ($amount > 1) $plural = "s";
    else $plural = "";

    $result["text_news"] = "You have had $amount group$plural of thieves leave, totalling <b><font class=negative>$totalDamage</font></b> thieves.";
    $result["text_screen"] = "Your influence has disbanded $totalDamage of " . $arrTrgStats[TRIBE] . "s thieves<br>";
    $result["damage"] = $totalDamage;
    $result["casted"] = $amount;

    $newThieves = $arrTrgArmys["unit5"] - $totalDamage;
    $objTrgUser->set_army(UNIT5, $newThieves);
    $retained = $totalDamage;
    if ($modifier >= 1)
        $retained = floor($retained * 0.75);

    $update = mysql_query("UPDATE army SET unit5_t4 = unit5_t4 + $retained WHERE id = $arrTrgStats[id]");

    return $result;
}
?>

