<?php
//******************************************************************************
// Functions update.php                                           Author: ORKFiA
//
// Information:
//
// Call check_to_update(userid) whenever a person logs in or someone performs an
// action on them
// to call this just put in  check_to_update(players id); you can use it in a
// loop example check_to_update($i);  and have $i loop from X-Y whatnot. or you
// can call it on its own.
//
// Change History:
//
// Skathen 26-06-2002
// Implementation of a code based updater as the cronjob has failed on me for
// the last time. the last time the user was updated (date day, and the hour of
// that day) are stored in the user table. from there, they are extracted and
// tested against the current server time. From this we obtain how many updates
// they need and pass that value through instead of updates_owed. a simple
// transfer that will hopefully work.
//
// Damadm00 01-03-2004
// Fixed the 29 feb bug... it needs a fix @ 28-02-2100 again :)
//
// Martel April 03, 2006
// Split the production array into three: bonuses, population, production.
// Recoded everything to also take advantage of objects. Gotland assisted
//
// Martel: Refactoring, major functions in separate files    - February 27, 2008
//******************************************************************************
include_once("inc/functions/news.php");
include_once("inc/functions/ops.php");
include_once("inc/functions/spells.php");

function check_to_update($user)
{
    //==========================================================================
    //                                                     Martel, July 09, 2006
    // Use global src object if the source == the tribe triggering this update
    // (self update)
    //==========================================================================
    $objSrcUser = &$GLOBALS["objSrcUser"];
    if ($user != $objSrcUser->get_userid())
        $objUser    = new clsUser($user);
    else
        $objUser    = &$objSrcUser;

    //==========================================================================
    // Calculate updates owed
    //==========================================================================
    $Last_update_hour = $objUser->get_user_info(LAST_UPDATE_HOUR);
    $Last_update_day  = $objUser->get_user_info(LAST_UPDATE_DAY);
//     $iTribeTick = $objUser->get_user_info(LAST_UPDATE_TICK);

    $day    = date("d");
    $hour   = date("H");
    $month  = date("m");
    $year   = date("y");
    $lmonth = $month - 1;

    if ($day < $Last_update_day)
    {
        if ($lmonth == 0)
        {
            $lmonth = 12;
        }

        if ($lmonth == 1 || $lmonth == 3 || $lmonth == 5 || $lmonth == 7 || $lmonth == 8 || $lmonth == 10 || $lmonth == 12)
        {
            $day = $day + 31;
        }
        elseif ($lmonth == 2)
        {
            if (($year % 4) == 0 )
            {
                $day = $day +29;
            }
            else
            {
                $day = $day + 28;
            }
        }
        elseif ($lmonth == 4 ||$lmonth  == 6 ||$lmonth == 9 ||$lmonth == 11)
        {
            $day = $day + 30;
        }
    }

    $iUpdatesOwed = (($day - $Last_update_day) * 24) + ($hour - $Last_update_hour);
    if ($iUpdatesOwed > 36)
    {
        $iUpdatesOwed = 36;
    }

    $objUser->set_user_info(LAST_UPDATE_DAY, date('d'));
    $objUser->set_user_info(LAST_UPDATE_HOUR, date('H'));
//     $objUser->set_user_info(LAST_UPDATE_TICK, $iGameTick);

    // Preparing for new method to count owed updates
    include_once('inc/classes/clsGame.php');
    $objGame    = new clsGame();
//     $iGameTick = $objGame->get_game_time(HOUR_COUNTER);
//     $iUpdatesOwed = floor($iGameTick - $iTribeTick);

    //==========================================================================
    // FORGET UPDATES - setting updatesOwed to 0 is permanent and will be saved
    // Until the new update system is implemented this is the only feasible way.
    //==========================================================================

    if ($objGame->get_game_switch(GLOBAL_PAUSE) == ON)
        $iUpdatesOwed = 0;

    // Tribes who are either killed, waiting to be reset or paused
    $strReset  = $objUser->get_stat(RESET_OPTION);
    $blnKilled = $objUser->get_stat(KILLED);
    $iPaused   = $objUser->get_user_info(PAUSE_ACCOUNT);
    if($strReset == 'yes' || $blnKilled == 1)
    {
        $iUpdatesOwed = 0;
    }
    elseif ($iPaused > 0)
    {
        // Counter to see how long tribe has left in "forced" protection
        // Expl: only after these updates a tribe can choose to un-pause

        $iPaused -= $iUpdatesOwed;
        if ($iPaused <= 1)
        {
            $iPaused = 1;
        }

        // Save the last update info
        $objUser->set_user_info(PAUSE_ACCOUNT, $iPaused);
    }

    //==========================================================================
    // Time to hand out updates...
    //==========================================================================
    if ($iUpdatesOwed > 0)
    {
        // 6 Week limit for Infinity ORKFiA                Martel, July 09, 2006
        $iHours = $objUser->get_user_info(HOURS);
        $objRace = $objUser->get_race();
        $intLifespan = $objRace->getLifespan();

        // M: Perform near-death updates until the tribe is dead
        if (($iHours + $iUpdatesOwed) > $intLifespan)
        {
            include_once('inc/functions/tribe.php');
            $blnReturn = FALSE;

            // Updates that should be used to determine chance of death
            $iMaxAfter100Owed = $iHours + $iUpdatesOwed - $intLifespan + $iUpdatesOwed;
            $iUpdatesAfter100Owed = min($iUpdatesOwed, $iMaxAfter100Owed);

            // Iterate through each update to see if it's time to die
            for ($i = 1; $i <= $iUpdatesAfter100Owed; $i++)
            {
                // Correct hour of news event
                $event = $iUpdatesAfter100Owed - $i;
                $event_time = date(TIMESTAMP_FORMAT, strtotime("-$event hours"));

                $iRand = rand(1, max(1, ( 24 - $iMaxAfter100Owed )));
                if ($iRand == 1)
                {
                    // Give remaining updates to tribe
                    $iUpdatesOwedBeforeDeath = $i
                                             + ( $iUpdatesOwed
                                               - $iUpdatesAfter100Owed);
                    call_update_script($iUpdatesOwedBeforeDeath, $objUser);

                    // For use in Alliance News
                    $iRulerAge = getRulerAge($objUser);
                    $strTribe  = $objUser->get_stat(TRIBE);

                    // Code for death due to age
                    $objUser->set_stat(KILLED, 1);
                    obj_kill_user($objUser);

                    // Alliance News
                    $strAlliNews =
                        "<b class=\"negative\">The ruler of " . $strTribe .
                        " has died at an age of " . $iRulerAge . ".</b>";

                    $iShowAlli = $objUser->get_stat(ALLIANCE);

                    // Tribe News
                    $strTribeNews =
                        "The death of the old and tired tribe ruler comes as " .
                        "no surprise. Most citizens have left the lands, and " .
                        "the leaderless military rampantly plundered what " .
                        "they could. The citizens remaining wish to " .
                        "contribute goods they hid to support your cause." .
                        "<br /><br />" .
                        $strAlliNews .
                        "<br /><br />" .
                        "Below are the news that your previous tribe recieved:";

                    $blnReturn = TRUE;
                }
                else
                {
                    $strAlliNews = '';
                    $iShowAlli = '';

                    $strTribeNews =
                        'Leader, your age is becoming a problem! The citizens ' .
                        'are preparing for the worst and your general openly ' .
                        'disobey you.';
                }

                $strSQL =
                    'INSERT INTO ' . "news" .
                           ' SET ' . "time" . " = '$event_time', " .
                                     "type" . " = 'death', " .
                                    "duser" . " = $user, " .
                                   "result" . " = 1, " .
                                     "text" . " = '$strTribeNews', " .
                             "kingdom_text" . " = '$strAlliNews', " .
                                 "kingdoma" . " = $iShowAlli";
                mysql_query($strSQL);

                if ($blnReturn)
                    break;
            }
        }

        // Vacation mode 48 hours (+6 hours enter-phase = 54 hours)
        if ($iPaused > 0 && $objUser->get_stat(KILLED) == 0)
        {
            // (Martel: paused accs will still age - "fair ranking" fix)
            $iNewHours = $objUser->get_user_info(HOURS) + $iUpdatesOwed;
            $objUser->set_user_info(HOURS, $iNewHours);

            // Only "forget" updates while in protection, not during enter-phase
            //                                  Bug-fix February 27, 2008 Martel
            if ($iPaused <= 48)
                $iUpdatesOwed = 0;
        }

        //======================================================================
        // Update Tribe
        // Martel: Moving this to a separate file            - February 27, 2008
        //======================================================================
        require_once('inc/functions/update_script.php');
        generate_updates($objUser, $iUpdatesOwed);
    }
}

// M: Old "legacy" function just in case - DO NOT USE
function call_update_script($number_updates, &$objUser)
{
    require_once('inc/functions/update_script.php');
    generate_updates($objUser, $number_updates);
}

?>
