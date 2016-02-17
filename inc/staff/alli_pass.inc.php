<?
function call_alli_pass_text()
{
    global $go, $id, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input alliance id:<input type=text size=4 name=id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=fetch></form>";
    IF($id && $go == "yes" ){
		$grab = mysql_query("select * from kingdom where id = $id");
		$grab = mysql_fetch_array($grab);
		ECHO "<br> Password is: $grab[password]";
	}
}
?>