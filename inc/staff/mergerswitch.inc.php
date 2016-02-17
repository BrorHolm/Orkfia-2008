<?
function call_mergerswitch_text()
{
    global $enable,$disable,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF (!$enable && !$disable) {
		$mergerswitch = mysql_query("SELECT mergers FROM admin_switches");
		$mergerswitch = mysql_fetch_array($mergerswitch);
		if ($mergerswitch['mergers'] == "on") {
			$value = "disable";
		} else {
			$value = "enable";
		}
    	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
		ECHO "<input type=submit value=$value name=$value></form>";
	}
	IF($enable) {
		mysql_query("UPDATE admin_switches SET mergers = 'on'");
		ECHO "The merger tool has been activated.";
	}
	IF($disable) {
		mysql_query("UPDATE admin_switches SET mergers = 'off'");
		ECHO "The merger tool is turned off now.";
	}
}
?>