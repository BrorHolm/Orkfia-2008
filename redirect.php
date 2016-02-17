<?
include('inc/on_load.php');
include_once("inc/functions/constants.php");
include_once("inc/functions/generic.php");
include_once("inc/classes/clsUser.php");

global $HTTP_SERVER_VARS, $HTTP_USER_AGENT;

if (isset($_GET['userid']) || isset($_POST['userid']))
{
    echo "And what do you think you are you doing?";

    mail("david_nn@hotmail.com","Redirect php hack","IP: " . $HTTP_SERVER_VARS['REMOTE_ADDR'] . " just tried to check $page for user $userid. He/she abused a severe hack and might have accessed any account ingame prior to this fix (March 06, 2006 - Martel). \n\nMore details on the wannabe-hacker: " . $HTTP_USER_AGENT . " ..." , "From: admins@orkfia.org");
//     mail("frost@orkfia.com","Redirect php hack","IP: " . $HTTP_SERVER_VARS['REMOTE_ADDR'] . " just tried to check $page for user $userid. He/she abused a severe hack and might have accessed any account ingame prior to this fix (March 06, 2006 - Martel). \n\nMore details on the wannabe-hacker: " . $HTTP_USER_AGENT . " ..." , "From: admins@orkfia.org");

    exit;
}

connectdb();
$GLOBALS["objSrcUser"] = new clsUser ($userid);
$include_page_text = "include_"."$page"."_text";
IF($page )
   {
   IF(file_exists("inc/pages/$page.inc.php"))
   {
   include("inc/pages/$page.inc.php");
   $include_page_text();
   } ELSE { "Please Hold "; }
   } ELSE { ECHO "Page Not Found" ; }


?>
