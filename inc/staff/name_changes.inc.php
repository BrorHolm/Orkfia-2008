<?php

function call_name_changes_text() 
{
	global 	$userid, $task, $nameid, $checker, $listtribeid, $changedname,
		    $declinereason, $local_stats, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

	$orkTime = date(TIMESTAMP_FORMAT);

	echo "<br><br>Select task:<br><br>|  <a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=pending'>View Pending Name Changes</a>  |  <a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=done'>View Done Name Changes</a>  |  <a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=rejected'>View Declined Name Changes</a>  |  <a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=cancelled'>View Cancelled Name Changes</a>  |<br><br>";
	
	if ($task == "decline") {
	// loads a form where you can give a reason for not changing a tribe his name
		ECHO "<form action='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=delete&nameid=$nameid' method='post'>";
		ECHO "<br><br><table border=0 width=50% cellspacing=0 cellpadding=0 class='border'>";
		ECHO "<tr><td colspan=2 align=center class='pd bold black dark bdown'>Decline Name Change</td></tr>";
		ECHO "<tr><td class='pd bold black darker bdown'>Decline Reason</td></tr>";
		ECHO "<tr><td class='pd'><input type='text' name='declinereason' maxLength='100'></td></tr>";
		ECHO "<tr><td class='pd' align='right'><input type=submit value='Decline Name Change'></td></tr>";
		ECHO "</table>";
		ECHO "</form>";
	}
	if ($task == "delete") {
	// the queries part for the decline option
		$seek = mysql_query("SELECT * FROM namechanges WHERE id = $nameid");
		$seek = mysql_fetch_array($seek);
		$message1 = "Your request to change your name to $seek[requestedname] has been declined.<br>The reason for this is: $declinereason .<br>";
		$sendmessage1 = mysql_query("INSERT INTO messages (id, for_user, from_user, date, subject, text, new, action) VALUES ('', '$seek[tribe]', '0', '".$orkTime."', 'Name Change Declined', '".$message1."', 'new', 'received')");
		$update = mysql_query("UPDATE namechanges SET request_status = 'declined', reason_declined = '$declinereason', mod_id = $local_stats[id] WHERE id = $nameid");
		$task = "pending";
	} 
	if ($task == "accept") {
	// doing the name change itself
			$fetch = mysql_query("Select * from namechanges where id = $nameid");	
			$fetch = mysql_fetch_array($fetch);
			$search = mysql_query("Select * from stats where id = {$fetch['tribe']}");
			$search = mysql_fetch_array($search);
                        $newname = quote_smart($fetch['requestedname']);
			
			// check to avoid duplicate names
			$dupcheck = mysql_query("SELECT tribe FROM stats WHERE tribe = $newname AND id != {$fetch['tribe']}");
			$dupcheck = mysql_fetch_array($dupcheck);
			if ($dupcheck)
			{
				echo "That name already exists<br /><br />";
				$task = "pending";
			}
			else
			{
				$update = mysql_query("Update stats set tribe = $newname where id = {$fetch['tribe']}");
				$update = mysql_query("Update namechanges set request_status = 'done', mod_id = '{$local_stats['id']}' where id = {$fetch['id']}");
				$strSQL = mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'Mod Move', '0', '0', '1', '', '<font class=\"negative\">{$search['tribe']}\'s (#{$search['kingdom']}) name has been changed into {$fetch['requestedname']}! </font>', '{$search['kingdom']}', '0')");
				ECHO "<br><br>Name change was done =D<br /><br />";
				$task = "pending";
			}
	} elseif ($task == "done") {
	// show list with all done name changes
		$fetch = mysql_query("SELECT * FROM namechanges WHERE request_status = 'done' order by id desc");
		$mergers = array();
		while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
		ECHO "<br><br><table border=0 cellspacing=0 cellpadding=0 width=75% class='border'>";
		ECHO "<tr><td colspan=6 align=center class='pd black bold dark bdown'><b>Done Name Changes</b></td></tr>";
		ECHO "<tr><td class='pd black bold darker bdown'>Tribe ID</td><td class='pd black bold darker bdown'>Old Name</td><td class='pd black bold darker bdown'>New Name</td><td class='pd black bold darker bdown'>Alliance</td><td class='pd black bold darker bdown'>Reason</td><td class='pd black bold darker bdown'>Mod</td></tr>";
		foreach($mergers as $strKey => $value) {
			$tribe = mysql_query("SELECT kingdom FROM stats WHERE id = $value[tribe]");
			$tribe = mysql_fetch_array($tribe);$mod = mysql_query("SELECT name FROM stats WHERE id=$value[mod_id]");
			$mod = mysql_fetch_array($mod);
			ECHO "<tr><td class='pd bdown'> $value[tribe] </td><td class='pd bdown'> $value[oldname] </td><td class='pd bdown'> $value[requestedname] </td><td class='pd bdown' align='left'>$tribe[kingdom] </td><td class='pd bdown'> $value[reason] </td><td class='pd bdown'> $mod[name] </td></tr>";
		}
		ECHO "</table>";
	} elseif ($task == "rejected") {
	// show list with all rejected name changes and the reason to reject it
		$fetch = mysql_query("SELECT * FROM namechanges WHERE request_status = 'declined' order by id desc");
		$mergers = array();
		while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
		ECHO "<br><br><table border=0 cellspacing=0 cellpadding=0 width=75% class='border'>";
		ECHO "<tr><td colspan=5 align=center class='pd black bold dark bdown'><b>Declined Name Changes</b></td></tr>";
		ECHO "<tr><td class='pd black bold darker bdown'>Tribe ID</td><td class='pd black bold darker bdown'>Tribe Name</td><td class='pd black bold darker bdown'>Alliance</td><td class='pd black bold darker bdown'>Mod</td><td class='pd black bold darker bdown'>Reason</td></tr>";
		foreach($mergers as $strKey => $value) {
			$tribe = mysql_query("SELECT kingdom FROM stats WHERE id = $value[tribe]");
			$tribe = mysql_fetch_array($tribe);
			$mod = mysql_query("SELECT name FROM stats WHERE id=$value[mod_id]");
			$mod = mysql_fetch_array($mod);
			ECHO "<tr><td class='pd bdown'> $value[tribe] </td><td class='pd bdown'> $value[oldname] </td><td class='pd bdown' align='left'>$tribe[kingdom] </td><td class='pd bdown'> $mod[name] </td><td class='pd bdown'>$value[reason_declined]</td></tr>";
		}
		ECHO "</table>";
	} elseif ($task == "cancelled") {
	// show list with all name change cancelled by the player
		$fetch = mysql_query("SELECT * FROM namechanges WHERE request_status = 'cancelled' order by id desc");
		$mergers = array();
		while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            $mergers[$arrmergers["id"]] = $arrmergers;
        }
		ECHO "<br><br><table border=0 cellspacing=0 cellpadding=0 width=75% class='border'>";
		ECHO "<tr><td colspan=5 align=center class='pd black bold dark bdown'><b>Cancelled Name Changes</b></td></tr>";
		ECHO "<tr><td class='pd black bold darker bdown'>Tribe ID</td><td class='pd black bold darker bdown'>Old Name</td><td class='pd black bold darker bdown'>Requested Name</td><td class='pd black bold darker bdown'>Alliance</td><td class='pd black bold darker bdown'>Reason</td></tr>";
		foreach($mergers as $strKey => $value) {
			$tribe = mysql_query("SELECT kingdom FROM stats WHERE id = $value[tribe]");
			$tribe = mysql_fetch_array($tribe);
			ECHO "<tr><td class='pd bdown'> $value[tribe] </td><td class='pd bdown'> $value[oldname]</td><td class='pd bdown'>$value[requestedname]</td><td class='pd bdown' align='left'>$tribe[kingdom] </td><td class='pd bdown'>$value[reason]</td></tr>";
		}
		ECHO "</table>";
	}
	if ($task == "pending") {
	// show list of all tribes ready to get a name change
		$fetch = mysql_query("SELECT * FROM namechanges WHERE request_status = 'ready' order by id desc");
		$mergers = array();
		while ($arrmergers = mysql_fetch_array ($fetch, MYSQL_ASSOC)) {
            		$mergers[$arrmergers["id"]] = $arrmergers;
		}
        ECHO "<br><br><table border=0 width=97% cellspacing=0 cellpadding=0 class='border'>";
		ECHO "<tr><td colspan=7 align=center class='pd bold black dark bdown'>New Name Changes</td></tr>";
		ECHO "<tr><td class='pd black bold darker bdown'>Tribe ID</td><td class='pd black bold darker bdown'>Old Name</td><td class='pd black bold darker bdown'>New Name</td><td class='pd black bold darker bdown'>Alliance</td><td class='pd black bold darker bdown'>Reason</td><td class='pd black bold darker bdown'>Accept</td><td class='pd black bold darker bdown'>Decline</td></tr>";
		foreach($mergers as $strKey => $value) 
		{
			$tribe = mysql_query("SELECT kingdom FROM stats WHERE id = $value[tribe]");
			$tribe = mysql_fetch_array($tribe);
			$accept = "<a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=accept&nameid=$value[id]'>Accept</a>";
			ECHO "<tr><td class='pd bdown' align='left'>$value[tribe]</td><td class='pd bdown' align='left'>$value[oldname]</td><td class='pd bdown' align='left'>$value[requestedname] </td><td class='pd bdown' align='left'>$tribe[kingdom] </td><td class='pd bdown' align='left'>$value[reason] </td><td class='pd bdown' align='left'><a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=accept&nameid=$value[id]'>Accept</a></td><td class='pd bdown' align='left'><a href='main.php?cat=game&amp;page=resort_tools&amp;tool=name_changes&amp;task=decline&nameid=$value[id]'>Decline</a></td></tr>";
		}
		ECHO "</table>";		
	}
}
