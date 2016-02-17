<?php
//******************************************************************************
// attacks barren.php                                      Martel, June 06, 2006
//******************************************************************************

function doAttack(&$objSrcUser, &$objTrgUser, $arrSentArmy)
{
    $srcId    = $objSrcUser->get_userid();
    $trgId    = $objTrgUser->get_userid();
    $srcKd    = $objSrcUser->get_stat(ALLIANCE);
    $trgKd    = $objTrgUser->get_stat(ALLIANCE);
    $iSrcLand = $objSrcUser->get_build(LAND);
    $iTrgLand = $objTrgUser->get_build(LAND);

    //==========================================================================
    // Barren Attack: Barren Acres and Fame
    //==========================================================================
    $gains          = pow(($iTrgLand / $iSrcLand), 2) + ($iTrgLand / ($iSrcLand * 3));
    $gains          = min($gains, 1);
    $barren_gains   = 0.135 * $gains;

    // Oleg Hai bonus
    $strSrcRace   = $objSrcUser->get_stat(RACE);
    if ($strSrcRace == "Oleg Hai")
    {
        $barren_gains =  $barren_gains *1.3;
    }

    //==========================================================================
    // Barren Grab Attack
    //==========================================================================
    $total_grab   = 0;
    $max_grab     = round($iTrgLand * $barren_gains);

    // Decide how many barren acres to take
    $intTrgBarren = $objTrgUser->get_barren();
    if ($max_grab <= 0)
        $total_grab = 0;
    elseif ($max_grab < $intTrgBarren)
        $total_grab = $max_grab;
    else
        $total_grab = $intTrgBarren;

    // "Bottom feeding" penalties
    if (($iTrgLand / $iSrcLand) < 0.75)
    {
        $total_grab = round($total_grab / 2);
        if (($iTrgLand / $iSrcLand) < 0.5)
        {
            $total_grab = round($total_grab / 2);
        }
    }

    // "Top feeding" penalties
    if (($iTrgLand / $iSrcLand) > 3)
    {
        $total_grab = round($total_grab / 2);
        if (($iTrgLand / $iSrcLand) > 5)
        {
            $total_grab = round($total_grab / 2);
        }
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
    // Update Target Land
    //==========================================================================
    $iNewLand = $iTrgLand - $total_grab;
    $objTrgUser->set_build(LAND, $iNewLand);

    //==========================================================================
    // Add land to incoming
    //==========================================================================
    if ($total_grab > 0)
    {
        $old_land = $objSrcUser->get_build($landtime);
        $objSrcUser->set_build($landtime, $total_grab + $old_land);
    }

    // Explored
    $explore_gains  = 0.2;
    $acres_explored = round($total_grab * $explore_gains);
    $land           = $objSrcUser->get_build(LAND);
    $objSrcUser->set_build(LAND, ($land + $acres_explored));

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

    $arrResults['gained_acres'] = $total_grab;
    $arrResults['gained_fame']  = $fame_win;

    return $arrResults;
}

?>