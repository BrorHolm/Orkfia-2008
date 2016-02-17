<?
// Martel
// July 25, 2005
// last function took me 5 hours ... saving time on this one

function call_makestaff_text()
{
    global $local_stats, $submit, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	ECHO "<b>Promote to Staff</b><br><br>";
    
    include('inc/functions/vote.php');
    
    // select everyone in this alliance
        $strSQL = "SELECT * " .
        "  FROM stats " .
        " WHERE kingdom = " . $local_stats[ALLIANCE] .
        " ORDER BY fame DESC";
        $result = mysql_query ($strSQL) or die("MySQL error: " . mysql_error());
	
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    echo "<select name=submit[promote] size=1>";
    echo render_option_list($result, TRIBE, ID, $intCurrentVote);
    echo "</select><br>";
    ECHO "<input type=submit name=submit[submit] value='Give WTB Access'> " .
         "<input type=submit name=submit[submit2] value='Give STAFF Access'></form>";
    
    if ($submit && !$submit['submit2'])
    {
	    mysql_query("UPDATE stats SET level='2' WHERE id = '$submit[promote]' AND level = '0'") or die("MySQL error: " . mysql_error());
	    ECHO "User should now have access to the resort tools.";
    }
    elseif ($submit && $submit['submit2'])
    {
	    mysql_query("UPDATE stats SET level='3' WHERE id = '$submit[promote]' AND level = '2'") or die("MySQL error: " . mysql_error());
	    ECHO "Make sure selected person was made wtb first, else this didn't work. <br />But he/she should now have access to the resort tools.";
    }
}