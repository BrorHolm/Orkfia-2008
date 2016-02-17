<?php

function include_raze_build2_text()
{
    global  $local_goods, $local_army, $local_build, $barren, $userid,
            $local_pop, $collapse, $local_stats, $unit_var, $unit_names,
            $gold_unit, $army_due, $max_train, $raze, $connection, $build_cost,
            $local_goods, $max_build, $building_count, $building_output,
            $building_variables, $local_build, $barren,
            $output_building_percent, $building_percent, $buildings_due,
            $current, $sub_current1, $sub_current2, $sub_current3, $userid,
            $local_pop;

    include_once('inc/functions/build.php');
    mysql_grab($userid, 'local', 'goods', 'build', 'stats', 'pop', 'army', 'user');
    build_cost();
    building_names();
    general_build();

    echo '<div id="textMedium">' . '<h2>' . "Demolision Report" . '</h2>' ;

    $yes = 'no';

    for ($i = 1; $i <= $building_count ; $i++)
    {
        $current = $building_variables[$i];
        $collapse[$current] = floor($collapse[$current]);

        if ($collapse[$current] <= 0)
        {
            $collapse[$current] = 0;
        }

        if ($collapse[$current] >= "1")
        {
            $yes="yes";

            IF($collapse[$current] > $local_build[$current])
            {
                ECHO "<p>Wait... You do  not own $collapse[$current] acres of $building_output[$i], so how can you demolish them?<br><br>";
                ECHO "<a href= main.php?cat=game&page=raze_build>Try Again</a></p></div>";

                include_game_down();
                exit;
            }

            ECHO "<p>$collapse[$current] $building_output[$i]</p>";

            $new['build']    = $local_build[$current] - $collapse[$current];
            $update['build'] = "UPDATE build SET $current ='$new[build]' WHERE id= $userid";
            $update['build'] = mysql_query($update['build'], $connection);
        }
    }

    if ($yes == "yes")
    {
        ECHO "<p>Have been demolished. Such a waste of crowns and logs...</p>";
    }

    if ($yes != "yes")
    {
        ECHO "<p>Sorry, what did you order demolished?</p>";
    }

    ECHO "<p><a href= main.php?cat=game&page=raze_build>Demolish More</a> :: ";
    ECHO "<a href=main.php?cat=game&page=build>Return To Construction</a></p>";
    echo "</div>";
}

?>
