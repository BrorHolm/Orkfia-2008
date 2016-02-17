<?php
//******************************************************************************
// attacks hitnrun.php                                     Martel, June 12, 2006
//******************************************************************************

function doAttack(&$objSrcUser, &$objTrgUser, $arrSentArmy)
{
    $srcKd = $objSrcUser->get_stat(KINGDOM);
    $trgKd = $objTrgUser->get_stat(KINGDOM);
    $strSrcRace   = $objSrcUser->get_stat(RACE);

    //==========================================================================
    // Hit'n'Run Attack: High defender losses, kills citizens
    //==========================================================================
    $trgCits  = $objTrgUser->get_pop(CITIZENS);
    $max_kill = round($trgCits * 0.05);

    // Max citizens to kill == citizens available
    if ($max_kill > $trgCits)
        $max_kill = $trgCits;

    if ($max_kill > 0)
    {
        $objTrgUser->set_pop(CITIZENS, ($trgCits - $max_kill));

        // Let's see if we killed the tribe
        include_once('inc/functions/generic.php');
        obj_test_for_kill($objTrgUser, $objSrcUser);
    }
    else
        $max_kill = 0;

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

    $arrResults['killed_citizens'] = $max_kill;

    return $arrResults;
}