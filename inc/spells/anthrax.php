<?php

////////////////////////////////////
/// Anthrax
/// Modified by Tragedy on april 16th 2002
/// Modified by Species5618, 2-3-2004, tweaked to new Magic Engine
////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    //Get details about the enemy.
    $arrTrgStats = $objTrgUser->get_stats();
    $arrTrgArmys = $objTrgUser->get_armys();
    $arrSrcMilReturns =  $objTrgUser->get_milreturns();
    $soldiers = $arrTrgArmys[UNIT1] - ($arrSrcMilReturns["unit1_t1"] + $arrSrcMilReturns["unit1_t2"] + $arrSrcMilReturns["unit1_t3"] + $arrSrcMilReturns["unit1_t4"]);
    $totaldamage = 0;

    for ($x = 1; $x <= $amount; $x++)
    {
        $intRandValue = rand(0,7);
        $intMaxDamage = (($intRandValue + 5) * 0.01 * $soldiers);
        $intDamage = floor(rand(0, $intMaxDamage));
        if (($intDamage < 100) && ($intMaxDamage > 100))
            $intDamage = floor(rand(50,100));
        $intDamage = round($dmg * $intDamage);
        if ($soldiers <= $intDamage)
            $intDamage = $soldiers;
        $intDamage = max($intDamage, 0);
        $soldiers -= $intDamage;
        $totaldamage += $intDamage;
    }

    //Set values for the return-object
    if ($amount>1) $result["text_news"] = "$amount mysterious infection has killed $totaldamage soldiers.";
    else $result["text_news"] = "$amount mysterious infections have killed $totaldamage soldiers.";
    $result["casted"] = $amount;
    $result["damage"] = $totaldamage;
    $result["text_screen"] = "Your Anthrax has killed $totaldamage Soldiers from ".$objTrgUser->get_stat(TRIBE)."(#".$objTrgUser->get_stat(KINGDOM) . ")";

    //Save new soldier-count to database
    $soldiers = $arrTrgArmys[UNIT1] - $totaldamage;
    $objTrgUser->set_army(UNIT1, $soldiers);

    return $result;
}

?>
