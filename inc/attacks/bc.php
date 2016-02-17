<?php
//******************************************************************************
// attacks bc.php                                          Martel, June 12, 2006
//******************************************************************************
include_once("inc/functions/war.php");

function doAttack(&$objSrcUser, &$objTrgUser, $arrSentArmy)
{
    $srcKd        = $objSrcUser->get_stat(KINGDOM);
    $trgKd        = $objTrgUser->get_stat(KINGDOM);
    $arrTrgBuilds = $objTrgUser->get_builds();
    $iSrcLand     = $objSrcUser->get_build(LAND);
    $strSrcRace   = $objSrcUser->get_stat(RACE);
    $arrTrgArmys  = $objTrgUser->get_armys();
    $arrTrgMilRet = $objTrgUser->get_milreturns();

    //==========================================================================
    // Blasphemy Crusade Attack: Destroy T/M Buildings and gain Fame
    //==========================================================================
    $destroyed = 0.04;

    $modifier = war_alli($srcKd, $trgKd);
    if ($modifier == 2)
        $destroyed *= 1.5;
    elseif ($modifier == 0 && war_target($trgKd) != 0)
        $destroyed *= 0.95;

    // Demolish x % Guilds
    $iDamaged_Guilds    = $arrTrgBuilds[GUILDS];
    if ($iDamaged_Guilds > ($arrTrgBuilds[LAND] * $destroyed))
        $iDamaged_Guilds = round($arrTrgBuilds[LAND] * $destroyed);

    // Demolish x % Academies
    $iDamaged_Academies = $arrTrgBuilds[ACADEMIES];
    if ($iDamaged_Academies > ($arrTrgBuilds[LAND] * $destroyed))
        $iDamaged_Academies = round($arrTrgBuilds[LAND] * $destroyed);

    // Demolish x % Hideouts
    $iDamaged_Hideouts  = $arrTrgBuilds[HIDEOUTS];
    if ($iDamaged_Hideouts > ($arrTrgBuilds[LAND] * $destroyed))
        $iDamaged_Hideouts = round($arrTrgBuilds[LAND] * $destroyed);

    // Kill x % Thieves
    $iThieves_killed = round(($arrTrgArmys[UNIT5]
                     - ($arrTrgMilRet[UNIT5_T1]
                       + $arrTrgMilRet[UNIT5_T2]
                       + $arrTrgMilRet[UNIT5_T3]
                       + $arrTrgMilRet[UNIT5_T4]))
                     * ($destroyed * 1.25));

    // Total # Buildings destroyed (to calc fame)
    $iDamaged_Total = $iDamaged_Academies
                    + $iDamaged_Guilds
                    + $iDamaged_Hideouts;

    // Ravens do 1/3 Damage
    if ($strSrcRace == "Raven")
    {
        $iThieves_killed    = round(0.333 * $iThieves_killed);
        $iDamaged_Total     = round(0.333 * $iDamaged_Total);
        $iDamaged_Academies = round(0.333 * $iDamaged_Academies);
        $iDamaged_Guilds    = round(0.333 * $iDamaged_Guilds);
        $iDamaged_Hideouts  = round(0.333 * $iDamaged_Hideouts);
    }

    $arrBuilds = array(
        GUILDS => $arrTrgBuilds[GUILDS] - $iDamaged_Guilds,
        HIDEOUTS => $arrTrgBuilds[HIDEOUTS] - $iDamaged_Hideouts,
        ACADEMIES => $arrTrgBuilds[ACADEMIES] - $iDamaged_Academies,
        LAND => $arrTrgBuilds[LAND] - $iDamaged_Total,
        LAND_T2 => $arrTrgBuilds[LAND_T2] + $iDamaged_Total);

    // Save new stats to DB
    $objTrgUser->set_builds($arrBuilds);
    $objTrgUser->set_army(UNIT5, ($arrTrgArmys[UNIT5] - $iThieves_killed));

    //==========================================================================
    // Return time
    //==========================================================================
    $wait     = 6;
    if ($strSrcRace == "Raven")
        $wait = 1;
    elseif ($strSrcRace == "Mori Hai" || $strSrcRace == "Dragon")
        $wait = 5;
    elseif ($strSrcRace == "Viking")
        $wait = 4;

    $objSrcUser->set_user_info(NEXT_ATTACK, $wait);

    //==========================================================================
    // Calculate Fame Gains
    //==========================================================================

    $fame_win = 0;

    // Fame only if target and source is in war
    if ($modifier == 2)
    {
        $ratio = min(1, $arrTrgBuilds[LAND] / $iSrcLand);
        $fame_win = round($ratio * $iDamaged_Total);

        // fame bonus from being in war
        $fame_win *= 1.2;
    }

    // Cannot take more fame than what the target has
    $fame_win     = round($fame_win);
    $target_fame  = $objTrgUser->get_stat(FAME);
    if ($fame_win > $target_fame)
        $fame_win = $target_fame;

    $fame1 = $objSrcUser->get_stat(FAME) + $fame_win;
    $fame2 = $target_fame - $fame_win;
    $objSrcUser->set_stat(FAME, $fame1);
    $objTrgUser->set_stat(FAME, $fame2);

    //==========================================================================

    $arrResults['gained_fame']       = $fame_win;
    $arrResults['killed_thieves']    = $iThieves_killed;
    $arrResults['damaged_academies'] = $iDamaged_Academies;
    $arrResults['damaged_guilds']    = $iDamaged_Guilds;
    $arrResults['damaged_hideouts']  = $iDamaged_Hideouts;
    $arrResults['damaged_total']     = $iDamaged_Total;

    return $arrResults;
}