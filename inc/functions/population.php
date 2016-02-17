<?php
//******************************************************************************
// functions population.php                               Martel, April 03, 2006
//
// Description: Contains functions for all tribe population needs. These go
// hand in hand with production.php and bonuses.php. Everything object based!
//
// Primary use: The update script and related advisors.
//******************************************************************************
include_once('inc/functions/production.php');
include_once('inc/functions/bonuses.php');

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getPopulation(&$objUser)
{
    $arrArmy = $objUser->get_armys();
    $population = array();

    $population['citizens']  = $objUser->get_pop(CITIZENS);

    $population['basics']    = $arrArmy['unit1'] + $arrArmy['unit1_t1'] + $arrArmy['unit1_t2'] + $arrArmy['unit1_t3'] + $arrArmy['unit1_t4'];
    $population['off_specs'] = $arrArmy['unit2'] + $arrArmy['unit2_t1'] + $arrArmy['unit2_t2'] + $arrArmy['unit2_t3'] + $arrArmy['unit2_t4'];
    $population['def_specs'] = $arrArmy['unit3'] + $arrArmy['unit3_t1'] + $arrArmy['unit3_t2'] + $arrArmy['unit3_t3'] + $arrArmy['unit3_t4'];
    $population['elites']    = $arrArmy['unit4'] + $arrArmy['unit4_t1'] + $arrArmy['unit4_t2'] + $arrArmy['unit4_t3'] + $arrArmy['unit4_t4'];
    $population['thieves']   = $arrArmy['unit5'] + $arrArmy['unit5_t1'] + $arrArmy['unit5_t2'] + $arrArmy['unit5_t3'] + $arrArmy['unit5_t4'];
    $population['mystics']   = $arrArmy['unit6'] + $arrArmy['unit6_t1'] + $arrArmy['unit6_t2'] + $arrArmy['unit6_t3'] + $arrArmy['unit6_t4'];

    $population['total_pop']  = array_sum($population);
    $population['total_army'] = $population['total_pop'] - $population['citizens'];

    return $population;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getMaxPopulation(&$objUser)
{
    $population  = array();
    $iAllianceId = $objUser->get_stat(KINGDOM);
    $strRace     = $objUser->get_stat(RACE);
    $arrBuilds   = $objUser->get_builds();

    // Base Housing Capacities
    include_once('inc/functions/build.php');
    $arrBuildVars = getBuildingVariables($strRace);
    $homes_hold   = $arrBuildVars['housing'][1];
    $extra_hold   = $arrBuildVars['housing'][2];
    $hideouts_hold= $arrBuildVars['housing'][12];

    // Maximum number of homes that will house population
    if ($strRace != "Mori Hai")
        $max_homes = floor($arrBuilds[LAND] * 0.3);
    else
        $max_homes = floor($arrBuilds[LAND] * 0.45);

    if ($arrBuilds[HOMES] >= $max_homes)
    {
        $arrBuilds[HOMES] = $max_homes;
    }

    // Various other buildings house population too
    $other_buildings = $arrBuilds[FARMS]
                     + $arrBuilds[WALLS]
                     + $arrBuilds[WEAPONRIES]
                     + $arrBuilds[GUILDS]
                     + $arrBuilds[MINES]
                     + $arrBuilds[MARKETS]
                     + $arrBuilds[LABS]
                     + $arrBuilds[CHURCHES]
                     + $arrBuilds[GUARDHOUSES]
                     + $arrBuilds[BANKS]
                     + $arrBuilds[ACADEMIES]
                     + $arrBuilds[YARDS];

    // Hideouts (is the same as other for most races)
    $hideouts = $arrBuilds[HIDEOUTS];

    // Raw Housing Capacity of Buildings
    $homes            = $arrBuilds[HOMES] * $homes_hold;
    $hideouts        *= $hideouts_hold;
    $other_buildings *= $extra_hold;
    $other_buildings += $hideouts;
    $total_room       = $homes + $other_buildings;

    // Population Spell Bonus (Elendian)
    $spellBonuses   = getSpellBonuses($objUser);
    $spell_bonus    = round(($total_room * $spellBonuses['population']), 0);

    // Research Bonus - New code                        January 09, 2008, Martel
    $arrResearch    = getResearchBonuses($objUser->get_alliance());
    $research_bonus = round($total_room * $arrResearch['population']);

    // Population Fame bonus
    $arrFameBonuses = getFameBonuses($objUser);
    $fame_bonus     = round($total_room * $arrFameBonuses['population']);

    // Total Population
    $total = floor($homes + $fame_bonus + $spell_bonus + $research_bonus + $other_buildings);

    // Max Citizens
    $arrPopulation  = getPopulation($objUser);
    $max_citizens   = floor($total - $arrPopulation['total_army']);

    // Total Citizens (How many citzizens there can be after update)
    $total_citizens = $arrPopulation['citizens'];

    // Get Growthrate
    $growthrate = getGrowthRate($objUser);

    $total_citizens = floor($total_citizens * $growthrate);

    if ($total_citizens > $max_citizens)
        $total_citizens = $max_citizens;

    if ($total_citizens < 200)
        $total_citizens = 200;

    $population['homes']          = $homes; // raw capacity of homes
    $population['extra_homes']    = $other_buildings; // raw of other buildings
    $population['total_raw']      = $total_room;
    $population['fame_bonus']     = $fame_bonus;
    $population['spell_bonus']    = $spell_bonus;
    $population['research_bonus'] = $research_bonus;
    $population['max_citizens']   = $max_citizens;
    $population['growth_rate']    = $growthrate;
    $population['total_citizens'] = $total_citizens;
    $population['total']          = $total; // total max population

    return $population;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getGrowthRate(&$objUser)
{
    $arrSpellBonuses = getSpellBonuses($objUser);
    $iGrowth         = $arrSpellBonuses['growthrate'];

    // Meteor Elf penalty - AI 15/05/07
    if (($iGrowth > 1) && ($objUser->get_stat(RACE) == "Meteor Elf"))
        $iGrowth = ($iGrowth - 1) * .7 + 1;

    // Check for starvation
    $arrGoods    = $objUser->get_goods();
    $production  = getFoodProduction($objUser);
    if (($arrGoods[FOOD] + $production['total']) < 0)
        $iGrowth = 0.8;

    return $iGrowth;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getMilitaryUpkeep(&$objUser)
{
    $arrArmy = $objUser->get_armys();

//     $upkeep  = ($arrArmy[UNIT1] * 0.1)
//              + ($arrArmy[UNIT2] * 0.3)
//              + ($arrArmy[UNIT3] * 0.3)
//              + ($arrArmy[UNIT4] * 0.7)
//              + ($arrArmy[UNIT5] * 0.2)
//              + ($arrArmy[UNIT6] * 0.2);

//     New formula:
//     ( Raw off + Raw def ) / 30 + (number of basics, specs and elites) / 10 + thieves / 4

    include_once('inc/functions/military.php');
    $arrArmyOffence = getArmyOffence($objUser);
    $arrArmyDefence = getArmyDefence($objUser);

    $raw_off = $arrArmyOffence['raw'];
    $raw_def = $arrArmyDefence['raw'];

    $upkeep = ($raw_off + $raw_def) / 30 +
                ($arrArmy[UNIT1] + $arrArmy[UNIT2] + $arrArmy[UNIT3] + $arrArmy[UNIT4]) / 10 +
                ($arrArmy[UNIT5] + $arrArmy[UNIT6]) / 4;

    if ($objUser->get_stat(RACE) == 'Dragon')
        $upkeep = round($upkeep * .33);

    return $upkeep;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getCitizenIncome(&$objUser)
{
    $taxes      = array();
    $strRace    = $objUser->get_stat(RACE);
    $population = getPopulation($objUser);
    $citizens   = $population['citizens'];

    $citizen_income = $citizens * 2;

    // Starvation = 50% Income
    $starvation  = 0;
    $arrGoods    = $objUser->get_goods();
    $production  = getFoodProduction($objUser);
    if ((($arrGoods[FOOD] + $production['total']) < 0) && $strRace != "Spirit")
        $starvation = round($citizen_income * 0.5);

    // Spell Bonus
    $update_spells      = getSpellBonuses($objUser);
    $quanta_bonus       = $update_spells['income'];
    $spell_income_bonus = round(($citizen_income - $starvation) * $quanta_bonus);

    // Total
    $total_income = $citizen_income
                  - $starvation
                  + $spell_income_bonus;

    $taxes['raw']         = $citizen_income;
    $taxes['starvation']  = $starvation;
    $taxes['spell_bonus'] = $spell_income_bonus;
    $taxes['total']       = $total_income;

    return $taxes;
}

?>
