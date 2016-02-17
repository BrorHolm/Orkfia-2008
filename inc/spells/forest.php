<?php

///////////////////////////////////
/// ELF SPELL - DENSE FOREST
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{
    $iOldLength = $objSrcUser->get_spell(FOREST);
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
    $result["text_screen"] = "Your elves look on in astonishment as the forests that protect your lands grow larger and too thick for even an elf to easily navigate. Your mage estimates that for $length more months your forests will help to protect your lands...";
    $result["damage"] = 0;

// Make it happen!
    $objSrcUser->set_spell(FOREST, $length);
    return $result;
}
?>