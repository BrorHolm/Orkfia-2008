<?php

function include_mail_text()
{
    global  $Host, $d_stats, $tribe, $mid, $subject, $set, $type, $action,
            $userid, $submit, $kingdom, $userid, $message, $inputBody, $orkTime,
            $local_stats, $connection, $posts, $replyid;

    include_once('inc/functions/forums.php');
    require_once('inc/functions/mail.php');

    $objSrcUser  = &$GLOBALS['objSrcUser'];
    $local_stats = $objSrcUser->get_stats();


    if (!$set) { $set = "view"; }
    if (!$kingdom) { $kingdom = $local_stats['kingdom']; }

    $count = '0';
    $topLinks =
        '<div class="center">' .
        "| " .
            "<a href=\"main.php?cat=game&amp;page=mail&amp;set=compose\">" .
                "Compose Mail" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=mail&amp;set=view\">" .
                "View Inbox" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=mail&amp;set=outbox\">" .
                "View Outbox" .
            "</a>" .
        " | " .
            "<a href=\"main.php?cat=game&amp;page=message&amp;tribe=1&amp;alliance=1\">" .
                "Send a Report" .
                        "</a>" .
                " | " .
                        "<a href=\"main.php?cat=game&amp;page=mail&amp;set=block\">" .
                                "Block Mail" .
            "</a>";

    if ($local_stats['type'] == 'elder')
    {
        $topLinks .=
        " | " .
            "<a href=\"main.php?cat=game&amp;page=mail&amp;set=eldermail\">" .
                "Alliance Mail" .
            "</a>";
    }
    $topLinks .=
        " |</div>";

    echo $topLinks;

    if ($set == "sendmail"){
        send_mail($userid, $tribe, $subject, $message);
        //changed to use send_mail function - AI 10/12/2006
    }
    if ($set == "eldermailsend"){
        $message = safeHTML($message);
        $subject = safeHTML($subject);
        $message = "$message<br /><br />Your elder: " .$local_stats['name'];
        if (!$subject){ $subject = "No Subject"; }
        $query = mysql_query("SELECT id FROM stats WHERE kingdom = $local_stats[kingdom]");
        while($datas=mysql_fetch_array($query)){
            if ($datas["id"] != $userid) {
                $create['message'] = mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', '".$datas['id']."', '".$userid."', '".$orkTime."', '".$subject."', '".$message."', 'new', 'received')");
                $update['timestamp'] = mysql_query ("UPDATE preferences SET last_m ='$orkTime' WHERE id= $tribe");
            }
        }
        $create['message'] = mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', '0', '".$userid."', '".$orkTime."', '".$subject."', '".$message."', 'old', 'sent')");
        $set = "eldermail";
        echo '<div class="center">' . "<h3>Message sent to all your alliance members.</h3></div>";
    }
    if ($set == "eldermail"){
        $eldermail =
            '<div id="textBig">' .
            "<h2>Mail your alliance</h2>" .
            "<form action=\"main.php?cat=game&amp;page=mail&amp;set=eldermailsend\" method=\"post\">" .
            "<br />" .
            "Subject: <input type=\"text\" name=\"subject\" size=\"30\" />" .
            "<br />" .
            "<textarea name=\"message\" rows=\"10\" cols=\"70\" wrap=\"on\"></textarea>" .
            "<br />" .
            "<input type=\"submit\" value=\"Send Message\" />" .
            "</form>" .
            "</div>";
        echo $eldermail;
    }
    if ($set == "compose")
    {
        $sendMailTargets = "<option value=\"spacer\">";
        if (isset($_GET['aid']) && !empty($_GET['aid']))
            $kingdom = intval($_GET['aid']);
        if (isset($_GET['tribe']) && !empty($_GET['tribe']))
            $replyid = intval($_GET['tribe']);
        $result = mysql_query ("SELECT * FROM stats WHERE kingdom = $kingdom ORDER BY tribe");
        while ($kdstats = (mysql_fetch_array($result, MYSQL_ASSOC))) {
            $kdstats["tribe"] = stripslashes($kdstats["tribe"]);
            if ($kdstats["id"] == $replyid) {
                $sendMailTargets .= "<option value=\"" . $kdstats['id'] . "\" selected>" . $kdstats['tribe'];
            } else {
                $sendMailTargets .= "<option value=\"" . $kdstats['id'] . "\">" . $kdstats['tribe'];
            }
        }
        $compose =
            "<br />" .
            "<table cellspacing=\"0\" cellpadding=\"0\" class=\"small\">" .
                "<tr class=\"header\">" .
                    "<th colspan=\"2\">" . "Compose Mail" . "</th>" .
                "</tr>" .

                "<tr class=\"subheader\">" .
                    "<th colspan=\"2\" class=\"center\">" . "Select Target" . "</th>" .
                "</tr>" .

                "<tr class=\"data\">" .
                    "<form action=\"main.php?cat=game&amp;page=mail&amp;set=compose\" method=\"post\">" .
                    "<th>" . "Alliance:" . "</th>" .
                    "<td>" .
                        "<input maxlength=\"4\" size=\"3\" name=\"kingdom\" value=\"$kingdom\" />" .
                        "<input type=\"submit\" value=\"Change\" />" .
                    "</td>" .
                    "</form>" .
                "</tr>" .

            "<form id=\"center\" action=\"main.php?cat=game&amp;page=mail&amp;set=sendmail\" method=\"post\">" .
                "<tr class=\"data\">" .
                    "<th>" . "Tribe:" . "</th>" .
                    "<td>" . "<select name=\"tribe\">" . $sendMailTargets . "</select>" . "</td>" .
                "</tr>" .
            "</table>" .
            "<br />" .
            '<div class="center">' .
            "Subject: <input type=\"text\" name=\"subject\" size=\"30\" />" .
            "<br />" .
            "<textarea name=\"message\" rows=\"10\" cols=\"70\" wrap=\"on\"></textarea>" .
            "<br />" .
            "<input type=\"submit\" value=\"Send Message\" />" .
            "</form>" .
            "</div>";
        echo $compose;
    }
    if ($set == "view"){
        $result = mysql_query ("SELECT * from messages WHERE for_user ='$userid' AND action = 'received' AND new != 'deleted' ORDER BY date DESC")or die(mysql_error());
        $num_mail = mysql_num_rows($result);
        if ($num_mail <= "0"){
            echo "You have no mail in your inbox.<br />";
            include_game_down();
            exit;
        }
        $update['timestamp'] = mysql_query ("UPDATE preferences SET last_m_check ='$orkTime' WHERE id= $userid");
        $updated['timestamp']= mysql_query($update['timestamp'], $connection);

        $inbox =
            "<form id=\"center\" name=\"mail\" method=\"post\" action=\"main.php?cat=game&amp;page=mail&amp;set=delete2\">" .
            "<table cellpadding=\"0\" cellspacing=\"0\" class=\"big\">" .
                "<tr class=\"header\">" .
                    "<th colspan=\"5\">" . "Inbox" . "</th>" .
                "</tr>" .

                "<tr class=\"subheader\">" .
                    "<th>" . "Subject" . "</th>" .
                    "<td class=\"left\">" . "From" . "</td>" .
                    "<td class=\"left\">" . "Date" . "</td>" .
                    "<td class=\"left\">" . "Status" . "</td>" .
                    "<td class=\"center\">" . "Delete" . "</td>" .
                "</tr>";
        while ($mail =(mysql_fetch_array($result)) )
        {
            $count++;
            if ( $count == '1' ) {
                $class = "";
            } else {
                $class = "bsup";
            }
            mysql_grab($mail['from_user'], 'd', 'stats');
            if (empty($mail['subject']))
            {
                $mail['subject'] = 'No Subject';
            }
        $inbox .=
                "<tr class=\"data\">" .
                    "<th class=\"" . $class . "\">" .
                        "<a href=\"main.php?cat=game&amp;page=mail&amp;set=read&amp;mid=" . $mail['id'] . "\">" .
                            cleanHTML($mail['subject']) .
                        "</a>" .
                    "</th>" .
                    "<td class=\"" . $class . " left\">" . cleanHTML($d_stats['tribe']) . "(#" . $d_stats['kingdom']. ")</td>" .
                    "<td class=\"" . $class . " left\">" . $mail['date'] . "</td>" .
                    "<td class=\"" . $class . " left\">" . $mail['new'] . "</td>" .
                    "<td class=\"" . $class . " center\">" . "<input name=\"posts[]\" type=\"checkbox\" value=\"" . $mail['id'] . "\" />" . "</td>" .
                "</tr>";
        }
        $inbox .=
            "</table>" .
            '<br /><div class="center">' .
            "| <a href='#' onclick=\"var posts=document.getElementsByName('mail')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=true;\">Check All</a>" .
            " | <a href='#' onclick=\"var posts=document.getElementsByName('mail')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=false;\">Uncheck All</a> |" .
            "</div><br />" .
            "<input type=\"submit\" name=\"submit\" value=\"Delete\" />" .
            "</form>";
        echo $inbox;
    }
    if ($set == "outbox"){
        $result = mysql_query ("SELECT * from messages WHERE from_user ='$userid' AND action = 'sent' AND new != 'deleted' ORDER BY date DESC")or die(mysql_error());
        $num_mail = mysql_num_rows($result);
        if ($num_mail <= "0"){
            echo "<div class=\"center\">You have no mail in your outbox.</div>";
            include_game_down();
            exit;
        }
        $outbox =
            "<form id=\"center\" name=\"mail\" method=\"post\" action=\"main.php?cat=game&amp;page=mail&amp;set=deleteout2\">" .
            "<table cellpadding=\"0\" cellspacing=\"0\" class=\"big\">" .
                "<tr class=\"header\">" .
                    "<th colspan=\"5\">" . "Outbox" . "</th>" .
                "</tr>" .

                "<tr class=\"subheader\">" .
                    "<th>" . "Subject" . "</th>" .
                    "<td class=\"left\">" . "To" . "</td>" .
                    "<td class=\"left\">" . "Date" . "</td>" .
                    "<td class=\"left\">" . "Status" . "</td>" .
                    "<td class=\"center\">" . "Delete" . "</td>" .
                "</tr>";
        while ($mail =(mysql_fetch_array($result)) ){
            $count++;
            if ( $count == '1' ) {
                $class = "";
            } else {
                $class = "bsup";
            }
            if ( $mail['for_user'] == "0" )
            {
                $receiver = "Your Alliance";
            }
            else
            {
                $foruser = mysql_query("SELECT tribe, kingdom FROM stats WHERE id = $mail[for_user]");
                $foruser = mysql_fetch_array($foruser);
                $receiver = cleanHTML($foruser['tribe'])."(#$foruser[kingdom])";
            }
            if (empty($mail['subject']))
            {
                $mail['subject'] = 'No Subject';
            }
        $outbox .=
                "<tr class=\"data\">" .
                    "<th class=\"" . $class . "\">" .
                        "<a href=\"main.php?cat=game&amp;page=mail&amp;set=readout&amp;mid=" . $mail['id'] . "\">" .
                            cleanHTML($mail['subject']) .
                        "</a>" .
                    "</th>" .
                    "<td class=\"" . $class . " left\">" . $receiver . "</td>" .
                    "<td class=\"" . $class . " left\">" . $mail['date'] . "</td>" .
                    "<td class=\"" . $class . " left\">" . $mail['new'] . "</td>" .
                    "<td class=\"" . $class . " center\">" . "<input name=\"posts[]\" type=\"checkbox\" value=\"" . $mail['id'] . "\" />" . "</td>" .
                "</tr>";
        }
        $outbox .=
            "</table>" .
            "<br /><br />" .
            "| <a href='#' onclick=\"var posts=document.getElementsByName('mail')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=true;\">Check All</a>" .
            " | <a href='#' onclick=\"var posts=document.getElementsByName('mail')[0]['posts[]']; for(var i=0,len=posts.length;i<len;i++) posts[i].checked=false;\">Uncheck All</a> |" .
            "<br /><br />" .
            "<input type=\"submit\" name=\"submit\" value=\"Delete\" />" .
            "</form>";
        echo $outbox;
    }
    if ($set == "readout"){
        $result = mysql_query ("SELECT * from messages WHERE from_user ='$userid' AND id = '$mid' AND action = 'sent' AND new != 'deleted'");
        $read =mysql_fetch_array($result);
        $read['subject'] = stripslashes(stripslashes($read['subject']));
        $read['text'] = stripslashes(stripslashes($read['text']));
        if ( $read['for_user'] == "0" ) {
            $receiver = "Your Alliance";
        } else {
            $foruser = mysql_query("SELECT tribe, kingdom FROM stats WHERE id = $read[for_user]");
            $foruser = mysql_fetch_array($foruser);
            $receiver = "$foruser[tribe](#$foruser[kingdom])";
        }
        $readout =
            "<table cellpadding=\"0\" cellspacing=\"0\" class=\"medium\">" .
                "<tr class=\"header\">" .
                    "<th>" ."Message to: " . $receiver . "</th>" .
                "</tr>" .

                "<tr class=\"subheader\">" .
                    "<th>" ."Subject: " . cleanHTML($read['subject']) . "</th>" .
                "</tr>" .

                "<tr class=\"message\">" .
                    "<td>" . "<br />" . cleanHTML($read['text']) . "<br />" . "</td>" .
                "</tr>" .
            "</table>" .
            "<br />" .
            '<div class="center">' . "| <a href=main.php?cat=game&page=mail&set=deleteout&mid=$mid>Delete</a> | " .
            "<a href=main.php?cat=game&page=mail&set=outbox>Return To Outbox</a> |</div>";
        echo $readout;
    }
    if ($set == "read"){
        $result = mysql_query ("SELECT * from messages WHERE for_user ='$userid' AND id = '$mid' AND action = 'received' AND new != 'deleted'");
        $read =mysql_fetch_array($result);
        mysql_grab($read['from_user'], 'd', 'stats');
        $read['subject'] = stripslashes(stripslashes($read['subject']));
        $read['text'] = stripslashes(stripslashes($read['text']));
        $readin =
            "<br /><table cellpadding=\"0\" cellspacing=\"0\" class=\"medium\">" .
                "<tr class=\"header\">" .
                    "<th>" ."Message from: " . stripslashes($d_stats['name']) . "</th>" .
                "</tr>" .

                "<tr class=\"subheader\">" .
                    "<th>" ."Subject: " . cleanHTML($read['subject']) . "</th>" .
                "</tr>" .

                "<tr class=\"message\">" .
                    "<td>" . "<br />" . cleanHTML($read['text']) . "<br />" . "</td>" .
                "</tr>" .
            "</table>" .
            "<br />" .
            '<div class="center">' .
            "| <a href=main.php?cat=game&page=mail&set=reply&mid=$mid>Reply</a> | " .
            "<a href=main.php?cat=game&page=mail&set=delete&mid=$mid>Delete</a> | " .
            "<a href=main.php?cat=game&page=mail&set=view&mid=$d_stats[id]>Return To Inbox</a> | " .
            '</div>';
        echo $readin;
        $old = mysql_query ("UPDATE messages SET new ='old' WHERE id ='$mid'");
        $mid2 = $mid+1;
        $select = mysql_query("SELECT action FROM messages WHERE id = '$mid2'");
        $select = mysql_fetch_array($select);
        if ($select['action'] == 'sent'){
            $old = mysql_query ("UPDATE messages SET new ='old' WHERE id ='$mid2'");
        }
    }
    if ($set == "delete"){
        $email_name = ("UPDATE messages SET new = 'deleted' WHERE id ='$mid' AND for_user = '$userid' AND action = 'received'");
        $delete = mysql_query($email_name, $connection);
        echo '<div id="textMedium"><p>' . "The message has been deleted.<br /><br />";
        echo "<a href=main.php?cat=game&page=mail&set=view>Return To Inbox</a></p>" . '</div';
    }
    if ($set == "delete2"){
        $sql = "UPDATE messages SET new = 'deleted' WHERE for_user = '$userid' AND action = 'received' ";
        $sql.= " AND id IN (";
        $posts=$_POST["posts"];
        $postcount=count($posts);
        for($i=0;$i<$postcount;$i++){
            $sql.="$posts[$i]";
            if ($i!=$postcount-1){
                $sql.=",";
            }
        }
        $sql.=")";
        $delete = mysql_query($sql, $connection);
        echo '<div id="textMedium"><p>' . "The selected messages are deleted.<br /><br />";
        echo "<a href=main.php?cat=game&page=mail&set=view>Return To Inbox</a></p>" . '</div';
    }
    if ($set == "deleteout"){
        $email_name = ("UPDATE messages SET new = 'deleted' WHERE id ='$mid' AND from_user = '$userid' AND action = 'sent'");
        $delete = mysql_query($email_name, $connection);
        echo '<div id="textMedium"><p>' . "The message has been deleted.<br /><br />";
        echo "<a href=main.php?cat=game&page=mail&set=outbox>Return To Outbox</a></p>" . '</div';
    }
    if ($set == "deleteout2"){
        $sql = "UPDATE messages SET new = 'deleted' WHERE from_user = '$userid' AND action = 'sent' ";
        $sql.= " AND id IN (";
        $posts=$_POST["posts"];
        $postcount=count($posts);
        for($i=0;$i<$postcount;$i++){
            $sql.="$posts[$i]";
            if ($i!=$postcount-1){
                $sql.=",";
            }
        }
        $sql.=")";
        $delete = mysql_query($sql, $connection);
        echo '<div id="textMedium"><p>' . "The selected messages are deleted.<br /><br />";
        echo "<a href=main.php?cat=game&page=mail&set=outbox>Return To Outbox</a></p>" . '</div';
    }
    if ($set == "reply"){
        if ($action != "post"){
            $result = mysql_query ("SELECT * from messages WHERE for_user ='$userid' AND id = '$mid' AND action = 'received'");
            $reply = mysql_fetch_array($result);
            $subject = "Re: ".cleanHTML($reply['subject'])." ";

            $replyText =
                "<form action=\"main.php?cat=game&amp;page=mail&amp;set=reply&amp;mid=$mid&amp;action=post\" method=\"post\">" .
                "<br />" .
                "Subject: <input type=\"text\" name=\"subject\" size=\"30\" value=\"" . $subject . "\" />" .
                "<br />" .
                "<textarea name=\"message\" rows=\"10\" cols=\"70\" wrap=\"virtual\"></textarea>" .
                "<br />" .
                "<input type=\"submit\" name=\"submit\" value=\"Send Message\" />" .
                "</form>";
            echo $replyText;
        }
        if ($action == "post"){
            $result = mysql_query ("SELECT * from messages WHERE for_user ='$userid' AND id = '$mid' AND action = 'received'");
            $reply =mysql_fetch_array($result);
            send_mail($userid, $reply['from_user'], $subject, $message);
            echo "<a href=main.php?cat=game&page=mail>Return To Mailbox</a>";
        }
    }
    if ($set == "block")
    {
        if (isset($_POST['tribe']) && $_POST['tribe'] != 'spacer' && $action == "block"){
            $blocker_id = $objSrcUser->get_userid();
            $blocked_id = quote_smart($_POST['tribe']);
            $objTrgUser = new clsUser($blocked_id);
            $blocked_name = $objTrgUser->get_stat(TRIBE);
            echo '<br /><div class="center">' . "You have blocked $blocked_name from sending you any more mail.</div>";
            block_mail($blocker_id, $blocked_id);
        }
        if (isset($_GET['id']) && $_GET['id'] > 0 && $action == "unblock"){
            $blocker_id = $objSrcUser->get_userid();
            $blocked_id = quote_smart($_GET['id']);
            $objTrgUser = new clsUser($blocked_id);
            $blocked_name = $objTrgUser->get_stat(TRIBE);
            echo '<br /><div class="center">' . "You have unblocked $blocked_name, they can send you mail again.</div>";
            unblock_mail($blocker_id, $blocked_id);
        }
        $tribes = mysql_query("select tribe,id from stats where kingdom = $kingdom order by tribe");
        $blockTargets = "<option value=\"spacer\"></option";
        while($allistats = mysql_fetch_assoc($tribes)){
            $tribe = stripslashes($allistats['tribe']);
            $id = $allistats['id'];
            $blockTargets .= "<option value=\"$id\">$tribe</option>";
        }
        echo "<br /><table cellspacing=\"0\" cellpadding=\"0\" class=\"small\">" .
                "<tr class=\"header\"><th colspan=\"2\">Block Mail</th></tr>" .
                "<tr class=\"subheader\"><th colspan=\"2\" class=\"center\">Select spammer</th></tr>" .
                "<tr class=\"data\"><form action=\"main.php?cat=game&amp;page=mail&amp;set=block\" method=\"post\">" .
                "<th>Alliance:</th><td><input maxlength=\"4\" size=\"3\" name=\"kingdom\" value=\"$kingdom\" />" .
                "<input type=\"submit\" value=\"Change\" /></td></form></tr>" .

                "<form action=\"main.php?cat=game&amp;page=mail&amp;set=block&amp;action=block\" method=\"post\">" .
                "<tr class=\"data\"><th>Tribe:</th><td><select name=\"tribe\">$blockTargets</select>" .
                "<input type=\"submit\" value=\"Block\" name=\"Block\" /></td></tr></form>" .
                "</table><br /><br />";

        $blocked_users = get_blocks_mail($objSrcUser->get_userid());
        echo "<table cellspacing=\"0\" cellpadding=\"0\" class=\"small\">" .
                "<tr class=\"header\"><th colspan=\"2\">Blocked users</th></tr>" .
                "<tr class=\"subheader\"><th colspan=\"2\" class=\"center\">Remove?</th></tr>";
        foreach($blocked_users as $blocked_user){
                echo "<tr class=\"data\"><th>{$blocked_user['tribe']}</th>" .
                    "<td><a href=\"main.php?cat=game&amp;page=mail&amp;set=block&amp;" .
                    "action=unblock&amp;id={$blocked_user['blocked_id']}\">Remove?</td></tr>";
        }
        echo "</table>";

    }
}
?>
