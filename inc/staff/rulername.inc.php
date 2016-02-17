<?
function call_rulername_text(){
    global $id,$confirm,$rulername,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input ID#: <input name=id size=5><br>";
	ECHO "Change ruler name to: <input type=text name=rulername maxlength=30 size=25><br>";
	ECHO "<input type=submit value=Save name=confirm>";
    ECHO "</form>";
    ECHO "<br><br>";
    IF($confirm && $id && $rulername)
    {
		$rulername = addslashes($rulername);
	    $result = mysql_query("UPDATE stats SET name = '$rulername' where id = $id");
		ECHO "Done =)";
	}
}
?>