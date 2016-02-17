<?php

// this is a function to sort the alliances and pick out the current alliance to display on the
// alliance page in /pages/alliance.inc.php, the display is also output here.
function get_rank_data($alliance_id)
{
    $arrRanking[LAND]       = get_alli_ranking(LAND, $alliance_id);
    $arrRanking[STRENGTH]   = get_alli_ranking(STRENGTH, $alliance_id);
    $arrRanking[FAME]       = get_alli_ranking(FAME, $alliance_id);

    //==========================================================================
    //                                                     Martel, July 25, 2006
    // If this alliance is #1 we check if we should update the annual ranks
    //==========================================================================
    if ($arrRanking[LAND] == 1)
    {
        include_once('inc/classes/clsGame.php');
        $objGame    = new clsGame();
        
        // Get Game Year & Most Recent Year Saved
        $iOrkYears  = $objGame->get_year_oe();
        $iLastYear  = $objGame->get_year_history();
        if ($iOrkYears != $iLastYear)
        {
            include_once('inc/classes/clsAlliance.php');
            $objTmpAlliance = new clsAlliance($alliance_id);
            $arrTmpRankings = $objTmpAlliance->get_rankings_alliances();

            $strAlliName = $arrTmpRankings[ALLI_NAME];
            $strAlliDesc = $arrTmpRankings[ALLI_DESC];
            
            $arrNewHistory = array
            (
                YEAR          => $iOrkYears,
                LAND          => $arrTmpRankings[LAND],
                RANK_FAME     => $arrRanking[FAME],
                RANK_STRENGTH => $arrRanking[STRENGTH],
                ALLI_ID       => $arrTmpRankings[ID],
                ALLI_NAME     => "'$strAlliName'",
                ALLI_DESC     => "'$strAlliDesc'",
                LAST_UPDATE   => date(TIMESTAMP_FORMAT, time())
            );
            $objGame->set_historys($arrNewHistory);
        }
    }
    //==========================================================================

    return $arrRanking;
}

//Get the rank of alliance $id in the requested field.
function get_alli_ranking($i_strRankingType, $id)
{
    $strSQL  = "SELECT $i_strRankingType FROM rankings_alliance WHERE id = $id";
    $res     = mysql_query($strSQL);
    $line    = mysql_fetch_assoc($res);
    $rankval = $line[$i_strRankingType];
    $strSQL  = "SELECT (COUNT(id)+1) AS rankval FROM rankings_alliance WHERE $i_strRankingType > $rankval AND id > 10";
    $res     = mysql_query($strSQL);
    $line    = mysql_fetch_assoc($res);

    return $line["rankval"];
}

function get_alli_land($alli)
{
        $strSQL = mysql_query("SELECT land FROM rankings_alliance WHERE id = $alli");
        $strSQL = mysql_fetch_array($strSQL);
        return $strSQL[0];
}
?>