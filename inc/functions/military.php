<?php
//******************************************************************************
// functions military.php                                   Martel, May 26, 2006
//
// Description: All offence / defence calculations in new functions to take
//              advantage of user objects and remove old global spiderweb-code.
//******************************************************************************
include_once('inc/functions/bonuses.php');
include_once('inc/functions/races.php');

//==============================================================================
//                                                          Martel, May 26, 2006
//==============================================================================
function getArmyOffence(&$objUser)
{
    $arrUnitInfo    = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitOffence = $arrUnitInfo['offence'];

    // Raw offence
    $arrArmy        = $objUser->get_armys();

    $raw_offence    = $arrArmy[UNIT1] * $arrUnitOffence[2]
                    + $arrArmy[UNIT2] * $arrUnitOffence[3]
                    + $arrArmy[UNIT3] * $arrUnitOffence[4]
                    + $arrArmy[UNIT4] * $arrUnitOffence[5]
                    + $arrArmy[UNIT5] * $arrUnitOffence[6];

    // Bonus From Weaponries
    $arrBuildBonus  = getBuildBonuses($objUser);
    $weapon_bonus   = floor($raw_offence * $arrBuildBonus['offence']);

    // Bonus From Science
    $arrSci         = getSciences($objUser->get_stat(ALLIANCE));
    $research_bonus = floor($raw_offence * $arrSci['war']);

    // Bonus From Spells
    $arrSpellBonus  = getSpellBonuses($objUser);
    $spell_bonus    = floor($raw_offence * $arrSpellBonus['offence']);

    // Bonus From Fame                                Martel, September 25, 2007
    $arrFameBonus   = getFameBonuses($objUser);
    $fame_bonus     = floor($raw_offence * $arrFameBonus['offence']);

    // Total
    $total  = $raw_offence
            + $weapon_bonus
            + $research_bonus
            + $spell_bonus
            + $fame_bonus;

    // Total Out
    $arrArmyOut      = $objUser->get_armys_out();
    $raw_offence_out = $arrArmyOut[UNIT1] * $arrUnitOffence[2]
                     + $arrArmyOut[UNIT2] * $arrUnitOffence[3]
                     + $arrArmyOut[UNIT3] * $arrUnitOffence[4]
                     + $arrArmyOut[UNIT4] * $arrUnitOffence[5]
                     + $arrArmyOut[UNIT5] * $arrUnitOffence[6];
    $out_spell      = floor($raw_offence_out * $arrSpellBonus['offence']);
    $out_research   = floor($raw_offence_out * $arrSci['war']);
    $out_weaponries = floor($raw_offence_out * $arrBuildBonus['offence']);
    $out_fame        = floor($raw_offence_out * $arrFameBonus['offence']);
    $total_out       = $raw_offence_out
                     + $out_spell
                     + $out_research
                     + $out_weaponries
                     + $out_fame;


    $offence['raw']            = $raw_offence;
    $offence['building_bonus'] = $weapon_bonus;
    $offence['research_bonus'] = $research_bonus;
    $offence['spell_bonus']    = $spell_bonus;
    $offence['fame_bonus']     = $fame_bonus;
    $offence['total']          = $total;
    $offence['total_out']      = $total_out;

    return $offence;
}

//==============================================================================
//                                                          Martel, May 27, 2006
//==============================================================================
function getArmyDefence(&$objUser, $attack_type = '')
{
    $arrUnitInfo    = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitDefence = $arrUnitInfo['defence'];

    // Raw deffence
    $arrArmy        = $objUser->get_armys();
    $raw_defence    = $arrArmy[UNIT1] * $arrUnitDefence[2]
                    + $arrArmy[UNIT2] * $arrUnitDefence[3]
                    + $arrArmy[UNIT3] * $arrUnitDefence[4]
                    + $arrArmy[UNIT4] * $arrUnitDefence[5]
                    + $arrArmy[UNIT5] * $arrUnitDefence[6];

    // Bonus From Walls
    $arrBuildBonus  = getBuildBonuses($objUser);
    $wall_bonus     = floor($raw_defence * $arrBuildBonus['defence']);

    // Bonus From Science
    $arrSci         = getSciences($objUser->get_stat(ALLIANCE));
    $research_bonus = floor($raw_defence * $arrSci['def']);

    // Bonus From Spells
    $arrSpellBonus  = getSpellBonuses($objUser);
    $spell_bonus    = floor($raw_defence * $arrSpellBonus['defence']);

    // Total
    $total  = $raw_defence
            + $wall_bonus
            + $research_bonus
            + $spell_bonus;

    // Total Home
    $arrArmyHome      = $objUser->get_armys_home();
    $raw_defence_home = $arrArmyHome[UNIT1] * $arrUnitDefence[2]
                      + $arrArmyHome[UNIT2] * $arrUnitDefence[3]
                      + $arrArmyHome[UNIT3] * $arrUnitDefence[4]
                      + $arrArmyHome[UNIT4] * $arrUnitDefence[5]
                      + $arrArmyHome[UNIT5] * $arrUnitDefence[6];
    // this'll add 15% to raw, fixed now - AI 16/11/06
    //if ($attack_type == ATTACK_RAID)
    //    $raw_defence_home *= 1.15;
    $home_raid       = 0;
    if($attack_type == ATTACK_RAID)
        $home_raid = floor($raw_defence_home * .15);
    $home_spell      = floor($raw_defence_home * $arrSpellBonus['defence']);
    $home_research   = floor($raw_defence_home * $arrSci['def']);
    $home_walls      = floor($raw_defence_home * $arrBuildBonus['defence']);
    $total_home     = $raw_defence_home
                    + $home_spell
                    + $home_research
                    + $home_walls
                    + $home_raid;



    $defence['raw']            = $raw_defence;
    $defence['building_bonus'] = $wall_bonus;
    $defence['research_bonus'] = $research_bonus;
    $defence['spell_bonus']    = $spell_bonus;
    $defence['total']          = $total;
    $defence['total_home']     = $total_home;

    return $defence;
}

?>
