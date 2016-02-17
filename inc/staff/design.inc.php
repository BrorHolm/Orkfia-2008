<?php

function call_design_text()
{
    global $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $green = mysql_query("SELECT count(*) as green FROM design WHERE color = 'green'");
    $green = mysql_fetch_array($green);
    $blue = mysql_query("SELECT count(*) as blue FROM design WHERE color = 'blue'");
    $blue = mysql_fetch_array($blue);
    $black = mysql_query("SELECT count(*) as black FROM design WHERE color = 'black_v2'");
    $black = mysql_fetch_array($black);
    $ice = mysql_query("SELECT count(*) as ice FROM design WHERE color = 'ice'");
    $ice = mysql_fetch_array($ice);
    $original = mysql_query("SELECT count(*) as original FROM design WHERE color = 'original'");
    $original = mysql_fetch_array($original);
    $forest = mysql_query("SELECT count(*) as forest FROM design WHERE color = 'forest_v1'");
    $forest = mysql_fetch_array($forest);
    $red = mysql_query("SELECT count(*) as red FROM design WHERE color = 'red'");
    $red = mysql_fetch_array($red);

    ECHO "<br><br>";
    ECHO "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">";
    ECHO "<tr class=header><th colspan=2>Color Schemes</th></tr>";
    ECHO "<tr class=subheader><th>Color</th>";
    ECHO "<th class=right>Amount</th></tr>";
    ECHO "<tr class=data><th>Forest Greens:</th>";
    ECHO "<td>".$forest['forest']."</td></tr>";
    ECHO "<tr class=data><th>Original:</th>";
    ECHO "<td>".$original['original']."</td></tr>";
    ECHO "<tr class=data><th>Green:</th>";
    ECHO "<td>".$green['green']."</td></tr>";
    ECHO "<tr class=data><th>Blue:</th>";
    ECHO "<td>".$blue['blue']."</td></tr>";
    ECHO "<tr class=data><th>Cursed Nights:</th>";
    ECHO "<td>".$black['black']."</td></tr>";
    ECHO "<tr class=data><th>Ice:</th>";
    ECHO "<td>".$ice['ice']."</td></tr>";
    ECHO "<tr class=data><th>Red:</th>";
    ECHO "<td>".$red['red']."</td></tr>";
    ECHO "</table>";

    $small = mysql_query("SELECT count(*) as small FROM design WHERE width = '750'");
    $small = mysql_fetch_array($small);
    $medium = mysql_query("SELECT count(*) as medium FROM design WHERE width = '1060'");
    $medium = mysql_fetch_array($medium);

    ECHO "<br><br>";
    ECHO "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">";
    ECHO "<tr class=header><th colspan=2>Resolution</th></tr>";
    ECHO "<tr class=subheader><th>Width</th>";
    ECHO "<td>Amount</td></tr>";
    ECHO "<tr class=data><th>750:</th>";
    ECHO "<td>".$small['small']."</td></tr>";
    ECHO "<tr class=data><th>1060:</th>";
    ECHO "<td>".$medium['medium']."</td></tr>";
    ECHO "</table>";
}
?>