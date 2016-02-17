<?php
//============================================================================
// This file attempts to abstract some of the mailing system to functions
//  to avoid the duplicate code we have now (which has cause many bugs)
// For now, we have the following functions:
// - send_mail($from_id, $to_id, $subject, $message); //sends mail
// - is_blocked_mail($from_id, $to_id); //returns wether to has blocked from
// - block_mail($blocker_id, $blocked_id); //blocks blocked mailing blocker
// - unblock_mail($blocker_id, $blocked_id); //unblocks
// - get_blocks_mail($blocker_id); //returns all blocked userids
//                                                            - AI 10/12/2006
//============================================================================

require_once('inc/functions/forums.php');

function send_mail($from_id, $to_id, $subject, $message){
    $orkTime = $GLOBALS['orkTime'];

    $objSrcUser = new clsUser($from_id);
    $objTrgUser = new clsUser($to_id);
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgStats = $objTrgUser->get_stats();
    // we should check for the blocking system around here
    if(is_blocked_mail($from_id,$to_id)){
        echo "<br /><br />You cannot mail {$arrTrgStats['tribe']} because you have been blocked from doing so.";
        include_game_down();
        exit;
    }
    $subject = safeHTML($subject);
    $message = safeHTML($message);
    $message = "$message<br /><br /><i>~{$arrSrcStats['tribe']}(#{$arrSrcStats['kingdom']})";
    if(!$subject) $subject = "No Subject";
    mysql_query("INSERT INTO messages (for_user, from_user, date, subject, text, new, action) VALUES ('$to_id', '$from_id', '$orkTime', '$subject', '$message', 'new', 'received')");
    mysql_query("INSERT INTO messages (for_user, from_user, date, subject, text, new, action) VALUES ('$to_id', '$from_id', '$orkTime', '$subject', '$message', 'new', 'sent')");
    echo "<h3>Message sent to {$arrTrgStats['tribe']}(#{$arrTrgStats['kingdom']})</h3><br />";
    mysql_query ("UPDATE preferences SET last_m ='$orkTime' WHERE id = $to_id");
}

function is_blocked_mail($from_id, $to_id){
    $res = mysql_query("SELECT * FROM block_messages WHERE blocker_id = $to_id AND blocked_id = $from_id");
    if($res === false) die(mysql_error());
    return ((bool)mysql_num_rows($res));
}

function block_mail($blocker_id, $blocked_id){
    mysql_query("REPLACE INTO block_messages (blocker_id, blocked_id) VALUES ($blocker_id, $blocked_id)");
}

function unblock_mail($blocker_id, $blocked_id){
    mysql_query("DELETE FROM block_messages WHERE blocker_id = $blocker_id AND blocked_id = $blocked_id");
}

function get_blocks_mail($blocker_id){
    $allblocks = array();
    $blocks = mysql_query("SELECT blocked_id,tribe FROM block_messages,stats WHERE blocker_id = $blocker_id AND blocked_id = id");
    while($blocked_row = mysql_fetch_assoc($blocks)){
        $allblocks[] = $blocked_row;
    }
    return $allblocks;
}

?>
