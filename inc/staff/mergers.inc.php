<?php

function call_mergers_text()
{
    global  $userid, $task, $mergerid, $checker, $listtribeid, $changedname,
            $declinereason, $local_stats, $tool;

    include_once('inc/functions/resort_tools.php');
    // we're abstracting the actual moving out to there
    include_once('inc/staff/move.inc.php');
    //if (! user_has_access($tool))
    //{
    //    echo "Sorry, this page is restricted to ORKFiA Staff";
    //    include_game_down();
    //    exit;
    //}
    check_access($tool);

    $orkTime = date('YmdHis');

    echo "<h2>Select task:</h2><br />|  <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=pending'>View Pending Mergers</a>  |  <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=done'>View Done Mergers</a>  |  <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=rejected'>View Declined Mergers</a>  |  <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=requested'>View Requested Mergers</a>  |<br /><br />";

    if ($task == "changename") {
    // loads a form where you can change the requested name of a tribe
        $seek = mysql_query("SELECT newname FROM mergers WHERE id = $mergerid");
        $seek = mysql_fetch_array($seek);
        echo "<form method=\"post\" action=\"main.php?cat=game&page=resort_tools&tool=mergers&task=changed&mergerid=$mergerid\">";
        echo "<br /><br /><table border=0 cellspacing=0 cellpadding=0 class='small'>";
        echo "<tr class='header'><th colspan=2> Change Requested Name </th></tr>";
        echo "<tr class='subheader'><th> Current Requested Name </th><td> New Name </td></tr>";
        echo "<tr class='data'><th> $seek[newname] </th><td> <input type='text' name='changedname' maxLength='30'></td> </tr>";
        echo "<tr class='data'><td colspan='2'><input type=submit value='Change requested name'></td></tr>";
        echo "</table>";
        echo "</form>";
    }
    if ($task == "changed") {
    // the queries part for changing the requested name of a tribe
        //check if someone else already has that name first
        $changedname = quote_smart(strip_tags(trim($changedname)));
        $check = mysql_query("SELECT * FROM stats WHERE tribe = $changedname AND id != $mergerid");
        if(mysql_num_rows($check) != 0){
            echo "<br /><br />Some tribe is already using that name<br /><br />";
        } else {
            $update = mysql_query("UPDATE mergers SET newname = $changedname WHERE id = $mergerid");
            echo "<br /><br />The Requested tribe name is changed<br /><br />";
            $task = "pending";
        }
    }
    if ($task == "decline") {
    // loads a form where you can give a reason for not merging a tribe
        echo "<form method=\"post\" action=\"main.php?cat=game&page=resort_tools&tool=mergers&task=delete&mergerid=$mergerid\">";
        echo "<br /><br /><table border=0 cellspacing=0 cellpadding=0 class='small'>";
        echo "<tr class='header'><th colspan=2> Decline Merge </th></tr>";
        echo "<tr class='subheader'><th>Decline Reason</th></tr>";
        echo "<tr class='data'><td><input type='text' name='declinereason' maxLength='100'></td></tr>";
        echo "<tr class='data'><td><input type=submit value='Decline Merge'></td></tr>";
        echo "</table>";
        echo "</form>";
    }
    if ($task == "delete") {
    // the queries part for the decline option
        $seek = mysql_query("SELECT * FROM mergers WHERE id = $mergerid");
        $seek = mysql_fetch_array($seek);
        $message1 = "Your merge request to $seek[target] has been declined.<br />The reason for this is: $declinereason .<br />";
        $message2 = "The merge from $seek[oldname](#$seek[origin]) has been declined.<br />The reason for this is: $declinereason .<br />";
        $bleh2 = mysql_query("SELECT id FROM stats WHERE type='elder' AND kingdom='$seek[target]'");
        $bleh2 = mysql_fetch_array($bleh2);
        $sendmessage1 = mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', '$seek[tribe]', '0', '".$orkTime."', 'Merge Request Declined', '".$message1."', 'new', 'received')");
        $sendmessage2 = mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', '$bleh2[id]', '0', '".$orkTime."', 'Merge Request Declined', '".$message2."', 'new', 'received')");
        $update = mysql_query("UPDATE mergers SET request_status = 'declined', declined_reason = '$declinereason', mod_id = $local_stats[id] WHERE id = $mergerid");
        $task = "pending";
    }
    if ($task == "accept") {
    // doing the merge itself
            $fetch = mysql_query("Select * from mergers where id = $mergerid");
            $fetch = mysql_fetch_array($fetch);
            $search = mysql_query("Select * from stats where id = $fetch[tribe]");
            $search = mysql_fetch_array($search);
            $newname = /*quote_smart(*/"'".$fetch['newname']."'"/*)*/;

            //$update = mysql_query("UPDATE goods SET credits = 0, market_money = 0, market_food = 0, market_soldiers = 0, market_wood = 0 WHERE id = {$fetch['tribe']}");
            //$update = mysql_query("UPDATE stats set kingdom = {$fetch['target']}, type = 'player', vote = 0, invested = 0, tribe = $newname where id = {$fetch['tribe']}");
            //$update = mysql_query("UPDATE rankings_personal set alli_id = {$fetch['target']} where id = {$fetch['tribe']}");
            mysql_query("UPDATE stats SET tribe = $newname where id = {$fetch['tribe']}");
            move_tribe($fetch['tribe'], $fetch['target']);
            $update = mysql_query("UPDATE mergers set request_status = 'done', merge_time = '$orkTime', mod_id = '{$local_stats['id']}' where id = {$fetch['id']}");
            $strSQL = mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'Mod Move', '0', '0', '1', '', '<font class = \"positive\">{$search['tribe']}(#{$fetch['origin']}) has been merged into $newname(#{$fetch['target']})! </font>', '{$fetch['origin']}', '{$fetch['target']}')");
            echo "<br /><br />Merge was done =D<br /><br />";
            $task = "pending";
    } elseif ($task == "done") {
    // show list with all done mergers
        $fetch = mysql_query("SELECT * FROM mergers WHERE request_status = 'done' order by merge_time desc");
        $mergers = array();
        while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
        echo "<br /><br /><table border=0 cellspacing=0 cellpadding=0 class='big'>";
        echo "<tr class='header'><th colspan='5'> Done Mergers </th></tr>";
        echo "<tr class='subheader'><th> Merge Time </td><td> Tribe ID </td><td> Old Location </td><td> New Location </td><td> Staff </td></tr>";
        foreach($mergers as $strKey => $value) {
            $mod = mysql_query("SELECT name FROM stats WHERE id=$value[mod_id]");
            $mod = mysql_fetch_array($mod);
            echo "<tr class='data'><th> $value[merge_time] </th><td> $value[tribe] </td><td> $value[oldname](#$value[origin]) </td><td> $value[newname](#$value[target]) </td><td> $mod[name] </td></tr>";
        }
        echo "</table>";
    } elseif ($task == "rejected") {
    // show list with all rejected mergers and the reason to reject it
        $fetch = mysql_query("SELECT * FROM mergers WHERE request_status = 'declined' order by merge_time desc");
        $mergers = array();
        while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
        echo "<br /><br /><table border=0 cellspacing=0 cellpadding=0 class='big'>";
        echo "<tr class='header'><th colspan=5> Declined Mergers </th></tr>";
        echo "<tr class='subheader'><th> Merge Time </th><td> Tribe ID </td><td> Location </td><td> Staff </td><td> Reason </td></tr>";
        foreach($mergers as $strKey => $value) {
            $mod = mysql_query("SELECT name FROM stats WHERE id=$value[mod_id]");
            $mod = mysql_fetch_array($mod);
            echo "<tr class='data'><th> $value[merge_time] </th><td> $value[tribe] </td><td> $value[oldname](#$value[origin]) </td><td> $mod[name] </td><td>$value[declined_reason]</td></tr>";
        }
        echo "</table>";
    } elseif ($task == "requested") {
    // show list with all mergers not accepted by the elder yet
        $fetch = mysql_query("SELECT * FROM mergers WHERE request_status = 'not ready' order by merge_time desc");
        $mergers = array();
        while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
        echo "<br /><br /><table border=0 cellspacing=0 cellpadding=0 class='big'>";
        echo "<tr class='header'><th colspan=4> Requested Mergers </th></tr>";
        echo "<tr class='subheader'><th> Merge Time </th><td> Tribe ID </td><td> Current Location </td><td> Wanted Location </td></tr>";
        foreach($mergers as $strKey => $value) {
            echo "<tr class='data'><th> $value[merge_time] </th><td> $value[tribe] </td><td> $value[oldname](#$value[origin]) </td><td>$value[newname](#$value[target])</td></tr>";
        }
        echo "</table>";
    }
    elseif ($task == "pending") {
    // show list of all tribes ready to get merged
        $fetch = mysql_query("SELECT * FROM mergers WHERE request_status = 'ready' order by merge_time desc");
        $mergers = array();
        while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
                    $mergers[$arrmergers["id"]] = $arrmergers;
        }
        echo    "<br /><br />" .
            '<table border="0" cellspacing="0" cellpadding="0" class="big">' .

                '<tr class="header">' .
                    '<th colspan="8">New Mergers</th>' .
                '</tr>' .

                '<tr class="subheader">' .
                    '<th> Merge Time </th>' .
                    '<td> Tribe ID </td>' .
                    '<td> Old Location </td>' .
                    '<td> New Location </td>' .
                    '<td class="center"> §1 </td>' .
                    '<td class="center"> Ruler<br />Age </td>' .
                    '<td> War </td>' .
                    '<td> Actions </td>' .
                '</tr>';
        foreach($mergers as $strKey => $value)
        {
//             $age = mysql_query("SELECT land FROM build WHERE id = $value[tribe]");
//             $age = mysql_fetch_array($land);
//             $ltxt = $land['land'];

            $bExists = TRUE;
            $strSQL = "SELECT id FROM " . TBL_USER . " WHERE id = $value[tribe]";
            $resSQL = mysql_query($strSQL);
            if (mysql_num_rows($resSQL) == 1)
                $objTmpUser = new clsUser($value['tribe']);
            else
                $bExists = FALSE;

            // 21 YEARS OLD ====================================================
            if ($bExists)
                $iHours = $objTmpUser->get_user_info(HOURS);
            else
                $iHours = 11808;

            if (($iYears = ceil(($iHours+192) / 12)) > 21)
                $strTmpYears = '<span class="negative">' . $iYears . '</span>';
            else
                $strTmpYears = '<span class="positive">' . $iYears . '</span>';

            // WEEKS JOINED < 6 ================================================
            if ($bExists)
                $strDate = $objTmpUser->get_gamestat(SIGNUP_TIME);
            else
                $strDate = '0000-00-00 00:00:00';

            if (ceil((strtotime($strDate) - strtotime('-6 weeks')) / 86400) < 0)
                $strTmpSignup = '<span class="negative">Veteran</span>';
            else
                $strTmpSignup = '<span class="positive">Novice</span>';

            include_once("inc/functions/war.php");
            $seek1['war_target'] = war_target($value['origin']);
            $seek2['war_target'] = war_target($value['target']);

            $seek4 = mysql_query("Select count(id) as bleh from stats where kingdom = $value[target]");
            $seek4 = mysql_fetch_array($seek4);
            IF($seek1['war_target'] > 0 || $seek2['war_target'] > 0) {
                $war = '<font class="negative">At War</font>';
                }
                    else {
                            $war = '<font class="positive">Ok!</font>';
            }
            if ($seek1['war_target'] > 0 || $seek2['war_target'] > 0  || $seek4['bleh'] >= MAX_ALLIANCE_SIZE) {
                $accept = "<font class=\"negative\">No Merging *</font>";
                $show = true;
            } else {
                $accept = "<a href='main.php?cat=game&page=resort_tools&tool=mergers&amp;task=accept&amp;mergerid=$value[id]'>Accept</a>";
            }
            echo "<tr class='data'>
                    <th class='bsdown'> $value[merge_time] </th>
                    <td class='bsdown'> $value[tribe] </td>
                    <td class='bsdown'> $value[oldname](#$value[origin]) </td>
                    <td class='bsdown'> $value[newname](#$value[target]) </td>
                    <td class='bsdown center'> " . $strTmpSignup . " </td>
                    <td class='bsdown center'> " . $strTmpYears . " </td>
                    <td class='bsdown'> $war </td>
                    <td class='bsdown'> <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=changename&mergerid=$value[id]'>Change Name</a><br />
                         $accept<br />
                         <a href='main.php?cat=game&page=resort_tools&tool=mergers&task=decline&mergerid=$value[id]'>Decline</a>
                    </td>
                </tr>";
        }
        echo "</table>";
        if (isset($show)) {
            echo "* = The no merging means that his alliance or the alliance he want to go to is at war, or that the alliance he want to merge to is already at ".MAX_ALLIANCE_SIZE." tribes.<br />";
        }

    }
}
