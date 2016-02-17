<?
function call_ip_text(){
	global $go, $ip2, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    $show_time = date(TIMESTAMP_FORMAT, strtotime("-7 days"));
    
	IF($ip2 && $go =="yes") {
		$result = mysql_query("SELECT * FROM logins WHERE ip = '$ip2' AND time > $show_time ORDER BY time DESC");
		$tribe = array();
		while ($arrtribe = mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$tribe[$arrtribe["id"]] = $arrtribe;
			}
		ECHO "<table border=1 width=66%>";
		ECHO "<tr><td colspan=4 align=center><b>IP tracking on ip $ip2</b></td></tr>";
		ECHO "<tr><td><b> Userid </b></td><td><b> Time </b></td><td><b> Browser </b></td></tr>";
		foreach($tribe as $strKey => $value) {
			ECHO "<tr><td>" .
				 '<a href="main.php?cat=game&page=resort_tools&tool=tribe_ip&amp;go=yes&amp;tribe_id=' . $value['userid'] . '">' .
			     $value['userid'] . "</a></td><td> $value[time] </td><td> $value[browser] </td></tr>";
		}
		ECHO "</table>";
	}

	echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=resort_tools&amp;tool=ip\">";
    ECHO "Input user ip to attain userid details:<input type=text size=20 name=ip2>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
	
}
?>

