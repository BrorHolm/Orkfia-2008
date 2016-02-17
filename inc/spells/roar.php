<?php

///////////////////////////////////
/// ORK SPELL - ROAR OF THE HORDE!
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $iOldLength = $objSrcUser->get_spell(ROAR);
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
    $result["text_screen"] = "An almighty roar is heard coming from your minions, the spirits of the horde will roar loudly in their hearts for an estimated $length months...";
    $result["damage"] = 0;

    // Make it happen!
    $objSrcUser->set_spell(ROAR, $length);
    return $result;
}
?>