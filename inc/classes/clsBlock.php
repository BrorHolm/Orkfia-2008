<?php
//==============================================================================
// clsBlock, a class with with the functions to block multiple tribes with
// the same IP from opping the same target      - AI 11/02/2007
// Interface:
// -isOpAllowed($objSrcUser, $objTrgUser) - returns wether it's allowed
// -logOp($objSrcUser, $objTrgUser, $op) - logs the op, reports if not allowed
// -reportOp($objSrcUser, $objTrgUser, $op, $done) - reports the op
// -cleanLog() - cleans the logs of ops older than 8 ticks
//==============================================================================

require_once('inc/functions/forums.php');

class clsBlock {
    //get current tick
    function getTick()
    {
        $tick = mysql_query("SELECT hour_counter FROM " . TBL_GAME_TIME);
        $tick = mysql_fetch_assoc($tick);
        $tick = $tick['hour_counter'];
        return $tick;
    }

    function isOpAllowed(&$objSrcUser, &$objTrgUser)
    {
        $tick   = clsBlock::getTick();
        $cutoff = $tick - 8;
        $srcId  = $objSrcUser->get_userid();
        $trgId  = $objTrgUser->get_userid();
        $srcIp  = $_SERVER['REMOTE_ADDR'];
        $ops    = mysql_query("SELECT * FROM blocks WHERE tick > $cutoff AND target_id = $trgId AND source_id != $srcId AND source_ip = '$srcIp'");
        if ($ops === false) die("Mysql error: " . mysql_error());
        return (mysql_num_rows($ops) == 0);
    }

    function logOp(&$objSrcUser, &$objTrgUser, $op)
    {
        $tick   = clsBlock::getTick();
        $srcId  = $objSrcUser->get_userid();
        $trgId  = $objTrgUser->get_userid();
        $srcIp  = $_SERVER['REMOTE_ADDR'];
        $op     = mysql_real_escape_string($op);
        if (!clsBlock::isOpAllowed($objSrcUser, $objTrgUser))
            clsBlock::reportOp($objSrcUser, $objTrgUser, $op, true);
        mysql_query("INSERT INTO blocks (target_id, source_id, source_ip, tick, op) VALUES ($trgId, $srcId, '$srcIp', $tick, '$op')") or die("Logging failure: " . mysql_error());
    }
    function reportOp(&$objSrcUser, &$objTrgUser, $op, $done=false)
    {
        $orkTime = $GLOBALS['orkTime'];
        $tick   = clsBlock::getTick();
        $cutoff = $tick - 8;
        $srcId  = $objSrcUser->get_userid();
        $trgId  = $objTrgUser->get_userid();
        $srcIp  = $_SERVER['REMOTE_ADDR'];
        $op     = mysql_real_escape_string($op);

        $ops    = mysql_query("SELECT * FROM blocks WHERE tick > $cutoff AND target_id = $trgId AND source_id != $srcId AND source_ip = '$srcIp' ORDER BY id DESC");

        $post   = "***Target***\r\nUserid: $trgId\r\nTribe: " . $objTrgUser->get_stat(TRIBE) . '(#' . $objTrgUser->get_stat(ALLIANCE) . ")\r\n\r\n";
        $post  .= "***Offender***\r\nUserid: $srcId\r\nTribe: " . $objSrcUser->get_stat(TRIBE) . '(#' . $objSrcUser->get_stat(ALLIANCE) . ")\r\nIP: " . $srcIp . "\r\n";
        if ($done)
            $post .= "Successfully Performed:";
        else
            $post  .= "Trying to perform:";
        $post  .= "$op\r\n At tick: $tick\r\n\r\n";
        $post  .= "***Previous ops from the same IP below\r\n\r\n";

        while($row = mysql_fetch_assoc($ops)){
            $objTmpUser = new clsUser($row['source_id']);
            $post .= "Userid: " . $row['source_id'] .
                    "\r\nTribe: " . $objTmpUser->get_stat(TRIBE) . '(#' . $objTmpUser->get_stat(ALLIANCE) . ")" .
                    "\r\nPerformed: " . $row['op'] .
                    "\r\nAt tick: " . $row['tick'] . "\r\n\r\n";
        }


        $alliance = 2;
        if ($done)
            $title  = "Blocking system: Successful ops";
        else
            $title  = "Blocking system: Blocked ops";
        $thread = mysql_query("SELECT post_id FROM forum WHERE poster_kd = $alliance AND parent_id = 0 AND title = '$title' AND type = 0") or die('mysql error: ' . mysql_error());
        if(mysql_num_rows($thread) == 0){
            mysql_query("INSERT INTO forum (poster_kd,title,post,date_time,updated,poster_name,poster_tribe) VALUES ($alliance,'$title','Automated report thread','$orkTime','$orkTime','Reporter','Reporter')") or die('mysql error: ' . mysql_error());
            $thread = mysql_query("SELECT post_id FROM forum WHERE poster_kd = $alliance AND parent_id = 0 AND title = '$title' AND type = 0") or die('mysql error: ' . mysql_error());
        }
        $thread = mysql_fetch_assoc($thread);
        $thread = $thread['post_id'];
        make_post($objSrcUser->get_userid(),$thread,$alliance,0,$post);

    }

    function cleanLogs()
    {
        $tick   = clsBlock::getTick();
        $cutoff = $tick - 8;
        mysql_query("DELETE FROM blocks WHERE tick < $cutoff") or die("Failure cleaning logs: " . mysql_error());
    }

}

?>
