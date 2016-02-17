<?

function call_userpw_text()
{
    global $id, $confirm, $level, $tool;
    
    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id: <input name=id size=5><br>";
    ECHO "<br><br>This function will randomize a new password and send it by mail.";
    ECHO "<br><br>";
    ECHO "<input type=submit value=Save name=confirm>";
    ECHO "</form>";
    
    IF($confirm && $id)
    {
        $objUser   = new clsUser ($id);
        $email     = $objUser->get_preference(EMAIL);
        $password  = generatePassword();
        $cpassword = $password;
        
        mysql_query("UPDATE user SET password = sha1('$cpassword') WHERE id = $id");
        $username = $objUser->get_user_info(USERNAME);
        mail("$email","ORKFiA New Password","Hello, \nYour password has been updated with a new one =) \n\nUsername: $username \nPassword: $password \n\n- The ORKFiA Team\n\n\nThis email is php generated, you cannot successfully reply." , "From: registration@orkfia.org\r\nX-Mailer: PHP/4.3.0\r\nX-Priority: Normal");
        echo "User " . $id . " will have a new pw sent within minutes to: " . $email;
    }
}

function generatePassword($length = 8)
{
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);

    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

?>