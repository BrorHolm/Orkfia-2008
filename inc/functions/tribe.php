<?php

function getRulerAge(&$objUser)
{
    $iRulerAge = $objUser->get_user_info(HOURS);

    $iRulerAge /= 12;
    $iRulerAge += 16;
    $iRulerAge = floor($iRulerAge);

    return $iRulerAge;
}

function getActiveSpells(&$objUser)
{
    include_once('inc/functions/spells.php');
    $arrSpells    = set_spell_vars($objUser);

    $arrSrcSpells = $objUser->get_spells();

    $count = 0;
    for (reset($arrSpells); list ($strSpellName, $arrSpell) = each ($arrSpells);)
    {
        if (isset($arrSrcSpells[$arrSpell[DBFIELD]]) && $arrSrcSpells[$arrSpell[DBFIELD]] > 0)
        {
            $count++;
        }
    }

    return $count;
}

function get_eldermessage_text(&$objAlliance)
{
    $strMsg = $objAlliance->get_alliance_info(ELDER_MESSAGE);
    $strMsg = nl2br(stripslashes(stripslashes(html_entity_decode($strMsg))));

    if ($strMsg != '')
    {
        $eldermessage =
            "<div id=\"textBig\">" .
                "<h2>Elder Message</h2>" .
                "<p>" .
                     $strMsg .
                "</p>" .
            "</div>";
    }
    else
        $eldermessage = $strMsg;

    return $eldermessage;
}

// function added by Species5618, 18-2-2004
// Gives tribe_table in string, used by the Vision-spell
// changed May 11, 2006 by Martel
function get_tribe_table(&$objUser)
{
    global  $arrStats;

    include_once('inc/functions/military.php');
    include_once('inc/functions/races.php');
    include_once('inc/functions/magic.php');
    include_once('inc/functions/population.php');

    $arrBuild       = $objUser->get_builds();
    $iAcres         = $arrBuild[LAND];
    $iBarren        = $objUser->get_barren();
    $arrStats       = $objUser->get_stats();
    $arrGoods       = $objUser->get_goods();
    $arrPopulation  = getPopulation($objUser);
    $arrArmys       = $objUser->get_armys();
    $iMageLevel     = get_mage_level($objUser);
    $userid         = $objUser->get_userid();
    $iStrength      = $objUser->get_strength();
    $iRulerAge      = getRulerAge($objUser);
//     $iSpells        = getActiveSpells($objUser);
    $arrUnitVars    = getUnitVariables($arrStats[RACE]);
    $arrUnitNames   = $arrUnitVars['output'];

    $total_defence = getArmyDefence($objUser);
    $total_defence = $total_defence['raw'];
    $suicidal_offence = getArmyOffence($objUser);;
    $suicidal_offence = $suicidal_offence['raw'];

    $offencePerAcre = ceil($suicidal_offence / $iAcres);
    $defencePerAcre = ceil($total_defence / $iAcres);
    $iMilitaryUnits = $arrArmys[UNIT1] + $arrArmys[UNIT2]
                    + $arrArmys[UNIT3] + $arrArmys[UNIT4];

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objUser, 'yes');

    $strServerTime = date("H:i:s");

    $res =
        "<table class=\"medium\" cellpadding=\"0\" cellspacing=\"0\">".
            "<tr class=\"header\">".
                "<th colspan=\"5\">". stripslashes($arrStats['tribe']) . " (#" . $arrStats['kingdom'] . ")". "</th>" .
            "</tr>".

            "<tr class=\"subheader\">" .
                "<th width=\"120px\">" . "Type" . "</th>" .
                "<td width=\"165px\">" . "Info" . "</td>" .
                "<td width=\"30px\">" . "&nbsp;" . "</td>" .
                "<th width=\"120px\">" . "Type" . "</th>" .
                "<td width=\"165px\">" . "Info" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Ruler Age:" . "</th>" .
                "<td>" . $iRulerAge. " Years" . "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Fame:" . "</th>" .
                "<td>" . number_format($arrStats['fame']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Race:" . "</th>" .
                "<td>" . $arrStats['race']. "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Strength:" . "</th>" .
                "<td>" . number_format($iStrength) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . $arrUnitNames[1] . "s:". "</th>" .
                "<td>" . number_format($arrPopulation['citizens']) . "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Military Units:" . "</th>" .
                "<td>" . number_format($iMilitaryUnits) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Mage Level:" . "</th>" .
                "<td>" . $iMageLevel . "</td>" .
                "<td>" . "&nbsp;" . "</td>";
        if ($arrStats[RACE] == 'Templar')
            $res .= "<th>" . "Mystics:" . "</th>";
        else
            $res .= "<th>" . "Thieves:" . "</th>";
        $res .=
                "<td>" . number_format($arrArmys['unit5']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Raw Offence:" . "</th>" .
                "<td>" . $offencePerAcre . " OPA</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Raw Defence:" . "</th>" .
                "<td>" . $defencePerAcre . " DPA</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Barren Land:" . "</th>" .
                "<td>" . number_format($iBarren) . "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Land:" . "</th>" .
                "<td>" . number_format($iAcres) . "</td>" .
            "</tr>" .
        "</table>".

        '<div class="center" style="font-size: 0.8em">' .
            'Server Time: ' . $strServerTime .
        '</div>';

    return $res;
}

//==============================================================================
// Mini-vision used on external_affairs                 Martel, January 14, 2007
//==============================================================================
function get_small_tribe_table(&$objUser)
{
    global  $Host;

    $arrRankingsPersonal = $objUser->get_rankings_personals();
    $iAcres              = $arrRankingsPersonal[LAND];
    $iStrength           = $arrRankingsPersonal[STRENGTH];
    $iFame               = $arrRankingsPersonal[FAME];
//     $iRulerAge           = $objUser->get_ruler_age();

    $strServerTime = date("H:i:s");

    $res =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0 auto;\">".
            "<tr class=\"header\">".
                "<th colspan=\"5\">";

    // Martel: New inactivity check.
    // Works with both month changes and leap years ;)
    $online   = $objUser->get_online('time');
    $old      = date(TIMESTAMP_FORMAT, strtotime('-5 minutes'));
    if ($online < $old)
    {
        $res .= '<img src="' . $Host . 'tribe_offline.gif" alt="" height="13" width="13" /> ';
    }
    else
    {
        $res .= '<img src="' . $Host . 'tribe_online.gif" alt="»" height="13" width="13" /> ';
    }

    $res .= stripslashes($arrRankingsPersonal[TRIBE_NAME]) . " (#" . $arrRankingsPersonal[ALLI_ID] . ")" . "</th>" .
            "</tr>".

            "<tr class=\"subheader\">" .
                "<th width=\"120px\">" . "Type" . "</th>" .
                "<td width=\"165px\">" . "Info" . "</td>" .
                "<td width=\"30px\">" . "&nbsp;" . "</td>" .
                "<th width=\"120px\">" . "Type" . "</th>" .
                "<td width=\"165px\">" . "Info" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Race:" . "</th>" .
                "<td>" . $arrRankingsPersonal[RACE]. "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Fame:" . "</th>" .
                "<td>" . number_format($iFame) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Strength:" . "</th>" .
                "<td>" . number_format($iStrength) . "</td>" .
                "<td>" . "&nbsp;" . "</td>" .
                "<th>" . "Land:" . "</th>" .
                "<td>" . number_format($iAcres) . "</td>" .
            "</tr>" .
        "</table>".

        '<div class="tableLinkSmall" style="font-size: 0.8em; margin: 0 auto;">Server Time: ' . $strServerTime . '</div>';

    return $res;
}

?>
