<?php
function call_fix_alliances_text()
{
    global $sure, $orkTime, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    // This query find alliances that lack a kingdom table
    $query1  = "SELECT stats.kingdom AS id FROM stats GROUP BY kingdom HAVING id NOT IN (SELECT kingdom.id AS id FROM kingdom)";
    $query1  = mysql_query($query1);
    $amount1 = mysql_num_rows($query1);    
    
    
    if (isset($_POST['sure']))
    {
        // Perform post-action
        $i = 0;
        while ($resIds  = mysql_fetch_array($query1))
        {
            if ($resIds[id] != '0')
            {
                mysql_query("REPLACE INTO " . TBL_ALLIANCE . " SET ID=$resIds[id]")          or die("alliance table");
                mysql_query("REPLACE INTO " . TBL_RANKINGS_ALLIANCE . " SET ID=$resIds[id]") or die("alliance_rankings table");
                mysql_query("REPLACE INTO " . TBL_WAR . " SET ID=$resIds[id]")               or die("war table");
                $i++;
            }
            else
                echo "(alliance #0 cannot be created - instead visit tribe page to remedy this)";
        }
        echo "$i DONE";
    }
    else
    {
	    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
        echo "Missing: " . $amount1 . " tables";
        ECHO "<br>Add tables for these alliances? (alliance, rankings_aliance & war): <input type='checkbox' name='sure'>";
        ECHO "<br><input type=submit value=Insert></form>";
    }
}

?>