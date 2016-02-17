<?php

///////////////////////////////////
/// Magical Void - remove spells
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats = $objTrgUser->get_stats();
    $totalRemoved = 0;
    for ($x = 1; $x <= $amount; $x++){
        $arrTrgSpells = $objTrgUser->get_spells();
        $intRand = rand(1, 100);
        if ($intRand <= 10) { $intDamage = 0; } 
        elseif ($intRand <= 90) { $intDamage = 1; } 
        else { $intDamage = 2; }

        // loop through each spell and remove the first intSpellsRemoved spells found 
        $intSpellsRemoved = 0;
        foreach ($arrTrgSpells as $strKey => $intValue){
            // because we are going the spells array from objUser, we DONT want to set the ID to 0, which is in the spell array
            if ( ( $intValue <> 0 ) && ( $strKey <> ID ) && ( $strKey <> 'power' ) ) {
                if ($intSpellsRemoved < $intDamage) {
                    $objTrgUser->set_spell($strKey, 0);
                    $intSpellsRemoved++;
                    $totalRemoved++;
                }
            }
        }
    }

    $result["text_news"] = "<b><font class=negative>$totalRemoved</font></b> spells were removed.";
    $result["text_screen"] = "You have removed " . $totalRemoved . " spells from " . $objTrgUser->get_stat(TRIBE) . 
                     "(#" . $objTrgUser->get_stat(KINGDOM) . ")";
    $result["damage"] = 1;
    $result["casted"] = $amount;
    
    return $result;
}
?>


