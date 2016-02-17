<?php
function call_email_text()
{
    $orkTime     = $GLOBALS['orkTime'];
    $tool        = $_GET['tool'];

    $objSrcUser = $GLOBALS['objSrcUser'];
    $userid = $objSrcUser->get_userid();

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
       echo "Sorry, this page is restricted to ORKFiA Staff";
       include_game_down();
       exit;
    }

    $strSQL = "SELECT email FROM preferences";
    $resSQL = mysql_query($strSQL);
    while ($strRES = mysql_fetch_row($resSQL))
        echo $strRES[0] . "<br />";

}
?>



