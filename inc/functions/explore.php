<?php
//******************************************************************************
// Functions explore.php                                    Martel, May 28, 2006
//
// Modification history:
// May 28, 2006      - Complete make-over with new code, objectoriented (Martel)
// February 27, 2006 - Document cleaned up (Termato)
// Mars 15, 2002     - changes for next round: explore_cost (thalura)
//
// Description: Contains various functions for the explore page and advisors
//******************************************************************************

function getExploreCosts(&$objUser)
{
    $arrBuild = $objUser->get_builds();
    $iMarkets = $arrBuild[MARKETS];
    $strRace  = $objUser->get_stat(RACE);

    // Calcs based on owned land + incoming
    $iAcres = $arrBuild[LAND]
            + $arrBuild[LAND_T1] + $arrBuild[LAND_T2]
            + $arrBuild[LAND_T3] + $arrBuild[LAND_T4];

    // Wood Elf Race Bonus
    $iWeBonus = 1;
    if ($strRace == "Wood Elf")
        $iWeBonus = 0.9;

    $money_cost = max(floor(($iAcres * 9) * $iWeBonus - $iMarkets * 12), 800);

    // Dense Forest Penalty
    $arrSpells = $objUser->get_spells();
    if ($arrSpells[FOREST] > 0)
        $money_cost = floor($money_cost * 1.15);

    // Cost in citz
    $cost_citizens = max(floor($iAcres * 0.08), 20);

    // Cost in basic soldiers
    $cost_basics   = max(floor($iAcres * 0.04), 10);

    $explore['crowns']   = $money_cost;
    $explore['citizens'] = $cost_citizens;
    $explore['basics']   = $cost_basics;

    return $explore;
}

function getMaxExplore(&$objUser)
{
    // Nazgul can't explore
    $strRace        = $objUser->get_stat(RACE);
    if ($strRace == "Nazgul")
        return 0;

    $arrBuild       = $objUser->get_builds();
    $arrExploreCost = getExploreCosts($objUser);

    $iCrowns        = $objUser->get_good(MONEY);
    $limit_crowns   = floor($iCrowns / $arrExploreCost['crowns']);

    $iCitz          = $objUser->get_pop(CITIZENS);
    $limit_citizens = floor(($iCitz - 1000 ) / $arrExploreCost['citizens']);

    $arrArmyHome    = $objUser->get_armys_home();
    $iBasics        = $arrArmyHome[UNIT1];
    $limit_basics   = floor($iBasics /$arrExploreCost['basics']);

    $limit_acres = floor($arrBuild[LAND] * .25)
                 - ( $arrBuild[LAND_T1]
                   + $arrBuild[LAND_T2]
                   + $arrBuild[LAND_T3]
                   + $arrBuild[LAND_T4] );

    // Classic Exception
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME && $objUser->get_user_info(HOURS) < PROTECTION_HOURS)
        $limit_acres = 1000 - ($arrBuild[LAND_T1] + $arrBuild[LAND_T2] + $arrBuild[LAND_T3] + $arrBuild[LAND_T4]);

    $max_explore = min($limit_crowns, $limit_citizens, $limit_basics, $limit_acres);
    $max_explore = max($max_explore, 0);

    return $max_explore;
}

function doExplore(&$objUser, $iAmount)
{
    $arrExploreCost = getExploreCosts($objUser);
    $money_used     = $iAmount * $arrExploreCost['crowns'];
    $used_citizens  = $iAmount * $arrExploreCost['citizens'];
    $used_basics    = $iAmount * $arrExploreCost['basics'];

    // Update Land
    $iOldL = $objUser->get_build(LAND_T4);
    $iNewL = max(0, $iOldL + $iAmount);
    $objUser->set_build(LAND_T4, $iNewL);

    // Update Money
    $iOldM = $objUser->get_good(MONEY);
    $iNewM = max(0, $iOldM - $money_used);
    $objUser->set_good(MONEY, $iNewM);

    // Update Citizens
    $iOldC = $objUser->get_pop(CITIZENS);
    $iNewC = max(0, $iOldC - $used_citizens);
    $objUser->set_pop(CITIZENS, $iNewC);

    // Update Basics
    $iOldB = $objUser->get_army(UNIT1);
    $iNewB = max(0, $iOldB - $used_basics);
    $objUser->set_army(UNIT1, $iNewB);

    return TRUE;
}

?>