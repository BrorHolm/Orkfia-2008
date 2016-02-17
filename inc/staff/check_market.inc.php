<?php

function call_check_market_text()
{
    global $go, $alli_id, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    if ($alli_id && $go =="yes")
    {
        $objAlli = new clsAlliance($alli_id);

        include_once('inc/pages/market.inc.php');
        echo get_market_history_table($objAlli);
        echo get_market_table($objAlli);
    }

    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input alliance id to attain market goods and history:";
    echo "<input type=text size=4 name=alli_id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
}
?>