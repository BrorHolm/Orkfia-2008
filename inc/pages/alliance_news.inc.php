<?php
//******************************************************************************
// pages alliance_news.inc.php                             Martel, June 04, 2006
//******************************************************************************

function include_alliance_news_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $iAlliance  = $objSrcUser->get_stat(ALLIANCE);

    // Navigational Links
    echo $topLinks =
        '<div class="center">' .
            '<a href="main.php?cat=game&amp;page=news">Tribe News</a> | ' .
            '<b>Alliance News</b> | ' .
            '<a href="main.php?cat=game&amp;page=global_news">Global News</a>' .
        '</div><br />';

    // Show alliance News
    echo showAllianceNews($iAlliance);
}

//==============================================================================
// Returns a string with the alliance news over the last 3 days
//==============================================================================
function showAllianceNews($iAlliance)
{
    require_once('inc/classes/clsAlliance.php');
    $objAlli = new clsAlliance($iAlliance);
    $arrAlli = $objAlli->get_alliance_infos();

    $timestamp2  = date('D d M, H:i:s');
    $show_time   = date(TIMESTAMP_FORMAT, strtotime('-3 days'));
    $strTableRow = '';

    $query  =
        "SELECT time, kingdoma, kingdomb, kingdom_text, type " .
        "FROM news WHERE " .
        "(kingdoma = '$iAlliance' " .
        "OR kingdomb = '$iAlliance') " .
        "AND time > '$show_time' " .
        "ORDER BY time DESC";

    $result = mysql_query($query) or die ("Error retrieving alliances news");
    $number = mysql_num_rows($result);

    if ($number == 0)
    {
        $strTableRow .=
            "<tr class=\"message\">" .
                "<th>" . "&nbsp;" . "</th>" .
                "<td>" . "There are no new items of interest, which is a good thing =)" . "</td>" .
            "</tr>";
    }
    else
    {
        $arrTypes = array ('raid', 'barren', 'standard', 'hitnrun', 'bc', 'retreat', 'invade');

        while ($newsloop = mysql_fetch_array($result))
        {
            if ($newsloop['kingdoma'] == $iAlliance && in_array($newsloop['type'], $arrTypes))
            {
                $newsloop['kingdom_text'] = '<span class="negative">»« </span>' . $newsloop['kingdom_text'];
            }
            elseif ($newsloop['kingdomb'] == $iAlliance && in_array($newsloop['type'], $arrTypes))
            {
                $newsloop['kingdom_text'] = '<span class="positive">«» </span>' . $newsloop['kingdom_text'];
            }

            $strTableRow .=
                "<tr class=\"data\">" .
                    "<td class=\"left\">" . $newsloop['time'] . "</td>" .
                    "<td class=\"left\">" . stripslashes($newsloop['kingdom_text']) . "</td>" .
                "</tr>";
        }
    }

    $strTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"big\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"3\">Alliance News - " . stripslashes($arrAlli['name']) . " (#" . $arrAlli['id'] . ")</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th width=\"12%\">" . "Time" . "</th>" .
                "<th>" . "News Item" . "</th>" .
            "</tr>" .

            $strTableRow .

        "</table>" .

        '<div class="center" style="font-size: 0.8em;">' .
            "Server Time: " . $timestamp2 .
        '</div>';

    return $strTable;
}

?>
