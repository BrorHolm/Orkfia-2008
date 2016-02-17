<?php

///////////////////////////////////
/// Lord of Harvest - Increased food prod
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $iOldLength = $objSrcUser->get_spell(FOOD);
    if ($minHours == 0 || $amount == 1)
    {
        $length = max($iOldLength, rand($arrSpell[DURMIN], $arrSpell[DURMAX]));
        $result["casted"] = $amount;
    }
    else
    {
// Run a loop and check to see if minimum required hours has been reached.
        $result["casted"] = $amount;
        for ($x = 1; $x <= $amount; $x++)
        {
            $length = rand($arrSpell[DURMIN], $arrSpell[DURMAX]);
            if ($length >= $minHours && $length > $iOldLength)
            {
                $result["casted"] = $x;
                break;
            }
        }

    }
        $result["text_screen"] = "You will have increased food production for $length more hours";
    $result["damage"] = 0;

// Make it happen!
    $objSrcUser->set_spell(FOOD, $length);
    return $result;
}
?>
