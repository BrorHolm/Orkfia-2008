<?php
//******************************************************************************
// Pages raze_army2.inc.php               Recoded December 16-21, 2007 by Martel
//  Only accepts POST requests
// History
//  Tragedy: updating so troops send out can not be released
//******************************************************************************
include_once('inc/functions/army.php');

function include_raze_army2_text()
{
    // M: Secure / Validate Input
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['released']))
    {
        require_once('inc/pages/logout.inc.php');
        include_logout_text();
    }

    $objUser             = &$GLOBALS["objSrcUser"];
    $arrArmys            = $objUser->get_armys();
    $arrUsers            = $objUser->get_user_infos();

    $objRace             = $objUser->get_race();
    $arrUnitVariables    = $objRace->getUnitVariables();
    $arrUnitNames        = $objRace->getUnitNames();

    $arrToRelease        = $_POST['released'];
    $arrToRelease[UNIT1] = max(0, floor(intval($arrToRelease[UNIT1])));
    $arrToRelease[UNIT2] = max(0, floor(intval($arrToRelease[UNIT2])));
    $arrToRelease[UNIT3] = max(0, floor(intval($arrToRelease[UNIT3])));
    $arrToRelease[UNIT4] = max(0, floor(intval($arrToRelease[UNIT4])));
    $arrToRelease[UNIT5] = max(0, floor(intval($arrToRelease[UNIT5])));
//     $arrToRelease[UNIT6] = max(0, floor(intval($arrToRelease[UNIT6])));

    // Maximum military releasable
    $arrMax = getMaxRelease($objUser);
    foreach ($arrToRelease as $unitx => $doesntmatter)
    {
        if ($arrToRelease[$unitx] > $arrMax[$unitx])
            $arrToRelease[$unitx] = $arrMax[$unitx];
    }

    if ($objRace->getRaceName() != "Oleg Hai")
    {
        $iSumRelease = array_sum($arrToRelease);
        $iToCitizens = $arrToRelease[UNIT1];
        $iToBasics   = $iSumRelease - $iToCitizens;
    }
    // Oleg unit4 (elite / mercenary) doesn't get trained from soldiers
    else
    {
        $iSumRelease = array_sum($arrToRelease);
        $iToCitizens = $arrToRelease[UNIT1] + $arrToRelease[UNIT4];
        $iToBasics   = $iSumRelease - $iToCitizens;
    }

    // Release all into citizens option
    if (isset($_POST['citizens']))
    {
        $iSumRelease = array_sum($arrToRelease);
        $iToCitizens = $iSumRelease;
        $iToBasics   = 0;
    }

    if ($iSumRelease <= 0)
    {
        header('location: main.php?cat=game&page=raze_army&error=empty');
        exit;
    }
    elseif ($arrUsers[HOURS] > $objRace->getLifespan())
    {
        header('location: main.php?cat=game&page=raze_army&error=too_old');
        exit;
    }

    // Save to DB (Update Military)
    foreach ($arrToRelease as $unitx => $iToRelease)
    {
        if ($iToRelease > 0)
        {
            // Release "in training 4 hours" BEFORE releasing "trained military"
            if ($arrArmys[$unitx.'_t4'] > 0)
            {
                // New value for incoming_t4
                $arrArmys[$unitx.'_t4'] -= $iToRelease;

                // Save left-overs to remove from army "at home"
                if ($arrArmys[$unitx.'_t4'] <= 0)
                {
                    $iToRelease             = abs($arrArmys[$unitx.'_t4']);
                    $arrArmys[$unitx.'_t4'] = 0;
                }
                else
                {
                    $iToRelease = 0;
                }

            }
            $arrArmys[$unitx] -= $iToRelease;
            $arrArmys[$unitx]  = max(0, $arrArmys[$unitx]);
        }
    }

    // Add units that were "degraded" to basics
    $arrArmys[UNIT1] += $iToBasics;

    // Update military
    $objUser->set_armys($arrArmys);

    // Update Oleg Hai Mercs (M: Reused and modified old code, can be improved.)
    if ($objRace->getRaceName() == "Oleg Hai" && $arrToRelease[UNIT4] > 0)
    {
        $arrArmyMercs = $objUser->get_army_mercs();

        $iMercsToRelease = $arrToRelease[UNIT4];
        if ($iMercsToRelease > $arrArmyMercs[MERC_T1])
        {
            $iMercsToRelease -= $arrArmyMercs[MERC_T1];
            if ($iMercsToRelease > $arrArmyMercs[MERC_T2])
            {
                $iMercsToRelease -= $arrArmyMercs[MERC_T2];
                if ($iMercsToRelease > $arrArmyMercs[MERC_T3])
                {
                    $iMercsToRelease       -= $arrArmyMercs[MERC_T3];
                    $arrArmyMercs[MERC_T3] -= $iMercsToRelease;
                    $arrArmyMercs[MERC_T3]  = max(0, $arrArmyMercs[MERC_T3]);
                }
                $arrArmyMercs[MERC_T2] -= $iMercsToRelease;
                $arrArmyMercs[MERC_T2]  = max(0, $arrArmyMercs[MERC_T2]);
            }
            $arrArmyMercs[MERC_T1] -= $iMercsToRelease;
            $arrArmyMercs[MERC_T1]  = max(0, $arrArmyMercs[MERC_T1]);
        }
        $arrArmyMercs[MERC_T0] -= $iMercsToRelease;
        $arrArmyMercs[MERC_T0]  = max(0, $arrArmyMercs[MERC_T0]);
        $objUser->set_army_mercs($arrArmyMercs);
    }

    // Update citizens
    $iOldCitizens = $objUser->get_pop(CITIZENS);
    $iNewCitizens = max(200, $iOldCitizens + $iToCitizens);
    $objUser->set_pop(CITIZENS, $iNewCitizens);

    // Create list with units released
    $strUnitList = '<ul>';
    $j = 1;
    foreach ($arrUnitVariables as $unitx)
    {
        if (isset($arrToRelease[$unitx]) && ($iTrained=$arrToRelease[$unitx])>0)
        {
            $strUnitName = $arrUnitNames[$j];
            $plural      = 's';
            if ($strUnitName == 'Swordmen')    $plural = '';
            if ($strUnitName == 'Pikemen')     $plural = '';
            if ($strUnitName == 'Crossbowmen') $plural = '';
            if ($strUnitName == 'Longbowmen')  $plural = '';
            if ($strUnitName == 'Thief')       $strUnitName = 'Thieve';
            if ($strUnitName == 'Priestess')   $plural = 'es';
            if ($strUnitName == 'Mummy')       $strUnitName = 'Mummie';
            if ($strUnitName == 'Harpy')       $strUnitName = 'Harpie';
            if ($iTrained == 1)                $plural = '';
            $strUnitList .= '<li>' . number_format($iTrained) . ' ' .
                            $strUnitName . $plural . '</li>';
        }
        $j++;
    }
    $strUnitList .= '</ul>';

    $strHarpyText = '';
    if ($objRace->getRaceName() == "Oleg Hai" && $arrToRelease[UNIT4] > 0)
    {
        $strHarpyText = '<p>' . number_format($arrToRelease[UNIT4]) .
                        ' harpy mercenaries have left our army camp.</p>';
    }

    $strReport =
    '<div id="textMedium">' .

        '<h2>Training Report</h2>' .

        '<p>The following military have been released from their duties.</p>' .

        $strUnitList .

        '<p>' .
            'You have released <strong>' . number_format($iSumRelease) . ' ' .
            'units.</strong> These units have been degraded to: <strong>' .
            number_format($iToCitizens) . ' ' . $arrUnitNames[1] .
            's</strong> and <strong>' . number_format($iToBasics) . ' ' .
            $arrUnitNames[2] . 's</strong>.' .
        '</p>' .

        $strHarpyText .

        '<p>' .
        '<a href="main.php?cat=game&amp;page=raze_army">Back To Releasing</a>' .
        '</p>' .

    '</div>';
    echo $strReport;
}
?>
