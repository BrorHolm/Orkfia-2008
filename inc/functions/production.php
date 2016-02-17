<?php
//******************************************************************************
// functions production.php                               Martel, April 03, 2006
//
// Description: Contains functions for all tribe production needs. These go
// hand in hand with population.php and bonuses.php. Everything object based!
//
// Primary use: The update script and related advisors.
//******************************************************************************
include_once('inc/functions/population.php');
include_once('inc/functions/bonuses.php');

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getWoodProduction(&$objUser)
{
    $production    = array();
    $iOldWood      = $objUser->get_good(WOOD);
    $iYards        = $objUser->get_build(YARDS);
    $wood          = 25;
    if ($objUser->get_stat(RACE) == 'Dragon')
        $wood      = 6.25;
    $raw           = $iYards * $wood;

    // Research Bonus = 100%                   Age 9, September 25, 2007, Martel
    // New code                                         January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($raw * $arrResearch['wood']);

    $decayed        = round($iOldWood * 0.0001);

    $total          = $raw + $research_bonus - $decayed;

    $production['per_each']       = $wood;
    $production['raw']            = floor($raw);
    $production['research_bonus'] = $research_bonus;
    $production['decayed']        = $decayed;
    $production['total']          = $total;

    return $production;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getResearchProduction(&$objUser)
{
    $production  = array();
    $arrBuilds   = $objUser->get_builds();
    $iLand       = $arrBuilds[LAND];
    $iLabs       = $arrBuilds[LABS];
    $research    = 1;
    $raw         = $iLabs * $research;

    // Research produced (incl. >30% penalty)
    $labs_percentage = floor(($iLabs / $iLand) * 100);
    $research        = 1 - max(($labs_percentage - 30) / 200, 0);
    $labs_produce    = floor($iLabs * $research);

    // Research Bonus = 25%                    Age 9, September 25, 2007, Martel
    // New code                                         January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($raw * $arrResearch['research']);

    // War Penalty on Resarch
    $iHours = $objUser->get_user_info(HOURS);
    include_once("inc/functions/war.php");
    if (war_target($objUser->get_stat(ALLIANCE)) != 0 && $iHours > PROTECTION_HOURS)
        $war_loss = round($labs_produce * 0.5);
    else
        $war_loss = 0;

    $total = $labs_produce + $research_bonus - $war_loss;

    // research penalty (>30% labs)
    $penalty = floor($iLabs - $labs_produce);

    $production['per_each']       = $research;
    $production['raw']            = $raw;
    $production['research_bonus'] = $research_bonus;
    $production['penalty']        = $penalty;
    $production['war_loss']       = $war_loss;
    $production['total']          = $total;

    return $production;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getFoodProduction(&$objUser)
{
    $production = array();
    $strRace    = $objUser->get_stat(RACE);
    $iOldFood   = $objUser->get_good(FOOD);
    $iFarms     = $objUser->get_build(FARMS);
    $food       = 250;
    if ($strRace == "Dragon")
        $food   = 62.5;
    $raw        = $iFarms * $food;

    // Self Spell Bonus
    $update_spells  = getSpellBonuses($objUser);
    $spell_bonus    = round($raw * $update_spells['food']);

    // Research Bonus - New code                        January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($raw * $arrResearch['food']);

    // Food Eaten
    $population     = getPopulation($objUser);
    $used           = $population['total_pop'] * 0.15;

    // Race Exceptions
    if ($strRace == 'Brittonian')   $used *= 0.5;  // 50% for brit
    elseif ($strRace == 'Mori Hai') $used *= 0.75; // 75% for mori
    elseif ($strRace == 'Spirit')   $used  = 0;    //  0% for spirit

    // Food Rotten
    $decayed = floor($iOldFood * 0.0002);

    // Food Produced
    $total   = $raw + $research_bonus + $spell_bonus - $used - $decayed;

    $production['per_each']       = $food;
    $production['raw']            = $raw;
    $production['research_bonus'] = $research_bonus;
    $production['spell_bonus']    = $spell_bonus;
    $production['used']           = $used;
    $production['decayed']        = $decayed;
    $production['total']          = $total;

    return $production;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getMineProduction(&$objUser)
{
    $production  = array();
    $iAllianceId = $objUser->get_stat(ALLIANCE);
    $strRace     = $objUser->get_stat(RACE);
    $iMines      = $objUser->get_build(MINES);
    $gold        = 400;
    if ($strRace == "Dwarf")
        $gold    = 500;
    elseif ($strRace == "Dragon")
        $gold    = 100;
    $raw         = $iMines * $gold;

    // Research Bonus - New code                        January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($raw * $arrResearch['income']);

    $total          = $raw + $research_bonus;

    $production['per_each']       = $gold;
    $production['raw']            = $raw;
    $production['research_bonus'] = $research_bonus;
    $production['total']          = $total;

    return $production;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getBankProduction(&$objUser)
{
    $production  = array();
    $iAllianceId = $objUser->get_stat(ALLIANCE);
    $iLand       = $objUser->get_build(LAND);
    $arrMines    = getMineProduction($objUser);
    $strRace     = $objUser->get_stat(RACE);
    $citizens    = $objUser->get_pop(CITIZENS);
    $iBanks      = $objUser->get_build(BANKS);

    // Bank Income Formula
    $landratio = $iLand / 2000;
    $citzacre  = max( ( ($citizens / $iLand) * Min(pow($landratio, 2), 1) ), 1);
    $gold = ($arrMines['per_each'] * 2) * exp(-50 / $citzacre);

    // Lowest Possible value
    if ($strRace == "Dragon" && $gold < 60)
        $gold = 60;
    elseif ($gold < 250)
        $gold = 250;

    $raw = $iBanks * $gold;

    // Research Bonus - New code                        January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($raw * $arrResearch['income']);

    $total = $raw + $research_bonus;

    $production['per_each']       = $gold;
    $production['raw']            = $raw;
    $production['research_bonus'] = $research_bonus;
    $production['total']          = $total;

    return $production;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getTotalIncome(&$objUser)
{
    $cash_balance = array();
    $strRace      = $objUser->get_stat(RACE);

    // Income From Citizens
    $arrTaxes     = getCitizenIncome($objUser);

    // Income From Mines
    $arrMineProd  = getMineProduction($objUser);

    // Income From Banks
    $arrBankProd  = getBankProduction($objUser);

    // Costs for Military Upkeep
    $iUpkeep      = getMilitaryUpkeep($objUser);

    // Total Income Balance
    $total_income = $arrTaxes['total']
                  + $arrMineProd['total']
                  + $arrBankProd['total']
                  - $iUpkeep;

    $cash_balance['total']    = $total_income;

    return $cash_balance;
}

?>
