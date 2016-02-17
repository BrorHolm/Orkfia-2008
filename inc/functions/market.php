<?php
//******************************************************************************
// Functions market.php                  Big Overhaul October 16, 2007 by Martel
// modificatio  history
// 16/04/2002 thalura   soldiers selling:
//                      verify_market_purchase
//                      verify_market_sale
//******************************************************************************

//==============================================================================
// New object-oriented way                               Martel October 16, 2007
//==============================================================================
function getPriceArray(&$objUser)
{
    include_once('inc/functions/races.php');
    $arrUnitVariables = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitCost      = $arrUnitVariables['gold'];

    $arrMarketCost[UNIT1] = ($arrUnitCost[2] * 2) + 15;
    $arrMarketCost[MONEY] = 2;
    $arrMarketCost[WOOD]  = 34;
    $arrMarketCost[FOOD]  = 3;

    return $arrMarketCost;
}

//==============================================================================
// New object-oriented way                               Martel October 16, 2007
// Finds out how much goods there are on the market, and how much we may buy
// Returns array similar to the getPriceArray
//==============================================================================
function max_market_buy(&$objSrcUser)
{
    $arrSrcStats    = $objSrcUser->get_stats();
    $arrSrcGoods    = $objSrcUser->get_goods();
    $objSrcAlliance = $objSrcUser->get_alliance();

    // M: Update just to ensure decays have affected the goods
    doUpdateMarket($objSrcAlliance);
    $arrSrcAlliance = $objSrcAlliance->get_alliance_infos();

    // M: New functions connected to the object-oriented update routines
    include_once('inc/functions/population.php');

    $iArmy  = getPopulation($objSrcUser);
    $iArmy = $iArmy['total_army'];

    $iRoom = getMaxPopulation($objSrcUser);
    $iRoom = $iRoom['total'];
    $iLeft = floor($iRoom - $iArmy) - 800; // 800 so we do not kill ourselves

    if ($iLeft < 0)
        $iLeft = 0;

    // M: Fetch the prices for each goods
    $arrMarketCost = getPriceArray($objSrcUser);

    // M: Besides looking up what we can afford, let's not allow someone to die
    $arrMaxMarketBuy[UNIT1] = min(floor($arrSrcGoods[CREDITS] / $arrMarketCost[UNIT1]), $iLeft);
    if ($arrMaxMarketBuy[UNIT1] >= $arrSrcAlliance[SOLDIERS])
        $arrMaxMarketBuy[UNIT1]  = $arrSrcAlliance[SOLDIERS];

    $arrMaxMarketBuy[MONEY] = floor($arrSrcGoods[CREDITS] / $arrMarketCost[MONEY]);
    if ($arrMaxMarketBuy[MONEY] >= $arrSrcAlliance[MONEY])
        $arrMaxMarketBuy[MONEY]  = $arrSrcAlliance[MONEY];

    $arrMaxMarketBuy[WOOD] = floor($arrSrcGoods[CREDITS] / $arrMarketCost[WOOD]);
    if ($arrMaxMarketBuy[WOOD] >= $arrSrcAlliance[WOOD])
        $arrMaxMarketBuy[WOOD]  = $arrSrcAlliance[WOOD];

    $arrMaxMarketBuy[FOOD] = floor($arrSrcGoods[CREDITS] / $arrMarketCost[FOOD]);
    if ($arrMaxMarketBuy[FOOD] >= $arrSrcAlliance[FOOD])
        $arrMaxMarketBuy[FOOD]  = $arrSrcAlliance[FOOD];

    return $arrMaxMarketBuy;
}

//==============================================================================
// New object-oriented way                               Martel October 16, 2007
// 16/04/2002 thalura   verification of total pop for soldiers to buy
//==============================================================================
function verify_market_purchase(&$objSrcUser)
{
    $arrSrcStats    = $objSrcUser->get_stats();
    $arrSrcGoods    = $objSrcUser->get_goods();
    $objSrcAlliance = $objSrcUser->get_alliance();
    $arrSrcAlliance = $objSrcAlliance->get_alliance_infos();

    // M: Secure / Validate Input
    $arrBuy = $_POST['buy'];
    if (!isset($_POST['buy']))
        return;
    $arrBuy[MONEY]    = max(0, floor(intval($arrBuy[MONEY])));
    $arrBuy[FOOD]     = max(0, floor(intval($arrBuy[FOOD])));
    $arrBuy[WOOD]     = max(0, floor(intval($arrBuy[WOOD])));
    $arrBuy[SOLDIERS] = max(0, floor(intval($arrBuy[SOLDIERS])));

    // M: Secure values against the max available goods
    $arrMax = max_market_buy($objSrcUser);
    if ($arrBuy[MONEY] > $arrMax[MONEY])     $arrBuy[MONEY]    = $arrMax[MONEY];
    if ($arrBuy[FOOD] > $arrMax[FOOD])       $arrBuy[FOOD]     = $arrMax[FOOD];
    if ($arrBuy[WOOD] > $arrMax[WOOD])       $arrBuy[WOOD]     = $arrMax[WOOD];
    if ($arrBuy[SOLDIERS] > $arrMax[UNIT1])  $arrBuy[SOLDIERS] = $arrMax[UNIT1];

    // M: Check if any numbers at all were provided
    if (array_sum($arrBuy) <= 0)
    {
        header('location: main.php?cat=game&page=market&action=buy&error=empty');
        exit;
    }

    // M: Calculate how many credits we're spending
    $arrMarketCost = getPriceArray($objSrcUser);
    $iSumCredits   = floor($arrBuy[MONEY]    * $arrMarketCost[MONEY]);
    $iSumCredits  += floor($arrBuy[FOOD]     * $arrMarketCost[FOOD]);
    $iSumCredits  += floor($arrBuy[WOOD]     * $arrMarketCost[WOOD]);
    $iSumCredits  += floor($arrBuy[SOLDIERS] * $arrMarketCost[UNIT1]); // unit_1

    // M: Check that we have enough credits
    if ($iSumCredits > $arrSrcGoods[CREDITS])
    {
        header('location: main.php?cat=game&page=market&action=buy&error=credits');
        exit;
    }

    // M: Save New Alliance Goods
    $arrNewSrcAlliance = array
    (
        MONEY           => $arrSrcAlliance[MONEY] - $arrBuy[MONEY],
        FOOD            => $arrSrcAlliance[FOOD] - $arrBuy[FOOD],
        WOOD            => $arrSrcAlliance[WOOD] - $arrBuy[WOOD],
        SOLDIERS        => $arrSrcAlliance[SOLDIERS] - $arrBuy[SOLDIERS]
    );
    $objSrcAlliance->set_alliance_infos($arrNewSrcAlliance);

    // M: Save New Soldiers
    $iNewUnit1 = max(0, $objSrcUser->get_army(UNIT1) + $arrBuy[SOLDIERS]);
    $objSrcUser->set_army(UNIT1, $iNewUnit1);

    // M: Save New Goods
    $arrNewSrcGoods = array
    (
        MONEY           => $arrSrcGoods[MONEY] + $arrBuy[MONEY],
        FOOD            => $arrSrcGoods[FOOD] + $arrBuy[FOOD],
        WOOD            => $arrSrcGoods[WOOD] + $arrBuy[WOOD],
        CREDITS         => max(0, $arrSrcGoods[CREDITS] - $iSumCredits),
        MARKET_MONEY    => $arrSrcGoods[MARKET_MONEY] - $arrBuy[MONEY],
        MARKET_FOOD     => $arrSrcGoods[MARKET_FOOD] - $arrBuy[FOOD],
        MARKET_WOOD     => $arrSrcGoods[MARKET_WOOD] - $arrBuy[WOOD],
        MARKET_SOLDIERS => $arrSrcGoods[MARKET_SOLDIERS] - $arrBuy[SOLDIERS]
    );
    $objSrcUser->set_goods($arrNewSrcGoods);

    // M: Make entry into market log
    mysql_query("INSERT INTO market_log VALUES ('', '{$arrSrcStats[ALLIANCE]}', '{$arrSrcStats[TRIBE]}', 'Buy', '{$arrBuy[MONEY]}', '{$arrBuy[FOOD]}', '{$arrBuy[WOOD]}', '{$arrBuy[SOLDIERS]}', NOW())");

    // M: Refresh the market page to show new status
    header('location: main.php?cat=game&page=market&action=buy');
}

//==============================================================================
// New object-oriented way                               Martel October 16, 2007
//==============================================================================
function verify_market_sale(&$objSrcUser)
{
    $arrSrcStats     = $objSrcUser->get_stats();
    $arrSrcGoods     = $objSrcUser->get_goods();
    $arrSrcArmysHome = $objSrcUser->get_armys_home();

    // M: Secure / Validate Input
    $arrSell = $_POST['sell'];
    if (!isset($_POST['sell']))
        return;
    $arrSell[MONEY]    = max(0, floor(intval($arrSell[MONEY])));
    $arrSell[FOOD]     = max(0, floor(intval($arrSell[FOOD])));
    $arrSell[WOOD]     = max(0, floor(intval($arrSell[WOOD])));
    $arrSell[SOLDIERS] = max(0, floor(intval($arrSell[SOLDIERS])));

    // M: Secure values against the available goods
    if ($arrSell[MONEY] > $arrSrcGoods[MONEY])
        $arrSell[MONEY] = $arrSrcGoods[MONEY];
    if ($arrSell[FOOD] > $arrSrcGoods[FOOD])
        $arrSell[FOOD] = $arrSrcGoods[FOOD];
    if ($arrSell[WOOD] > $arrSrcGoods[WOOD])
        $arrSell[WOOD] = $arrSrcGoods[WOOD];
    if ($arrSell[SOLDIERS] > $arrSrcArmysHome[UNIT1])
        $arrSell[SOLDIERS] = $arrSrcArmysHome[UNIT1];

    // M: Check if any numbers at all were provided
    if (array_sum($arrSell) <= 0)
    {
        header('location: main.php?cat=game&page=market&action=sell&error=empty');
        exit;
    }

    // M: Calculate how many credits we're receiving
    $arrMarketCost = getPriceArray($objSrcUser);
    $iSumCredits   = round($arrSell[MONEY]    * $arrMarketCost[MONEY]);
    $iSumCredits  += round($arrSell[FOOD]     * $arrMarketCost[FOOD]);
    $iSumCredits  += round($arrSell[WOOD]     * $arrMarketCost[WOOD]);
    $iSumCredits  += round($arrSell[SOLDIERS] * $arrMarketCost[UNIT1]);

    $objSrcAlliance = $objSrcUser->get_alliance();
    $arrSrcAlliance = $objSrcAlliance->get_alliance_infos();

    // M: Save New Alliance Goods
    $arrNewSrcAlliance = array
    (
        MONEY           => $arrSrcAlliance[MONEY] + $arrSell[MONEY],
        FOOD            => $arrSrcAlliance[FOOD] + $arrSell[FOOD],
        WOOD            => $arrSrcAlliance[WOOD] + $arrSell[WOOD],
        SOLDIERS        => $arrSrcAlliance[SOLDIERS] + $arrSell[SOLDIERS]
    );
    $objSrcAlliance->set_alliance_infos($arrNewSrcAlliance);

    // M: Save New Goods
    $arrNewSrcGoods = array
    (
        MONEY           => max(0, $arrSrcGoods[MONEY] - $arrSell[MONEY]),
        FOOD            => max(0, $arrSrcGoods[FOOD] - $arrSell[FOOD]),
        WOOD            => max(0, $arrSrcGoods[WOOD] - $arrSell[WOOD]),
        CREDITS         => max(0, $arrSrcGoods[CREDITS] + $iSumCredits),
        MARKET_MONEY    => $arrSrcGoods[MARKET_MONEY] + $arrSell[MONEY],
        MARKET_FOOD     => $arrSrcGoods[MARKET_FOOD] + $arrSell[FOOD],
        MARKET_WOOD     => $arrSrcGoods[MARKET_WOOD] + $arrSell[WOOD],
        MARKET_SOLDIERS => $arrSrcGoods[MARKET_SOLDIERS] + $arrSell[SOLDIERS]
    );
    $objSrcUser->set_goods($arrNewSrcGoods);

    // Save New Soldiers
    $iNewUnit1 = max(0, $objSrcUser->get_army(UNIT1) - $arrSell[SOLDIERS]);
    $objSrcUser->set_army(UNIT1, $iNewUnit1);

    // M: Make entry into market log
    mysql_query("INSERT INTO market_log VALUES ('', '$arrSrcStats[kingdom]', '$arrSrcStats[tribe]', 'Sell', '$arrSell[money]', '$arrSell[food]', '$arrSell[wood]', '$arrSell[soldiers]', NOW())");

    // M: Refresh the market page to show new status
    header('location: main.php?cat=game&page=market&action=sell');
}

//==============================================================================
// New object-oriented way to update the market          Martel October 16, 2007
//==============================================================================
function doUpdateMarket(&$objAlliance)
{
    include_once('inc/classes/clsGame.php');
    $objGame     = new clsGame();
    $iHours      = $objGame->get_game_time(HOUR_COUNTER);
    $arrAlliance = $objAlliance->get_alliance_infos();
    $iUpdates    = $iHours - $arrAlliance[MARKET_HOUR];

    // calculate # of updates and update the market accordingly
    $intUpdatesOwed = $iUpdates;
    if ($intUpdatesOwed > 0)
    {
        $new_food = $arrAlliance[FOOD];
        $new_wood = $arrAlliance[WOOD];

        // decay of food and wood stacked on market, added loop - AI 03/05/07
        while ($intUpdatesOwed-- > 0)
        {
            $new_food = $new_food * ( 1 - 1/1500);
            $new_wood = $new_wood * 0.9995;
        }

        $arrAllianceNew =  array
        (
            FOOD => round($new_food),
            WOOD => round($new_wood),
            MARKET_HOUR => $arrAlliance[MARKET_HOUR] + $iUpdates
        );
        $objAlliance->set_alliance_infos($arrAllianceNew);
    }
}

?>
