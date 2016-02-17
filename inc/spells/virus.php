<?php

///////////////////////////////////
/// Engineered Virus
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $arrSrcStats = $objSrcUser->get_stats();

    include_once("inc/functions/war.php");

    $modifier = war_alli($arrSrcStats[ALLIANCE], $arrTrgStats[ALLIANCE]);
    if ($modifier < 1)
    {
        echo "Sorry leader, this is a war only spell!";
        include_game_down();
        exit;
    }

    $intLength = rand($arrSpell[DURMIN], $arrSpell[DURMAX]);
    $intDamage = max($intLength, 1);

    $result["text_news"]    = "LEADER! We have heard reports of a strange illness afflicting our lands! We suspect an enemy has infected our people with a <b class=negative>virus</b> of some kind. It will take <b class=negative>$intDamage</b> months to contain the virus.";
    $result["text_screen"]  = "Your mage has engineered a mystical virus that will plague the people of " . $arrTrgStats[TRIBE] . ". Our mage estimates it will take their tribe $intDamage months to contain the virus.";
    $result["casted"]       = $amount;
    $result["damage"]       = 1;

    $objTrgUser->set_spell(VIRUS, $intDamage);

    return $result;
}
?>




