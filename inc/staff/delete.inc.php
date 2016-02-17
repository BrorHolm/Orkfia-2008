<?
function call_delete_text()
{
    global $go, $delete_id, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    if ($go != "sure" && $go != "yes")
    {
        ECHO "<br><b>THIS IS THE DELETE FUNCTION!! MAKE SURE YOU DONT MISCLICK AND DOUBLE CHECK ALL IDs</b>";
        echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
        ECHO "Input user id to delete:<input type=text size=5 name=delete_id>";
        ECHO "<input type=hidden name=go value=sure>";
        ECHO "<br><input type=submit value=delete></form>";
    }

    if ($delete_id && $go == "sure" && $delete_id != '1')
    {
        $name = mysql_query("SELECT tribe, kingdom FROM stats WHERE id = $delete_id");
        $name = mysql_fetch_array($name);
        echo "THE USER YOU ARE ABOUT TO DELETE IS:<BR>";
        echo "$name[tribe](#$name[kingdom])<br><br>";
        echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
        echo "<input type=hidden name=go value=yes>";
        echo "<input type=hidden name=delete_id value=$delete_id>";
        echo "<br><input type=submit value=delete></form>";
    }

    if ($delete_id && $go == "yes" && $delete_id != '1')
    {
        if (doBackupTribe($delete_id, 'delete'))
        {
            ECHO "User #$delete_id has been deleted <br /><br /> (Comment by Martel: back-up exists.)";
        }
        else
        {
            echo "(Backup failed!) User #$delete_id has been deleted.";
        }

        // Remove invested RPs
//         include_once("inc/functions/research.php");
//         delete_my_rps($delete_id);

        // Delete Tribe (an automatic routine will remove it from alliance #0)
        mysql_query("UPDATE " . TBL_STAT . " SET " . ALLIANCE . " = 0 WHERE id = $delete_id");
        mysql_query("UPDATE " . TBL_RANKINGS_PERSONAL . " SET " . ALLI_ID . " = 0 WHERE id = $delete_id");
    }
}

//==============================================================================
// New backup routine                                      Martel, July 12, 2006
//==============================================================================
function doBackupTribe($delete_id, $strType)
{
    $arrUserTables = array(1 => TBL_ARMY, TBL_ARMY_MERCS, TBL_BUILD,
    TBL_DESIGN, TBL_GOODS, TBL_KILLS, TBL_MILRETURN, TBL_ONLINE, TBL_POP,
    TBL_PREFERENCES, TBL_RANKINGS_PERSONAL, TBL_SPELL, TBL_STAT,
    TBL_THIEVERY, TBL_USER);

    $string = '';
    foreach ($arrUserTables as $strTable)
    {
        $string .=
        'INSERT INTO ' . $strTable .
               ' SET ';

        $resSQL = mysql_query("SELECT * FROM " . $strTable . " WHERE id = " .
                                                                $delete_id);
        if ($arrRes = mysql_fetch_assoc($resSQL))
        {

            foreach ($arrRes as $key => $value)
            {
                $string .=
                $key . " = '" . $value . "', ";
            }
            $string = substr($string, 0, -2);
            $string .=
                ";\r\n";
        }
        else
        {
            $string .=
                ID . " = " . $delete_id .
                ";\r\n";
        }
    }
    $string .= "\r\n\r\n ======================================== \r\n\r\n";

    $fp = fopen("admin/$strType.txt", "a+");
    if ($fp)
    {
        fwrite($fp, "$string"); fclose($fp);
        return TRUE;
    }
    else
        return FALSE;
}

?>
