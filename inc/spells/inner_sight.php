<?php
//******************************************************************************
// Inner Sight - spy on research
//  History
//
//   Rewritten so we don't do the same calculations everywhere,
//   objectification and not using functions/get.php - AI 05/05/07
//
//   Fully recoded. Two lines that's how you do it ;) - December 25, 2007 Martel
//******************************************************************************

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $objTrgAlli = $objTrgUser->get_alliance();

    require_once('inc/pages/research.inc.php');
    require_once('inc/pages/market.inc.php');

    $result["text_screen"] = "You peer into the inner workings of the " .
                             'victim... <div class="center">' .
                             get_alliance_science_table($objTrgAlli) . '<br />' .
                             get_market_table($objTrgAlli) . '</div>';
    $result["damage"] = 1;
    $result["casted"] = $amount;

    return $result;
}
?>


