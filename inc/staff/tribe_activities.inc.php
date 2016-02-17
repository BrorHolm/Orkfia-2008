<?
function call_tribe_activities_text(){
	global $go, $tribe_id,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($tribe_id && $go =="yes") {
		$result = mysql_query("Select * from news where ouser=$tribe_id");
		$tribe = array();
		while ($arrtribe = mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$tribe[$arrtribe["id"]] = $arrtribe;
			}
		ECHO "<table border=1 width=66%>";
		ECHO "<tr><td colspan=4 align=center><b>Tribe Activity tracking on $tribe_id</b></td></tr>";
		ECHO "<tr><td><b> Time </b></td><td><b> Type </b></td><td><b> Target </b></td><td><b> Text </b></td><td><b> ip </b></td></tr>";
		foreach($tribe as $strKey => $value) {
			$stats = mysql_query("SELECT * from stats where id = $value[duser]");
			$stats = mysql_fetch_array($stats);
			ECHO "<tr><td> $value[time] </td><td> $value[type] </td><td> $stats[tribe](#".$stats[kingdom].")<br>id = $value[duser] </td><td> $value[text]</td><td> $value[ip]</td></tr>";
		}
		ECHO "</table>";
	}

	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id to attain activites:<input type=text size=4 name=tribe_id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
	
}
?>