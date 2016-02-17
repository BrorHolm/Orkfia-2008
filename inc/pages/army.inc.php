<?php
//******************************************************************************
// Pages army.inc.php                         Recoded October 18, 2007 by Martel
//******************************************************************************
include_once('inc/functions/army.php');

function include_army_text()
{
    $objUser      = &$GLOBALS["objSrcUser"];
    $arrStats     = $objUser->get_stats();
    $arrUsers     = $objUser->get_user_infos();
    $arrArmys     = $objUser->get_armys();
    $arrArmysHome = $objUser->get_armys_home();
    $arrArmysDue  = $objUser->get_armys_due();

    echo $topLinks =
        '<div class="center">' .
            '<a href="main.php?cat=game&amp;page=build">Construction</a> | ' .
            '<strong>Military Training</strong> | ' .
            '<a href="main.php?cat=game&amp;page=explore">Exploration</a>' .
        '</div>';

    $objRace          = $objUser->get_race();
    $arrUnitVariables = $objRace->getUnitVariables();
    $arrUnitNames     = $objRace->getUnitNames();
    $arrUnitCost      = $objRace->getUnitCosts();
    $arrUnitOffence   = $objRace->getUnitOffences();
    $arrUnitDefence   = $objRace->getUnitDefences();

    $strOlegText = '';
    if ($arrStats['race'] == "Oleg Hai")
    {
        $strOlegText =
            '<p>' .
                '<strong>' . ucfirst($arrUnitNames[5]) . 's</strong> are ' .
                'mercenary soldiers living amongst your ' .
                strtolower($arrUnitNames[1]) . 's. Up to 40% can be hired and ' .
                'sent out in attacks, but only during their first month with ' .
                'you. After that they will disagree to join any combat ' .
                'measures other than defending your lands.' .
            '</p>';
    }


    // At the end of a lifespan you can't train or release military
    if ($arrUsers[HOURS] > $objRace->getLifespan())
    {
        echo $strReport =
            '<div id="textMedium">' .
                '<p>' .
                    "Your general is nowhere in sight! And your captains seem to " .
                    "be doubting your capability to judge properly what to train " .
                    "or not." .
                "</p>" .
            '</div>';

        include_game_down();
        exit;
    }
    $advisorText =
        '<div id="textMedium">' .
            '<p>' .
                "<strong>Your general</strong> greets you: <br />" .
                "To keep your tribe safe and strong you need an army. To upgrade " .
                "your army to units of higher " .
                "class you will first need to train " .
                "<strong>" . strtolower($arrUnitNames[2]) . "s</strong>." .
            "</p>" .
            $strOlegText .
        "</div>" .
        '<br />';
    echo $advisorText;

    // Non-forced update rankings (Non-forced limited to once each 15 minutes)
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objUser);

    // M: Error Messages =======================================================
    // Keep these short
    if (isset($_GET['error']) && $_GET['error'] == "empty")
    {
        echo '<div class="center">' .
                "I'm sorry leader, you did not train anything." .
             "</div><br />";
    }
    elseif (isset($_GET['error']) && $_GET['error'] == "basics")
    {
        echo '<div class="center">' .
                "I'm sorry leader, we don't have enough basic soldiers to " .
                "upgrade that many military units." .
             "</div><br />";
    }
    elseif (isset($_GET['error']) && $_GET['error'] == "poplimit")
    {
        echo '<div class="center">' .
                "I'm sorry leader, if we train that many soldiers we will " .
                "have too few citizens." .
             "</div><br />";
    }
    elseif (isset($_GET['error']) && $_GET['error'] == "money")
    {
        echo '<div class="center">' .
                "I'm sorry leader, we don't have enough crowns to " .
                "upgrade that many military units." .
             "</div><br />";
    }
    //==========================================================================

    $advisorLinks =
        '<div class="tableLinkMedium">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=military">The General</a>' .
            ' :: ' .
            '<a href="main.php?cat=game&amp;page=raze_army">Release Military</a>' .
        '</div>';
    echo $advisorLinks;

    $j = 2; // for unit array, we start at location #2 (basics == unit1)
    $strCurrent = $arrUnitVariables[$j]; // UNIT1 = 'unit1'

    // Maximum military trainable
    $arrMax = getMaxTrain($objUser);

    // Show basic military already in training
    $strInTraining = '';
    if ($arrArmysDue[$strCurrent] > 0)
    {
        $strInTraining = '<span style="font-size: 0.8em;">' .
                         '(' .number_format($arrArmysDue[$strCurrent]) . ')' .
                         '</span>&nbsp;';
    }

    $strBasicsTable =
        '<form id="center" action="main.php?cat=game&amp;page=army2" method="post">' .

        '<table cellpadding="0" cellspacing="0" class="medium">' .

            '<tr class="header">' .
                '<th colspan="6">Basic</th>' .
            '</tr>' .

            '<tr class="subheader">' .
                '<th width="35%">Class</th>' .
                '<td class="right" width="25%">Owned</td>' .
                '<td class="right">Cost</td>' .
                '<td class="right">Max</td>' .
                '<td class="right">Train</td>' .
            '</tr>' .

            '<tr class="data">' .

                '<th>' .
                    $arrUnitNames[$j] . 's <span class="militstats">(' .
                    $arrUnitOffence[$j] . '/' . $arrUnitDefence[$j] . ')</span>' .
                '</th>' .

                '<td class="right">' .
                    $strInTraining .
                    '<strong>'. number_format($arrArmys[$strCurrent]) .
                    '</strong>' .
                '</td>' .

                '<td class="right">' .
                    number_format($arrUnitCost[$j]) .
                '</td>' .

                '<td align="right">' .
                    number_format($arrMax[$strCurrent]) .
                '</td>' .

                '<td class="right">' .
                    '<input name="trained['. $strCurrent .
                    ']" size="8" maxlength="8" />' .
                '</td>' .

            '</tr>' .
        '</table>';
    echo $strBasicsTable;

    echo '<br />';

    echo $strAdvancedTable =
        '<table cellpadding="0" cellspacing="0" class="medium">' .

            '<tr class="header">' .
                '<th colspan="6">Advanced</th>' .
            '</tr>' .

            '<tr class="subheader">' .
                '<th width="35%">Class</th>' .
                '<td class="right" width="25%">Owned</td>' .
                '<td class="right">Cost</td>' .
                '<td class="right">Max</td>' .
                '<td class="right">Upgrade</td>' .
            '</tr>';

    $j++; // for unit arrays, we start at location #3 (off spec)
    foreach ($arrUnitCost as $doesntmatter)
    {
        // We can only train units that cost money, so only show those
        if (isset($arrUnitCost[$j]) && $arrUnitCost[$j] > 0)
        {
            $strCurrent = $arrUnitVariables[$j]; // unit2, unit3.. etc
//             $sub_current1 = "$strCurrent"."_t1";
//             $sub_current2 = "$strCurrent"."_t2";
//             $sub_current3 = "$strCurrent"."_t3";
//             $sub_current4 = "$strCurrent"."_t4";

            $plural = 's';
            if ($arrUnitNames[$j] == 'Swordmen')    $plural = '';
            if ($arrUnitNames[$j] == 'Pikemen')     $plural = '';
            if ($arrUnitNames[$j] == 'Crossbowmen') $plural = '';
            if ($arrUnitNames[$j] == 'Longbowmen')  $plural = '';
            if ($arrUnitNames[$j] == 'Thief')       $arrUnitNames[$j] = 'Thieve';
            if ($arrUnitNames[$j] == 'Priestess')   $plural = 'es';
            if ($arrUnitNames[$j] == 'Mummy')       $arrUnitNames[$j] = 'Mummie';

            // Show military units already being trained
            $strInTraining = '';
            if ($arrArmysDue[$strCurrent] > 0)
            {
                $strInTraining =
                '<span style="font-size: 0.8em;">' .
                '(' . number_format($arrArmysDue[$strCurrent]) . ')' .
                '</span>&nbsp;';
            }

            echo
            '<tr class="data">' .
                '<th>' . $arrUnitNames[$j] . $plural .
                    ' <span class="militstats">(' . $arrUnitOffence[$j] . '/' .
                    $arrUnitDefence[$j] . ')</span>' .
                '</th>' .

                '<td class="right">' .
                    $strInTraining .
                    '<strong>' . number_format($arrArmys[$strCurrent]) . '</strong>' .
                '</td>' .

                '<td class="right">' . number_format($arrUnitCost[$j]) . '</td>' .

                '<td align="right">' .
                    '<span class="indicator">' . number_format($arrMax[$strCurrent]) .
                    '</span>' .
                '</td>' .

                '<td class="right">' .
                    '<input name="trained[' . $strCurrent .
                    ']" size="8" maxlength="8" />' .
                '</td>' .

            '</tr>';
        }
        $j++;
    }

    echo
        '</table>' .
        '<br />' .
        '<input type="submit" value="Train Military" />' .
        '</form>';

// $bleh = mysql_query("Select username from user where id = {$arrStats[ID]}");
// $bleh = mysql_fetch_array($bleh);
// $b = date("d");
// $tiripoda = $bleh['username']."alpha".$b;
// $b_checker = md5($tiripoda);
// <input type=\"hidden\" name=\"piss_off_bots\" value=\"$b_checker\" />
}
?>
