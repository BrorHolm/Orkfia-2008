<?
function call_checkinfo_text()
{
    global $id,$op,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id: <input name=id size=5><br><br><br>";
    ECHO "<input type=submit value=Check_Info>";
    ECHO "</form>";
    ECHO "<br><br>";
    IF($id){
        $result1 = mysql_query("Select * From user Where id = $id");
		$result2 = mysql_query("Select * From stats Where id = $id");
		$userinfo = mysql_fetch_array($result1);
		$statsinfo = mysql_fetch_array($result2);
		ECHO "<table border=1 width=66%>";
        ECHO "<tr><td colspan=2 align=center><b>User $id 's Information</b></td></tr>";
		ECHO "<tr><td>Real name </td><td>$userinfo[realname]</td></tr>";
		ECHO "<tr><td>Country </td><td>$userinfo[country]</td></tr>";
		ECHO "<tr><td>Username </td><td>$userinfo[username]</td></tr>";
		ECHO "<tr><td>Updated </td><td>$userinfo[hours] times</td></tr>";
		ECHO "<tr><td>Tribe Name </td><td>$statsinfo[tribe]</td></tr>";
		ECHO "<tr><td>Alias </td><td>$statsinfo[name]</td></tr>";
		ECHO "<tr><td>Race </td><td>$statsinfo[race]</td></tr>";
		ECHO "<tr><td>Alliance </td><td>$statsinfo[kingdom]</td></tr>";
	    ECHO "<tr><td>Sci Invested </td><td>$statsinfo[invested]</td></tr>";
		ECHO "<tr><td>Level </td><td>$statsinfo[level]</td></tr>";
        ECHO "</table>";
		ECHO "Level 2 = WTB Member, Level 3 = Staff Member, 5 = Head of Resort, 6 = Head of ORKFiA";

    }
}
?>
