<?php
//******************************************************************************
// functions reset_account                              November 09, 2007 Martel
//******************************************************************************

function getStartValues($strRace, $iLand = STARTING_LAND)
{
    // Race housing effeciency
    include_once('inc/functions/build.php');
    $arrBuildVariables   = getBuildingVariables($strRace);
    $iCitzPerHome        = $arrBuildVariables['housing'][1];

    // Units
    $arrInit[UNIT1]    = floor($iLand * .8);
    $arrInit[UNIT2]    = floor($iLand * .04);
    $arrInit[UNIT3]    = floor($iLand * .04);
    $arrInit[UNIT4]    = floor($iLand * .04);
    $arrInit[UNIT5]    = floor($iLand * .4);
//     $arrInit[UNIT6] = 0; // Mystic units

    // Buildings
    $arrInit[LAND]     = $iLand;
    $arrInit[HOMES]    = floor($iLand * 0.3);
    $arrInit[FARMS]    = floor($iLand * 0.075);
    $arrInit[MARKETS]  = floor($iLand * 0.07);
    $arrInit[YARDS]    = floor(1 + ($iLand / 1000)) * 40;
    $arrInit[GUILDS]   = 10;
    $arrInit[HIDEOUTS] = 10;

    // Goods
    $arrInit[MONEY]    = round((960 / PROTECTION_HOURS) * $iCitzPerHome * $iLand);
    $arrInit[FOOD]     = round((320 / PROTECTION_HOURS) * $iCitzPerHome);
    $arrInit[WOOD]     = round((200 / PROTECTION_HOURS) * $iLand);
    $arrInit[RESEARCH] = 5000;

    // Citizens
    $arrInit[CITIZENS] = round((960 / PROTECTION_HOURS) * $iCitzPerHome);

    // Fame
    $arrInit[FAME]     = 5000;

    // Spells
    $arrInit[POPULATION]  = round(PROTECTION_HOURS * .375);
    $arrInit[GROWTH]      = round(PROTECTION_HOURS * .375);
    $arrInit['matawaska'] = round(PROTECTION_HOURS * .375); // actual name= food
    $arrInit[INCOME]      = round(PROTECTION_HOURS * .375);

    // Classic exceptions
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
    {
        $arrInit[MONEY]    = 300000;
        $arrInit[FOOD]     = 5000;
        $arrInit[WOOD]     = 25000;
        $arrInit[CITIZENS] = 5000;
    }


    // Race Exceptions
    if ($strRace == 'Spirit')
        $arrInit['farms'] = 0;
    elseif ($strRace == 'Templar')
    {
        $arrInit['hideouts'] = 0;
//         $arrInit[UNIT5] = 0;
//         $arrInit[UNIT6] = floor($iLand * .4);
    }

    return $arrInit;
}

?>