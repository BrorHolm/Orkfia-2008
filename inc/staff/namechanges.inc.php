<?
function call_namechanges_text()
{
    global $enable,$disable, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF (!$enable && !$disable) {
		$namechanges = mysql_query("SELECT names FROM admin_switches");
		$namechanges = mysql_fetch_array($namechanges);
		if ($namechanges['names'] == "on") {
			$value = "disable";
		} else {
			$value = "enable";
		}
    	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
		ECHO "<input type=submit value=$value name=$value></form>";
	}
	IF($enable) {
		mysql_query("UPDATE admin_switches SET names = 'on'");
		ECHO "The name changes tool has been activated.";
	}
	IF($disable) {
		mysql_query("UPDATE admin_switches SET names = 'off'");
		ECHO "The name changes tool is turned off now.";
	}
}
?>