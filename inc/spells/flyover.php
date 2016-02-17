<?php

///////////////////////////////////
/// Flyover
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    include_once('inc/pages/alliance_news.inc.php');
//  include_once("inc/functions/war.php");
//
//  $modifier = war_alli($objSrcUser->get_stat(ALLIANCE), $objTrgUser->get_stat(ALLIANCE));
//     if ($modifier == 0 && war_target($objTrgUser->get_stat(ALLIANCE)) != 0)
//     {
//         echo "Cannot use Fly Over on alliances in war!";
//         include_game_down();
//         exit;
//     }

    $result["text_screen"] = showAllianceNews($objTrgUser->get_stat(ALLIANCE));
    $result["casted"] = $amount;
    $result["damage"] = 1;

    return $result;
}
?>








