<?php
function call_clean_untitleds_text()
{
    global $check_inactives, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $strMail = "After 2 weeks of inactivity your account was removed in ORKFiA =(\n\n" . HOST .
               "\n\nWe hope to see you back again soon." . SIGNED_ORKFIA;

    $old_time = date(TIMESTAMP_FORMAT, strtotime("-14 days"));
    $strSQL =
        "SELECT user.id, stats.kingdom " .
        "FROM user, stats, preferences " .
            "WHERE user.last_login < '$old_time' " .
            "AND stats.kingdom > 10 " .
            "AND user.pause_account = 0 " .
            "AND user.id > 1 " .
            "AND user.id = stats.id " .
            "AND user.id = preferences.id " .
            "AND preferences.email_activation = 'verified'";
    $result = mysql_query($strSQL);

    $inactives = array();
    while ($arrinactives = mysql_fetch_array ($result, MYSQL_ASSOC))
    {
        $inactives[$arrinactives["id"]] = $arrinactives;
    }

    $count = 0;
    foreach($inactives as $strKey => $value)
    {
        $count++;
        if ($check_inactives)
        {
            $seek = mysql_query("Select * from preferences where id = $value[id]");
            $seek = mysql_fetch_array($seek);

            mail($seek['email'], "Your ORKFiA Account", $strMail, "From: ORKFiA <" .
            EMAIL_REGISTRATION . ">\r\nX-Mailer: PHP/" . phpversion() .
            "\r\nX-Priority: Normal");

            mysql_query("UPDATE stats SET kingdom=0 WHERE id = $value[id]");
            mysql_query("DELETE FROM rankings_personal WHERE id = $value[id]");
        }
    }

    if ($check_inactives)
    {
        ECHO "<p>Removed $count accounts. Mails were sent to all accounts.</p>" .
             '<hr />' .
             "<p>" . nl2br($strMail) . "</p><hr />";
    }
    ELSE
    {
        ECHO "<p>$count inactive accounts (2 weeks since last login) will be removed, go through with this?</p>";
        echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
        ECHO '<label for="confirm">Yes :</label>' .
             '<input type="checkbox" name="check_inactives" id="confirm" />' .
             '<input type="submit" value="delete" /></form>';
    }
}
?>