<?php

///////////////////////////////////
/// Enforced Honesty - Burn thievery credits
/// Modified by DamadmOO
////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
//Get info on the enemy
	$arrTrgStats = $objTrgUser->get_stats();
	$arrSrcStats = $objSrcUser->get_stats();
	$tp = mysql_query("SELECT credits FROM thievery WHERE id=$arrTrgStats[id]");
	$tp = mysql_fetch_array($tp);
	$tp = $tp['credits'];
//Calculate damage
	$totalDamage = 0;
	for ($x = 1; $x <= $amount; $x++)
	{
		$intDamage = floor($dmg * $tp * 0.05);
		$totalDamage += $intDamage;
		$tp -= $intDamage;    
	}

//Prepare return-variable
	$result["casted"] = $amount;
	$result["damage"] = $totalDamage;
	$result["text_news"] = "We have found $totalDamage thievery points burned by Enforced Honesty.";
	$result["text_screen"] = "Target had $tp[credits] credits. Your enforced honesty has ruined $totalDamage thievery points of " . $objTrgUser->get_stat(TRIBE) . "(#" .
			         $objTrgUser->get_stat(KINGDOM) . ")";

//Save changes
	$update = mysql_query("UPDATE thievery SET credits = $tp WHERE id=$arrTrgStats[id]");
	
	return $result;
	

}
?>

