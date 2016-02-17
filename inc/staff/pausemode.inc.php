<?
function call_pausemode_text()
{
	global $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
$res = mysql_query("SELECT PauseModeActive FROM admin_switches");
$line = mysql_fetch_assoc($res);
echo "Pause-mode is currently: " . $line["PauseModeActive"] . "<BR>";
	
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
	?>
<INPUT type="submit" value="Enable Pause-mode" name="enable">
</FORM>
	<? echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">"; ?>
<INPUT type="submit" value="Disable Pause-mode" name="disable">
</FORM>
<?
	if ($enable)
	{
		mysql_query("UPDATE admin_switches SET PauseModeActive = 'on'");
		echo "Pause-mode enabled!";
	}
	if ($disable)
	{
		mysql_query("UPDATE admin_switches SET PauseModeActive = 'off'");
		echo "Pause-mode disabled!";
	}
}

?>