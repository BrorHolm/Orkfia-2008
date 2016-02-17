<?php

function connectdb()
{
    global $ip, $connection, $select_db, $timestamp;
//     global $HTTP_SERVER_VARS;
//     $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];

//     $bannednetworks = array("167.88.192.30","24.196.0.0","255.255.0.0","131.211.235.34","219.94.56.0","219.94.59.0","219.94.60.0","219.94.117.0","219.94.118.0","219.94.131.0");
//     for ($i = 0; $i < count($bannednetworks); $i++) {
//         $network = $bannednetworks[$i];
//         if (ip2long($ip) & ip2long($network[1]) == ip2long($network[0])) {
//             echo "<br><br><center><b>This ip has been banned from playing orkfia.</b></center>";
//             exit;
//         }
//     }

    $timestamp = date('YmdHis');
    $server = "localhost:3306";
    $user = "phpsuppo";
    $pass = str_rot13('mS64MJT9');
    $pass = $pass; // To testers: Just change $pass to your own PW -here-

    if (!isset($connection))
        $connection = @mysql_pconnect($server, $user, $pass)
            or die("Error: " . mysql_error());

    switch ($_SERVER['SERVER_NAME'])
    {
        case DINAH_SERVER_NAME:
            $select_db = mysql_select_db("phpsuppo_c"); // Dinah (Classic)
        break;
        case DEV_SERVER_NAME:
            $select_db = mysql_select_db("phpsuppo_d"); // Devork -FOR TESTING-
        break;
        default:
            $select_db = mysql_select_db("phpsuppo_3"); // Infinity
    }
}

?>