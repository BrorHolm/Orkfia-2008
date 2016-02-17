<?php
//******************************************************************************
// functions bonuses.php                                  Martel, April 03, 2006
//
// Description: Contains functions to calculate bonuses from research, fame,
// self spells, etc. These go hand in hand with production.php and
// population.php. Everything object based!
//
// Primary use: The update script and related advisors.
//******************************************************************************

//==============================================================================
//                                                        Martel, April 03, 2006
// M: This function isn't needed anymore.. Just fetch it using the alliance obj
//==============================================================================
function getSciences($iAllianceId)
{
    require_once('inc/classes/clsAlliance.php');
    $objAlli = new clsAlliance($iAllianceId);
    return $objAlli->get_alliance_sciences();
}

//==============================================================================
//                                                       Martel, August 12, 2007
// Implementing it - January 09, 2008, Martel
//==============================================================================
function getResearchBonuses(&$objAlli)
{
    // Sciences vary between 0-0.66, modifiers also vary between 0-0.66
    // 'eng', 'war', 'def', 'prod'
    $arrSci = $objAlli->get_alliance_sciences();

    // PRODUCTION ==============================================================

    $wood     = $arrSci['prod'];
    $income   = $arrSci['prod'];
    $food     = $arrSci['prod'];
    $research = $arrSci['prod'] * 0.25;

    // POPULATION ==============================================================

    $population = $arrSci['eng'];

    // LOSSES ==================================================================

    /*
    $losses = $arrSci['def'] * 0.5;
    */

    // OFFENCE =================================================================

    /*
    $offence = $arrSci['war'];
    */

    // DEFENCE =================================================================

    /*
    $defence = $arrSci['def'];
    */

    // BUILD TIME ==============================================================

    /*
    $modifiers['build_time'] = 1;
    if ($arrSci['eng'] >= 0.3)
        $modifiers['build_time'] = 0.75; // Should only affect 4hr build times?
    */

    // MYSTICS =================================================================

    /*
    $spell_damage  = $arrSci['war'];
    $spell_success = $arrSci['war'];
    */

    // THIEVES =================================================================

    /*
    $op_damage  = $arrSci['war'];
    $op_success = $arrSci['war'];
    */

    $modifiers['income']        = $income;
    $modifiers['food']          = $food;
    $modifiers['wood']          = $wood;
    $modifiers['research']      = $research;
    $modifiers['population']    = $population;
    /*$modifiers['growthrate']    = $growthrate;
    $modifiers['offence']       = $offence;
    $modifiers['defence']       = $defence;
    $modifiers['losses']        = $losses;
    $modifiers['spell_damage']  = $spell_damage;
    $modifiers['spell_success'] = $spell_success;
    $modifiers['op_damage']     = $op_damage;
    $modifiers['op_success']    = $op_success;*/

    return $modifiers;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getFameBonuses(&$objUser)
{
    $iFame    = $objUser->get_stat(FAME);
    if ($iFame > 50000) { $iFame = 50000; }

    $population = 0;

    // POPULATION ==============================================================

    $population = $iFame / 100000;

    // OFFENCE =================================================================

    $offence = $iFame / 100000;
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
        $offence = 0;

    $modifiers['population'] = $population;
    $modifiers['offence']    = $offence;

    return $modifiers;
}

//==============================================================================
//                                                        Martel, April 03, 2006
//==============================================================================
function getSpellBonuses(&$objUser)
{
    include_once('inc/functions/races.php');
    $arrSpells = $objUser->get_spells();
    $strRace   = $objUser->get_stat(RACE);

    $income     = 0;
    $food       = 0;
    $population = 0;
    $growthrate = 1.05;
    $offence    = 0;
    $defence    = 0;
    $losses     = 0;

    // INCOME ==================================================================

    if ($arrSpells[INCOME] > 0)
        $income = 0.1;

    // FOOD ====================================================================

    if ($arrSpells[FOOD] > 0)
        $food = 0.1;

    // POPULATION ==============================================================

    if ($arrSpells[POPULATION] > 0)
        $population = 0.1;

    // GROWTH ==================================================================

    if ($arrSpells[GROWTH] > 0)
        $growthrate  = 1.15;

    if ($arrSpells[BROOD] > 0)
        $growthrate += 0.1;

    if ($arrSpells[PEST] > 0 && in_array($strRace, getRaces('Cursed')))
        $growthrate += 0.045;
    elseif ($arrSpells[PEST] > 9)
        $growthrate  = 0.955;

    // OFFENCE =================================================================

    if ($arrSpells[OFFENCE] > 0)
        $offence = 0.15;

    if ($arrSpells[ROAR] > 0)
        $offence += 0.1;
    elseif ($arrSpells[MORTALITY] > 0)
        $offence += 0.05;

    // DEFENCE =================================================================

    if ($arrSpells[DEFENCE] > 0)
        $defence = 0.1;

    if ($arrSpells[SALEM] > 0)
        $defence = 0.0;

    // LOSSES ==================================================================

    if ($arrSpells[FOUNTAIN] > 0)
        $losses = 0.4;


    $modifiers['income']     = $income;
    $modifiers['food']       = $food;
    $modifiers['population'] = $population;
    $modifiers['growthrate'] = $growthrate;
    $modifiers['offence']    = $offence;
    $modifiers['defence']    = $defence;
    $modifiers['losses']     = $losses;

    return $modifiers;
}

//==============================================================================
//                                                          Martel, May 31, 2006
//==============================================================================
function getBuildBonuses(&$objUser)
{
    $strRace        = $objUser->get_stat(RACE);
    $arrBuildings   = $objUser->get_builds();
    $iLand          = $arrBuildings[LAND];

    $offence = 0;
    $defence = 0;

    // OFFENCE =================================================================

    $iWeaponries = $arrBuildings[WEAPONRIES];
    $offence     = $iWeaponries / $iLand;

    if ($offence > 0.2)
        $offence = 0.2;

    if ($strRace == 'Dwarf')
        $offence *= 1.35;

    // DEFENCE =================================================================

    $iWalls      = $arrBuildings[WALLS];
    $defence     = $iWalls / $iLand;

    if ($defence > 0.2)
        $defence = 0.2;


    $modifiers['offence'] = $offence;
    $modifiers['defence'] = $defence;

    return $modifiers;
}

?>
