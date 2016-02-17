<?
function call_alliname_text()
{
    global $id, $confirm, $Aname, $Adesc, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    IF($confirm && $id && ($Aname || $Adesc))
    {
        $result = mysql_query("UPDATE kingdom SET name = '$Aname', description = '$Adesc' WHERE id = '$id'");
        $result = mysql_query("UPDATE rankings_alliance SET alli_name = '$Aname', alli_desc = '$Adesc' WHERE id = '$id'");
        ECHO "Done =)";
    }
    else
    {
        ECHO "<FORM METHOD='post' ACTION=\"".$_SERVER['REQUEST_URI']."\">";
        if (! isset($_POST['id']))
        {
            ECHO "Input Alliance #: <input name='id' value=\"$_POST[id]\" size=5><br><br>";
        }
        else
        {
            echo "<input type='hidden' name='id' value=\"$_POST[id]\">";
            $alliance = mysql_query("SELECT * FROM kingdom WHERE id ='$_POST[id]'") or die ('Staff tool: alliname DB-error.');
            $alliance = mysql_fetch_array($alliance);
            echo "Change Name to: <input type='text' name='Aname' value=\"$alliance[name]\" maxlength='15' size='15'><br><br>";
            echo "Change Description to: <input type='text' name='Adesc' value=\"$alliance[description]\" maxlength='30' size='30'><br><br> ";
        }
        ECHO "<input type='submit' value='OK' name='confirm'>";
        ECHO "</form>";
    }
}
?>