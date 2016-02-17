<?php

//==============================================================================
// Determine access rights                                                Martel
//==============================================================================
function user_has_access($tool)
{
    // Get necessary information about the user
    $objUser     = &$GLOBALS["objSrcUser"];
    $arrSrcStats = $objUser->get_stats();
    $level       = $arrSrcStats['level'];
    $resort      = $arrSrcStats['kingdom'];
    
    // Get requirements for tool
    $SQL = "SELECT * FROM staff_tools WHERE toolname = '$tool' LIMIT 1";
    $result = mysql_query($SQL) or die("SQLerror while retrieving staff_tools");
    $result = mysql_fetch_array($result);
    $reqlevel = $result['req_level'];
    $reqresort = $result['req_resort'];
    
    // Judge whether to give the user access or not
    if ($level < $reqlevel)
    {
        return false;
    }
    elseif ($resort != $reqresort && $reqresort != '0' && $level != '6')
    {
        return false;
    }
    else
    {
        return true;
    }
}
//============================================================================
// Function to centralize the code needed on _every_ staff page - AI 30/10/06
//============================================================================
function check_access($tool)
{
    if ( !user_has_access($tool) )
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
}
//==============================================================================
// Record page hits to staff tools                                        Martel
//==============================================================================
function log_staff_activity($tool)
{
    global $userid;
    
    $SQL  = "SELECT toolname, mod_id, amount FROM staff_activity WHERE ";
    $SQL .= "toolname = '$tool' AND mod_id = $userid";
    
    $modactivity = mysql_fetch_array(mysql_query($SQL));
	if (! isset($modactivity['amount']))
	{
    	$SQL    = "INSERT INTO staff_activity ( toolname, mod_id, amount ) ";
    	$SQL   .= "VALUES ( '$tool', $userid, 1 )";
		$update = mysql_query($SQL);
	}
	else
	{
    	$SQL  = "UPDATE modactivity SET amount = amount+1 WHERE ";
    	$SQL .= "toolname = '$tool' AND mod_id = $userid";
		$update = mysql_query($SQL);
	}
}

?>
