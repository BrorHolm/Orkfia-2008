<?php

///////////////////////////////////
/// Wrath of XENE - Destroys Guardhouses
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $arrTrgBuild = $objTrgUser->get_builds();
    $guards = $arrTrgBuild[GUARDHOUSES];

    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage    = floor($dmg*$guards*.01);
        $totalDamage += $intDamage;
        $guards      -= $intDamage;
    }

    $result["text_news"] = "A mystical presence has rampaged through our tribe! <strong class=negative>$totalDamage</strong> guardhouses were destroyed.";
    $result["text_screen"] = "Your mage manages to destroy <strong class=positive>$totalDamage</strong> of " .
    $objTrgUser->get_stat(TRIBE) . " (#" . $objTrgUser->get_stat(KINGDOM) . ")s guardhouses.";
    $result["casted"] = $amount;
    $result["damage"] = $totalDamage;

        $objTrgUser->set_build(GUARDHOUSES, $guards);

    return $result;
}
?>


