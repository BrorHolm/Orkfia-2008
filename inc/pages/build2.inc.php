<?php

function include_build2_text()
{
    global  $build_cost, $local_goods, $max_build, $building_count, $wood_cost,
            $building_output, $building_variables, $local_build, $barren,
            $output_building_percent, $building_percent, $buildings_due,
            $current, $sub_current1, $sub_current2, $sub_current3, $userid,
            $local_pop;

    include_once('inc/functions/build.php');
    mysql_grab($userid, 'local', 'goods', 'build', 'stats', 'pop', 'army', 'user');
    build_cost();
    building_names();
    general_build();
    build_buildings();

    ECHO '<div class="center">' . "<br /><a href=main.php?cat=game&page=build>Return To Construction</a></div>";
}

?>
