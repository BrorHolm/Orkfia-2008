<?php
function include_rankings_text()
{
    global $Host;

    require_once('inc/races/clsRace.php');
    include_once('inc/classes/clsGame.php');
    $objGame    = new clsGame();
    $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

    $show = 'alliance';
    if (isset($_GET['show'])) { $show = $_GET['show']; }
    echo get_rankings_links($show);

    $link = "main.php?cat=game&amp;page=rankings&amp;show=$show&amp;type=";

    $rankingsText = '<div id="textBig">';

    switch($show)
    {
        case "annual":

            $rankingsText .=
                "<h2>History Rankings</h2>" .
                "<h3>Alliance</h3>" .
                "<p>" .
                " | <a href=\"" . $link . "currentage\">Current Age</a> |" .
                "   <a href=\"" . $link . "topalli\">King Of The Hill</a> |" .
                "   <a href=\"" . $link . "topland\">Largest Alliance</a> |" .
//                 "   <a href=\"" . $link . "truetopalli\">Famous Names</a> |" .
//                 "   <a href=\"" . $link . "truetopalliid\">Famous #'s</a> |" .
                "</p>";

            if (!isset($_GET['type']))
                $_GET['type'] = 'currentage';

        break;
        case "personal":

            $rankingsText .=
            "<h2>Personal Rankings</h2>" .
            "<p> | <a href=\"" . $link . "pland\">Land</a> |" .
            "   <a href=\"" . $link . "pstr\">Strength</a> |" .
            "   <a href=\"" . $link . "pfame\">Fame</a> |" .

            "</p><p>";

            //modified to find out about races dynamically - AI 17/02/2007
            $races = clsRace::getRaces();
            $active_races = clsRace::getActiveRaces();
            foreach($races as $number => $race)
            {
                if(($number % 3) == 0)
                    $rankingsText .= '- ';
                $style = '';
                if (!in_array($race,$active_races))
                    $style = 'style="font-style:italic;"';
                $rankingsText .= "<span $style>$race [ " .
                            "<a href=\"". $link ."rland&amp;race=$number\">Land</a> | " .
                            "<a href=\"". $link ."rstr&amp;race=$number\">Strength</a> | " .
                            "<a href=\"". $link ."rfame&amp;race=$number\">Fame</a> ]  </span>";
                if(($number % 3) == 2)
                    $rankingsText .= '<br />';
            }

            $rankingsText .= "</p>";

            if (!isset($_GET['type']))
                $_GET['type'] = 'pland';

        break;
        case "alliance":

            $rankingsText .=
            "<h2>Alliance Rankings</h2>" .
            "<p>| <a href=\"" . $link . "aland\">Land</a> | " .
            "   <a href=\"" . $link . "astr\">Strength</a> | " .
            "   <a href=\"" . $link . "afame\">Fame</a> | " .
            "<a href=\"" . $link . "support\">Top Voting Supporters</a></p>";

            if (!isset($_GET['type']))
                $_GET['type'] = 'aland';

        break;
    }
    $rankingsText .= '</div>';
    echo $rankingsText;

    if (!isset($_GET['type']))
    {
        include_game_down();
        exit;
    }
    $strType = $_GET["type"];

    if ($strType == "pstr") {
        $strTitle = "Individual Strength Rankings";
        $strRankingType = "p";
        $type = "Strength";
        $dbtype = "nw";
    } elseif ($strType == "pfame") {
        $strTitle = "Individual Fame Rankings";
        $strRankingType = "p";
        $type = "Fame";
        $dbtype = "fame";
    } elseif ($strType == "pland") {
        $strTitle = "Individual Land Rankings";
        $strRankingType = "p";
        $type = "Acres";
        $dbtype = "land";
    } elseif ($strType == "astr") {
        $strTitle = "Alliance Strength Rankings";
        $strRankingType = "a";
        $type = "Strength";
        $dbtype = "nw";
    } elseif ($strType == "aland") {
        $strTitle = "Alliance Land Rankings";
        $strRankingType = "a";
        $type = "Acres";
        $dbtype = "land";
    } elseif ($strType == "afame") {
        $strTitle = "Alliance Fame Rankings";
        $strRankingType = "a";
        $type = "Fame";
        $dbtype = "fame";
    } elseif ($strType == "rstr") {
        $strTitle = "Individual Race Strength Rankings";
        $strRankingType = "r";
        $type = "Strength";
        $dbtype = "nw";
    } elseif ($strType == "rland") {
        $strTitle = "Individual Race Land Rankings";
        $strRankingType = "r";
        $type = "Acres";
        $dbtype = "land";
    } elseif ($strType == "rfame") {
        $strTitle = "Individual Race Fame Rankings";
        $strRankingType = "r";
        $type = "Fame";
        $dbtype = "fame";
    } elseif ($strType == "support") {
        $strTitle = "Top Supporters";
        $strRankingType = "s";
    } elseif ($strType == "topalli") {
        $strTitle = "King Of The Hill History";
        $strRankingType = "ta";
    } elseif ($strType == "topland") {
        $strTitle = "Largest Alliance History";
        $strRankingType = "tl";
    } elseif ($strType == "currentage") {
        $strTitle = "Age $iAgeNumber Rankings";
        $strRankingType = "ca";
    }
//     elseif ($strType == "truetopalli") {
//         $strTitle = "King Of The Hill (In Recent History)";
//         $strRankingType = "tta";
//         $dbtype = "alli_name";
//     } elseif ($strType == "truetopalliid") {
//         $strTitle = "King Of The Hill (In Recent History)";
//         $strRankingType = "tta";
//         $dbtype = "alli_id";
//     }


    if (isset($_GET['race']))
    {
        $race = $_GET['race'];

        //modified to be dynamic - AI 17/02/2007
        $races = clsRace::getRaces();
        $race = $races[$race];
    }

    echo "<br />";

    if ($strRankingType == "p")
    {
        $res = mysql_query("SELECT id,tribe_name,race,alli_id,". $dbtype ." FROM rankings_personal WHERE alli_id > 10 ORDER BY " . $dbtype . " DESC LIMIT 200");

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"4\">" .$strTitle . "</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th>Tribe Name</th>";
            echo "<th>Race</th>";
            echo "<th>Alliance</th>";
            echo "<th class=\"right\">" .$type . "</th>";
        echo "</tr>";
        $iCount = 1;
        while ($line = mysql_fetch_assoc($res))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th>$iCount. " . stripslashes($line["tribe_name"]) . "</th>";
                echo "<td class=\"left\">" . $line["race"] . "</td>";
                echo "<td class=\"left\">"  .
                    "(#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $line["alli_id"] . "\">" .
                    $line["alli_id"] . "</a>)</td>";
                echo "<td>" . number_format($line[$dbtype]) . "</td>";
            echo "</tr>";
            $iCount++;
         }
    }
    elseif ($strRankingType == "a")
    {
        $res = mysql_query("SELECT id,alli_name,alli_desc," . $dbtype . " FROM rankings_alliance WHERE id > 10 ORDER BY " . $dbtype . " DESC LIMIT 50");

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"5\">" .$strTitle . "</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th width=\"25\">&nbsp;</th>";
            echo "<th>Alliance Name</th>";
            echo "<th class=\"left\">Alliance Description</th>";
            echo "<th>#</th>";
            echo "<th class=\"right\">" .$type . "</th>";
        echo "</tr>";
        $iCount = 1;
        while ($line = mysql_fetch_assoc($res))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th class=\"left\">" . $iCount. ".</th>";
                echo "<th class=\"left\">" . stripslashes($line["alli_name"]) . "</th>";
                echo "<td class=\"left\">" . stripslashes($line["alli_desc"]) . "</td>";
                echo "<td class=\"left\">" . "(#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $line["id"] . "\">" .
                $line["id"] . "</a>) ". "</td>";
                echo "<td>" . number_format($line[$dbtype]) . "</td>";
            echo "</tr>";
            $iCount++;
        }
    }
    elseif ($strRankingType == "r")
    {
        $res = mysql_query("SELECT id,tribe_name,race,alli_id,". $dbtype ." FROM rankings_personal WHERE alli_id > 10 AND race = '" . $race . "' ORDER BY " . $dbtype . " DESC LIMIT 50");

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"4\">" .$strTitle . "</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th>Tribe Name</th>";
            echo "<th>Race</th>";
            echo "<th>Alliance</th>";
            echo "<th class=\"right\">" .$type . "</th>";
        echo "</tr>";
        $iCount = 1;
        while ($line = mysql_fetch_assoc($res))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th>$iCount. " . stripslashes($line["tribe_name"]) . "</th>";
                echo "<td class=\"left\">" . $line["race"] . "</td>";
                echo "<td class=\"left\">" .
                    "(#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $line["alli_id"] . "\">" .
                    $line["alli_id"] . "</a>)</td>";
                echo "<td>" . number_format($line[$dbtype]) . "</td>";
            echo "</tr>";
            $iCount++;
         }
    }
    elseif ($strRankingType == "s")
    {
        $query = mysql_query("SELECT id, name, vote_count FROM `" . ALLIANCE . "` WHERE id > 1 ORDER BY vote_count DESC  LIMIT 0 , 50");

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"2\">Top Supporters</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th>Alliance Name</th>";
            echo "<th class=\"right\">Votes</th>";
        echo "</tr>";
        $iCount = 1;
        while ($line = mysql_fetch_assoc($query))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th>$iCount. " . stripslashes($line['name']) . " (#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=".$line['id']."\">".$line['id']."</a>)</th>";
                echo "<td>" . number_format($line['vote_count']) . "</td>";
            echo "</tr>";
            $iCount++;
         }
    }
    elseif ($strRankingType == "ta")
    {
        include_once('inc/classes/clsGame.php');
        $objGame = new clsGame();
        $iCurrentYear = $objGame->get_year_history();

        // Nuvarande
        $resSQL = mysql_query("SELECT alli_id, alli_name, alli_desc, year, land FROM rankings_history WHERE year = $iCurrentYear AND year > 105 LIMIT 1");

        // Alla genom history
        $resSQL2 = mysql_query("SELECT year, alli_id, alli_name, alli_desc FROM rankings_history WHERE alli_id > 10 AND year > 105 GROUP BY year ASC");

        // Lista Alla För att summera i PHP
        $old_id = 0;
        $iCount = 0;
        while ($arrRow = mysql_fetch_assoc($resSQL2))
        {
            $new_id = $arrRow['alli_id'];

            if ($new_id != $old_id)
            {
                // prepare for new alliance
                $iCount++;

                // Copy SQL result row
                $arrAlliance[$iCount] = $arrRow;

                // add a starting year column to display period, eg 100 - 106 OE
                $arrAlliance[$iCount]['starting_year'] = $arrRow['year'];

                // add an ending year column to display period, eg 100 - 106 OE
                $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];

                // add a year counter column, starting value = 1
                $arrAlliance[$iCount]['years'] = 1;

                // Save alliance id for next loop
                $old_id = $new_id;
            }
            elseif ($new_id == $old_id)
            {
                // add another year to our counter column
                $arrAlliance[$iCount]['years']++;

                // update ending year column
                $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];
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

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"6\">" .$strTitle . "</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th colspan=\"2\"> Alliance Name </th>";
            echo "<th width=\"40%\"> Description </th>";
            echo "<th> # </th>";
            echo "<th> Period </th>";
            echo "<th class=\"right\"> Years </th>";
        echo "</tr>";

        $iCount = 1;
        while ($arrAlliance1 = mysql_fetch_array($resSQL))
        {
            // pick out the current year alliance from our history array
            foreach ($arrAlliance as $arrAlliance2)
            {
                if ($arrAlliance1['year'] == $arrAlliance2['ending_year'])
                {
                    $arrAlliance1 = $arrAlliance2;

                    break;
                }
            }

            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th width=\"25\" class=\"bsdown\">$iCount. </th>";
                echo "<th class=\"bsdown\">" . stripslashes($arrAlliance1['alli_name']) . "</th>";
                echo "<td class=\"bsdown left\">" . $arrAlliance1['alli_desc'] . "</td>";
                echo "<td class=\"bsdown left\">" . "(#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $arrAlliance1['alli_id']."\">" . $arrAlliance1['alli_id'] . "</a>)" . "</td>";
                echo "<td class=\"bsdown left\">" . $arrAlliance1['starting_year'] . ' - <strong>' . $arrAlliance1['ending_year'] . "</strong> OE</td>";
                echo "<td class=\"bsdown\">" . $arrAlliance1['years'] . "</td>";
            echo "</tr>";
            $iCount++;
        }
        foreach ($arrAlliance as $arrAlliance)
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th width=\"25\">$iCount. </th>";
                echo "<th>" . stripslashes($arrAlliance['alli_name']) . "</th>";
                echo "<td class=\"left\">" . $arrAlliance['alli_desc'] . "</td>";
                echo "<td class=\"left\">" . "(#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $arrAlliance['alli_id']."\">" . $arrAlliance['alli_id'] . "</a>)" . "</td>";
                echo "<td class=\"left\">" . $arrAlliance['starting_year'] . ' - ' . $arrAlliance['ending_year'] . " OE</td>";
                echo "<td>" . $arrAlliance['years'] . "</td>";
            echo "</tr>";
            $iCount++;

//             if ($iCount == 10)
//             {
//                 break;
//             }
        }
    }
    elseif ($strRankingType == "tl")
    {
        include_once('inc/classes/clsGame.php');
        $objGame = new clsGame();
        $iCurrentYear = $objGame->get_year_history();

        $query1 = mysql_query("SELECT alli_id,alli_name,alli_desc,year,land FROM rankings_history WHERE year = $iCurrentYear AND year > 105 LIMIT 1");

        echo "<table class=\"big\" cellpadding=\"0\" cellspacing=\"0\">";
        echo "<tr class=\"header\"><th colspan=\"5\">" .$strTitle . "</th></tr>";
        echo "<tr class=\"subheader\">";
            echo "<th colspan=\"2\"> Alliance Name </th>";
            echo "<th> Description </th>";
            echo "<th class=\"right\"> Year </th>";
            echo "<th class=\"right\"> Acres </th>";
        echo "</tr>";

        $iCount = 1;
        while ($line = mysql_fetch_array($query1))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th width=\"25\" class=\"bsdown\">$iCount.</th>";
                echo "<th class=\"bsdown\">" . stripslashes($line['alli_name']) . " (#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=".$line['alli_id']."\">".$line['alli_id']."</a>) </th>";
                echo "<td class=\"bsdown\">" . $line['alli_desc'] . "</td>";
                echo "<td class=\"bsdown\">" . $line['year'] . " OE</td>";
                echo "<td class=\"bsdown\">" . number_format($line['land']) . "</td>";
            echo "</tr>";
            $iCount++;
        }

        $query  = mysql_query("SELECT alli_id,alli_name,alli_desc,year,MAX(land) as land FROM rankings_history WHERE alli_id > 10 AND year > 105 GROUP BY alli_id ORDER BY land DESC"); // GROUP BY alli_name
        while ($line = mysql_fetch_array($query))
        {
            $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
            echo "<tr class=\"data" . $cm ."\">";
                echo "<th width=\"25\">$iCount.</th>";
                echo "<th>" . stripslashes($line['alli_name'])/**/. " (#<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=".$line['alli_id']."\">".$line['alli_id']."</a>)" /**/. "</th>";
                echo "<td>" . $line['alli_desc'] . "</td>";
                echo "<td>" . $line['year'] . " OE</td>";
                echo "<td>" . number_format($line['land']) . "</td>";
            echo "</tr>";
            $iCount++;
        }
    }
    elseif ($strRankingType == "ca") // Current Age
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
        $resSQL2 = mysql_query("SELECT year, alli_id, alli_name, alli_desc FROM rankings_history WHERE alli_id > 10 AND year >= $iFirstYear AND year <= $iLastYear GROUP BY alli_id,year ASC");

        // Lista Alla För att summera i PHP
        $old_id = 0;
        $iCount = 0;
        while ($arrRow = mysql_fetch_assoc($resSQL2))
        {
            $new_id = $arrRow['alli_id'];

            if ($new_id != $old_id)
            {
                // prepare for new alliance
                $iCount++;

                // Copy SQL result row
                $arrAlliance[$iCount] = $arrRow;

                // add a starting year column to display period, eg 100 - 106 OE
                $arrAlliance[$iCount]['starting_year'] = $arrRow['year'];

                // add an ending year column to display period, eg 100 - 106 OE
                $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];

                // add a year counter column, starting value = 1
                $arrAlliance[$iCount]['years'] = 1;

                // Save alliance id for next loop
                $old_id = $new_id;
            }
            elseif ($new_id == $old_id)
            {
                // add another year to our counter column
                $arrAlliance[$iCount]['years']++;

                // update ending year column
                $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];
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

        echo '<div class="center">' . '<h1 style="margin-top: 0;">Age ' . $iAgeNumber . ' Rankings (' . $iFirstYear . ' - ' . $iLastYear . ' OE)</h1>' . '</div>';
//             echo "<h2>Top Alliance History</h2>";

        $strTopAllianceCurrentTable =
            '<table class="big" cellpadding="0" cellspacing="0">' .
                '<tr class="header">' .
                    '<th colspan="5">King of the Hill</th>' .
                '</tr>' .
                '<tr class="subheader">' .
                    '<th>&nbsp;</th>' .
                    '<th>Alliance Name</th>' .
                    '<th>Alliance Description</th>' .
                    '<th>#</th>' .
                    '<th class="right">Years</th>' .
                '</tr>';

        $iCount = 1;
        foreach ($arrAlliance as $arrAlliance)
        {
            $strTopAllianceCurrentTable .=
                '<tr class="data">' .
                    '<th width="25">' . $iCount . '.</th>' .
                    '<th>' . stripslashes($arrAlliance['alli_name']) . '</th>' .
                    '<td class="left">' . stripslashes($arrAlliance['alli_desc']) . '</td>' .
                    '<td class="left">' . '(#<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $arrAlliance['alli_id'] . '">' . $arrAlliance['alli_id'] . '</a>)' . '</td>' .
                    '<td>' . $arrAlliance['years'] . ' years on top</td>' .
                '</tr>';
            $iCount++;
        }

        $strTopAllianceCurrentTable .=
            '</table>';
        echo $strTopAllianceCurrentTable;

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

//             echo "<h2>Top Acreage History</h2>";
            echo '<br />';

            $strTopAcreageCurrentTable =
                '<table class="big" cellpadding="0" cellspacing="0">' .
                    '<tr class="header">' .
                        '<th colspan="6">Largest Alliance</th>' .
                    '</tr>' .
                    '<tr class="subheader">' .
                        '<th>&nbsp;</th>' .
                        '<th>Alliance Name</th>' .
                        '<th>Alliance Description</th>' .
                        '<th>#</th>' .
                        '<th class="right">Year</th>' .
                        '<th class="right">Acres</th>' .
                    '</tr>';

            $iCount = 1;
            $query  = mysql_query("SELECT MAX(land) as land,alli_id,year FROM rankings_history WHERE alli_id > 10 AND year >= $iFirstYear AND year <= $iLastYear GROUP BY alli_id ORDER BY land DESC");
            while ($line = mysql_fetch_array($query))
            {
                $line2 = mysql_fetch_array(mysql_query("SELECT year,alli_name,alli_desc FROM rankings_history WHERE land = $line[land] AND alli_id = $line[alli_id]"));
                $cm = ($iCount % 2 == 0 ) ? " even" : " odd";
                $strTopAcreageCurrentTable .=
                    '<tr class="data"' . $cm . '">' .
                        '<th width="25">' . $iCount . '.</th>' .
                        '<th>' . stripslashes($line2['alli_name']) . '</th>' .
                        '<td class="left">' . stripslashes($line2['alli_desc']) . '</td>' .
                        '<td class="left">(#<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $line['alli_id'] . '">' . $line['alli_id'] . '</a>)' . '</td>' .
                        '<td>(' . $line2['year'] . ' OE)</td>' .
                        '<td>' . number_format($line['land']) . ' acres </td>' .
                    '</tr>';
                $iCount++;
            }

            $strTopAcreageCurrentTable .=
                '</table>';
            echo $strTopAcreageCurrentTable;
        }
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
            $iCount = 0;
            while ($arrRow = mysql_fetch_assoc($resSQL2))
            {
                $new_id = $arrRow['alli_id'];

                if ($new_id != $old_id)
                {
                    // prepare for new alliance
                    $iCount++;

                    // Copy SQL result row
                    $arrAlliance[$iCount] = $arrRow;

                    // add a starting year column to display period, eg 100 - 106 OE
                    $arrAlliance[$iCount]['starting_year'] = $arrRow['year'];

                    // add an ending year column to display period, eg 100 - 106 OE
                    $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];

                    // add a year counter column, starting value = 1
                    $arrAlliance[$iCount]['years'] = 1;

                    // Save alliance id for next loop
                    $old_id = $new_id;
                }
                elseif ($new_id == $old_id)
                {
                    // add another year to our counter column
                    $arrAlliance[$iCount]['years']++;

                    // update ending year column
                    $arrAlliance[$iCount]['ending_year'] = $arrRow['year'];
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

//             echo '<div class="center"><h2>Timeline</h2></div>';
            echo '<br />';

            $strTimelineCurrentTable =
                '<table class="big" cellpadding="0" cellspacing="0">' .
                    '<tr class="header">' .
                        '<th colspan="5">Timeline</th>' .
                    '</tr>' .
                    '<tr class="subheader">' .
                        '<th>Period</th>' .
                        '<th>Alliance Name</th>' .
                        '<th>Alliance Description</th>' .
                        '<th>#</th>' .
                        '<th class="right">Years</th>' .
                    '</tr>';

            $iCount = 1;
            foreach ($arrAlliance as $arrAlliance)
            {
                $strTimelineCurrentTable .=
                    '<tr class="data">' .
                        '<td class="left">(' . $arrAlliance['starting_year'] . ' - ' . $arrAlliance['ending_year'] . ' OE) </td>' .
                        '<th>' . stripslashes($arrAlliance['alli_name']) . '</th>' .
                        '<td class="left">' . stripslashes($arrAlliance['alli_desc']) . '</td>' .
                        '<td class="left">' . '(#<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $arrAlliance['alli_id'] . '">' . $arrAlliance['alli_id'] . '</a>)</td>' .
                        '<td>' . $arrAlliance['years'] . ' years on top </td>' .
                    '</tr>';
                $iCount++;
            }
            $strTimelineCurrentTable .=
                '</table>';
            echo $strTimelineCurrentTable;
        }
        doit();

    }
//     elseif ($strRankingType == 'tta')
//     {
//         $strTable =
//             '<table class="medium" cellpadding="0" cellspacing="0">' .
//                 '<tr class="header">' .
//                     '<th colspan="4">' .$strTitle . '</th>' .
//                 '</tr>' .
//                 '<tr class="subheader">' .
//                     '<th>Alliance Name</th>' .
//                     '<th>#</th>' .
//                     '<th class="center">Years</th>' .
//                     '<th class="right">Active</th>' .
//                 '</tr>';

//         $strSQL = "SELECT alli_name, alli_desc, alli_id, count(year) as years, year FROM rankings_history WHERE alli_id > 10 AND year > 105 GROUP BY $dbtype ORDER BY id DESC";
//         $resSQL = mysql_query($strSQL);
//         $iCount = 1;
//         while ($arrAlliance = mysql_fetch_array($resSQL))
//         {
//             $strTable .=
//                 '<tr class="data">' .
//                     '<th>' . stripslashes($arrAlliance['alli_name']) . '</th>' .
//                     '<td class="left">' . '(#<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $arrAlliance['alli_id'] . '">' . $arrAlliance['alli_id'] . '</a>)</td>' .
//                     '<td class="center">' . $arrAlliance['years'] . '</td>' .
//                     '<td>~' . $arrAlliance['year'] . ' OE</td>' .
//                 '</tr>';
//             $iCount++;
//         }
//         echo $strTable;
//     }

    echo '</table>';
}

//==============================================================================
// Links at top of page                                     Martel, May 24, 2006
//==============================================================================
function get_rankings_links($show = '')
{
    $arrPage = array(1 => 'annual', 'alliance', 'personal');
    $arrName = array(1 => 'History ', 'Alliance', 'Personal');

    $str = '<div class="center">| ';
    foreach ($arrPage as $key => $page )
    {
        if ($show != $page)
        {
            $str .= '<a href="main.php?cat=game&amp;page=rankings&amp;show=' .
                   $page . '">' . $arrName[$key] . '</a>';
        }
        else
        {
            $str .= '<b>' . $arrName[$key] . '</b>';
        }
        $str .= ' | ';
    }
    $str .= '</div>';

    return $str;
}

?>
