<?php

///////////////////////////////////
/// Poison - destroy food
/// Modified by Species5618, 5-3-2004, tweaked to new Magic Engine
////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    //Get info on the enemy
    $arrTrgBuild = $objTrgUser->get_builds();
    $arrTrgStats = $objTrgUser->get_stats();
    $arrTrgGoods = $objTrgUser->get_goods();
    $currFood = $arrTrgGoods[FOOD];

    //Calculate damage
    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intDamage = floor($dmg * $currFood * 0.05);
        $totalDamage += $intDamage;
        $currFood -= $intDamage;
    }

    //Prepare return-variable
    $result["casted"] = $amount;
    $result["damage"] = $totalDamage;
    $result["text_news"] = "We have found $totalDamage kgs of food destroyed by poison.";
    $result["text_screen"] = "Your poison has ruined $totalDamage kgs of food on " . $objTrgUser->get_stat(TRIBE) . "(#" .
                     $objTrgUser->get_stat(KINGDOM) . ") farms";

    //Save changes
    $objTrgUser->set_good(FOOD, $currFood);

    return $result;


}
?>

