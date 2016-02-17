<?php

///////////////////////////////////
/// CURSED SPELL - PESTILENCE
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $iOldLength = $objSrcUser->get_spell(PEST);
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

    $result["text_screen"] = "Your mage enchants your troops for an estimated $length more months, giving them the ability to inflict more pain and suffering upon all those whom they conquer...";
    $result["damage"] = 0;

    // Make it happen!
    $objSrcUser->set_spell(PEST, $length);
    return $result;
}
?>