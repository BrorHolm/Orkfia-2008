<?php
//******************************************************************************
// staff tool ip2.inc.php                                Martel, August 30, 2006
//******************************************************************************
function call_ip2_text(){
	global $go, $ip2, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    ECHO "<h2>User Logins from 1 IP the last 7 days</h2>";
    echo "Remember not to only rely on IP, while many logins is a safe bet don't <br />stare yourself blind at these. 1337 cheaters will have many and different IPs.<br />Remember AOL too.<br /><br />";
        
    $show_time = date(TIMESTAMP_FORMAT, strtotime("-7 days"));
    
	if ($ip2 && $go == "yes")
	{
    	$ip2 = trim($ip2);
        $strSQL =         	
            "SELECT userid, ip, COUNT(*) AS logins, time " .
            "FROM logins " .
            "WHERE ip = '$ip2' AND time > $show_time " .
            "GROUP BY userid " .
            "ORDER BY id ASC, time DESC";

		ECHO "<table class=medium cellspacing=0 cellpadding=0>";
		ECHO "<tr class=header><th colspan=5>Login IP tracking on $ip2</th></tr>";
		ECHO "<tr class=subheader><th> Userid </th><td> IP </td><td> Logins </td><td> Last(?) Login </td><td> Check: </td></tr>";
		
		$result = mysql_query($strSQL) or die("query error");
		while ($value = mysql_fetch_array ($result, MYSQL_ASSOC))
		{
    	    echo $strTableTr = 
	        "<tr class=data>" .
		        "<th>" .
				    '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=checkinfo&id=' . $value['userid'] . '">' .
			        $value['userid'] . "</a>" .
		        '</th>' .
		        '<td>' .
		            $value['ip'] .
		        '</td>' .
		        '<td>' .
		            $value['logins'] .
		        '</td>' .
		        '<td>' . 
		            $value['time'] .
		        '</td>' .
		        "<td>" .
				    '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=tribe_ip&amp;go=yes&amp;tribe_id=' . $value['userid'] . '">' .
			        "Tribe IP" . "</a>" .
		        '</td>' .
	        '</tr>';
	    }
		ECHO "</table> <br />";
	}

	echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=resort_tools&amp;tool=ip2\">";
    ECHO "Input user ip to attain userid details: <input type=text size=20 name=ip2>";
    echo "<br />";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
	
}
?>

