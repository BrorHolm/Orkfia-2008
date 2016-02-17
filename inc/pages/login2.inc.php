<?php
//******************************************************************************
// Page login2                                         by Martel, April 06, 2006
//
// Information: (Security Update)
// Password now encrypted using sha1 with improved routines of checking this.
//******************************************************************************
function include_login2_text()
{
    global  $result, $grab, $logins, $orkTime, $update, $HTTP_USER_AGENT,
            $updated, $connection, $userid;

    $login['username']        = stripslashes($_POST['login']['username']);
    $login['password']        = stripslashes($_POST['login']['password']);
    $login_check['username']  = mysql_escape_string($login['username']);
    $login_check['password']  = mysql_escape_string($login['password']);
    $ip                       = quote_smart($_SERVER['REMOTE_ADDR']);

    if ($_SERVER['SERVER_NAME'] == 'development.orkfia.org')
    {
        $check = substr($login_check['username'], 0, 3);
        if ($check == 'Dev') $login_check['username'] = substr($login_check['username'], 3);
        else $login_check['username'] = 'iamn00b';
    }

    if ($login_check['username'] && $login_check['password'])
    {
        // check if password is correct
        $login_search = mysql_query("SELECT * FROM user WHERE username = '$login_check[username]' AND password = sha1('$login_check[password]')");
        if ($grab = mysql_fetch_array($login_search))
        {
            $logins = $grab['logins'] + 1;
            $orkTime = date(TIMESTAMP_FORMAT);
            $now = date("Hi");
            session_start();
            $session = session_id();

            $update  = mysql_query ("UPDATE user SET logins='$logins', status = 2, last_login='$orkTime', time_limit = $now, session = '$session' WHERE username = '$login_check[username]' && id = '$grab[id]'");
            $updated = mysql_query($update, $connection);

            mysql_query("INSERT INTO `logins`  VALUES ('', $ip, '$grab[id]', '$orkTime','$HTTP_USER_AGENT')");

            $md5me = "t1r1p0d4"."$grab[username]";
            $md5sum = md5($md5me);
            setcookie("userid", "$grab[id]");
            setcookie("check", "$md5sum");
            setcookie("Look Here", "Tampering Cookies Will Not Work :)");

            header("Location: main.php?cat=game&page=tribe");
        }
        else // wrong password or username
        {
            header("Location: main.php?cat=main&page=main&error=error");
        }
    }
    else
    {
        header("Location: main.php?cat=main&page=main&error=empty");
    }
}

?>
