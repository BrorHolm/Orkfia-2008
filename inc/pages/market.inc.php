<?php
//******************************************************************************
// Pages market.inc.php                   Major overhaul October 17, 2007 Martel
// History: Now object-only, no global mess and no functions/get.php methods
//******************************************************************************
include_once('inc/functions/market.php');

function include_market_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];

    $topLinks =
        '<div class="center">' .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=market&amp;action=buy\">" .
                "Buy Goods" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=market&amp;action=sell\">" .
                "Sell Goods" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=market&amp;action=history\">" .
                "Market History" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=market&amp;action=log\">" .
                "Log" .
            "</a>" .
        " |" .
        "</div>";
    echo $topLinks;

    $action = "buy";
    if (isset($_GET['action']) && !empty($_GET['action']))
        $action = strval($_GET['action']);
    switch ($action)
    {
        case "sell":

            $arrSrcStats    = $objSrcUser->get_stats();
            $arrSrcGoods    = $objSrcUser->get_goods();
            $arrSrcUsers    = $objSrcUser->get_user_infos();
            if ($arrSrcUsers[HOURS] < 24 && $arrSrcStats[KILLED] == 0 && $arrSrcGoods[CREDITS] < 1)
            {
                $strDiv =
                    '<div id="textSmall">' .
                        '<p>' .
                            "Sorry, you can't sell any goods on the market " .
                            "for another " . (24 - $arrSrcUsers['hours']) .
                            " months." .
                        '</p>' .
                        '<p>' .
                            '<a href="main.php?cat=game&page=market">' .
                            'Return to the Market' . '</a>' .
                        '</p>' .
                    '</div>';
                echo $strDiv;

                break;
            }

            // M: Perform market sale when the form is submitted
            if (isset($_POST['submit']))
                verify_market_sale($objSrcUser);

            // M: Show welcoming message and available credits
            $strWelcomeText =
                '<div id="textMedium">' .
                    '<p>' .
                        'Greetings ' . stripslashes($arrSrcStats[NAME]) .
                        ', how may we serve you today?' .
                    '</p>' .
                    '<p>' .
                        'You have <strong><span class="indicator">' .
                        number_format($arrSrcGoods['credits']) .
                        '</span> credits</strong>.' .
                    '</p>' .
                '</div><br />';
            echo $strWelcomeText;

            // M: Error handling
            if (isset($_GET['error']) && $_GET['error'] == "empty")
            {
                echo '<div class="center">' .
                        "You did not sell anything." .
                     "</div><br />";
            }

            // M: Start building table
            $arrMarketCost = getPriceArray($objSrcUser);
            $arrSrcArmys   = $objSrcUser->get_armys();

            $marketSell =
                "<form method=\"post\" action=\"main.php?cat=game&amp;page=market&amp;action=sell\" id=\"center\">" .

                "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
                    "<tr class=\"header\">" .
                        "<th colspan=\"4\">" . "Sell Goods" . "</th>" .
                    "</tr>" .

                    "<tr class=\"subheader\">" .
                        "<th>" . "Type" . "</th>" .
                        '<th class="center">' . "Price" . "</th>" .
                        "<td>" . "Available Amount" . "</td>" .
                        "<td>" . "Sell" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Money:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['money']) . "</td>" .
                        "<td><strong>" . number_format($arrSrcGoods['money']) . "</strong>" . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"sell[money]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Food:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['food']) . "</td>" .
                        "<td><strong>" . number_format($arrSrcGoods['food']) . "</strong>" . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"sell[food]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Wood:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['wood']) . "</td>" .
                        "<td><strong>" . number_format($arrSrcGoods['wood']) . "</strong>" . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"sell[wood]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Soldiers:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['unit1']) . "</td>" .
                        "<td><strong>" . number_format($arrSrcArmys['unit1']) . "</strong>" . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"sell[soldiers]\" size=\"10\" />" . "</td>" .
                    "</tr>" .
                "</table>" .
                "<br />" .

                "<input type=\"submit\" value=\"Sell Goods\" name=\"submit\" />" .

                "</form>";
            echo $marketSell;

        break;
        case "buy":

            // M: Perform market purchase when the form is submitted
            if (isset($_POST['submit']))
                verify_market_purchase($objSrcUser);

            // M: Show welcoming message and available credits
            $arrSrcStats    = $objSrcUser->get_stats();
            $arrSrcGoods    = $objSrcUser->get_goods();
            $strWelcomeText =
                '<div id="textMedium">' .
                    '<p>' .
                        'Greetings ' . stripslashes($arrSrcStats[NAME]) .
                        ', how may we serve you today?' .
                    '</p>' .
                    '<p>' .
                        'You have <strong><span class="indicator">' .
                        number_format($arrSrcGoods['credits']) .
                        '</span> credits</strong>.' .
                    '</p>' .
                '</div><br />';
            echo $strWelcomeText;

            if (isset($_GET['error']) && $_GET['error'] == "credits")
            {
                echo '<div class="center">' .
                        "Sorry, you dont have enough credits to do that." .
                     "</div><br />";
            }
            elseif (isset($_GET['error']) && $_GET['error'] == "empty")
            {
                echo '<div class="center">' .
                        "You did not buy anything." .
                     "</div><br />";
            }
            elseif (isset($_GET['error']) && $_GET['error'] == "citz")
            {
                echo '<div class="center">' .
                        "Sorry, you can't buy more soldiers than you have room for." .
                     "</div><br />";
            }

            // M: Start building table
            $arrMarketCost   = getPriceArray($objSrcUser); // Price
            $objSrcAlliance  = $objSrcUser->get_alliance();
            $arrSrcAlliance  = $objSrcAlliance->get_alliance_infos(); // Availab
            $arrMaxMarketBuy = max_market_buy($objSrcUser); // Max

            $marketBuy =
                "<form method=\"post\" action=\"main.php?cat=game&amp;page=market&amp;action=buy\" id=\"center\">" .

                "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
                    "<tr class=\"header\">" .
                        "<th colspan=\"5\">" . "Buy Goods" . "</th>" .
                    "</tr>" .

                    "<tr class=\"subheader\">" .
                        "<th>" . "Type" . "</th>" .
                        '<th class="center">' . "Price" . "</th>" .
                        "<td>" . "Available" . "</td>" .
                        "<td>" . "Max" . "</td>" .
                        "<td>" . "Buy" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Money:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['money']) . "</td>" .
                        "<td>" . number_format($arrSrcAlliance['money']) . "</td>" .
                        "<td>" . number_format($arrMaxMarketBuy['money']) . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"buy[money]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Food:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['food']) . "</td>" .
                        "<td>" . number_format($arrSrcAlliance['food']) . "</td>" .
                        "<td>" . number_format($arrMaxMarketBuy['food']) . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"buy[food]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Wood:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['wood']) . "</td>" .
                        "<td>" . number_format($arrSrcAlliance['wood']) . "</td>" .
                        "<td>" . number_format($arrMaxMarketBuy['wood']) . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"buy[wood]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Soldiers:" . "</th>" .
                        '<td class="center">' . number_format($arrMarketCost['unit1']) . "</td>" .
                        "<td>" . number_format($arrSrcAlliance['soldiers']) . "</td>" .
                        "<td>" . number_format($arrMaxMarketBuy['unit1']) . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"buy[soldiers]\" size=\"10\" />" . "</td>" .
                    "</tr>" .

                "</table>" .
                "<br />" .

                "<input type=\"submit\" value=\"Buy Goods\" name=\"submit\" />" .
                "</form>";
            echo $marketBuy;

            $arrSrcUsers     = $objSrcUser->get_user_infos();
            if ($arrSrcUsers['hours'] < 24 && $arrSrcStats['killed'] == 0 && $arrSrcGoods['credits'] > 0)
            {
                echo
                    '<div class="center">' .
                        "<p><em>Leader, remember this before you buy: save " .
                        "some credits,<br /> otherwise you will not be able " .
                        "to sell any goods on the market for " .
                        (24 - $arrSrcUsers['hours']) . " months. <br />1 " .
                        "credit is enough to keep this option open until then." .
                        "</em></p>" .
                    '</div>';
            }

        break;
        case "history":

            $objSrcAlliance = $objSrcUser->get_alliance();
            echo '<br />' . get_market_history_table($objSrcAlliance);
            echo '<br />' . get_market_table($objSrcAlliance);

        break;
        case "log":

            $objSrcAlliance = $objSrcUser->get_alliance();
            echo '<br />' . get_market_log_table($objSrcAlliance);

        break;
    }
}

//==============================================================================
// New object-oriented way to show market goods          Martel October 16, 2007
// Returns a string with the alliance market inventory table
//==============================================================================
function get_market_table(&$objAlli)
{
    include_once('inc/functions/market.php');
    doUpdateMarket($objAlli);
    $arrAlliance = $objAlli->get_alliance_infos();

    $alliancePool =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">Alliance Market - " . stripslashes($arrAlliance['name']) . " (#" . $arrAlliance[ID] . ")</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Money:" . "</th>" .
                "<td>" . number_format($arrAlliance['money']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Food:" . "</th>" .
                "<td>" . number_format($arrAlliance['food']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Wood:" . "</th>" .
                "<td>" . number_format($arrAlliance['wood']) . " logs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Soldiers: " . "</th>" .
                "<td>" . number_format($arrAlliance['soldiers']) . " units" . "</td>" .
            "</tr>" .
        "</table>";

    return $alliancePool;
}

//==============================================================================
// New object-oriented way to show market history        Martel October 12, 2007
// Returns a string with the market history table
//==============================================================================
function get_market_history_table(&$objAlliance)
{
    $strMarketHistoryTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"5\">" . "Market History" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Tribe" . "</th>" .
                "<td>" . "Money" . "</td>" .
                "<td>" . "Food" . "</td>" .
                "<td>" . "Wood" . "</td>" .
                "<td>" . "Soldiers" . "</td>" .
            "</tr>";

    $arrSum = array(MARKET_MONEY => 0, MARKET_FOOD => 0, MARKET_WOOD => 0, MARKET_SOLDIERS => 0);
    $objTmpUser  = new clsUser(1);
    foreach ($objAlliance->get_userids() as $iUser)
    {
        $objTmpUser->set_userid($iUser); // This might speed it up a bit.. Reuse
        $arrTmpGoods = $objTmpUser->get_goods();
        $arrTmpStats = $objTmpUser->get_stats();

        $strMarketHistoryTable .=
            "<tr class=\"data\">" .
                "<th>" . stripslashes($arrTmpStats[TRIBE]) . "</th>" .
                "<td>" . number_format($arrTmpGoods[MARKET_MONEY]) . "</td>" .
                "<td>" . number_format($arrTmpGoods[MARKET_FOOD]) . "</td>" .
                "<td>" . number_format($arrTmpGoods[MARKET_WOOD]) . "</td>" .
                "<td>" . number_format($arrTmpGoods[MARKET_SOLDIERS]) . "</td>" .
            "</tr>";

        // M: Add to total for each resource type
        foreach ($arrSum as $str => $i)
        {
            $arrSum[$str] += $arrTmpGoods[$str];
        }
    }

    $arrAlli = $objAlliance->get_alliance_infos();
    $strMarketHistoryTable .=
            "<tr class=\"data\">" .
                "<th class=bsup>" . "Unaccounted For:" . "</th>" .
                "<td class=bsup>" . number_format( $arrAlli[MONEY] - $arrSum[MARKET_MONEY] ) . "</td>" .
                "<td class=bsup>" . number_format( $arrAlli[FOOD] - $arrSum[MARKET_FOOD] ) . "</td>" .
                "<td class=bsup>" . number_format( $arrAlli[WOOD] - $arrSum[MARKET_WOOD] ) . "</td>" .
                "<td class=bsup>" . number_format( $arrAlli[SOLDIERS] - $arrSum[MARKET_SOLDIERS] ) . "</td>" .
            "</tr>" .
        "</table>";

    return $strMarketHistoryTable;
}

//==============================================================================
// New object-oriented way to show market logs           Martel October 16, 2007
// Returns a string with the market log table
//==============================================================================
function get_market_log_table(&$objAlli)
{
    $iAlliance = $objAlli->get_allianceid();
    $old       = date(TIMESTAMP_FORMAT, strtotime('-7 days'));
    $strSQL    = "SELECT * FROM market_log " .
                 "WHERE alliance = $iAlliance AND time > '$old' " .
                 "ORDER BY time DESC";
    $result    = mysql_query($strSQL);

    $strMarketLogTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"big\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"7\">" . "The Market Log" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Time" . "</th>" .
                "<td class=\"left\">" . "Tribe" . "</td>" .
                "<td class=\"left\">" . "Action" . "</td>" .
                "<td>" . "Money" . "</td>" .
                "<td>" . "Food" . "</td>" .
                "<td>" . "Wood" . "</td>" .
                "<td>" . "Soldiers" . "</td>" .
            "</tr>";

    while($arrSrcMarket = mysql_fetch_array($result))
    {
        $strMarketLogTable .=
            "<tr class=\"data\">" .
                "<th>" . $arrSrcMarket['time'] . "</th>" .
                "<td class=\"left\">" . $arrSrcMarket['tribe'] . "</td>" .
                "<td class=\"left\">" . $arrSrcMarket['type'] . "</td>" .
                "<td>" . number_format( $arrSrcMarket['money'] ) . "</td>" .
                "<td>" . number_format( $arrSrcMarket['food'] ) . "</td>" .
                "<td>" . number_format( $arrSrcMarket['wood'] ) . "</td>" .
                "<td>" . number_format( $arrSrcMarket['unit1'] ) . "</td>" .
            "</tr>";
    }
    $strMarketLogTable .=
        "</table>";

    return $strMarketLogTable;
}
?>
