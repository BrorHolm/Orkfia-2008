<?
function call_view_text()
{
    global  $id, $op, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id: <input name=id size=5><br><br><br>";
    ECHO "<input type=submit value=Vision name=op>";
    ECHO "</form>";
    IF($op && $id)
    {
        IF($op == "Vision")
        {
            include('inc/functions/magic.php');
            include('inc/functions/power.php');
            include('inc/functions/tribe.php');
            $objTrgUser = new clsUser($id);
            echo get_tribe_table($objTrgUser);
        }
    }
}
?>
