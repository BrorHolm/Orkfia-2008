<?
function call_news_text()
{
	global $go, $tribeid, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($tribeid && $go =="yes") {
		$result = mysql_query("SELECT time,text from news WHERE duser = $tribeid ORDER BY time DESC") or die(mysql_error());
		echo "<table cellspacing=0 cellpadding=0 width=80% class=\"border\">";
		echo "<tr><td colspan=2 class=\"pd bold dark black bdown\" align=center>";
		echo "The Headlines</td></tr>";
		while ($newsloop =(mysql_fetch_array($result))) {
			echo "<tr><td class=\"pd 11\">";
			echo "$newsloop[time]</td><td class=\"pd 11\">$newsloop[text]</td></tr>";
		}
		echo "</table>";
	}

	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id:<input type=text size=20 name=tribeid>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";
	
}
?>

