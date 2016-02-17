<?php
function call_clean_text()
{
	global $tool, $go;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    if ($go == "yes")
    {
        $old    = date(TIMESTAMP_FORMAT, strtotime('-7 days'));    			
    	$SQL    = mysql_query ("delete from news WHERE time < $old"); 
    	$news   = mysql_affected_rows();
    	$SQL    = mysql_query ("delete from market_log WHERE time < $old");
    	$market = mysql_affected_rows();
    	mysql_query ("OPTIMIZE TABLE news");
    	mysql_query ("OPTIMIZE TABLE market_log");
    	
    	ECHO "Cleaning of old news ($news) & market logs ($market) done =)";
    }
    
    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Recommended that this is done regularly. Remove 1 week old market logs and news?";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"Clean Database\"></form>";	
	
}
?>