<?php
//******************************************************************************
// functions build.php                                      Martel, May 25, 2006
//
// Description: Functions for use with construction and related advisor(s).
//******************************************************************************

//==============================================================================
// Building in Percent                                      Martel, May 25, 2006
//==============================================================================
function getBuildInPercent(&$objUser, $item, $formatted = '')
{
    $percentage = ($item / $objUser->get_build(LAND)) * 100;

    if ($formatted == "yes")
        return number_format($percentage);
    else
        return $percentage;
}

//==============================================================================
// Race Building Names, etc.                                Martel, May 25, 2006
//==============================================================================
function getBuildingVariables($strRace)
{
    include_once('inc/functions/races.php');

    // OUTPUT ==================================================================
    if (in_array($strRace, getRaces('Winged')))
    {
        $homes = "Eyries";
        $farms = "Crofts";
    }
    elseif (in_array($strRace, getRaces('Cursed')))
    {   $homes = "Crypts";
        $farms = "Sewage Farms";
    }
    elseif (in_array($strRace, getRaces('Elves')))
    {
        $homes = "Houses";
        $farms = "Fields";
    }
    elseif (in_array($strRace, getRaces('Orks')))
    {
        $homes = "Dwellings";
        $farms = "Pig Farms";
    }
    elseif (in_array($strRace, getRaces('Humans')))
    {
        $homes = "Hovels";
        $farms = "Grain Farms";
    }

    $arrOutput = array(1 => $homes, $farms, 'Walls', 'Weaponries', 'Guilds',
    'Mines', 'Markets', 'Labs', 'Churches', 'Guard Houses', 'Banks', 'Hideouts',
    'Academies', 'Yards');

    // TOOL TIPS ===============================================================

    $arrToolTips = array(1 => 'Provide housing for military and citizens',
        'Produce food for your population',
        'Adds to the defence of your military',
        'Adds to the offence of your military',
        'Generate and store mana points',
        'Produce money for your tribe',
        'Reduce costs for constructing and exploring',
        'Produce research points for your alliance',
        'Adds protection against magic',
        'Adds protection against thievery',
        'Produce money for your tribe, depending on citizens',
        'Generate and store thievery points',
        'Increase your mage level',
        'Produce logs for your tribe'
    );

    // VARIABLES ===============================================================

    $arrVariables = array(1 => HOMES, FARMS, WALLS, WEAPONRIES, GUILDS, MINES,
    MARKETS, LABS, CHURCHES, GUARDHOUSES, BANKS, HIDEOUTS, ACADEMIES, YARDS);

    // HOUSING =================================================================

    $homes_hold = 300;
    $extra_hold = 10;
    $hideouts_hold = $extra_hold;
    if ($strRace == 'Brittonian')
        $homes_hold = 600;
    elseif ($strRace == 'High Elf')
        $homes_hold = 360;
    elseif ($strRace == 'Dragon')
    {
        $homes_hold = 50;
        $extra_hold = 5;
    }
    elseif ($strRace == 'Uruk Hai')
        $homes_hold = 275;
    elseif ($strRace == 'Meteor Elf')
        $hideouts_hold = 150;

    $arrHousing = array(1 => $homes_hold, $extra_hold, $extra_hold, $extra_hold, $extra_hold, $extra_hold,
    $extra_hold, $extra_hold, $extra_hold, $extra_hold, $extra_hold, $hideouts_hold, $extra_hold, $extra_hold);

    $arrBuildings['output']    = $arrOutput;
    $arrBuildings['tooltips']  = $arrToolTips;
    $arrBuildings['variables'] = $arrVariables;
    $arrBuildings['housing']   = $arrHousing;

    return $arrBuildings;
}

//==============================================================================
// replace this one with the new function above wherever it occurrs...
//==============================================================================
function building_names()
{
    global  $build_cost, $building_output, $building_variables,
            $building_count, $objSrcUser;

    // Bad... but better... replace with above asap. globals are not nice
    $arrTemp = getBuildingVariables($objSrcUser->get_stat(RACE));
    $building_variables = $arrTemp['variables'];
    $building_output = $arrTemp['output'];
    $building_count = count($building_variables);
}

//==============================================================================
// Martel: dedicated function to get the barren acres of a target
// Alias of object's function.... try not to use this
//==============================================================================
function getBarrenObj(&$objUser)
{
    $iBarren = $objUser->get_barren();

    return $iBarren;
}

//==============================================================================
// Alias of what? Used where? Perhaps nowhere.... Try not to use this.
//==============================================================================
function new_barren_acres()
{
    global  $objTrgUser, $intSrcBarren, $intTrgBarren;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $arrSrcBuilds = $objSrcUser->get_builds();
    $arrTrgBuilds = $objTrgUser->get_builds();
    $intSrcBarren =  $objSrcUser->get_barren();
    $intTrgBarren =  $objTrgUser->get_barren();
}

//==============================================================================
// Global mess ... Produces all sorts of values
//==============================================================================
function general_build()
{
    global  $build_cost, $local_goods, $max_build, $building_count, $wood_cost,
            $building_output, $building_variables, $local_build,$barren,
            $output_building_percent, $building_percent, $buildings_due,
            $current, $sub_current1, $sub_current2, $sub_current3, $userid;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $local_goods = $objSrcUser->get_goods();
    $local_build = $objSrcUser->get_builds();

    $max_build1 = floor($local_goods['money'] / $build_cost);
    $max_build2 = floor($local_goods['wood'] / $wood_cost);
    $max_build  = min($max_build1,$max_build2);

    $space_gone = 0;

    for ($i = 1; $i <= $building_count; $i++)
    {
        $current = $building_variables[$i];

        $sub_current1 = "$current" . "_t1";
        $sub_current2 = "$current" . "_t2";
        $sub_current3 = "$current" . "_t3";
        $sub_current4 = "$current" . "_t4";

        $buildings_due[$current] = ($local_build[$sub_current1]
                                 + $local_build[$sub_current2]
                                 + $local_build[$sub_current3]
                                 + $local_build[$sub_current4]);

        $space_gone = ($space_gone + $local_build[$current] + $buildings_due[$current]);
        $building_percent[$current] = ($local_build[$current] / $local_build['land']);
        $output_building_percent[$current] = floor($building_percent[$current] * 100);
    }
    $barren = floor($local_build['land'] - $space_gone);
    $max_build = min($max_build,$barren) ;
}

//==============================================================================
// Global mess ... Function to handle construction POST orders
//==============================================================================
function build_buildings()
{
    global  $max_build, $barren, $buildings_built, $built, $building_variables,
            $building_count, $local_build, $userid, $connection, $local_goods,
            $build_cost, $wood_cost, $local_stats;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $local_goods = $objSrcUser->get_goods();
    $local_build = $objSrcUser->get_builds();
    $local_stats = $objSrcUser->get_stats();

    for ($i = 1; $i <= $building_count; $i++)
    {
        $current = $building_variables[$i];
        $built['current'] = floor($built[$current]);

        if ($built[$current] < "0")
        {
            $built[$current] = "0";
        }

        $buildings_built = ($buildings_built + $built[$current]);
    }

    if ($buildings_built <= "0")
    {
        echo '<div class="center">' . "I'm sorry but you didn't build anything.</div>";

        include_game_down();
        exit;
    }

    if ($max_build < $buildings_built)
    {
        echo '<div class="center">' ."You can build on a maximum of $max_build acre(s).<br />";
        echo "You tried to build on $buildings_built.</div>";

        include_game_down();
        exit;
    }

    if ($max_build >= $buildings_built)
    {
        $buildings_built = floor($buildings_built);
        echo '<div class="center">' ." You have just built on $buildings_built acre(s)</div>";

        for ($i = 1; $i <= $building_count ; $i++)
        {
            $current = $building_variables[$i];

            $timer = "_t4";
            if ($local_stats['race'] == "Dwarf")
            {
                $timer = "_t2";
            }

            $cur_time = "$current" . "$timer";

            if (($built[$current] >= 1))
            {
                $new[$current]      = $built[$current] + $local_build[$cur_time];
                $update[$current]   = "UPDATE build SET $cur_time='$new[$current]' WHERE id= $userid";
                $building[$current] = mysql_query($update[$current], $connection);
            }
        }

        $total_build_cost  = ($build_cost * $buildings_built);
        $total_wood_cost   = ($wood_cost * $buildings_built);
        $result = mysql_query(" UPDATE goods SET
                                money = money-$total_build_cost,
                                wood = wood-$total_wood_cost
                                WHERE id = $userid");

        echo '<div class="center">' . "The construction of these buildings cost you $total_build_cost cr and $total_wood_cost logs.</div>   ";
    }
}



//==============================================================================
// Global mess ... Calculates building costs for build and build2 inc pages
//==============================================================================
function build_cost()
{
    global  $build_cost, $local_build, $wood_cost;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $local_build = $objSrcUser->get_builds();

    $build_cost = floor(($local_build['land'] * 3.5) - ($local_build['markets'] * 50));
    $modify     = $local_build['land'] * 0.05;

    if ($local_build['markets'] > 0)
    {
        $modify2 = $local_build['markets'] * 0.5;
    }
    else
    {
        $modify2 = 0;
    }

    $wood_cost  = round((50 + $modify) - ($modify2));
    if ($wood_cost <= (50 + ($local_build['land'] * 0.01)))
    {
        $wood_cost = round(50 + ($local_build['land'] * 0.01));
    }

    // Buildings cannot be lower then 400cr for anyone and cannot be higher
    // then 15000 for anyone.
    if ($build_cost < 400)
        $build_cost = 400;
    elseif ($build_cost > 15000)
        $build_cost = 15000;

    // Saved these below for historical reasons.. old formula on building costs
    // Build cost  = (Land * 2) - (markets*3.5)

}

?>

