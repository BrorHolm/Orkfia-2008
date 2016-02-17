<?php
//******************************************************************************
// Staff tools: reset alliances                         November 06, 2007 Martel
// History:
// frost (age20): rewritten routine.
//******************************************************************************

function call_reset_alliances_text()
{
    global $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    // Select all alliance numbers
    $oldAlliances = mysql_query("SELECT id FROM kingdom WHERE id > 10 ORDER BY id ASC");

    // Set old alli ids + 5000
    $update = mysql_query("UPDATE kingdom SET id=id+5000 WHERE id > 10");
    $update = mysql_query("UPDATE stats SET kingdom=kingdom+5000 WHERE kingdom > 10");
    $update = mysql_query("UPDATE forum SET poster_kd=poster_kd+5000 WHERE poster_kd > 10");
    //$update = mysql_query("UPDATE market_log SET alliance=alliance+5000 WHERE alliance > 10");
    $update = mysql_query("UPDATE mergers SET target=target+5000 WHERE target > 10");
    $update = mysql_query("UPDATE rankings_personal SET alli_id=alli_id+5000 WHERE alli_id > 10");

    $alliLocCount = 0;
    while($alliLoc = mysql_fetch_row($oldAlliances))
    {
       $AlliArray[$alliLocCount] = $alliLoc[0];
       $alliLocCount++;
       echo ".";
    }
    echo "<br>manual alli count:",$alliLocCount;
    $backupAlliArray = $AlliArray;
    $AlliArrayCount = count($AlliArray);
    echo "<br>array alli count:",$AlliArrayCount;
    shuffle($AlliArray);
    shuffle($AlliArray);
    shuffle($AlliArray);
    echo "<br>";
    // cleanup
    $update = mysql_query("TRUNCATE TABLE news")or die(mysql_error());
    $update = mysql_query("TRUNCATE TABLE auto_event")or die(mysql_error());
    $update = mysql_query("TRUNCATE TABLE rankings_alliance")or die(mysql_error());

    for($x = 0; $x < $AlliArrayCount; $x++)
    {
        //echo ".";
        echo "<br>";
        echo $x,". ",$AlliArray[$x],"<",$backupAlliArray[$x];
        $update = mysql_query("UPDATE kingdom SET id = $AlliArray[$x] WHERE id = $backupAlliArray[$x]+5000")or die("<br>kingdom failed");
        $update = mysql_query("UPDATE stats SET kingdom=$AlliArray[$x] WHERE kingdom=$backupAlliArray[$x]+5000")or die("<br>stats failed");
        $update = mysql_query("UPDATE forum SET poster_kd=$AlliArray[$x] WHERE poster_kd=$backupAlliArray[$x]+5000")or die("<br>forum failed");
        $update = mysql_query("UPDATE mergers SET target=$AlliArray[$x] WHERE target=$backupAlliArray[$x]+5000")or die("<br>merger failed");
        $update = mysql_query("INSERT INTO `news` ( `type` , `kingdom_text` , `kingdoma` ) VALUES ('admin', 'The new age will start soon. Please check the admin message on the tribe page for detailed information.', $AlliArray[$x])")or die("<br>adding news failed");
        $update = mysql_query("INSERT INTO `rankings_alliance` ( `id` ) VALUES ( '$AlliArray[$x]' )")or die(mysql_error());
        $update = mysql_query("UPDATE rankings_personal SET alli_id=$AlliArray[$x] WHERE alli_id=$backupAlliArray[$x]+5000")or die("<br>tribe_rankings failed");
    }

    echo "<br>after, manual alli count:",$x;
    $num_rows = mysql_query("SELECT * FROM rankings_personal WHERE alli_id > 10 GROUP BY alli_id");
    $num_rows = mysql_num_rows($num_rows);
    echo "<br>after, (personal rankings) alli count:",$num_rows;
    echo "<br>reshuffle done";

    /* M: I used this SQL sentence to find an alliance who had no kingdom table data

    SELECT alli_id
    FROM rankings_personal
    WHERE alli_id NOT
    IN (

    SELECT id
    FROM kingdom
    )
    LIMIT 0 , 30

    */

}
?>