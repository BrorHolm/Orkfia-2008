<?php

function include_build_text()
{
    global  $Host, $build_cost, $local_goods, $max_build,
            $local_build,
            $wood_cost, $output_building_percent, $building_percent,
            $buildings_due, $current, $sub_current1, $sub_current2,
            $sub_current3, $userid, $local_pop;

    $objSrcUser = &$GLOBALS["objSrcUser"];

    include_once('inc/functions/build.php');
//     mysql_grab($userid, 'local', 'goods', 'build', 'pop'); 1 1 down...
    $local_goods = $objSrcUser->get_goods();
    $local_build = $objSrcUser->get_builds();
    $local_pop = $objSrcUser->get_pops();
    build_cost();
    building_names();
    general_build();

    // New object oriented code

    $arrBuildingVariables = getBuildingVariables($objSrcUser->get_stat(RACE));

    $arrBuildVar     = $arrBuildingVariables['variables'];
    $arrBuildOutput  = $arrBuildingVariables['output'];
    $arrBuildTooltip = $arrBuildingVariables['tooltips'];
    $iBuildings      = count($arrBuildVar);
    $iBarren         = $objSrcUser->get_barren();

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser);

    $arrSrcStats = $objSrcUser->get_stats();
    $arrBuild = $objSrcUser->get_builds();

    echo $topLinks =
        '<div class="center">' .
            '<b>Construction</b> | ' .
            '<a href="main.php?cat=game&amp;page=army">Military Training</a> | ' .
            '<a href="main.php?cat=game&amp;page=explore">Exploration</a>' .
        '</div>';

    $advisorText =
        '<div id="textBig">' .
        '<p>' .
            "<b>Your tribe architect</b> greets you: <br />" .
            "To let your civilization grow and prosper, you must build your land. " .
            "How you build your land is completely up to you, " .
            "but some buildings are necessary for your people to survive." .
        "</p>" .
        "<p>" .
            "It will cost you <b>" . number_format($build_cost) . " crowns</b>" .
            " and <b>" . number_format($wood_cost) . " logs</b> to build on " .
            "<b>1 barren acre</b>." .
        '</p>' .
        '<p align="center">' .
            "You have <b><span class=\"indicator\">$iBarren</span> barren " .
            "acre(s)</b> and you can afford to " .
            "build on <b class=\"indicator\">$max_build</b>" .
            " of them." .
        "</p>" .
        "</div>";
    echo $advisorText;

    $advisorLinks =
        '<br />' .
        '<div class="tableLinkBig">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=build">Tribe Architect</a>' .
            ' :: ' .
            '<a href="main.php?cat=game&amp;page=raze_build">Demolish Buildings</a>' .
        '</div>';
    echo $advisorLinks;

    $buildTable =
        '<form id="center" action="main.php?cat=game&amp;page=build2" method="post" style="margin-top: 0pt;">' .
        '<table class="big" cellpadding="0" cellspacing="0">' .
            '<tr class="header">' .
                '<th colspan="9">' .'Order Construction' . '</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th>' . 'Type' . '</th>' .
                '<th class="right">' . 'Owned' . '</th>' .
                '<th class="right">' . '%' . '</th>' .
                '<th class="right">' . 'Build' . '</th>' .
                '<th class="right">' . '&nbsp;' . '</th>' .
                '<th>' . 'Type' . '</th>' .
                '<th class="right">' . 'Owned' . '</th>' .
                '<th class="right">' . '%' . '</th>' .
                '<th class="right">' . 'Build' . '</th>' .
            '</tr>';

    for ($i = 1; $i <= $iBuildings; $i++)
    {
        $current = $arrBuildVar[$i];
        $i2 = $i + 1;
        $current2 = $arrBuildVar[$i2];

        $buildTable .=
            '<tr class="data">' .
                '<th width="20%"><label for="' . $i . '" title="' . $arrBuildTooltip[$i] . '">' . $arrBuildOutput[$i] . '</label></th>' .
                '<td width="12%">';

        if ($buildings_due[$arrBuildVar[$i]] > 0)
        {
            $buildTable .=
                '<span style="font-size: 0.8em;">(' .
                $buildings_due[$arrBuildVar[$i]] .
                ')</span>';
        }

        $buildTable .=
                ' <strong>' . $local_build[$current] . '</strong></td>' .
                '<td width="7%">' .
                getBuildInPercent($objSrcUser, $local_build[$current], 'yes') .
                "</td>" .
                "<td width=\"7%\"><input id=\"$i\" size=\"3\" maxlength=\"4\" name=\"built[$current]\" /></td>" .
                "<td>&nbsp;</td>" .
                '<th width=\"20%\"><label for="' . $i2 . '" title="' . $arrBuildTooltip[$i2] . '">' . $arrBuildOutput[$i2] . '</label></th>' .
                "<td width=\"12%\" class=\"right\">";

        if ($buildings_due[$arrBuildVar[$i2]] > 0)
        {
            $buildTable .=
                '<span style="font-size: 0.8em;">(' .
                $buildings_due[$arrBuildVar[$i2]] . ")</span>";
        }

        $buildTable .=
                "<strong> $local_build[$current2]</strong>" . "</td>" .
                "<td width=\"7%\" class=\"right\">" .
                getBuildInPercent($objSrcUser, $local_build[$current2], 'yes') .
                "</td>" .
                "<td width=\"7%\" class=\"right\">" .
                "<input id=\"$i2\" size=\"3\" maxlength=\"4\" name=\"built[$current2]\" />" . "</td>" .
            "</tr>";
        $i++;
    }

    $buildTable .=
        "</table>";
    echo $buildTable;

?>
        <span style="font-size: 0.8em;">Total <b><?=$local_build[LAND]; ?></b> acres.</span>
        <br /><br />
        <input type="submit" value="Order Construction" />
    </form>
<?

}

?>
