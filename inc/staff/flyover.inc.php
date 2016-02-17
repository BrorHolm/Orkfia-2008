<?php

function call_flyover_text()
{
    global $go, $alli_id, $tool;
    
    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    IF($alli_id && $go =="yes")
    {
        include_once('inc/pages/alliance_news.inc.php');
        echo showAllianceNews($alli_id);
    }
    
    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input alliance id to attain flyover:";
    echo "<input type=text size=4 name=alli_id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
}
?>