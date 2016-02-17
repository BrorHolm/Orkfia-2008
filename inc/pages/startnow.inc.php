<?php
////////////////////////////////////////////////////////////////////////////////
//  This page allows a user to skip the PROTECTION_HOURS of protection and start
// his tribe instantly.
// AI 16/07/2007
////////////////////////////////////////////////////////////////////////////////

require_once('inc/functions/build.php');

function include_startnow_text(){
    $objUser = &$GLOBALS['objSrcUser'];
    $objRace = $objUser->get_race();
    $strRace = $objRace->getRaceName();
    $arrBuilds  = $objUser->get_builds();
    $arrGoods   = $objUser->get_goods();
    $intBarren  = getBarrenObj($objUser);
    $arrUnitDefences = $objRace->getUnitDefences();
    $arrUnitCosts    = $objRace->getUnitCosts();
    $arrUnitNames    = $objRace->getUnitNames();
    $arrUnitVariables= $objRace->getUnitVariables();
    $iHours   = $objUser->get_user_info(HOURS);
    $iAlli   = $objUser->get_rankings_personal(ALLI_ID);

    if ($iHours != 0)
    {
        echo "You no longer have " . PROTECTION_HOURS . " months remaining, " .
            "the ritual can no longer be rushed.";
        include_game_down();
        exit;
    }
    elseif ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
    {
        echo "No rushing in classic. But while you're here, have you considered <a href=\"main.php?cat=game&amp;page=sponsors\">sponsoring the game</a> yet?";
        include_game_down();
        exit;
    }
    elseif ($iAlli == 10)
    {
        echo "You're in the Graveyard, please find a recruiting alliance and " .
        "then go to Preferences -> Request Merge to move into a new " .
        "alliance. If there is none, please ask a staff member in alliance #2 " .
        "to help you move you into a random alliance.";
        include_game_down();
        exit;
    }

    //var_dump($arrArmys);
    //echo "<br />";
    //var_dump($arrBuilds);
    //echo "<br />";
    //var_dump($arrGoods);
    ////////////////////////////////////////////////////////////////////////////
    //  We now have all required information and have checked if the tribe is
    // still eligible. Next is to do calculations and then either show them
    // or act on them, depending on wether or not the button was pressed.
    ////////////////////////////////////////////////////////////////////////////

    //Step 1: build barren acres, we'll do this costlessly
    $intAcres     = $arrBuilds[LAND];
    //homes
    if ($strRace == "Mori Hai")
        $intPrefHomes = round( $intAcres * .45 );
    else
        $intPrefHomes = round( $intAcres * .3 );
    //farms
    if ($strRace == "Mori Hai")
        $intPrefFarms = round( $intAcres * .09 );
    elseif ($strRace == "High Elf")
        $intPrefFarms = round( $intAcres * .096 );
    else
        $intPrefFarms = round( $intAcres * .08 );
    //yards
    $intPrefYards = round( $intAcres * .025 );
    //markets
    $intPrefMarkets = round( $intAcres * .08 );
    //guilds
    $intPrefGuilds = ceil( $intAcres * .001 ) * 10;
    //hideouts
    if ($strRace == "Templar")
        $intPrefHideouts = 0;
    else
        $intPrefHideouts = ceil( $intAcres * .001 ) * 10;


    if ($intPrefHomes > $arrBuilds[HOMES] && $intBarren > 0)
    {
        $intBuildHomes = $intPrefHomes - $arrBuilds[HOMES];
        if ($intBuildHomes > $intBarren)
            $intBuildHomes = $intBarren;
        $intBarren -= $intBuildHomes;
    }
    if ($intPrefFarms > $arrBuilds[FARMS] && $intBarren > 0)
    {
        $intBuildFarms = $intPrefFarms - $arrBuilds[FARMS];
        if ($intBuildFarms > $intBarren)
            $intBuildFarms = $intBarren;
        $intBarren -= $intBuildFarms;
    }
    if ($intPrefYards > $arrBuilds[YARDS] && $intBarren > 0)
    {
        $intBuildYards = $intPrefYards - $arrBuilds[YARDS];
        if ($intBuildYards > $intBarren)
            $intBuildYards = $intBarren;
        $intBarren -= $intBuildYards;
    }
    if ($intPrefMarkets > $arrBuilds[MARKETS] && $intBarren > 0)
    {
        $intBuildMarkets = $intPrefMarkets - $arrBuilds[MARKETS];
        if ($intBuildMarkets > $intBarren)
            $intBuildMarkets = $intBarren;
        $intBarren -= $intBuildMarkets;
    }
    if ($intPrefGuilds > $arrBuilds[GUILDS] && $intBarren > 0)
    {
        $intBuildGuilds = $intPrefGuilds - $arrBuilds[GUILDS];
        if ($intBuildGuilds > $intBarren)
            $intBuildGuilds = $intBarren;
        $intBarren -= $intBuildGuilds;
    }
    if ($intPrefHideouts > $arrBuilds[HIDEOUTS] && $intBarren > 0)
    {
        $intBuildHideouts = $intPrefHideouts - $arrBuilds[HIDEOUTS];
        if ($intBuildHideouts > $intBarren)
            $intBuildHideouts = $intBarren;
        $intBarren -= $intBuildHideouts;
    }
    if ($intBarren > 0)
    {
        $intBuildMines = $intBarren;
        $intBarren -= $intBuildMines;
    }

    //Step 2: select the most space-efficient defensive unit and spend all your money on it, don't forget the cost of the basic

    $best_index = 3;
    //3 and 6 are hardcoded in, which is bad, but the current unit system is just stupid - AI
    for ($i = 3;$i < 6;$i++)
    {
        if (
             ($arrUnitDefences[$i] > $arrUnitDefences[$best_index]) ||
             (
               ($arrUnitDefences[$i] == $arrUnitDefences[$best_index]) &&
               ($arrUnitCosts[$i] < $arrUnitCosts[$best_index])
             )
           )
            $best_index = $i;
    }
    //again, hardcoded 2 for soldier: BAD - AI
    $intUnitCost = $arrUnitCosts[2] + $arrUnitCosts[$best_index];
    $intTrainUnits = floor( $arrGoods[MONEY] / $intUnitCost );
    $intCostMoney = $intUnitCost * $intTrainUnits;




    ////////////////////////////////////////////////////////////////////////////
    //  This is where we display or do the stuff
    ////////////////////////////////////////////////////////////////////////////
    if (isset($_POST['startnow']) && isset($_POST['imsure']) && $_POST['startnow'] == "Start Now" && $_POST['imsure'] == "yes")
    {
        // let's do it

        //first the buildings, check if we need to do this
        if (getBarrenObj($objUser) > 0)
        {
            if (isset($intBuildHomes))
                $arrBuilds[HOMES] += $intBuildHomes;
            if (isset($intBuildFarms))
                $arrBuilds[FARMS] += $intBuildFarms;
            if (isset($intBuildYards))
                $arrBuilds[YARDS] += $intBuildYards;
            if (isset($intBuildMarkets))
                $arrBuilds[MARKETS] += $intBuildMarkets;
            if (isset($intBuildGuilds))
                $arrBuilds[GUILDS] += $intBuildGuilds;
            if (isset($intBuildHideouts))
                $arrBuilds[HIDEOUTS] += $intBuildHideouts;
            if (isset($intBuildMines))
                $arrBuilds[MINES] += $intBuildMines;
            $objUser->set_builds($arrBuilds);
        }

        //Now the military units
        $strUnitVar = $arrUnitVariables[$best_index];
        $intUnits = $objUser->get_army($strUnitVar);
        $intUnits += $intTrainUnits;
        $objUser->set_army($strUnitVar, $intUnits);

        //finally the costs
        $arrGoods[MONEY] -= $intCostMoney;
        $arrGoods[RESEARCH] = 0;
        $objUser->set_goods($arrGoods);

        //don't forget to kick us out of protection
        $objUser->set_user_info(HOURS,PROTECTION_HOURS);

        // tell the user we're done
        echo '<div id="textMedium"><p>Your followers have rushed through the summoning ritual and hastily constructed our defences. We are now ready to face the rest of ORKFiA.</p><p><a href="main.php?cat=game&amp;page=tribe">Return to Tribe</a></p></div>';

    }
    else
    {
        // here's where we display it
        echo '<div id="textMedium"><p>We can rush the summoning ritual, hastily build our lands and quickly recruit a defensive military. Doing so would entail the following.</p>';

        //check if we're building something
        if (getBarrenObj($objUser) > 0)
        {
            echo "<p>Building:</p><ul>";
            if (isset($intBuildHomes))
                echo "<li>$intBuildHomes Homes</li>";
            if (isset($intBuildFarms))
                echo "<li>$intBuildFarms Farms</li>";
            if (isset($intBuildYards))
                echo "<li>$intBuildYards Yards</li>";
            if (isset($intBuildMarkets))
                echo "<li>$intBuildMarkets Markets</li>";
            if (isset($intBuildGuilds))
                echo "<li>$intBuildGuilds Guilds</li>";
            if (isset($intBuildHideouts))
                echo "<li>$intBuildHideouts Hideouts</li>";
            if (isset($intBuildMines))
                echo "<li>$intBuildMines Mines</li>";
            echo "</ul>";
        }

        echo "<p>Training:</p><ul>";
        echo "<li>$intTrainUnits {$arrUnitNames[$best_index]}</li>";
        echo "</ul>";

        echo "<p>Costing:</p><ul>";
        echo "<li>$intCostMoney Crowns</li>";
        echo "<li>" . $arrGoods[RESEARCH] . " research points</li>";
        echo "</ul>";

        echo "<form method='post' action='main.php?cat=game&amp;page=startnow'>";
        echo "<input type='checkbox' name='imsure' value='yes' id='imsure' />";
        echo "<label for='imsure'>I want to rush the summoning ritual.</label><br />";
        echo "<input type='submit' name='startnow' value='Start Now' />";
        echo "</form><br />";

        echo '</div>';
    }



}
?>
