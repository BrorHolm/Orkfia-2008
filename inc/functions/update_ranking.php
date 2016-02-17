<?php
//******************************************************************************
// functions update_ranking.php                            Martel, July 13, 2006
//
// Ranking functions, recoded for infinity orkfia
//******************************************************************************

//==============================================================================
//                                                         Martel, July 13, 2006
// Tribe Rankings are updated once each 15 minute period, or if forced
//==============================================================================
function doUpdateRankings(&$objUser, $strForceUpd = 'no')
{
    $iLastUpd = strtotime($objUser->get_rankings_personal(LAST_UPDATE));

    if ($strForceUpd == 'no')
    {
        // 60 seconds per minute * 15 minutes equals 900 seconds
        $round = 60 * 15;

        // Calculate timestamp of last even 1/4th hour
        $rounded_time = floor(time() / $round) * $round;

        // if last ranking update occured earlier than this 1/4 hour
        if ($iLastUpd < $rounded_time)
        {
            doUpdateTribe($objUser);
        }
    }
    elseif ($strForceUpd == 'yes')
    {
        doUpdateTribe($objUser);
    }
}

//==============================================================================
// Private Function!! Do not call this directly            Martel, July 13, 2006
//==============================================================================
function doUpdateTribe(&$objUser)
{
    // Get old rankings
    $arrRankingsPersonal = $objUser->get_rankings_personals();

    // Get current stats
    $arrStats            = $objUser->get_stats();
    $iAcres              = $objUser->get_build(LAND);
    $iHours              = $objUser->get_user_info(HOURS);
    $iStrength           = $objUser->get_strength();

    // Check to see if the tribe-info has changed
    $blnChanged = FALSE;
    if ($iAcres != $arrRankingsPersonal[LAND])
        $blnChanged = TRUE;
    elseif ($arrStats[FAME] != $arrRankingsPersonal[FAME])
        $blnChanged = TRUE;
    elseif ($arrStats[ALLIANCE] != $arrRankingsPersonal[ALLI_ID])
        $blnChanged = TRUE;
    elseif ($arrStats[TYPE] != $arrRankingsPersonal[PLAYER_TYPE])
        $blnChanged = TRUE;
    elseif ($arrStats[TRIBE] != $arrRankingsPersonal[TRIBE_NAME])
        $blnChanged = TRUE;
    elseif ($arrStats[RACE] != $arrRankingsPersonal[RACE])
        $blnChanged = TRUE;
    elseif ($iStrength != $arrRankingsPersonal[STRENGTH])
        $blnChanged = TRUE;
    elseif ($iHours != $arrRankingsPersonal[HOURS] && $arrRankingsPersonal[HOURS] != 0)
        $blnChanged = TRUE;

    // Dead or Reset Tribes get "hidden" rankings, paused show as protected
    if (($arrStats[KILLED] > 0 && $arrStats[KILLED] != 3) || $arrStats[RESET_OPTION] == 'yes')
    {
        $iAcres = STARTING_LAND;
        $arrStats[FAME] = 5000;
        $iHours = 0;
        $iStrength = 3000;
    }
    elseif ($objUser->isPaused())
    {
        $iHours = 0;
    }

    // Now do the actual updating
    if ($blnChanged)
    {
        $strRace       = $arrStats[RACE];
        $strPlayerType = $arrStats[TYPE];
        $strTribeName  = $arrStats[TRIBE];

        // Personal Rankings
        $arrNewRankingsPersonal = array
        (
            ALLI_ID => $arrStats[ALLIANCE],
            LAND => $iAcres,
            FAME => $arrStats[FAME],
            STRENGTH => $iStrength,
            HOURS => $iHours,
            RACE => $strRace,
            PLAYER_TYPE => $strPlayerType,
            TRIBE_NAME => $strTribeName,
            LAST_UPDATE => date(TIMESTAMP_FORMAT, time())

        );
        $objUser->set_rankings_personals($arrNewRankingsPersonal);

        // Update Alliance Rankings
        doUpdateAlliance($objUser);
    }
}

//==============================================================================
// Private Function!! Do not call this directly            Martel, July 13, 2006
//==============================================================================
function doUpdateAlliance(&$objUser)
{
    $objAlliance = $objUser->get_alliance();
    $objAlliance->do_update_ranking();
}

?>
