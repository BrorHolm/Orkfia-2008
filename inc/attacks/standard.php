<?php
//******************************************************************************
// attacks standard.php                                    Martel, June 06, 2006
//******************************************************************************

function doAttack(&$objSrcUser, &$objTrgUser, $arrSentArmy)
{
    $srcId        = $objSrcUser->get_userid();
    $trgId        = $objTrgUser->get_userid();
    $srcKd        = $objSrcUser->get_stat(KINGDOM);
    $trgKd        = $objTrgUser->get_stat(KINGDOM);
    $arrTrgBuilds = $objTrgUser->get_builds();
    $iSrcLand     = $objSrcUser->get_build(LAND);

    //==========================================================================
    // Standard Attack: Acres and Fame
    //==========================================================================
    $gains          = pow(($arrTrgBuilds[LAND] / $iSrcLand ), 2) + ($arrTrgBuilds[LAND] / ($iSrcLand * 3));
    $gains          = min($gains, 1);
    $standard_gains = 0.115 * $gains;

    // Oleg Hai bonus
    $strSrcRace = $objSrcUser->get_stat(RACE);
    if ($strSrcRace == "Oleg Hai")
        $standard_gains *= 1.3;

    if (($standard_gains * $arrTrgBuilds[LAND]) > ($iSrcLand * 1.3 * $standard_gains))
        $standard_gains *= (($iSrcLand * 1.3) / $arrTrgBuilds[LAND]);

    if ($arrTrgBuilds[LAND] > ($iSrcLand * 3))
        $standard_gains /= 2;

    //==========================================================================
    // War effects on gains
    //==========================================================================
    include_once("inc/functions/war.php");
    $modifier = war_alli($srcKd, $trgKd);
    if ($modifier == 2)
        $standard_gains *= 1.2;

    $target = war_target($trgKd);
    if ($target != 0 && $modifier == 0)
        $standard_gains *= 0.95;

    //==========================================================================
    // Spell effects on gains
    //==========================================================================
    if ($objTrgUser->get_spell(FOREST) > 0)
        $standard_gains *= 0.8;

    if ($objSrcUser->get_spell(MORTALITY) > 0)
        $standard_gains += 0.01;

    //==========================================================================
    // Bottom feed penalty on gains
    //==========================================================================
    if ((($arrTrgBuilds[LAND] / $iSrcLand) < 0.8) && $arrTrgBuilds[LAND] < 1500)
    {
        $bottom_feeder_difference = $arrTrgBuilds[LAND] / $iSrcLand;
        $bottom_feeder_penalty    = (0.8 - $bottom_feeder_difference) / 10;
        $standard_gains          -= (5 * $bottom_feeder_penalty);
    }
    elseif ((($arrTrgBuilds[LAND] / $iSrcLand) < 0.8) && $arrTrgBuilds[LAND] > 1499)
    {
        $bottom_feeder_difference = $arrTrgBuilds[LAND] / $iSrcLand;
        $bottom_feeder_penalty    = (0.85 - $bottom_feeder_difference) / 10;
        $standard_gains          -= (5 * $bottom_feeder_penalty);
    }

    //==========================================================================
    // Minimum Gains limit
    //==========================================================================
    if ($standard_gains < 0.01)
        $standard_gains = 0.01;

    //==========================================================================
    // Calculate Acre Gains
    //==========================================================================
    include_once('inc/functions/build.php');
    $arrBuildVars = getBuildingVariables($objTrgUser->get_stat(RACE));
    $arrBuildVars = $arrBuildVars['variables'];
    $max_build    = $objTrgUser->get_number_build_types();
    $total_grab   = 0;

    // Built
    for ($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($standard_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab += $acres_won[$strVar];
    }

    // 1 hour
    for ($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $strVar             = $strVar."_t1";
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($standard_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab += $acres_won[$strVar];
    }

    // 2 hours
    for ($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $strVar             = $strVar."_t2";
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($standard_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab += $acres_won[$strVar];
    }

    // 3 hours
    for($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $strVar             = $strVar."_t3";
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($standard_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab += $acres_won[$strVar];
    }

    // 4 hours
    for($i = 1; $i <= $max_build; $i++)
    {
        $strVar             = trim($arrBuildVars[$i]);
        $strVar             = $strVar."_t4";
        $acres_won[$strVar] = round($arrTrgBuilds[$strVar]* ($standard_gains));
        $buildings          = $objTrgUser->get_build($strVar);
        $land               = $objTrgUser->get_build(LAND);
        $arrBuilds          = array ($strVar => $buildings - $acres_won[$strVar], LAND => $land - $acres_won[$strVar] );
        $objTrgUser->set_builds($arrBuilds);
        $total_grab += $acres_won[$strVar];
    }

    // Explored
    $explore_gains  = 0.2;
    $acres_explored = round($total_grab * $explore_gains);
    $land           = $objSrcUser->get_build(LAND);
    $objSrcUser->set_build(LAND, ($land + $acres_explored));

    //==========================================================================
    // Record
    //==========================================================================
    $fetch_record = mysql_query("Select * from records where id = 1");
    $fetch_record = @mysql_fetch_array($fetch_record);
    if ($total_grab > $fetch_record['grab'])
    {
        $id = $objSrcUser->get_userid();
        $update = mysql_query("Update records set grab = $total_grab, grab_id = $id where id = 1");
    }

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
    $fame_win     = max(0, $total_grab * 2);

    // War effects
    include_once('inc/functions/war.php');
    $objSrcAlliance = $objSrcUser->get_alliance();
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

    return $arrResults;
}

?>