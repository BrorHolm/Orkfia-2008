<?php
function include_resourcefarms_text()
{
    $counter = 1;
    $resourceFarms =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"4\">" . "Resource Farms" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "" . "</th>" .
                "<th>" . "Tribe Name" . "</th>" .
                "<th class=\"right\">" . "Money" . "</th>" .
                "<th class=\"right\">" . "Strength" . "</th>" .
            "</tr>";

    $result = mysql_query("SELECT goods.id as id, stats.tribe as tribe, " .
                          "stats.kingdom as kingdom, goods.money as money, " .
                          "rankings_personal.nw as strength " .
                          "FROM goods,rankings_personal,stats " .
                          "WHERE rankings_personal.hours >= " . PROTECTION_HOURS . " AND stats.kingdom > 10 " .
                          "AND stats.killed = 0 AND stats.reset_option != 'yes' " .
                          "AND rankings_personal.id = goods.id AND goods.id = stats.id " .
                          "ORDER BY goods.money DESC LIMIT 30")
                   or die("Resource Farms");

    while($farms = mysql_fetch_array($result) )
    {
        $resourceFarms .=
            "<tr class=\"data\">" .
                "<td class=\"left\">" . $counter . " " .
                "</td>" .
                "<td class=\"left\">" .
                    "<a href=\"main.php?cat=game&amp;page=external_affairs&amp;tribe=" . $farms['id'] . "&amp;aid=" . $farms['kingdom']. "\">" .
                        stripslashes($farms['tribe']) .
                    "</a> " .
                    "(#" .
                    "<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $farms['kingdom'] . "\">" .
                        $farms['kingdom'] .
                    "</a>" .
                    ")" .
                "</td>" .
                "<td>" . number_format($farms['money']) . " cr" . "</td>" .
                "<td>" . number_format($farms['strength']) . "</td>" .
            "</tr>";
        $counter++;;
    }

    $resourceFarms .=
        "</table>";

    echo $resourceFarms;
}
?>