<?php
//******************************************************************************
// attacks raid.php                                        Martel, June 06, 2006
//******************************************************************************

function doAttack(&$objSrcUser, &$objTrgUser, $arrSentArmy)
{
    $srcKd          = $objSrcUser->get_stat(ALLIANCE);
    $trgKd          = $objTrgUser->get_stat(ALLIANCE);
    $strSrcRace     = $objSrcUser->get_stat(RACE);
    $iSrcLand       = $objSrcUser->get_build(LAND);
    $arrTrgBuilds   = $objTrgUser->get_builds();
    $gains          = pow(($arrTrgBuilds[LAND] / $iSrcLand), 2) + ($arrTrgBuilds[LAND] / ($iSrcLand * 3));
    $gains          = min ($gains,1);

    $quick_gains    = 0.01 * $gains;           // 1% Land Gain

    if ($strSrcRace == "Oleg Hai")
        $quick_gains = $quick_gains * 1.3;

    //==========================================================================
    // Calculate Acre Gains
    //==========================================================================
    include_once('inc/functions/build.php');
    $arrBuildVars = getBuildingVariables($objTrgUser->get_stat(RACE));
    $arrBuildVars = $arrBuildVars['variables'];
    $max_build    = $objTrgUser->get_number_build_types();
    $total_grab   = 0;

    for($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($quick_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab = $total_grab + $acres_won[$strVar];
    }

    // Explored
    $explore_gains  = 0.2;
    $acres_explored = round($total_grab * $explore_gains);
    $land           = $objSrcUser->get_build(LAND);
    $objSrcUser->set_build(LAND, ($land + $acres_explored));


    //==========================================================================
    // Calculate Citizens Killed
    //==========================================================================
    $trgCits        = $objTrgUser->get_pop(CITIZENS);

    $max_kill = $trgCits * 0.2;
    $trgRace  = $objTrgUser->get_stat(RACE);

    if ($max_kill < ($arrTrgBuilds[LAND] * 3) && $trgRace != "Dragon")
        $max_kill = $arrTrgBuilds[LAND] * 3;
    elseif ($max_kill < ($arrTrgBuilds[LAND] * 2) && $trgRace == "Dragon")
        $max_kill = $arrTrgBuilds[LAND] * 2;

    // War effects on max kill
    include_once("inc/functions/war.php");
    $modifier = war_alli($srcKd, $trgKd);
    if ($modifier == 2)
        $max_kill = $max_kill * 1.2;

    // Martel: small bug fixed, $arrTrgBuilds not $arrTrgBuild
    $wallsPercent = ($arrTrgBuilds[WALLS] * 100) / $arrTrgBuilds[LAND];
    if ($wallsPercent > 20)
        $wallsPercent = 20;

    // frost: age 18, each % walls shelters 2% citizen
    for ($y = 1; $y <= $wallsPercent; $y++)
        $max_kill *= 0.98;

    // Max citizens to kill == citizens available
    $max_kill = round($max_kill);
    if ($max_kill > $trgCits)
        $max_kill = $trgCits;

    if ($max_kill > 0)
    {
        $intCitizens_alive = $trgCits - $max_kill;
        $objTrgUser->set_pop(CITIZENS, $intCitizens_alive);
    }
    else
        $max_kill = 0;

    // Let's see if we killed the tribe
    include_once('inc/functions/generic.php');
    obj_test_for_kill($objTrgUser, $objSrcUser);

    //==========================================================================
    // Calculate Cash Gains and Fame
    //==========================================================================
    $trgMoney       = $objTrgUser->get_good(MONEY);

    $mod = 1;
    $stolen_crowns  = round($trgMoney * 0.04);

    if ($arrTrgBuilds[LAND] > $iSrcLand)
        $fame_win   = round($total_grab * 1.0);
    elseif ($arrTrgBuilds[LAND] < ($iSrcLand * 0.7))
    {
        $fame_win   = round(-$total_grab * 1.0);
        $mod        = ($arrTrgBuilds[LAND] * 1.3) / $iSrcLand;
    }
    else
        $fame_win   = round($total_grab * 1.2);

    if ($mod < 0.7)
        $mod = 0.7;

    $max_crowns     = round($arrTrgBuilds[LAND] * 500);

    $stolen_crowns  = min(round($stolen_crowns * $mod), round($max_crowns));

    $objTrgUser->set_good(MONEY, $trgMoney - $stolen_crowns);
    $money = $objSrcUser->get_good(MONEY);
    $objSrcUser->set_good(MONEY, $money + $stolen_crowns);

    //==========================================================================
    // Return time
    //==========================================================================
    $wait       = 4; // 4 hour attack time

    if ($strSrcRace == "Raven")
        $wait   = 1;
    elseif ($strSrcRace == "Mori Hai" || $strSrcRace == "Dragon")
        $wait   = 3;

    $landtime = 'land_t' . $wait;
    $objSrcUser->set_user_info(NEXT_ATTACK, $wait);

    //==========================================================================
    // Add land to incoming
    //==========================================================================
    if ($total_grab > 0)
    {
        $old_land = $objSrcUser->get_build($landtime);
        $objSrcUser->set_build($landtime, $total_grab + $old_land);
    }

    //==========================================================================
    // Calculate Fame Gains
    //==========================================================================

    // War effects
    $objSrcAlliance = $objSrcUser->get_alliance();
    require_once('inc/functions/war.php');
    if (checkWarBetween($objSrcAlliance, $trgKd))
    {
        // Update land counter in new war system           March 06, 2008 Martel
        $iNeeded = $objSrcAlliance->get_war('land_needed');
        $objSrcAlliance->set_war('land_needed', max(0, $iNeeded - $total_grab));

        // War effects on fame
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

    $arrResults['gained_acres']     = $total_grab;
    $arrResults['explored_acres']   = $acres_explored;
    $arrResults['gained_fame']      = $fame_win;
    $arrResults['gained_crowns']    = $stolen_crowns;
    $arrResults['killed_citizens']  = $max_kill;

    return $arrResults;
}

?>