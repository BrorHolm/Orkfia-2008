<?php

///////////////////////////////////
/// Wrath of Cyclops - destroy walls
/// Modified by Species5618, 5-3-2004, tweaked to new Magic Engine
////////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
	$arrTrgStats = $objTrgUser->get_stats();
	$arrTrgBuild = $objTrgUser->get_builds();
	$currWalls = $arrTrgBuild[WALLS];

	$totalDamage = 0;
	for ($x = 1; $x <= $amount; $x++)
	{
		$intDamage = floor($arrTrgBuild[WALLS]*.015);
		$intDamage = round($dmg * $intDamage);
		$totalDamage += $intDamage;
		$currWalls -= $intDamage;
	}

	$result["casted"] = $amount;
	$result["damage"] = $totalDamage;
	$result["text_screen"] = "Your Cyclops managed to destroy <b><font class=positive>$totalDamage</font></b> of " .
        			 $objTrgUser->get_stat(TRIBE) . "(#" . $objTrgUser->get_stat(KINGDOM) . ")s walls.";
	if ($amount > 1) $plural = "were";
	else $plural = "was";
	$result["text_news"] = "$amount <font class=indicator>Cyclops</font> $plural spotted throwing stones at your city! <b><font class=negative>$totalDamage</font></b> walls were destroyed.";

	$objTrgUser->set_build(WALLS, $currWalls);

	return $result;
}
?>


