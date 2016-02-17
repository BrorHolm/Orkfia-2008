<?php

function call_phpinfo_text()
{
    global $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<br /><br /><br /><br /><br />";
	echo phpinfo();
}

?>