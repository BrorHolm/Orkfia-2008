<?php
//******************************************************************************
// pages alliance_news.inc.php                             Martel, June 04, 2006
//******************************************************************************

function include_global_news_text()
{
    // Navigational Links
    echo $topLinks =
        '<div class="center">' .
            '<a href="main.php?cat=game&amp;page=news">Tribe News</a> | ' .
            '<a href="main.php?cat=game&amp;page=alliance_news">Alliance News</a> | ' .
            '<b>Global News</b>' .
        '</div><br />';

    // Show Global News
    echo showGlobalNews();
}

//==============================================================================
// Returns a string with the global news over the last 2 days
//==============================================================================
function showGlobalNews($strSize = 'big')
{
    $timestamp2 = date('D d M, H:i:s');
    $show_time  = date(TIMESTAMP_FORMAT, strtotime('-3 days'));

    $strTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"$strSize\">" .
        "<tr class=\"header\">" .
            "<th colspan=\"2\">" . "The Headlines" . "</th>" .
        "</tr>" .

        "<tr class=\"subheader\">" .
            "<th width=\"15%\">" . "Time" . "</th>" .
            "<th width=\"85%\">" . "News Item" . "</th>" .
        "</tr>";

    $query  =
        "SELECT time, kingdom_text " .
        "FROM news " .
        "WHERE " .
        "type = 'global' " .
        "AND " .
        "time > $show_time " .
        "ORDER BY time DESC, id ASC";

    $result = mysql_query($query) or die("Error retrieving global news");
    $number = mysql_num_rows($result);

    if ($number == 0)
    {
        $strTable .=
            "<tr class=\"message\">" .
                "<th>" . "&nbsp;" . "</th>" .
                "<td>" . "There are no new items of interest, which is a " .
                "boring thing. Come on ppl and go to WAR!" . "</td>" .
            "</tr>";

    }
    else
    {
        while ($newsloop =(mysql_fetch_array($result)))
        {
            $strTable .=
                "<tr class=\"message\">" .
                    "<td>" . $newsloop['time'] . "</td>" .
                    "<td>" . stripslashes($newsloop['kingdom_text']) . "</td>" .
                "</tr>";
        }
    }

    $strTable .=
        "</table>" .
        '<div class="center" style="font-size: 0.8em;">' .
            "Server Time: " . $timestamp2 .
        '</div>';

    return $strTable;
}

?>
