<?
// Martel
// July 25, 2005
function call_bootcamp_text()
{
    global $id, $submit, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "<b>Boot Camp Tools</b><br><br>";

    $query = mysql_query("SELECT id,name from kingdom WHERE bootcamp='yes'") or die("Sql error: " . mysql_error());

    ECHO "<table class=small cellpadding=0 cellspacing=0>";
    ECHO "<tr class=header><th colspan=4>Existing Boot Camps</th></tr>";
    ECHO "<tr class=subheader><th>Alliance Name</th></tr>";
    $i = 1;
    while ($row = mysql_fetch_array($query))
    {
        ECHO "<tr class=data><td>$i. $row[name] (#$row[id])</td></tr>";
        $i++;
    }
    if ($i == 1)
        ECHO "<tr class=data><td class=center><i>There Are No Bootcamps</i></td></tr>";

    ECHO "</table><br>";

    ECHO "Enter alliance number: <input name=id size=5><br>";
    ECHO "Should this alliance be a boot camp?<br><br>";
    ECHO "<input type=submit value=yes name=submit>";
    ECHO "<input type=submit value=no name=submit>";
    ECHO "</form>";

    if ($id && $submit) // make sure that an alliance number has been provided
    {
        if ($id > 10)
        {
            mysql_query("UPDATE kingdom SET bootcamp='$submit' where id='$id'") or die("Sql error: " . mysql_error());
            header('location: main.php?cat=game&page=resort_tools&tool=bootcamp');
        }
        elseif ($id < 11 && $id > 0)
        {
            ECHO "You can't change boot camp status for staff alliances.";
        }
        else
        {
            ECHO "Invalid alliance number. ";
        }
    }
}
?>