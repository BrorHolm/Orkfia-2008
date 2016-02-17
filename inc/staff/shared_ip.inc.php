<?php
// Changed to sort by either ID or IP depending on input - AI 02/03/2007
function call_shared_ip_text()
{
    global $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $show_time = date(TIMESTAMP_FORMAT, strtotime("-1 days"));

    echo $strDiv =
        '<div id="largeText">' .
            '<h2>' . 'Cross Logging - 24 hour Service' . '</h2>' .
            '<p>' .
                'IPs listed here have been directly associated with more than ' .
                'one user-id over <br />the past 24 hours. Martel(c) 1337 version ;)' .
            '</p>' .
        '</div>';

    $strTable =
        '<table class="medium" cellspacing=0 cellpadding=0>' .
            '<tr class="header">' .
                '<th colspan="2">' .
                    'IP Testing' .
                '</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th>' .
                    '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=shared_ip&amp;sort=ip">' .
                        'IP' .
                    '</a>' .
                '</th>' .
                '<td>' .
                    '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=shared_ip&amp;sort=id">' .
                        'User-ids' .
                    '</a>' .
                '</td>' .
            '</tr>';

    //sanitize userinput - AI
    $sort = 'ip';
    if(isset($_GET['sort']) && $_GET['sort'] == 'id')
        $sort = 'id';
    $result = mysql_query("SELECT ip, COUNT(userid) FROM logins WHERE userid > 1 AND time > '$show_time' AND ip > '' GROUP BY ip HAVING COUNT(userid) > 1 ORDER BY $sort") or die(mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $i = 0;
        $strListIds = '';
        $strSQL = 'SELECT ip, userid, count( ip ) AS logins ' .
                  'FROM logins ' .
                  "WHERE ip = '$row[ip]' " .
                  "AND time > '$show_time' " .
                  "AND userid > 1 " .
                  "AND ip > '' " .
                  'GROUP BY userid';
        $resSQL = mysql_query($strSQL);
        while ($arrRes = mysql_fetch_array($resSQL))
        {
            $strListIds .= '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=checkinfo&amp;id=' . $arrRes['userid'] . '">';
            $strListIds .= $arrRes['userid'] . ' ';
            $strListIds .= '</a>';
            $i++;
        }

        if ($i > 1)
        {
            $strTable .=
            '<tr class="data">' .
                '<td class="left">' .
                    '<a href="main.php?cat=game&amp;page=resort_tools&amp;tool=ip&amp;ip2=' . $row['ip'] . '&amp;go=yes">' . $row['ip'] . '</a>' .
                '</td>' .
                '<td>' .
                    $strListIds .
                '</td>' .
            '</tr>';
        }
    }

    $strTable .=
        '</table>';
    echo $strTable;
}
?>
