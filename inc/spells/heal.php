<?php
///////////////////////////////////
/// Heal - removes pestilence - Martel, and updated again November 09, 2007
/// Added engineered virus and fame - February 01, 2008, Martel
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $arrTrgStats  = $objTrgUser->get_stats();
    $iDuration    = max(0, $objTrgUser->get_spell(PEST));
    $iDuration2   = max(0, $objTrgUser->get_spell(VIRUS));

    $plural = 's';
    $plural2 = 's';
    if ($iDuration == 1) $plural = '';
    if ($iDuration2 == 1) $plural2 = '';

    $result["text_news"]   = "An allied mage has <span class=positive>healed</span> our population from pestilence and engineered virus.";
    $result["casted"]      = $amount;
    $result["damage"]      = $iDuration + $iDuration2;
    $result["text_screen"] = "We healed our alli " . stripslashes($arrTrgStats[TRIBE]) . " from <strong class=positive>$iDuration month$plural</strong> of Pestilence and <strong class=positive>$iDuration2 month$plural2</strong> of Engineered Virus.";
    // Heal may target your own tribe, customized msg   February 13, 2008 Martel
    if ($objTrgUser->get_userid() == $objSrcUser->get_userid())
    {
        $result["text_screen"] = "We healed ourselves from <strong class=positive>$iDuration month$plural</strong> of Pestilence and <strong class=positive>$iDuration2 month$plural2</strong> of Engineered Virus.";
        $result["text_news"] = '';
        $result["damage"] = 0;
    }

    $objTrgUser->set_spells(array(PEST => 0, VIRUS => 0));

    return $result;
}
?>


