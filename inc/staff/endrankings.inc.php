<?php
function call_endrankings_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    include_once('inc/classes/clsGame.php');
    $objGame = new clsGame();
    $iCurrentYear = $objGame->get_year_history();
    $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

    include_once('inc/classes/clsAge.php');
    $objNewAge = new clsAge();
    $objNewAge->loadAge($iAgeNumber);
    $iFirstYear = $objNewAge->getFirstYear();
    $iLastYear  = $objNewAge->getLastYear();

    // Alla genom history
    $resSQL2 = mysql_query("SELECT year, alli_id, alli_name, alli_desc FROM rankings_history WHERE alli_id > 10 AND year >= $iFirstYear AND year <= $iLastYear GROUP BY alli_id,year ASC");

    // Lista Alla För att summera i PHP
    $old_id = 0;
    $count = 0;
    while ($arrRow = mysql_fetch_assoc($resSQL2))
    {
        $new_id = $arrRow['alli_id'];

        if ($new_id != $old_id)
        {
            // prepare for new alliance
            $count++;

            // Copy SQL result row
            $arrAlliance[$count] = $arrRow;

            // add a starting year column to display period, eg 100 - 106 OE
            $arrAlliance[$count]['starting_year'] = $arrRow['year'];

            // add an ending year column to display period, eg 100 - 106 OE
            $arrAlliance[$count]['ending_year'] = $arrRow['year'];

            // add a year counter column, starting value = 1
            $arrAlliance[$count]['years'] = 1;

            // Save alliance id for next loop
            $old_id = $new_id;
        }
        elseif ($new_id == $old_id)
        {
            // add another year to our counter column
            $arrAlliance[$count]['years']++;

            // update ending year column
            $arrAlliance[$count]['ending_year'] = $arrRow['year'];
        }
    }

    //======================================================================
    // Sort the array
    //======================================================================
    // Obtain the column to sorty by
    foreach ($arrAlliance as $key => $row) {
       $years[$key] = $row['years'];
    }
    // Sort the data with years descending
    // Add $arrAlliance as the last parameter, to sort by the common key
    array_multisort($years, SORT_DESC, $arrAlliance);

    //======================================================================
    // Done sorting ;)
    //======================================================================

    echo "<h1>Age $iAgeNumber ($iFirstYear - $iLastYear OE)</h1>";
        echo "<h2>Top Alliance History</h2>";

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";

    $count = 1;
    foreach ($arrAlliance as $arrAlliance)
    {
        echo "<tr class=\"data\">";
            echo "<th width=\"25\">$count. </th>";
            echo "<th>" . stripslashes($arrAlliance['alli_name']) . "</th>";
            echo "<td class=\"left\">" . "(#<A HREF=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $arrAlliance['alli_id']."\">" . $arrAlliance['alli_id'] . "</A>)" . "</td>";
            echo "<td>" . $arrAlliance['years'] . " years on top</td>";
        $count++;
        echo "</tr>";
    }
    echo "</table>";

    //**************************************************************************
    // 2nd LIST
    //**************************************************************************
    function doit1()
    {
        include_once('inc/classes/clsGame.php');
        $objGame = new clsGame();
        $iCurrentYear = $objGame->get_year_history();
        $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

        include_once('inc/classes/clsAge.php');
        $objNewAge = new clsAge();
        $objNewAge->loadAge($iAgeNumber);
        $iFirstYear = $objNewAge->getFirstYear();
        $iLastYear  = $objNewAge->getLastYear();

        echo "<h2>Top Acreage History</h2>";
        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";

        $count = 1;
        $query  = mysql_query("SELECT MAX(land) as land,alli_id,year FROM rankings_history WHERE alli_id > 10 AND year >= $iFirstYear AND year <= $iLastYear GROUP BY alli_id ORDER BY land DESC LIMIT 9");
        while ($line = mysql_fetch_array($query))
        {
            $line2 = mysql_fetch_array(mysql_query("SELECT year,alli_name,alli_desc FROM rankings_history WHERE land = $line[land] AND alli_id = $line[alli_id]"));
            $cm = ($count % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th width=\"25\">$count.</th>";
                echo "<th>" . stripslashes($line2['alli_name'])/**/. " (#<A HREF=\"main.php?cat=game&amp;page=alliance&amp;aid=".$line['alli_id']."\">".$line['alli_id']."</a>)" /**/. "</th>";
                echo "<td>" . number_format($line['land']) . " acres </td>";
                echo "<td>(" . $line2['year'] . " OE)</td>";
            echo "</tr>";
            $count++;
        }
        echo "</table>";
    }
    // hehe... hate me :) "$life has no meaning outside life()"
    doit1();

    //**************************************************************************
    // 3rd LIST
    //**************************************************************************
    function doit()
    {
        include_once('inc/classes/clsGame.php');
        $objGame = new clsGame();
        $iCurrentYear = $objGame->get_year_history();
        $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

        include_once('inc/classes/clsAge.php');
        $objNewAge = new clsAge();
        $objNewAge->loadAge($iAgeNumber);
        $iFirstYear = $objNewAge->getFirstYear();
        $iLastYear  = $objNewAge->getLastYear();

        // Alla genom history
        $resSQL2 = mysql_query("SELECT year, alli_id, alli_name, alli_desc FROM rankings_history WHERE alli_id > 10 AND year >= $iFirstYear AND year <= $iLastYear GROUP BY year ASC");

        // Lista Alla För att summera i PHP
        $old_id = 0;
        $count = 0;
        while ($arrRow = mysql_fetch_assoc($resSQL2))
        {
            $new_id = $arrRow['alli_id'];

            if ($new_id != $old_id)
            {
                // prepare for new alliance
                $count++;

                // Copy SQL result row
                $arrAlliance[$count] = $arrRow;

                // add a starting year column to display period, eg 100 - 106 OE
                $arrAlliance[$count]['starting_year'] = $arrRow['year'];

                // add an ending year column to display period, eg 100 - 106 OE
                $arrAlliance[$count]['ending_year'] = $arrRow['year'];

                // add a year counter column, starting value = 1
                $arrAlliance[$count]['years'] = 1;

                // Save alliance id for next loop
                $old_id = $new_id;
            }
            elseif ($new_id == $old_id)
            {
                // add another year to our counter column
                $arrAlliance[$count]['years']++;

                // update ending year column
                $arrAlliance[$count]['ending_year'] = $arrRow['year'];
            }
        }

        //======================================================================
        // Sort the array
        //======================================================================
        // Obtain the column to sorty by
        foreach ($arrAlliance as $key => $row) {
           $years[$key] = $row['starting_year'];
        }
        // Sort the data with years descending
        // Add $arrAlliance as the last parameter, to sort by the common key
        array_multisort($years, SORT_ASC, $arrAlliance);

        //======================================================================
        // Done sorting ;)
        //======================================================================

        echo "<h2>Timeline</h2>";

            echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";

        $count = 1;
        foreach ($arrAlliance as $arrAlliance)
        {
            echo "<tr class=\"data\">";
                echo "<td class=\"left\">(" . $arrAlliance['starting_year'] . ' - ' . $arrAlliance['ending_year'] . " OE) </td>";
                echo "<th>" . stripslashes($arrAlliance['alli_name']) . "</th>";
                echo "<td class=\"left\">" . "(#<A HREF=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $arrAlliance['alli_id']."\">" . $arrAlliance['alli_id'] . "</A>)" . "</td>";
                echo "<td>" . $arrAlliance['years'] . " years on top </td>";
            $count++;
            echo "</tr>";
        }
        echo "</table>";
    }

    // hehe... hate me :) "$life has no meaning outside life()"
    doit();
}
?>