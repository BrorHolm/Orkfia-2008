<?php

function include_racestats_text()
{
    // Get Total Number of Tribes
    $strSQL   = "SELECT COUNT(*) as total ".
                "FROM " . TBL_STAT . " " .
                "WHERE " . ALLIANCE . " > 10";
    $iTotal   = mysql_result(mysql_query($strSQL), 0, 0);
    $iTotal2  = 0;
    
    // Get All Races
    include_once('inc/classes/clsRace.php');
    $arrRaces = clsRace::getRaces();
    $arrActiveRaces = clsRace::getActiveRaces();
    
    // Begin Create Table	
    $strRaceStatsTable =
        '<table cellspacing="0" cellpadding="0" class="small">' .
                '<tr class="header">' .
                        '<th colspan="2">' . "Racial Stats" . "</th>" .
                "</tr>" .
                        
                '<tr class="subheader">' .
                        "<th>" . "Race" . "</th>" .
                        '<th class="right">' . "%" . "</th>" .
                "</tr>";
	
    for ($i = 0; $i < count($arrRaces); $i++)
    {
        // Give each 3rd row a border down
        $strStyle = ''; //if (($i % 3) == 0) { $strStyle = ' class = "bsdown" '; }
        if (!in_array($arrRaces[$i],$arrActiveRaces))
            $strStyle = ' style="font-style:italic;"';
        
        // Get Number of Tribes of the Current Race
        $SQL = "SELECT id FROM " . TBL_STAT . " " .
               "WHERE " . RACE . " = '$arrRaces[$i]' AND " . 
                      ALLIANCE . " > 10";
        $count[$i] = mysql_num_rows(mysql_query($SQL));
        $iTotal2  += $count[$i];
		
        $strRaceStatsTable .=
            '<tr class="data">' .
                    "<th$strStyle>" . $arrRaces[$i] . "</th>" . 
                    "<td$strStyle>" . round(($count[$i]/$iTotal) * 100,2). " %</td>" .
            "</tr>";
    }
    
	$strRaceStatsTable .=
                '<tr class="data">' .
                    "<th>" . "Unknown" . "</th>" . 
                    "<td>" . round((($iTotal - $iTotal2)/$iTotal) * 100,2) . " %</td>" .
                "</tr>" .
            "</table>";

	echo $strRaceStatsTable;
}

?>
