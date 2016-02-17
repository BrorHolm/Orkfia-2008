<?php
function call_mail_text()
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

    echo "<h2>Orkfia Mass Mailer (Ingame PM)</h2>";

    echo "<p>Use this carefully and responsibly. The sender will be your own tribe.</p>";

    echo "<form method=\"post\" action=\"{$_SERVER['REQUEST_URI']}\">";

    ECHO "<label>Who would you like to mail:</label> ";
    ECHO "<Select name='who' size=1>";
    ECHO "<option value='all'>Mail Everyone</option>";
    ECHO "<option value='admins'>Mail Heads of ORKFiA</option>";
    echo "<option value='heads'>Mail Heads of Resorts</option>";
    ECHO "<option value='staff' SELECTED>Mail Staff</option>";
    ECHO "<option value='elders'>Elders</option>";
    ECHO "</select>";
    ECHO "<br /><br />";

    ECHO "<label>Subject:</label><br />";
    ECHO "<input name=subject size=50 />";
    ECHO "<br /><br />";

    ECHO "<label>Message:</label><br />";
    ECHO "<textarea rows=7 cols=50 wrap=on name=message>\n\n\n~ The ORKFiA Staff Team</textarea>";
    ECHO "<br /><br />";

    ECHO "<input type=submit name='submit' value='Send mass pm' />";

    ECHO "</form>";

//==============================================================================
// Don't send the mail if noone pressed the button...              - AI 30/10/06
//==============================================================================
    if(isset($_POST['submit']))
    {
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $who = $_POST['who'];
        IF(!$subject){ $subject = "No Subject"; }
        $result = mysql_query ("SELECT id,level,type FROM stats"/* where id = $tribe"*/);
        while($tribe = mysql_fetch_assoc($result)){
            if(is_mail_target($who, $tribe['type'], $tribe['level'])) {
                $query =
                      "INSERT INTO messages
                      (for_user, from_user, date, subject, text, new, action)
                      VALUES ({$tribe['id']}, $userid, '$orkTime', ".quote_smart($subject).", ".quote_smart($message).", 'new', 'received')";
                mysql_query($query);
            }
        }
    }
}
function is_mail_target($needed, $type, $level)
{
    switch($needed){
        case 'all':
            return true;
        case 'admins':
            return $level == 6;
        case 'heads':
            return $level >= 5;
        case 'staff':
            return $level >= 2;
        case 'elders':
            return $type == "elder";
    }
    die("WTF?");
}
?>



