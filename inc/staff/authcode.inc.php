<?
function call_authcode_text()
{
    global $id,$confirm,$Aname,$tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    ECHO "<p>Just enter verified to stop the auth request, or to require them to read their new email use something random.</p>";
    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input ID#: <input name=id size=5><br>";
    ECHO "Change authcode to: <input type=text name=Aname maxlength=30 size=25><br>";
    ECHO "<input type=submit value=Send name=confirm>";
    ECHO "</form>";
    ECHO "<br><br>";

    IF($confirm && $id && $Aname)
    {
        $result = mysql_query("UPDATE preferences SET email_activation = '$Aname' where id = $id");
        $result = mysql_query ("SELECT * FROM preferences WHERE id = $id");
        $check  =mysql_fetch_array($result);

        ECHO "<p>Activation code changed to: $Aname  . Email sent to $check[email]</p>";
        mail("$check[email]","ORKFiA Activation","Welcome to ORKFiA =) \n\nHere is your activation code: $Aname \n\n\nIt is recommended you tend to your tribe at least once per day. If you require help or this is your first time playing ORKFiA, you may find the forums and the manual useful.\n\nWe hope you enjoy this age in ORKFiA =)\n\n- The ORKFiA Team\n\n\nThis email is php generated, you cannot successfully reply." , "From: Admins@orkfia.net");
    }
}
?>