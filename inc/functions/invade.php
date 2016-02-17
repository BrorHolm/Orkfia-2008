<?php
//******************************************************************************
// functions invade.php                                    Martel, June 03, 2006
//
// History:
// 15/04/2002 thalura - changes for next round
// 21/12/2003 natix   - changed some code to warn the player
//                      when he sends out more than he has
//******************************************************************************
include_once('inc/functions/bonuses.php');
include_once('inc/functions/races.php');

//==============================================================================
// See if army sent is available
//==============================================================================
function verifyArmyAvailable(&$objUser, $arrArmySent)
{
    $arrArmyHome = $objUser->get_armys_home();

    $maxed_out = 0;

    if ($arrArmySent[UNIT1] > $arrArmyHome[UNIT1])
        $maxed_out = 1;

    if ($arrArmySent[UNIT2] > $arrArmyHome[UNIT2])
        $maxed_out = 1;

    if ($arrArmySent[UNIT3] > $arrArmyHome[UNIT3])
        $maxed_out = 1;

    if ($arrArmySent[UNIT4] > $arrArmyHome[UNIT4])
        $maxed_out = 1;

    if ($arrArmySent[UNIT5] > $arrArmyHome[UNIT5])
        $maxed_out = 1;

    return $maxed_out;
}

//==========================================================================
// Frost: oleg check to see if sent mercs where trained in the same hour
//==========================================================================
function verifyArmyAvailableOleg(&$objUser, $arrArmySent)
{
    $mercsTrainedThisHour = $objUser->get_army_merc(MERC_T3);
    $maxed_out = 0;

    if ($mercsTrainedThisHour < $arrArmySent[UNIT4])
        $maxed_out = 1;

    return $maxed_out;
}

//==============================================================================
// 30% chance that an attack against eagles will auto-fail
//==============================================================================
function getEagleCheck()
{
    $eagle   = 0;
    $iRandom = rand(1, 10000);

    if ($iRandom < 3000)
        $eagle = 1;

    return $eagle;
}

//==============================================================================
// 25% chance that viking news are hidden
//==============================================================================
function getVikingCheck()
{
    $viking  = 0;
    $iRandom = rand(1, 10000);

    if ($iRandom < 2500)
        $viking = 1;

    return $viking;
}

//==============================================================================
// Check for Monitoring (Martel)
// Will check if monitoring is active, and if so we'll create tribe news for it
// Also, vikings will be able to hide from thieves thanks to their stealth attack
//==============================================================================
function getMonitoringCheck(&$objSrcUser)
{
    $arrSrcStats = $objSrcUser->get_stats();

    // find all monitoring ops that are affecting us currently
    $query   = "SELECT * FROM military_expeditions WHERE trg_id = $arrSrcStats[id] AND duration_hours > 0";
    $result  = mysql_query($query);
    $numrows = mysql_num_rows($result);
    if ($numrows > 0)
    {
        // get details to use in tribe news
        $stats['tribe_name'] = $arrSrcStats[TRIBE];
        $stats['alli_id']    = "(#" . $arrSrcStats[ALLIANCE] . ")";
        $orkTime             = date(TIMESTAMP_FORMAT);

        // Remove Monitoring Counter
        $objSrcUser->set_thievery(MONITOR, 0);
        while($row = mysql_fetch_array($result))
        {
            // create tribe news in thief's tribe
            mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$orkTime', '', 'monitoring', '$row[src_id]', '$arrSrcStats[id]', '1', " . quote_smart("Your thieves report that $stats[tribe_name] $stats[alli_id] just sent out their military.") . ", '')");

            // trigger thief's news flag
            $objSrcUser->set_user_info(LAST_NEWS, $orkTime);

            // delete thief's 'expedition' entry
            mysql_query("DELETE FROM military_expeditions WHERE exp_id='$row[exp_id]'");
        }
    }
}

//==============================================================================
//                                                         Martel, June 05, 2006
// History:
// Changed by Martel - September 06, 2005
//  - tribes can be reinfected if they already had pest
// Changed by AI     - 07/01/2007
//==============================================================================
function checkPestilence(&$objSrcUser, &$objTrgUser)
{
    // If the target has pestilence, the attacker will be infected for 12 hours
    $pestSrc = 'no';
    if ($objTrgUser->get_spell(PEST) > 0)
    {
        $objSrcUser->set_spell(PEST, 12);
        $pestSrc = 'yes';
    }

    // The target will not be reinfected by itself.
    $pestTrg = 'no';
    if ($objSrcUser->get_spell(PEST) > 0)
    {
        $objTrgUser->set_spell(PEST, 12);
        $pestTrg = 'yes';
    }

    $arrSpreadPest['attacker'] = $pestSrc;
    $arrSpreadPest['defender'] = $pestTrg;

    return $arrSpreadPest;
}

//==============================================================================
// Suicide Check
//==============================================================================
function getSuicideCheck(&$objUser, $arrArmySent)
{
    $iSuicideType   = 0;

    $arrUnitInfo    = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitDefence = $arrUnitInfo['defence'];
    $arrArmy        = $objUser->get_armys();

    $iDefSent = $arrArmySent[UNIT1] * $arrUnitDefence[2]
              + $arrArmySent[UNIT2] * $arrUnitDefence[3]
              + $arrArmySent[UNIT3] * $arrUnitDefence[4]
              + $arrArmySent[UNIT4] * $arrUnitDefence[5]
              + $arrArmySent[UNIT5] * $arrUnitDefence[6];

    $iDefHome = (($arrArmy[UNIT1] - $arrArmySent[UNIT1]) * $arrUnitDefence[2])
              + (($arrArmy[UNIT2] - $arrArmySent[UNIT2]) * $arrUnitDefence[3])
              + (($arrArmy[UNIT3] - $arrArmySent[UNIT3]) * $arrUnitDefence[4])
              + (($arrArmy[UNIT4] - $arrArmySent[UNIT4]) * $arrUnitDefence[5])
              + (($arrArmy[UNIT5] - $arrArmySent[UNIT5]) * $arrUnitDefence[6]);

    // If less then 25% of total defense left at home -> suicide
    $iDefTotal = $iDefSent + $iDefHome;
    if ($iDefTotal * 0.25 > $iDefHome)
        $iSuicideType = 1;

    return $iSuicideType;
}

//==============================================================================
//                                                         Martel, June 03, 2006
//==============================================================================
function getSentOffence(&$objUser, $arrArmySent)
{
    $arrUnitInfo    = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitOffence = $arrUnitInfo['offence'];

    // Raw offence
    $raw_offence    = $arrArmySent[UNIT1] * $arrUnitOffence[2]
                    + $arrArmySent[UNIT2] * $arrUnitOffence[3]
                    + $arrArmySent[UNIT3] * $arrUnitOffence[4]
                    + $arrArmySent[UNIT4] * $arrUnitOffence[5]
                    + $arrArmySent[UNIT5] * $arrUnitOffence[6];

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

    return $total;
}

//==============================================================================
// Returns losses in % for each attack type and race
//==============================================================================
// Changed strRace to strSrcRace, added strTrgRace to correctly calc losses
//  by viking (or canuck if we put them back in.
// Note that high elf + mortality losses might still be bugged, but we don't
//  have a userid here, so we can't check objSrcUser for mortality unless we
//  do evil globals stuff                                         - AI 07/10/06
//==============================================================================
function getLossesPct($strAttack, $strSrcRace, $strTrgRace = '')
{
    switch ($strAttack)
    {
        case 'standard':

            $dOffenceLost = 0.04;
            $dDefenceLost = 0.02;

        break;
        case 'raid':

            $dOffenceLost = 0.006;
            $dDefenceLost = 0.03;

        break;
        case 'barren':

            $dOffenceLost = 0.006;
            $dDefenceLost = 0.005;

        break;
        case 'hitnrun':

            $dOffenceLost = 0.03;
            $dDefenceLost = 0.01;

        break;
        case 'bc':

            $dOffenceLost = 0.04;
            $dDefenceLost = 0.03;

        break;
    }

    // Race Exceptions

    //==========================================================================
    // stuff for the attacker (Martel cleaned up October 15, 2007 )
    //  Attacking dragons get 20% lower damages while High Elves are immortal.
    //  This goes for attacking tribes, the rules for defending tribes are
    //  different.
    //==========================================================================
    if ($strSrcRace == 'Dragon')
        $dOffenceLost *= 0.2;
    elseif ($strSrcRace == 'High Elf')
        $dOffenceLost *= 0;
    elseif ($strSrcRace == 'Raven' && $strAttack == 'hitnrun')
        $dOffenceLost *= 0.5;
    //==========================================================================
    // stuff for the defender (Martel cleaned up October 15, 2007 )
    //  note elseif and Src here, if the attacker is a viking, the defender loses
    //  more
    //==========================================================================
    elseif ($strSrcRace == 'Viking')
        $dDefenceLost *= 2;

    if ($strTrgRace == 'Dragon')
        $dDefenceLost *= 0.2;
    // High elf immortality we do later, when we check for mortality

    // returns an array with the % modifier for both attacking and defending
    $arrPctLost['offence'] = $dOffenceLost;
    $arrPctLost['defence'] = $dDefenceLost;

    return $arrPctLost;
}

//==============================================================================
// Calculate Losses for Attacker
//==============================================================================
function getSrcLosses(&$objSrcUser, $arrArmySent, $killed, $strStrategy)
{
    $arrSrcArmys     = $objSrcUser->get_armys();
    $strSrcRace      = $objSrcUser->get_stat(RACE);
    $arrPctLost      = getLossesPct($strStrategy, $strSrcRace);
    $intSrcLostPercent = $arrPctLost['offence'];

    if ($killed < 1)
        $killed = 1;
    elseif ($killed > 1)
        $killed *= 1.1;

    $intSrcLostPercent /= $killed;

    if ($strSrcRace == "High Elf")
        $intSrcLostPercent = 0;
    // Fountain decreases attacker losses by 40%
    elseif ($objSrcUser->get_spell(FOUNTAIN) > 0)
        $intSrcLostPercent *= 0.6;

    //==========================================================================
    // Basics lost
    //==========================================================================

    $arrSrcArmyLost[UNIT1]     = round($arrArmySent[UNIT1] * $intSrcLostPercent);
    $intSrcArmySurvived[UNIT1] = ($arrArmySent[UNIT1] - $arrSrcArmyLost[UNIT1]);
    $intSrcArmyAlive[UNIT1]    = ($arrSrcArmys[UNIT1] - $arrSrcArmyLost[UNIT1]);

    //==========================================================================
    // Offspecs lost
    //==========================================================================

    $arrSrcArmyLost[UNIT2]     = round($arrArmySent[UNIT2] * $intSrcLostPercent);
    $intSrcArmySurvived[UNIT2] = ($arrArmySent[UNIT2] - $arrSrcArmyLost[UNIT2]);
    $intSrcArmyAlive[UNIT2]    = ($arrSrcArmys[UNIT2] - $arrSrcArmyLost[UNIT2]);

    //==========================================================================
    // Defspecs lost
    //==========================================================================

    $arrSrcArmyLost[UNIT3]     = round($arrArmySent[UNIT3] * $intSrcLostPercent);
    $intSrcArmySurvived[UNIT3] = ($arrArmySent[UNIT3] - $arrSrcArmyLost[UNIT3]);
    $intSrcArmyAlive[UNIT3]    = ($arrSrcArmys[UNIT3] - $arrSrcArmyLost[UNIT3]);

    //==========================================================================
    // Elites lost
    //==========================================================================

    $arrSrcArmyLost[UNIT4]     = round($arrArmySent[UNIT4] * $intSrcLostPercent);

    // Race Exceptions to Elite Losses on Attacking
    if ($strSrcRace == "Undead" && $strStrategy == "bc")
    {
        $arrSrcArmyLost[UNIT4] *= 0.5;
        $arrSrcArmyLost[UNIT4] = round($arrSrcArmyLost[UNIT4]);
    }
    elseif ($strSrcRace == "Undead")
    {
        $arrSrcArmyLost[UNIT4] = 0;
    }
    elseif ($strSrcRace == "Uruk Hai")
    {
        $arrSrcArmyLost[UNIT4] *= 0.8;
        $arrSrcArmyLost[UNIT4] = round($arrSrcArmyLost[UNIT4]);
    }
    elseif ($strSrcRace == "Raven")
    {
        // Martel: 76% of normal losses on Blackwings when attacking.
        $arrSrcArmyLost[UNIT4] *= 0.76;
        $arrSrcArmyLost[UNIT4] = round($arrSrcArmyLost[UNIT4]);
    }

    $intSrcArmySurvived[UNIT4] = ($arrArmySent[UNIT4] - $arrSrcArmyLost[UNIT4]);
    $intSrcArmyAlive[UNIT4]    = ($arrSrcArmys[UNIT4] - $arrSrcArmyLost[UNIT4]);

    //==========================================================================
    // Thieves lost
    //==========================================================================

    $arrSrcArmyLost[UNIT5]     = round($arrArmySent[UNIT5] * $intSrcLostPercent);
    $intSrcArmySurvived[UNIT5] = ($arrArmySent[UNIT5] - $arrSrcArmyLost[UNIT5]);
    $intSrcArmyAlive[UNIT5]    = ($arrSrcArmys[UNIT5] - $arrSrcArmyLost[UNIT5]);

    //==========================================================================
    // Update Armies
    //==========================================================================

    $arrArmy = array(
                        UNIT1 => $intSrcArmyAlive[UNIT1],
                        UNIT2 => $intSrcArmyAlive[UNIT2],
                        UNIT3 => $intSrcArmyAlive[UNIT3],
                        UNIT4 => $intSrcArmyAlive[UNIT4],
                        UNIT5 => $intSrcArmyAlive[UNIT5]
                    );
    $objSrcUser->set_armys($arrArmy);


    //==========================================================================
    // Update Oleg hai Mercenaries (army_mercs table)
    //==========================================================================
    if ($strSrcRace == "Oleg Hai")
    {
        $merc_t3 = $objSrcUser->get_army_merc(MERC_T3);
        $objSrcUser->set_army_merc(MERC_T3, ($merc_t3 - $arrArmySent[UNIT4]));
    }

    //==========================================================================
    // Update Military Returning
    //==========================================================================
    $wait = 4; // 4 updates by default

    if ($strSrcRace == "Raven")
        $wait = 1;
    elseif ($strSrcRace == "Mori Hai" || $strSrcRace == "Dragon")
        $wait = 3;

    $unit1 = "unit1_t".$wait;
    $unit2 = "unit2_t".$wait;
    $unit3 = "unit3_t".$wait;
    $unit4 = "unit4_t".$wait;
    $unit5 = "unit5_t".$wait;

    $milreturns = $objSrcUser->get_milreturns();
    $arrMilret  =   array (
                    $unit1 => $milreturns[$unit1] + $intSrcArmySurvived[UNIT1],
                    $unit2 => $milreturns[$unit2] + $intSrcArmySurvived[UNIT2],
                    $unit3 => $milreturns[$unit3] + $intSrcArmySurvived[UNIT3],
                    $unit4 => $milreturns[$unit4] + $intSrcArmySurvived[UNIT4],
                    $unit5 => $milreturns[$unit5] + $intSrcArmySurvived[UNIT5]
                    );
    $objSrcUser->set_milreturns($arrMilret);

    //==========================================================================
    // "abuse" the mercs table (meant for olegs only) to distinguish between
    // mori-thieves out on attacks and mori-thieves out on thievery ops.
    //==========================================================================
    if ($strSrcRace == "Mori Hai")
        $objSrcUser->set_army_merc(MERC_T2, $intSrcArmySurvived[UNIT5]);

    return $arrSrcArmyLost;
}

//==============================================================================
// Calculate Losses for Defender
//==============================================================================
function getTrgLosses(&$objTrgUser, &$objSrcUser, $killed, $strStrategy)
{
    $strSrcRace  = $objSrcUser->get_stat(RACE);
    $strTrgRace  = $objTrgUser->get_stat(RACE);
    $arrTrgArmys = $objTrgUser->get_armys();
    $arrArmyHome = $objTrgUser->get_armys_home();

    $arrPctLost        = getLossesPct($strStrategy, $strSrcRace, $strTrgRace);
    $intTrgLostPercent = $arrPctLost['defence'];

    if ($strStrategy == 'hitnrun')
        $intTrgLostPercent += (0.03 * $killed);

    // Fix that will prevent more losses than the target has military?
    // (Real correction must be to analyze the $killed variable. Ask Aldorin!)
    if ($intTrgLostPercent > 1)
        $intTrgLostPercent = 1;

    // All high elf units are immortal, so no damage unless Mortality is cast
    if ($strTrgRace == "High Elf" && $objSrcUser->get_spell(MORTALITY) < 1)
        $intTrgLostPercent = 0;

    // Fountain of Resurrection decreases defender losses by 40%
    if ($objTrgUser->get_spell(FOUNTAIN) > 0)
        $intTrgLostPercent *= 0.6;

    //==========================================================================
    // Basics lost
    //==========================================================================
    $arrUnitVars    = getUnitVariables($objTrgUser->get_stat(RACE));
    $arrUnitDefence = $arrUnitVars['defence'];

    if ($arrUnitDefence[2] != 0)
    {
        $arrTrgArmyLost[UNIT1]  = round($arrArmyHome[UNIT1] * $intTrgLostPercent);
        $intTrgArmyAlive[UNIT1] = $arrTrgArmys[UNIT1] - $arrTrgArmyLost[UNIT1];
    }
    else
    {
        $arrTrgArmyLost[UNIT1]  = 0;
        $intTrgArmyAlive[UNIT1] = $arrTrgArmys[UNIT1];
    }

    //==========================================================================
    // Offspecs lost
    //==========================================================================
    if ($arrUnitDefence[3] != 0)
    {
        $arrTrgArmyLost[UNIT2]  = round($arrArmyHome[UNIT2] * $intTrgLostPercent);
        $intTrgArmyAlive[UNIT2] = $arrTrgArmys[UNIT2] - $arrTrgArmyLost[UNIT2];
    }
    else
    {
        $arrTrgArmyLost[UNIT2]  = 0;
        $intTrgArmyAlive[UNIT2] = $arrTrgArmys[UNIT2];
    }

    //==========================================================================
    // Defspecs lost
    //==========================================================================
    if ($arrUnitDefence[4] != 0)
    {
        $arrTrgArmyLost[UNIT3]  = round($arrArmyHome[UNIT3] * $intTrgLostPercent);
        $intTrgArmyAlive[UNIT3] = $arrTrgArmys[UNIT3] - $arrTrgArmyLost[UNIT3];
    }
    else
    {
        $arrTrgArmyLost[UNIT3]  = 0;
        $intTrgArmyAlive[UNIT3] = $arrTrgArmys[UNIT3];
    }

    //==========================================================================
    // Elites lost
    //==========================================================================

    // Undead elites are immortal, but not to the Mortality spell
    if ($strTrgRace == "Undead" && $objSrcUser->get_spell(MORTALITY) < 1)
        $intTrgLostPercent = 0;
    // Uruk Hai elites take less dmg
    elseif ($strTrgRace == "Uruk Hai")
        $intTrgLostPercent = $intTrgLostPercent * 0.8;

    if ($arrUnitDefence[5] != 0)
    {
        $arrTrgArmyLost[UNIT4]  = round($arrArmyHome[UNIT4] * $intTrgLostPercent);
        $intTrgArmyAlive[UNIT4] = $arrTrgArmys[UNIT4] - $arrTrgArmyLost[UNIT4];
    }
    else
    {
        $arrTrgArmyLost[UNIT4]  = 0;
        $intTrgArmyAlive[UNIT4] = $arrTrgArmys[UNIT4];
    }

    //==========================================================================
    // Thieves lost
    //==========================================================================
    IF ($arrUnitDefence[6] != 0)
    {
        $arrTrgArmyLost[UNIT5]  = round($arrArmyHome[UNIT5] * $intTrgLostPercent);
        $intTrgArmyAlive[UNIT5] = $arrTrgArmys[UNIT5] - $arrTrgArmyLost[UNIT5];
    }
    ELSE
    {
        $arrTrgArmyLost[UNIT5]  = 0;
        $intTrgArmyAlive[UNIT5] = $arrTrgArmys[UNIT5];
    }

    //==========================================================================
    // Update Defender's Army
    //==========================================================================

    $arrArmy = array(
                        UNIT1 => $intTrgArmyAlive[UNIT1],
                        UNIT2 => $intTrgArmyAlive[UNIT2],
                        UNIT3 => $intTrgArmyAlive[UNIT3],
                        UNIT4 => $intTrgArmyAlive[UNIT4],
                        UNIT5 => $intTrgArmyAlive[UNIT5]
                    );

    $objTrgUser->set_armys($arrArmy);

    //==========================================================================
    // oleg hai merc losses for army_mercs table
    //==========================================================================
    if ($strTrgRace == "Oleg Hai")
    {
        $razeMerc = $arrTrgArmyLost[UNIT4];
        $currentMerc = $objTrgUser->get_army_mercs();
        if ($razeMerc > $currentMerc[MERC_T1]) {
            $razeMerc = $razeMerc - $currentMerc[MERC_T1];
            if ($razeMerc > $currentMerc[MERC_T2]) {
                $razeMerc = $razeMerc - $currentMerc[MERC_T2];
                if ($razeMerc > $currentMerc[MERC_T3]) {
                    $razeMerc = $razeMerc - $currentMerc[MERC_T3];
                    $merc_t3 = $objTrgUser->get_army_merc(MERC_T3);
                    $objTrgUser->set_army_merc(MERC_T3, $merc_t3-$razeMerc);
                }
                $merc_t2 = $objTrgUser->get_army_merc(MERC_T2);
                $objTrgUser->set_army_merc(MERC_T2, $merc_t2-$razeMerc);
            }
            $merc_t1 = $objTrgUser->get_army_merc(MERC_T1);
            $objTrgUser->set_army_merc(MERC_T1, $merc_t1-$razeMerc);
        }
        $merc_t0 = $objTrgUser->get_army_merc(MERC_T0);
        $objTrgUser->set_army_merc(MERC_T0, $merc_t0-$razeMerc);
    }

    //==========================================================================
    // Undead bonus: killed units of defender are raised as zombies (basics)
    // Total Killed                                        Martel, June 05, 2006
    //==========================================================================
    if ($objSrcUser->get_stat(RACE) == "Undead")
    {
        $iSumKilled = array_sum($arrTrgArmyLost);
        $unit1 = $objSrcUser->get_army(UNIT1);
        $objSrcUser->set_army(UNIT1, $unit1 + $iSumKilled);
    }

    return $arrTrgArmyLost;
}

function doRetreat(&$objSrcUser, &$objTrgUser, $arrArmySent, $eagle = 0, $viking)
{
    global  $ip;

    $strRace = $objSrcUser->get_stat(RACE);

    // "abuse" the mercs table (meant for olegs only) to distinguish between
    // mori-thieves out on attacks and mori-thieves out on thievery ops.
    if ($strRace == "Mori Hai" && $eagle == 0)
        $objSrcUser->set_army_merc(MERC_T2, $arrArmySent[UNIT5]);
    elseif ($strRace == "Mori Hai" && $eagle == 1)
        $objSrcUser->set_army_merc(MERC_T1, $arrArmySent[UNIT5]);

    $arrMilRet = $objSrcUser->get_milreturns();

    // Retreating armies are placed in the milreturn table
    if ($strRace == "Raven")
    {
        if ($eagle == 0) // raven regular fail
        {
            $army_unit1_1 = floor($arrArmySent[UNIT1] / 2);
            $army_unit1_2 = $arrArmySent[UNIT1] - $army_unit1_1;
            $army_unit2_1 = floor($arrArmySent[UNIT2] / 2);
            $army_unit2_2 = $arrArmySent[UNIT2] - $army_unit2_1;
            $army_unit3_1 = floor($arrArmySent[UNIT3] / 2);
            $army_unit3_2 = $arrArmySent[UNIT3] - $army_unit3_1;
            $army_unit4_1 = floor($arrArmySent[UNIT4] / 2);
            $army_unit4_2 = $arrArmySent[UNIT4] - $army_unit4_1;
            $army_unit5_1 = floor($arrArmySent[UNIT5] / 2);
            $army_unit5_2 = $arrArmySent[UNIT5] - $army_unit5_1;

            $arrReturns = array
            (
                UNIT1_T1 => $arrMilRet[UNIT1_T1] + $army_unit1_1,
                UNIT1_T2 => $arrMilRet[UNIT1_T2] + $army_unit1_2,
                UNIT2_T1 => $arrMilRet[UNIT2_T1] + $army_unit2_1,
                UNIT2_T2 => $arrMilRet[UNIT2_T2] + $army_unit2_2,
                UNIT3_T1 => $arrMilRet[UNIT3_T1] + $army_unit3_1,
                UNIT3_T2 => $arrMilRet[UNIT3_T2] + $army_unit3_2,
                UNIT4_T1 => $arrMilRet[UNIT4_T1] + $army_unit4_1,
                UNIT4_T2 => $arrMilRet[UNIT4_T2] + $army_unit4_2,
                UNIT5_T1 => $arrMilRet[UNIT5_T1] + $army_unit5_1,
                UNIT5_T2 => $arrMilRet[UNIT5_T2] + $army_unit5_2
            );

            $objSrcUser->set_milreturns($arrReturns);
            $objSrcUser->set_user_info(NEXT_ATTACK, 2);
        }
        else // eagle misbattle = 1hr return
        {
            $arrReturns = array
            (
                UNIT1_T1 => $arrMilRet[UNIT1_T1] + $arrArmySent[UNIT1],
                UNIT2_T1 => $arrMilRet[UNIT2_T1] + $arrArmySent[UNIT2],
                UNIT3_T1 => $arrMilRet[UNIT3_T1] + $arrArmySent[UNIT3],
                UNIT4_T1 => $arrMilRet[UNIT4_T1] + $arrArmySent[UNIT4],
                UNIT5_T1 => $arrMilRet[UNIT5_T1] + $arrArmySent[UNIT5]
            );
            $objSrcUser->set_milreturns($arrReturns);
            $objSrcUser->set_user_info(NEXT_ATTACK, 1);
        }
    }
    else
    {
        // Martel: Prevent negative mercs after update when retreating
        if ($strRace == "Oleg Hai")
        {
            $merc_t3 = $objSrcUser->get_army_merc(MERC_T3);
            $objSrcUser->set_army_merc(MERC_T3, $merc_t3 - $arrArmySent[UNIT4]);
        }

        if ($eagle == 0)
        {
            $arrReturns = array
            (
                UNIT1_T2 => $arrMilRet[UNIT1_T2] + $arrArmySent[UNIT1],
                UNIT2_T2 => $arrMilRet[UNIT2_T2] + $arrArmySent[UNIT2],
                UNIT3_T2 => $arrMilRet[UNIT3_T2] + $arrArmySent[UNIT3],
                UNIT4_T2 => $arrMilRet[UNIT4_T2] + $arrArmySent[UNIT4],
                UNIT5_T2 => $arrMilRet[UNIT5_T2] + $arrArmySent[UNIT5]
            );
            $objSrcUser->set_milreturns($arrReturns);
            $objSrcUser->set_user_info(NEXT_ATTACK, 2);
        }
        else // eagle misbattle = 1hr return
        {
            $arrReturns = array
            (
                UNIT1_T1 => $arrMilRet[UNIT1_T1] + $arrArmySent[UNIT1],
                UNIT2_T1 => $arrMilRet[UNIT2_T1] + $arrArmySent[UNIT2],
                UNIT3_T1 => $arrMilRet[UNIT3_T1] + $arrArmySent[UNIT3],
                UNIT4_T1 => $arrMilRet[UNIT4_T1] + $arrArmySent[UNIT4],
                UNIT5_T1 => $arrMilRet[UNIT5_T1] + $arrArmySent[UNIT5]
            );
            $objSrcUser->set_milreturns($arrReturns);
            $objSrcUser->set_user_info(NEXT_ATTACK, 1);
        }
    }

    $srcId = $objSrcUser->get_userid();
    $srcKd = $objSrcUser->get_stat(ALLIANCE);
    $trgId = $objTrgUser->get_userid();
    $trgKd = $objTrgUser->get_stat(ALLIANCE);
    $srcDisplay = $objSrcUser->get_stat(TRIBE) . " (#" . $srcKd . ")";
    $trgDisplay = $objTrgUser->get_stat(TRIBE) . " (#" . $trgKd . ")";

    // Failed Attack Report
    $strReport =
            '<div id="textMedium">' .
                '<h2>' . "Invade Report" . '</h2>';

    // Create news for failed attack
    if ($viking == 0)
    {
        $strTrgAlliance =
            $srcDisplay . " retreated before reaching " .
            "<span class=\"positive\">" . $trgDisplay. "</span>";

        // defender
        if ($eagle == 0)
        {
            $strTrgTribe = "We look on as $srcDisplay retreated before us.";
            $strTrgAlliance = "$srcDisplay has retreated before $trgDisplay";
            $strReport .=
                '<p>' .
                    'Your captains call the attack off as they see your army is not strong enough to win the battle.' .
                '</p>' .
                '<p>' .
                    'Your general report that your army will arrive home and be ready to attack in 2 hours.' .
                '</p>';
        }
        // (eagle) defender
        elseif ($eagle == 1)
        {
            $strTrgTribe =  "$srcDisplay has tried to find our tribe, but we " .
                            "managed to elude them!";
            $strTrgAlliance = "$srcDisplay has tried to find $trgDisplay, " .
                              "but the Eagles managed to elude them!";
            $strReport .=
                '<p>' .
                    'After a long and tiresome search your army has given up.' .
                '</p>' .
                '<p>' .
                    'Unable to find the Eagles they are returning home where they will be ready to fight in one hour.' .
                '</p>';
        }
    }
    else
    {
        $strTrgAlliance = "An unidentified tribe of vikings retreated before " .
                          "reaching <span class=\"positive\">$trgDisplay</span>";
        $strTrgTribe    = "We look on as an unidentified tribe of vikings " .
                          "retreated before us.";
        $strReport .=
            '<p>' .
                'Your captains call the attack off as they see your army is not strong enough to win the battle.' .
            '</p>' .
            '<p>' .
                'Your general report that your army will arrive home and be ready to attack in 2 hours.' .
            '</p>' .
            '<p>' .
                'We got lucky, our location won\'t end up in the news.' .
            '</p>';

        $trgKd = '';
        $srcKd = '';
    }

    // Failed Attack Report
    $strReport .=
            '<p>' .
                '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            '</p>' .
        '</div>';
    echo $strReport;

    $query  = "INSERT INTO news VALUES ('', NOW(), '$ip', 'invade', $trgId, $srcId, 1, " .
              quote_smart($strTrgTribe) . ", " . quote_smart($strTrgAlliance) .
              ",$trgKd,$srcKd, 1)";
    $res    = mysql_query($query);
    $objTrgUser->set_user_info(LAST_NEWS, date(intval(TIMESTAMP_FORMAT)));

    include_game_down();
    exit;
}

?>
