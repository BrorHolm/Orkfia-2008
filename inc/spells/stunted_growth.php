<?php

///////////////////////////////////
/// Stunted Growth
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();

    $intLength = rand($arrSpell[DURMIN], $arrSpell[DURMAX]);
    $intDamage = $intLength;

    $result["text_news"]   = "Your growth has been stunted for <strong class=negative>$intDamage</strong> months.";
    $result["text_screen"] = "You have stunted " . stripslashes($arrTrgStats[TRIBE]) . "'s growth for $intDamage months.";
    $result["damage"]      = 1;
    $result["casted"]      = $amount;

    $objTrgUser->set_spell(STUNTED_GROWTH, $intDamage);

    return $result;
}
?>

