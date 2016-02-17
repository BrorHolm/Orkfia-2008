<?php
//******************************************************************************
// staff tool signups.inc.php                               Martel, May 24, 2007
//******************************************************************************
function call_signups_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    ECHO "<h2>Signup History</h2>";

//     $show_time = date(TIMESTAMP_FORMAT, strtotime("-7 days"));

    if (TRUE || $_POST['go'] == "yes")
    {
        $strSQL = 'SELECT YEAR(signup_time) as year, MONTH(signup_time) as month, DAY(signup_time) AS day, count(*) as signups FROM gamestats'
                . ' GROUP BY year,month,day'
                . ' ORDER BY year DESC,month DESC,day DESC LIMIT 0, 30';

        ECHO "<table class=tiny cellspacing=0 cellpadding=0>";
        ECHO "<tr class=header><th colspan=4>Signup History</th></tr>";
        ECHO "<tr class=subheader><th> Year </th><td> Month </td><td> Day </td><td> Signups </td></tr>";

        $result = mysql_query($strSQL) or die("query error");
        while ($value = mysql_fetch_array ($result, MYSQL_ASSOC))
        {
            echo $strTableTr =
            "<tr class=data>" .
                "<th>" .
                    $value['year'] .
                '</th>' .
                '<td>' .
                    $value['month'] .
                '</td>' .
                '<td>' .
                    $value['day'] .
                '</td>' .
                '<td>' .
                    $value['signups'] .
                '</td>' .
            '</tr>';
        }
        ECHO "</table> <br />";
        echo "Prediction for tomorrow: ";
    }

    echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=resort_tools&amp;tool=signups\">";
    echo "<br />";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show me\"></form>";

}
?>

