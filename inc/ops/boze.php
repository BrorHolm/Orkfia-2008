<?php

/*
BOZE - Steals incoming acres
Last update: Writing the thing
14-08-2007, by AI
*/

function get_op_type()
{
        return "aggressive";
}

function get_op_chance()
{
        return 50;
}

function get_op_name()
{
        return "Boze";
}

function do_op(&$objSrcUser, &$objTrgUser, $cnt, $thieves, $mod)
{
//     require('inc/functions/bonuses.php');

//     $alli        = $objTrgUser->get_stat(ALLIANCE);
//     $arrSci      = getSciences($alli);
//     $defsci      = $arrSci['def'];
    $percentage  = 0.1;
//     $percentage *= 1 - ($defsci / 200); //up to 50% land saved by defsci
    $acres       = $objTrgUser->get_build(LAND);
    $mingain     = $acres * .005; //.01; changed infinity-age 9
    $prefthieves = $acres * 5; //TODO, this is a completely random number
    $thiefmod    = min(1, $thieves / $prefthieves ); // the "you didn't send enough thieves" modifier

    $land_1      = $objTrgUser->get_build(LAND_T1);
    $land_2      = $objTrgUser->get_build(LAND_T2);
    $land_3      = $objTrgUser->get_build(LAND_T3);
    $land_4      = $objTrgUser->get_build(LAND_T4);

    $incoming    = $land_1 + $land_2 + $land_3 + $land_4;
    $totaltaken  = 0;

    for ($x = 1; $x <= $cnt; $x++)
    {
        //calc unmodded amount to take
        $totake = $incoming * $percentage;
        if ($mingain > $totake)
            $totake = $mingain;

        //mod it
        $taken = floor( $totake * $mod * $thiefmod );

        //make sure we don't take acres that aren't there
        if ($taken > $incoming)
            $taken = $incoming;

        //add and subtract and stuff
        $totaltaken += $taken;
        $incoming   -= $taken;

    }

    $toremove = $totaltaken; //keep this var for news purposes and, you know, giving them to the opper

    /* this is long, ugly code, it can probably be done in 8 lines or so, but I'm tired right now and can't think of how, TODO */
    if ($toremove > $land_1)
    {
        $toremove -= $land_1;
        $land_1 = 0;
    }
    else
    {
        $land_1 -= $toremove;
        $toremove = 0;
    }

    if ($toremove > $land_2)
    {
        $toremove -= $land_2;
        $land_2 = 0;
    }
    else
    {
        $land_2 -= $toremove;
        $toremove = 0;
    }
    if ($toremove > $land_3)
    {
        $toremove -= $land_3;
        $land_3 = 0;
    }
    else
    {
        $land_3 -= $toremove;
        $toremove = 0;
    }
    if ($toremove > $land_4)
    {
        $toremove -= $land_4;
        $land_4 = 0;
    }
    else
    {
        $land_4 -= $toremove;
        $toremove = 0;
    }

    //Now that we've (finally) calced what to do, remove the acres
    $objTrgUser->set_build(LAND_T1, $land_1);
    $objTrgUser->set_build(LAND_T2, $land_2);
    $objTrgUser->set_build(LAND_T3, $land_3);
    $objTrgUser->set_build(LAND_T4, $land_4);

    //give the opper his acres
    $in_in_4 = $objSrcUser->get_build(LAND_T4);
    $objSrcUser->set_build(LAND_T4, ($in_in_4 + $totaltaken) );

    // War effects
    include_once('inc/functions/war.php');
    $objSrcAlliance = $objSrcUser->get_alliance();
    if (checkWarBetween($objSrcAlliance, $objTrgUser->get_stat(ALLIANCE)))
    {
        // Update land counter in new war system           March 06, 2008 Martel
        $iNeeded = $objSrcAlliance->get_war('land_needed');
        $objSrcAlliance->set_war('land_needed', max(0, $iNeeded - $totaltaken));
    }

    //all the hard stuff should be done now, newsreports here we come...
    $result["fame"] = $totaltaken * 3;
    $result["text_screen"] = "Boze has reappropriated <span class=\"negative\">$totaltaken</span> incoming acres of the enemy.";
    $result["text_news"] = "<span class=\"negative\">$totaltaken</span> incoming acres have failed to arrive.";

    return $result;


}

?>
