<?php

///////////////////////////////////
/// Vision
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
     
	include_once('inc/functions/tribe.php');
	global $arrStats;
	$result["casted"] = $amount;
	$result["damage"] = 0;
	$result["text_screen"] = get_tribe_table($objTrgUser);

return $result;
}

?>
