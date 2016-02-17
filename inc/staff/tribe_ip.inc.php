<?
function call_tribe_ip_text(){
	global $local_stats, $go, $tribe_id,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($tribe_id && $go =="yes") {
		$result = mysql_query("SELECT * FROM logins WHERE userid = $tribe_id ORDER BY time DESC");
		$tribe = array();
		while ($arrtribe = mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$tribe[$arrtribe["id"]] = $arrtribe;
			}
		ECHO "<table border=1 width=66%>";
		ECHO "<tr><td colspan=4 align=center><b>IP tracking on $tribe_id</b></td></tr>";
		ECHO "<tr><td><b> IP </b></td><td><b> time </b></td><td><b> Browser </b></td></tr>";
		$stats = mysql_query("SELECT * from stats where id = $tribe_id");
		$stats = mysql_fetch_array($stats);
		IF($stats[level] < 4 || $local_stats[level] == 6) {
			foreach($tribe as $strKey => $value) {
				$value[ip] = trim($value[ip]);
				ECHO "<tr><td>" .
				     '<a href="main.php?cat=game&page=resort_tools&tool=ip&amp;go=yes&amp;ip2=' . $value['ip'] . '">' .
				     $value[ip] . "</a>".
				     "</td><td> $value[time] </td><td> $value[browser] </td></tr>";
			}

		}
		ECHO "</table>";
	}

	echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=resort_tools&amp;tool=tribe_ip\">";
    ECHO "Input user id to attain ip details:<input type=text size=4 name=tribe_id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
	
}
?>