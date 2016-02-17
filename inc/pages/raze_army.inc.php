<?php
//******************************************************************************
// Pages raze_army.inc.php                Recoded December 16-21, 2007 by Martel
//******************************************************************************
include_once('inc/functions/army.php');

function include_raze_army_text()
{
    $objUser          = &$GLOBALS["objSrcUser"];
    $arrUsers         = $objUser->get_user_infos();
    $arrArmys         = $objUser->get_armys();

    $objRace          = $objUser->get_race();
    $arrUnitVariables = $objRace->getUnitVariables();
    $arrUnitNames     = $objRace->getUnitNames();
    $arrUnitCost      = $objRace->getUnitCosts();
    $arrUnitOffence   = $objRace->getUnitOffences();
    $arrUnitDefence   = $objRace->getUnitDefences();

    // At the end of a lifespan you can't train or release military
    if ($arrUsers[HOURS] > $objRace->getLifespan())
    {
        echo $strReport =
            '<div id="textMedium">' .
                '<p>' .
                    "Your general is nowhere in sight! And your captains seem to " .
                    "be doubting your capability to judge properly what to release " .
                    "or not." .
                "</p>" .
            '</div>';

        include_game_down();
        exit;
    }

    // M: Error Messages =======================================================
    // Keep these short
    if (isset($_GET['error']) && $_GET['error'] == "empty")
    {
        echo '<div class="center">' .
                "I'm sorry leader, you did not release anything." .
             "</div><br />";
    }
    elseif (isset($_GET['error']) && $_GET['error'] == "too_old")
    {
        echo '<div class="center">' .
                 "Your general is nowhere in sight and your captains seem to be<br />doubting " .
                 "your capability to judge properly what to release or not." .
             "</div><br />";
    }
    //==========================================================================

    $advisorLinks =
        '<div class="tableLinkMedium">' .
            '<a href="main.php?cat=game&amp;page=army">Back to Military Training</a>' .
        '</div>';
    echo $advisorLinks;

    $j = 2; // for unit array, we start at location #2 (basics == unit1)
    $strCurrent = $arrUnitVariables[$j]; // UNIT1 = 'unit1'

    // Maximum military trainable
    $arrMax = getMaxRelease($objUser);

    // Show basic military already in training
    $strInTraining = '';
    if ($arrArmys[$strCurrent . '_t4'] > 0)
    {
        $strInTraining = '<span style="font-size: 0.8em;">' .
                         '(' .number_format($arrArmys[$strCurrent . '_t4']) .
                         ')</span>&nbsp;';
    }

    $strBasicsTable =
        '<form id="center" action="main.php?cat=game&amp;page=raze_army2" method="post">' .

        '<table cellpadding="0" cellspacing="0" class="medium">' .

            '<tr class="header">' .
                '<th colspan="6">Basic</th>' .
            '</tr>' .

            '<tr class="subheader">' .
                '<th width="40%">Class</th>' .
                '<td class="right" width="25%">Owned</td>' .
                '<td class="right">Max</td>' .
                '<td class="right">Release</td>' .
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

                '<td align="right">' .
                    number_format($arrMax[$strCurrent]) .
                '</td>' .

                '<td class="right">' .
                    '<input name="released['. $strCurrent .
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
                '<th width="40%">Class</th>' .
                '<td class="right" width="25%">Owned</td>' .
                '<td class="right">Max</td>' .
                '<td class="right">Release</td>' .
            '</tr>';

    $j++; // for unit arrays, we start at location #3 (off spec)
    foreach ($arrUnitCost as $doesntmatter)
    {
        // We can only train units that cost money, so only show those
        if (isset($arrUnitCost[$j]) && $arrUnitCost[$j] > 0)
        {
            $strCurrent = $arrUnitVariables[$j]; // unit2, unit3.. etc

            $plural = 's';
            if ($arrUnitNames[$j] == 'Swordmen')    $plural = '';
            if ($arrUnitNames[$j] == 'Pikemen')     $plural = '';
            if ($arrUnitNames[$j] == 'Crossbowmen') $plural = '';
            if ($arrUnitNames[$j] == 'Longbowmen')  $plural = '';
            if ($arrUnitNames[$j] == 'Thief')       $arrUnitNames[$j] = 'Thieve';
            if ($arrUnitNames[$j] == 'Priestess')   $plural = 'es';
            if ($arrUnitNames[$j] == 'Mummy')       $arrUnitNames[$j] = 'Mummie';
            if ($arrUnitNames[$j] == 'Harpy')       $arrUnitNames[$j] = 'Harpie';

            // Show military units already being trained
            $strInTraining = '';
            if ($arrArmys[$strCurrent . '_t4'] > 0)
            {
                $strInTraining =
                '<span style="font-size: 0.8em;">' .
                '(' . number_format($arrArmys[$strCurrent . '_t4']) . ')' .
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

                '<td align="right">' .
                    number_format($arrMax[$strCurrent]) .
                '</td>' .

                '<td class="right">' .
                    '<input name="released[' . $strCurrent .
                    ']" size="8" maxlength="8" />' .
                '</td>' .

            '</tr>';
        }
        $j++;
    }

    echo
        '</table>' .
        '<br />' .
        '<input type="submit" name="release" value="Release Into Basics" /> ' .
        '<input type="submit" name="citizens" value="Release Into Citizens" />' .
        '</form>';

    if ($objRace->getRaceName() == "Oleg Hai")
    {
        echo $strOlegText =
        '<div id="textMedium">' .
            '<p>' .
                '<strong>' . ucfirst($arrUnitNames[5]) . 's</strong> are ' .
                'mercenary soldiers living amongst your ' .
                strtolower($arrUnitNames[1]) . 's. They can only be released ' .
                'into ' . strtolower($arrUnitNames[1]) . 's.' .
            '</p>' .
        '</div>';
    }
}
?>