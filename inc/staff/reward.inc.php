<?php
function call_reward_text()
{
    $tool = $GLOBALS['tool'];

    require_once('inc/functions/resort_tools.php');
    check_access($tool);

    if (isset($_POST['confirm']) && isset($_POST['id']))
    {
        $id = $_POST['id'];
        if (empty($id))
        {
            $error .= "Who are you rewarding?<br />";
        }
        if (isset($_POST['crowns'])) $crowns = $_POST['crowns'];
        if (isset($_POST['food'])) $food = $_POST['food'];
        if (isset($_POST['logs'])) $logs = $_POST['logs'];
        if (isset($_POST['rps'])) $rps = $_POST['rps'];
        if (empty($crowns) && empty($food) && empty($logs) && empty($rps))
        {
            $error .= "No reward given<br />";
        }
        $message = "Hey there,\n\nCongrats on placing in the top three in the Banner competition\nAs Promised here is your reward for your outstanding work\n\n";
        if ($crowns) $message .= "$crowns crowns\n";
        if ($food) $message .= "$food kgs of food\n";
        if ($logs) $message .= "$logs logs\n";
        if ($rps) $message .= "$rps research points\n";
        $message .= "\nWe look forward to continue working with you in the future\n\n~Greeting from Marketing~";

        if (!isset($error)){

            $sender = $GLOBALS['objSrcUser'];
            $sender = $sender->get_userid();

            if (!is_numeric($crowns)) $crowns = 0;
            if (!is_numeric($food)) $food = 0;
            if (!is_numeric($logs)) $logs = 0;
            if (!is_numeric($rps)) $rps = 0;

            mysql_query("UPDATE goods SET money = money + $crowns, food = food + $food, wood = wood + $logs, research = research + $rps WHERE id = $id");
            require_once('inc/functions/mail.php');
            send_mail($sender, $id, 'Banner Competition Reward', $message);
        }
    }

    if (isset($error)) echo "<span style=\"color:#FF0000;\">$error</span>";

    echo "Reward a user<br />" .
        "<form method=\"post\" action=\"{$_SERVER['REQUEST_URI']}\">" .
        "User ID: <input name=\"id\" size=\"5\" /><br />" .
        "Crowns: <input name=\"crowns\" size=\"10\" /><br />" .
        "Food: <input name=\"food\" size=\"10\" /><br />" .
        "Logs: <input name=\"logs\" size\"10\" /><br />" .
        "Rps: <input name=\"rps\" size=\"10\" /><br />" .
        "<input type=\"submit\" name=\"confirm\" value=\"Send reward\" />" .
        "</form>";
}
?>
