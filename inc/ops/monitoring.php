<?php

/*
 * MONITORING - Spies on enemy and reports back when they send out military.
 * Last update: November 28, 2005 by Martel
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function get_op_type()
{
        return "aggressive";
}

function get_op_chance()
{
        return 70;
}

function get_op_name()
{
        return "Monitoring";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{    
	if ($objTrgUser->get_build(LAND)*2 > $thieves)
	{
		$result["text_screen"] = "Your thieves tried to monitor the target, but not enough thieves were available to complete the task. Send more thieves on this operation.";
	}
	else
	{
		// this will only affect the target, because one target may have many
		// people who is monitoring it (it will pick the monitoring which has 
		// more hours left)
		$oldHours = $objTrgUser->get_thievery(MONITOR);
		$random = rand(1,4);
		$newHours = max($random, $oldHours);
		$objTrgUser->set_thievery(MONITOR, $newHours);

		// Check if we have allready op'ed this target recently.. If so we 
		// update the old table with hours etc, the player's intention is 
		// probably to increase # hours duration whenever this happens
		$srcid = $objSrcUser->get_userid();
		$trgid = $objTrgUser->get_userid();
		$SQL = "SELECT * FROM military_expeditions WHERE src_id = '$srcid' AND trg_id = '$trgid' AND type = 'monitoring'";
		$resultset = mysql_query($SQL);
		$numrows = mysql_num_rows($resultset);

		if ($numrows > 0)
		{
			$row = mysql_fetch_array($resultset);
			$exp_id = $row['exp_id'];
    
			$query =  "UPDATE military_expeditions ";
			$query .= "SET duration_hours = '$random' ";
			$query .= "WHERE       exp_id = '$exp_id'";
			mysql_query($query);
		}
		else
		{	
			$query =  "INSERT INTO military_expeditions ";
			$query .= "SET     src_id = '$srcid', ";
			$query .= "        trg_id = '$trgid', ";
			$query .= "          type = 'monitoring', ";
			$query .= "duration_hours = '$random', ";
			$query .= "        unit_5 = '', ";
			$query .= "   return_hour = ''";
			mysql_query($query);
		}

		$result["text_screen"] = "Your thieves manage to infiltrate the enemy. They will monitor the enemy for troop movements for $random hours.";
	}

	$result["fame"] = 0;
	$result["text_news"] = "";

	return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{    
		$res = mysql_query("SELECT land FROM build WHERE id = $d_user[id]");
		$line = mysql_fetch_assoc($res);
		if ($line["land"]*2 > $thieves)
		{
			$result["text_screen"] = "Your thieves tried to monitor the target, but not enough thieves were available to complete the task. Send more thieves on this operation.";
		}
		else
		{
    		// this will only affect the target, because one target may have many
    		// people who is monitoring it (it will pick the monitoring which has 
    		// more hours left)
    		$oldHours = mysql_query("SELECT monitor FROM thievery WHERE id=$d_user[id]");
    		$oldHours = mysql_result($oldHours, 0);
    		$random = rand(1,4);
			$newHours = max($random, $oldHours);
			mysql_query("UPDATE thievery SET monitor = $newHours WHERE id=$d_user[id]");
			
			// Check if we have allready op'ed this target recently.. If so we 
			// update the old table with hours etc, the player's intention is 
			// probably to increase # hours duration whenever this happens
			$SQL = "SELECT * FROM military_expeditions WHERE src_id = '$o_user[id]' AND trg_id = '$d_user[id]' AND type = 'monitoring'";
			$resultset = mysql_query($SQL);
			$numrows = mysql_num_rows($resultset);
						
			if ($numrows > 0)
			{
    			$row = mysql_fetch_array($resultset);
    			$exp_id = $row['exp_id'];
    			
    			$query =  "UPDATE military_expeditions ";
    			$query .= "SET duration_hours = '$random' ";
    			$query .= "WHERE       exp_id = '$exp_id'";
    			mysql_query($query);
			}
			else
			{	
    			$query =  "INSERT INTO military_expeditions ";
    			$query .= "SET     src_id = '$o_user[id]', ";
    			$query .= "        trg_id = '$d_user[id]', ";
    			$query .= "          type = 'monitoring', ";
    			$query .= "duration_hours = '$random', ";
    			$query .= "        unit_5 = '', ";
    			$query .= "   return_hour = ''";
    			mysql_query($query);
			}
    		
	        $result["text_screen"] = "Your thieves manage to infiltrate the enemy. They will monitor the enemy for troop movements for $random hours.";
		}

        $result["fame"] = 0;
        $result["text_news"] = "";

        return $result;
}
*/
?>