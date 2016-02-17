<?
function call_userlevel_text()
{
    global $id,$confirm,$level,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id: <input name=id size=5><br>";
	ECHO "Change Level to: <input name=level size=5><br>";
	ECHO "<br><br>Level 2 - WTB Member";
	ECHO "<br>Level 3 - Staff Member";
	ECHO "<br>Level 5 - Head of Resort";
	ECHO "<br>Level 6 - Head of ORKFiA";
    ECHO "<br><br>";
    ECHO "<input type=submit value=Save name=confirm>";
    ECHO "</form>";
    
    IF($confirm && $id && $level)
    {
		IF ($level < 7)
		{
    		$result = mysql_query("UPDATE stats SET level = $level where id = $id");
    		//$result = mysql_query("UPDATE user SET logins = 40 where id = $id");
		}
        
		$seek = mysql_query("Select * from stats where id = $id");
		$seek = mysql_fetch_array($seek);
		ECHO "$seek[name] ($seek[tribe] #$seek[kingdom]) with User #$id, is now level $seek[level], ";
		IF ($level < 0) {ECHO "This will cause errors, <br><br>levels are 0,2,3,4,5,6... nothing else!";}
		IF ($level == 2) {ECHO "WTB Member.";}
		IF ($level == 3) {ECHO "Staff Member.";}
		IF ($level == 5) {ECHO "Head of Resort.";}
		IF ($level == 6) {ECHO "Head of ORKFiA.";}
		IF ($level > 6) {ECHO "This will cause errors, <br><br>levels are 0,3,4,5,6... nothing else!";}
	}
}
?>