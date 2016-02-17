<?
function include_raze_build_text()
{
    global  $Host, $build_cost, $local_goods, $max_build, $building_count,
            $building_output, $building_variables, $local_build, $barren,
            $output_building_percent, $building_percent, $buildings_due,
            $current, $sub_current1, $sub_current2, $sub_current3, $userid,
            $local_pop;

    include_once('inc/functions/build.php');
    mysql_grab($userid, 'local', 'goods', 'build', 'pop');
    build_cost();
    building_names();
    general_build();

    $advisorLinks =
        '<div class="tableLinkBig">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=build">Tribe Architect</a>' .
            ' :: ' .
            '<a href="main.php?cat=game&amp;page=build">Construction</a>' .
        '</div>';
    echo $advisorLinks;

    ECHO "<form action=\"main.php?cat=game&amp;page=raze_build2\" method=\"post\">";
    ECHO "<table class=\"big\" cellpadding=0 cellspacing=0>";
        ECHO "<tr class=\"header\"><th colspan=\"9\">";
        ECHO "Demolish Buildings";
        ECHO "</th></tr>";

        ECHO "<tr class=\"subheader\">";
            ECHO "<th>Type</th>";
            ECHO "<th class=\"right\">Owned</th>";
            ECHO "<th class=\"right\">Collapse</th>";
            ECHO "<th>&nbsp;</th>";
            ECHO "<th>Type</th>";
            ECHO "<th class=\"right\">Owned</th>";
            ECHO "<th class=\"right\">Collapse</th>";
        ECHO "</tr>";

    for ($i = 1; $i <= $building_count; $i++)
    {
        $current = $building_variables[$i];
        $i2 = $i + 1;
        $current2 = $building_variables[$i2];

        ECHO "<tr class=\"data\">";
            ECHO "<th width=\"20%\">$building_output[$i]</th>";
            ECHO "<td width=\"12%\" class=\"right\">";
            IF ($buildings_due[$building_variables[$i]] > 0) {
                ECHO "<span style=\"font-size: 0.8em;\">(".$buildings_due[$building_variables[$i]].")</span> ";
            }
            ECHO "<b>$local_build[$current]</b></td>";
            ECHO "<td width=\"13%\" class=\"right\"><input size=5 name=\"collapse[$current]\"></td>";
            ECHO "<td>&nbsp;</td>";
            ECHO "<th width=\"20%\">$building_output[$i2]</th>";
            ECHO "<td width=\"12%\" class=\"right\">";
            IF ($buildings_due[$building_variables[$i2]] > 0) {
                ECHO "<span style=\"font-size: 0.8em;\">(".$buildings_due[$building_variables[$i2]].")</span> ";
            }
            ECHO "<b>$local_build[$current2]</b></td>";
            ECHO "<td width=\"13%\" class=\"right\"><input size=5 name=\"collapse[$current2]\"></td>";
        ECHO "</tr>";
        $i = $i +1;
    }
    ECHO "</table>";
    ECHO '<div class="center" style="font-size: 0.8em;">' . "Total <strong>$local_build[land]</strong> acres.</div>";
    ECHO '<div class="center">' . '<input type="submit" value="Demolish Buildings"></div>';

    ECHO "</form>";
}

?>