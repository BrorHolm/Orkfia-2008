<?
function call_banner_text(){
    global $local_stats, $id, $confirm, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($confirm && $id){
		IF($id == 1) 
		{
			ECHO "ohhhh you dont want to do that, trust me $local_stats[name] =P";
		}else {
		ECHO "Alliance #$id has had its banner removed!";
        $result = mysql_query("UPDATE kingdom SET image = '' where id = $id");
	    }
    }
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input alliance #: <input name=id size=5><br>";
    ECHO "<input type=submit value=Remove_banner name=confirm>";
    ECHO "</form>";
    ECHO "<br><br>";
    
}
?>
