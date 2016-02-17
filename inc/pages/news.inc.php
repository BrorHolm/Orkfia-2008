<?php
//******************************************************************************
// pages news.inc.php                                      Martel, June 04, 2006
//******************************************************************************

function include_news_text()
{
    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $iUserid     = $objSrcUser->get_userid();

    // Unset tribe's news-flag
    if ($objSrcUser->get_user_info(LAST_NEWS) > 0)
        $objSrcUser->set_user_info(LAST_NEWS, 0);

    // Navigational Links
    echo $topLinks =
        '<div class="center">' .
        '<b>Tribe News</b> | ' .
        '<a href="main.php?cat=game&amp;page=alliance_news">Alliance News</a> | ' .
        '<a href="main.php?cat=game&amp;page=global_news">Global News</a>' .
        '</div><br />';

    // Show Tribe News
    echo showTribeNews($objSrcUser);
}

//==============================================================================
// Returns a string with the tribe news over the last 2 days
//==============================================================================
function showTribeNews(&$objUser)
{
    $iUserid     = $objUser->get_userid();

    $show_time   = date(TIMESTAMP_FORMAT, strtotime('-2 days'));

    $strTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"big\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "The Headlines" . "</th>" .
            "</tr>" .
            "<tr class=\"subheader\">" .
                "<th width=\"15%\">" . "Time" . "</th>" .
                "<th width=\"85%\">" . "News Item" . "</th>" .
            "</tr>";

    $query =
        "SELECT time, text " .
        "FROM news WHERE " .
        "duser = $iUserid " .
        "AND " .
        "time > $show_time " .
        "ORDER BY time DESC, id ASC";

    $result = mysql_query($query) or die("Error while retrieving tribe news");

    $number = mysql_num_rows($result);
    if ($number == "0")
    {
        $strTable .=
            "<tr class=\"message\">" .
                "<th>" . "&nbsp;" . "</th>" .
                "<td>" . "There are no new items of interest, which is a good thing =)" . "</td>" .
            "</tr>";
    }
    else
    {
        while ($newsloop =(mysql_fetch_array($result)))
        {
        $strTable .=
            "<tr class=\"message\">" .
                "<td>" . $newsloop['time'] . "</td>" .
                "<td>" . stripslashes($newsloop['text']) . "</td>" .
            "</tr>";
        }
    }
    $strTable .=
        "</table>" .
        '<div class="center" style="font-size: 0.8em;">' .
            'Server Time: ' . date('D d M, H:i:s') .
        '</div>';

    return $strTable;
}

?>
