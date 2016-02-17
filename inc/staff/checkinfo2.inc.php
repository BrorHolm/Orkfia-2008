<?php
//******************************************************************************
// staff tool checkinfo2.inc.php                         Martel, August 31, 2006
//******************************************************************************
function call_checkinfo2_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    echo $strDiv =
        '<h2>' . 'Check User(s) Registration Info' . '</h2>' .
        '<p>' .
           'Enter one or several user-ids, separate each by a space and ' .
           '<br />they will be listed if they exist in the database. Staff excluded.' .
        '</p>';

    echo $strForm =
        '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=' . $tool . '">' .
            '<input type="text" size="60" name="user_ids" value="' . $_POST['user_ids'] . '" />' .
            '<br /><br />' .
            '<input type="submit" value="List User(s)" />' .
            '<br /><br />' .
        '</form>';

    if (isset($_POST['user_ids']) && trim($_POST['user_ids']) != '')
    {
        echo $strTable =
        '<table class="big" cellspacing="0" cellpadding="0">' .
            '<tr class="header">' .
                '<th colspan="12">' . 'Check User Info' . '</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th>' . "ID" . '</th>' .
                '<th>' . "Name" . '</th>' .
                '<th>' . "From" . '</th>' .
                '<th>' . "E-mail" . '</th>' .
                '<th>' . "Login" . '</th>' .
                '<th>' . "Alias" . '</th>' .
                '<th>' . "Tribe" . '</th>' .
                '<th>' . "Alli#" . '</th>' .
                '<th>' . "Logins" . '</th>' .
                '<th>' . "TWG" . '</th>' .
                '<th>' . "RPs" . '</th>' .
            '</tr>';

        $strUserIds = trim($_POST['user_ids']);
        $arrUserIds = explode(" ", $strUserIds);
        foreach ($arrUserIds as $iUserId)
        {
            $iUserId = intval($iUserId);

            $strSQL =
                "SELECT * " .
                "FROM user, stats, preferences " .
                "WHERE user.id = $iUserId " .
                "AND user.id = stats.id " .
                "AND user.id = preferences.id " .
                "AND stats.kingdom > 10";

            $result = mysql_query($strSQL) or die("query error");
            while ($value = mysql_fetch_array ($result, MYSQL_ASSOC))
            {
                echo $strTableTr =
                '<tr class="data">' .
                    '<th>' .
                        $value['id'] .
                    '</th>' .
                    '<td class="left">' .
                        stripslashes($value['realname']) .
                    '</td>' .
                    '<td class="left">' .
                        $value['country'] .
                    '</td>' .
                    '<td class="left">' .
                        $value['email'] .
                    '</td>' .
                    '<td class="left">' .
                        stripslashes($value['username']) .
                    '</td>' .
                    '<td class="left">' .
                        stripslashes($value['name']) .
                    '</td>' .
                    '<td class="left">' .
                        stripslashes($value['tribe']) .
                    '</td>' .
                    '<td>' .
                        $value['kingdom'] .
                    '</td>' .
                    '<td>' .
                        $value['logins'] .
                    '</td>' .
                    '<td>' .
                        $value['twg_vote'] .
                    '</td>' .
                    '<td>' .
                        $value['invested'] .
                    '</td>' .
                '</tr>';
            }
        }
        echo "</table>";
        echo "<p>(TWG = Last week a tribe voted, 0 by default)</p>";
    }
}
?>
