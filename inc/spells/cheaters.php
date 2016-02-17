<?php

///////////////////////////////////
/// Cheater - destroy citizens & kills province
///////////////////////////////////
// updated to work correctly - AI 03/10/06
//==============================================

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{

    $arrSrcBuild = $objSrcUser->get_builds();
    $arrTrgBuild = $objTrgUser->get_builds();
    $arrTrgStats = $objTrgUser->get_stats();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgPops  = $objTrgUser->get_pops();
    $citizens    = $arrTrgPops[CITIZENS];

    $intDamage   = floor($arrTrgPops[CITIZENS]);

    $strMsgTemp  = "<span class=negative>Fireballs</span> rained from the sky! <strong class=negative>$intDamage</strong> citizens where killed.<br /><strong class=negative>You have been fried by an admin!</strong>";
    $strAlliTemp = "<strong class=admin>" . $objTrgUser->get_stat(TRIBE) . " was fried by an admin!</strong>";

    $intNewcitizens = ($arrTrgPops[CITIZENS] - $intDamage);
    $objTrgUser->set_pop(CITIZENS, $intNewcitizens);

    // Tragedy: KILL THEM ALL !!
    $result["text_news"]    = $strMsgTemp;
    $result["alli_news"]    = $strAlliTemp;
    $result["text_screen"]  = "Fireballs rain from the sky of <font color=gold>" . $objTrgUser->get_stat(TRIBE) . " (#" .
    $objTrgUser->get_stat(KINGDOM) . ")</font> and kills <strong class=negative>$intDamage</strong> citizens.";
    $result["casted"]       = /*-*/1; //if this is < 0, it messes up the whole system
    $result["damage"]       = -101;

    return $result;
}
?>
