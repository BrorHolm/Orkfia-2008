<?php
function include_orkfiastats_text()
{
    global $orkTime;

    include_once('inc/classes/clsGame.php');
    $objGame      = new clsGame();
    $arrRecords   = $objGame->get_game_records();

    $orkTime = date(TIMESTAMP_FORMAT);
    $old = $orkTime-300;
    $result = mysql_query("SELECT id FROM online WHERE time > $old");
    $online = mysql_num_rows($result);
    $maxonline = max($online, $arrRecords[ONLINE]);
//  $update = mysql_query("Update records set online = $maxonline where id = 1");
    $objGame->set_game_record(ONLINE, $maxonline);

    if ($arrRecords[AGESTART] < time()){
        $age_time = time_since($arrRecords[AGESTART]);
    }else{
        $age_time = "Yet to start";
    }

//  echo $age25 = mktime (0,0,0,04,13,2006); //hours,mins,secs,month,day,year
    $fetch1 = mysql_query("SELECT id FROM spells WHERE pest > 0");
    $fetch1 = mysql_num_rows($fetch1);
    $fetch2 = mysql_query("SELECT id FROM stats");
    $fetch2 = mysql_num_rows($fetch2);
    $fetch3 = mysql_query("Select alli_id,alli_name from rankings_personal,rankings_alliance where rankings_personal.id = $arrRecords[grab_id] and alli_id = rankings_alliance.id");
    $fetch3 = @mysql_fetch_array($fetch3);
    $fetch4 = mysql_query("Select alli_id,alli_name from rankings_personal,rankings_alliance where rankings_personal.id = $arrRecords[arson_id] and alli_id = rankings_alliance.id");
    $fetch4 = @mysql_fetch_array($fetch4);
    $fetch5 = mysql_query("Select alli_id,alli_name from rankings_personal,rankings_alliance where rankings_personal.id = $arrRecords[killed_id] and alli_id = rankings_alliance.id");
    $fetch5 = @mysql_fetch_array($fetch5);
    $fetch6 = mysql_query("Select alli_id,alli_name from rankings_personal,rankings_alliance where rankings_personal.id = $arrRecords[fireball_id] and alli_id = rankings_alliance.id");
    $fetch6 = @mysql_fetch_array($fetch6);
    $cursed = floor( $fetch1 / $fetch2 * 10000 );
    $cursed2 = $cursed / 100;
    $infected = $arrRecords[PEST] / 100;
    if($cursed > $arrRecords[PEST]) {
//      mysql_query("UPDATE records SET pest = $cursed WHERE id = 1");
        $objGame->set_game_record(PEST, $cursed);
        $infected = $cursed / 100;
    }

    $fetch3['alli_name'] = stripslashes($fetch3['alli_name']);
    $fetch4['alli_name'] = stripslashes($fetch4['alli_name']);
    $fetch5['alli_name'] = stripslashes($fetch5['alli_name']);
    $fetch6['alli_name'] = stripslashes($fetch6['alli_name']);

    // Empty results get their numbers so the game doesn't scream error at us ;)
    if (! isset($fetch3['alli_id']))
    {
        $fetch3['alli_id'] = 0;
    }
    if (! isset($fetch4['alli_id']))
    {
        $fetch4['alli_id'] = 0;
    }
    if (! isset($fetch5['alli_id']))
    {
        $fetch5['alli_id'] = 0;
    }
    if (! isset($fetch6['alli_id']))
    {
        $fetch6['alli_id'] = 0;
    }

    IF ( $online == "1" ) {
        $plural1 = "was";
        $plural2 = "";
    } ELSE {
        $plural1 = "were";
        $plural2 = "s";
    }

    $check = mysql_query("Select * from build where id = $arrRecords[grab_id]");
    $check = @mysql_fetch_array($check);
    if ($check['land_t1'] == $arrRecords['grab'] || $check['land_t2'] == $arrRecords['grab'] ||
        $check['land_t3'] == $arrRecords['grab'] || $check['land_t4'] == $arrRecords['grab'])
    {
        $fetch3['alli_name'] = "Hidden for now";
        $fetch3['alli_id'] = 0;
    }

    $orkStats =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th>" . "Stats Running Time" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<td class=\"center\">" . $age_time . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Players Online" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<td class=\"center\" colspan=\"2\">" .
                    "There " . $plural1 . " " . $online . " player" . $plural2 . " online in the last 3 minutes.<br /><br />" .
                "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Most online at one time:" . "</th>" .
                "<td>" . $maxonline . " Players" . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Pestilence" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Percentage Currently Infected:" . "</th>" .
                "<td>" . $cursed2 . "%" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Highest Percentage Infected:" . "</th>" .
                "<td>" . $infected . "%" . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Fireball" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Most Damaging Fireball:" . "</th>" .
                "<td>" . $arrRecords[FIREBALL] . " Citizens" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "By Alliance:" . "</th>" .
                "<td>" . $fetch6['alli_name'] . "(#" . $fetch6['alli_id'] . ")" . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Standard Attack" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Most Acres Taken:" . "</th>" .
                "<td>" . $arrRecords[GRAB] . " Acres" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "By Alliance:" . "</th>" .
                "<td>" . $fetch3['alli_name'] . "(#" . $fetch3['alli_id'] . ")" . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Arson" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Most Damaging Arson:" . "</th>" .
                "<td>" . $arrRecords[ARSON] . " Homes" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "By Alliance:" . "</th>" .
                "<td>" . $fetch4['alli_name'] . "(#" . $fetch4['alli_id'] . ")" . "</td>" .
            "</tr>" .
        "</table>" .

        "<br />" .

        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Largest Kill" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Largest Tribe Killed:" . "</th>" .
                "<td>" . $arrRecords[KILLED] . " Acres" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "By Alliance:" . "</th>" .
                "<td>" . $fetch5['alli_name'] . "(#" . $fetch5['alli_id'] . ")" . "</td>" .
            "</tr>" .
        "</table>";

    echo $orkStats;
}


//
// Works out the time since the entry post, takes a an argument in unix time
//

function time_since($original)
{
    // array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
    );

    $today = time(); /* Current unix time  */
    $since = $today - $original;

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {

        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            // DEBUG print "<!-- It's $name -->\n";
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
    }
    if ($i + 2 < $j) {
        // now getting the third item
        $seconds3 = $chunks[$i + 2][0];
        $name3 = $chunks[$i + 2][1];

        // add second item if it's greater than 0
        if (($count3 = floor(($since - (($seconds2 * $count2)+($seconds * $count))) / $seconds3)) != 0) {
            $print .= ($count3 == 1) ? ', 1 '.$name3 : ", $count3 {$name3}s";
        }
    }
    if ($i + 3 < $j) {
        // now getting the third item
        $seconds4 = $chunks[$i + 3][0];
        $name4 = $chunks[$i + 3][1];

        // add second item if it's greater than 0
        if (($count4 = floor(($since - (($seconds3 * $count3)+($seconds2 * $count2)+($seconds * $count))) / $seconds4)) != 0) {
            $print .= ($count4 == 1) ? ', 1 '.$name4 : ", $count4 {$name4}s";
        }
    }

    return $print;
}
?>
