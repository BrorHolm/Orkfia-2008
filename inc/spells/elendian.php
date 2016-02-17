<?php

///////////////////////////////////
/// Elendian - Increased Pop
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
	if ($minHours == 0 || $amount == 1) 
	{
		$length = rand($arrSpell[DURMIN], $arrSpell[DURMAX]);
		$result["casted"] = $amount;
	}
	else
	{
// Run a loop and check to see if minimum required hours has been reached.
		$result["casted"] = $amount;
		for ($x = 1; $x <= $amount; $x++)
		{
			$length = rand($arrSpell[DURMIN], $arrSpell[DURMAX]);
			if ($length >= $minHours) 
			{
				$result["casted"] = $x;
				break;
			}
		}
		
	}
    	$result["text_screen"] = "You will have increased population for $length more hours";
	$result["damage"] = 0;

// Make it happen!
	$objSrcUser->set_spell(POPULATION, $length);

	return $result;
}
?>
