<?
function call_tribename_text(){
    global $id,$confirm,$Aname,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input ID#: <input name=id size=5><br>";
    ECHO "Change Name to: <input type=text name=Aname maxlength=30 size=25><br>";
    ECHO "<input type=submit value=Save name=confirm>";
    ECHO "</form>";
    ECHO "<br><br>";
    IF($confirm && $id && $Aname)
    {
    	$Aname = quote_smart(strip_tags(trim($Aname)));
        $check = mysql_query("SELECT * FROM stats WHERE tribe = $Aname AND id != $id");
        if(mysql_num_rows($check) != 0){
            echo "that name is already in use";
        } else {
            $result = mysql_query("UPDATE stats SET tribe = $Aname where id = $id");
            $result = mysql_query("UPDATE ranking_write SET tribe_name = $Aname where id = $id");
            ECHO "Done =)";
        }
    }
}
?>
