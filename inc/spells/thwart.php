<?php

///////////////////////////////////
/// Thwart - protection from magic - Added minimum hours request February 01, 2008, Martel
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $iOldLength = $objSrcUser->get_spell(THWART);
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

    // Make it happen!
    $objSrcUser->set_spell(THWART, $length);

    $result["text_screen"] = "You have increased your protection against enemy mystics for $length months.";
    $result["damage"] = 0;

    return $result;
}
?>
