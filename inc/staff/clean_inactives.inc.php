<?
function call_clean_inactives_text()
{
	global $check_inactives, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

	$old_time = date(TIMESTAMP_FORMAT, strtotime("-2 days"));
	$strSQL =
	    "SELECT user.id, stats.kingdom " .
        "FROM user, stats, preferences " .
        "WHERE user.last_login < '$old_time' " .
        "AND stats.kingdom > 10 " .
        "AND user.id > 1 " .
        "AND user.id = stats.id " .
        "AND user.id = preferences.id " .
        "AND preferences.email_activation != 'verified'";
    $result = mysql_query($strSQL);
	
	$inactives = array();
	while ($arrinactives = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$inactives[$arrinactives["id"]] = $arrinactives;
	}
	$count = 0;
	foreach($inactives as $strKey => $value) {
		$count++;
		IF($check_inactives){ 
			
				$sql =mysql_query("UPDATE stats SET kingdom=0 WHERE id=$value[id]");
				$sql =mysql_query("DELETE FROM rankings_personal WHERE id =$value[id]");
			
		}
		
	}
	IF($check_inactives) {
		ECHO "Removed $count accounts. ";	
	} ELSE {
		ECHO "$count non verified accounts (48 hours waiting time) will be removed, go through with this?";
	    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
		ECHO "yes : <input type='checkbox' name='check_inactives'>";
		ECHO "<br><input type=submit value=delete></form>";
	}
}
?>