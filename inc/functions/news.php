<?php


function insert_news_item($i_strType, $i_intDestUserId, $i_intSrcUserId,
    $i_intResult, $i_strMessage, $i_strAllianceMessage) {

    $strSQL = "INSERT INTO news (id, time, ip, type, duser, " .
              "       ouser, result, text, kingdom_text) " .
              "VALUES (" .
                 "'', " . 
                 date(TIMESTAMP_FORMAT) . ", " . 
                 "'" . $GLOBALS["HTTP_SERVER_VARS"]["REMOTE_ADDR"]. "', " .
                 " '$i_strType', " .
                 $i_intDestUserId . ", " . 
                 $i_intSrcUserId . ", " .
                 $i_intResult . ", " .
                 " '". mysql_real_escape_string($i_strMessage) . "', " .
                 " '". mysql_real_escape_string($i_strAllianceMessage) . "')";

    mysql_query($strSQL) or die("insert_news_item: " . mysql_error());
}

?>
