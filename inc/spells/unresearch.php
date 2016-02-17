<?php

///////////////////////////////////
/// Unresearch - sabotage research
///////////////////////////////////

function cast_spell(&$objSrcUser, &$objTrgUser, $arrSpell, $amount, $minHours, $dmg)
{

    $userid = $objTrgUser->get_userid();
    $seek = mysql_query("SELECT kingdom FROM stats WHERE id = $userid");
    $seek = mysql_fetch_array($seek);

    $strSQL = "SELECT research FROM kingdom WHERE id = $seek[kingdom]";
    $mres = mysql_query ($strSQL) or die("cast:". $arrSpell[DISPLAY] . ":" . mysql_error());
    $arrKingdom = mysql_fetch_array($mres);
    $research = $arrKingdom['research'];

    $totalDamage = 0;
    for ($x = 1; $x <= $amount; $x++)
    {
        $intRandom = rand(1,15)*0.02;
        $intDamage = floor($dmg * $research * $intRandom);
        $totalDamage += $intDamage;
        $research -= $intDamage;
    }

    $result["text_news"] = "<strong class=negative>$totalDamage</strong> research points were destroyed.";
    $result["text_screen"] = "Your spells manage to destroy <strong class=positive>$intDamage</strong> of " .
    $objTrgUser->get_stat(TRIBE) . " (#" . $objTrgUser->get_stat(KINGDOM) . ")'s research points.";
    $result["casted"] = $amount;
    $result["damage"] = $totalDamage;

    $strSQL = "UPDATE kingdom SET research=$research WHERE id = $seek[kingdom]";
    $mres = mysql_query ($strSQL) or die("cast:". $arrSpell[DISPLAY] . ":" . mysql_error());

    return $result;
}
?>
