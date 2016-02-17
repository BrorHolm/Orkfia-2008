<?php

///////////////////////////////////
/// Magical Rust - Destroy 0.5% Weaponries
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $arrTrgBuild = $objTrgUser->get_builds();
    $weaponries = $arrTrgBuild[WEAPONRIES];

    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage = ceil($dmg * $weaponries*.005);
            $totalDamage += $intDamage;
        $weaponries -= $intDamage;
    }

    $result["text_news"]   = "Rust has destroyed some of our weapons! <strong class=negative>$totalDamage</strong> Weaponries were completely ruined.";
    $result["text_screen"] = "Rust decays at your enemies weapons, <strong class=positive>$totalDamage</strong> of " .
    stripslashes($objTrgUser->get_stat(TRIBE)) . " (#" . $objTrgUser->get_stat(KINGDOM) . ")s weaponries were completely destroyed.";
    $result["casted"]      = $amount;
    $result["damage"]      = $intDamage;

    $objTrgUser->set_build(WEAPONRIES, $weaponries);

    return $result;
}
?>


