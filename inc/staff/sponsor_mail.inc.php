<?php

function call_sponsor_mail_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $id      = $_POST['id'];
    $email   = $_POST['email'];
    $message = $_POST['message'];
    $subject = $_POST['subject'];
    if ($subject && $message && $email)
    {
        mail("$email", "$subject", "$message", "From: sponsors@orkfia.org");
        ECHO "<b><i>sent</b></i><br>";
        echo "<div id=textBig>" . "<p>" . nl2br($message) . '</p>' . '</div>';
    }
    elseif ($subject && $message && $id)
    {
        $seek = mysql_query("Select * from preferences where id = $id");
        $seek = mysql_fetch_array($seek);
        mail("$seek[email]", "$subject","$message", "From: sponsors@orkfia.org");
        ECHO "<b><i>sent to $seek[email]</b></i><br>";
        echo "<div id=textBig>" . "<p>" . nl2br($message) . '</p>' . '</div><br />';
    }

    $strStandard =
'Hi, thank you for your kind donation.

May we ask what personal details we are allowed use, when listing you as a sponsor?

First name + last name (or game nick), and country?

Please reply to be listed on our page within short, and once again thank you for sponsoring the game!

Sincerely,
Frost, Martel, the ORKFiAn staff, and all your fellow players';

    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Email: <input name=email size=40> OR ID: <input name=id size=5>";
    ECHO "<br><br>Subject: <input name=subject size=40 value='Welcome as a sponsor'><br>";
    ECHO "<br>Message: <br>     <textarea rows=7 cols=50 wrap=on name=message>$strStandard</textarea><br>";
    ECHO "<input type=submit value=Send name=confirm>";
    ECHO "</form>";
}
?>