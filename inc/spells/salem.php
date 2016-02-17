<?php

///////////////////////////////////
/// Enchantres Salem - reduce target's def
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();

    $length = 1;

    $result["text_news"]   = "Your army has been seduced by the beautiful Enchantress Salem. It has lost its magical advantage in defence for <strong class=negative>1</strong> month.";
    $result["text_screen"] = "You have enchanted " . stripslashes($arrTrgStats[TRIBE]) . "'s army for 1 hour";
    $result["casted"]      = $amount;
    $result["damage"]      = 1;

    $objTrgUser->set_spell(SALEM, $length);

    return $result;
}
?>
