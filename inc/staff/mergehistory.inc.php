<?
function call_mergehistory_text(){
	global $go, $id, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    	
	IF($id && $go =="yes")
	{
    	$fetch = mysql_query("SELECT * FROM mergers WHERE request_status = 'done' AND tribe = '$id' order by merge_time desc");
		$mergers = array();
		while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC))
		{
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
		ECHO "<br><br><table border=0 cellspacing=0 cellpadding=0 width=75% class='border'>";
		ECHO "<tr><td colspan=5 align=center class='pd black bold dark bdown'><b>Done Mergers</b></td></tr>";
		ECHO "<tr><td class='pd black bold darker bdown'>Merge Time</td><td class='pd black bold darker bdown'>Tribe ID</td><td class='pd black bold darker bdown'>Old Location</td><td class='pd black bold darker bdown'>New Location</td></tr>";
		foreach($mergers as $strKey => $value) 
		{
			ECHO "<tr><td class='pd bdown'> $value[merge_time] </td><td class='pd bdown'> $value[tribe] </td><td class='pd bdown'> $value[oldname] (#$value[origin]) </td><td class='pd bdown'> $value[newname] (#$value[target]) </td></tr>";
		}
		ECHO "</table>";
	}

	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id to attain merge history (if there is any):<input type=text size=20 name=id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"Show me\"></form>";
	
}
?>

