<?php
//******************************************************************************
// Pages army.inc.php                         Recoded October 18, 2007 by Martel
// Only accepts POST requests
//******************************************************************************
include_once('inc/functions/army.php');

function include_army2_text()
{
    // M: Secure / Validate Input
    if (!isset($_POST['trained']))
        include_once('inc/pages/logout.inc.php');

    $objUser      = &$GLOBALS["objSrcUser"];
    $arrArmys     = $objUser->get_armys();
    $iCitizens    = $objUser->get_pop(CITIZENS);
    $arrArmysHome = $objUser->get_armys_home();
    $arrGoods     = $objUser->get_goods();
    $arrUsers     = $objUser->get_user_infos();

    $objRace          = $objUser->get_race();
    $arrUnitVariables = $objRace->getUnitVariables();
    $arrUnitNames     = $objRace->getUnitNames();
    $arrUnitCost      = $objRace->getUnitCosts();

    $arrToTrain        = $_POST['trained'];
    $arrToTrain[UNIT1] = max(0, floor(intval($arrToTrain[UNIT1])));
    $arrToTrain[UNIT2] = max(0, floor(intval($arrToTrain[UNIT2])));
    $arrToTrain[UNIT3] = max(0, floor(intval($arrToTrain[UNIT3])));
    $arrToTrain[UNIT4] = max(0, floor(intval($arrToTrain[UNIT4])));
    $arrToTrain[UNIT5] = max(0, floor(intval($arrToTrain[UNIT5])));

    // Maximum military trainable
    $arrMax = getMaxTrain($objUser);
    if ($arrToTrain[UNIT1] > $arrMax[UNIT1]) $arrToTrain[UNIT1] = $arrMax[UNIT1];
    if ($arrToTrain[UNIT2] > $arrMax[UNIT2]) $arrToTrain[UNIT2] = $arrMax[UNIT2];
    if ($arrToTrain[UNIT3] > $arrMax[UNIT3]) $arrToTrain[UNIT3] = $arrMax[UNIT3];
    if ($arrToTrain[UNIT4] > $arrMax[UNIT4]) $arrToTrain[UNIT4] = $arrMax[UNIT4];
    if ($arrToTrain[UNIT5] > $arrMax[UNIT5]) $arrToTrain[UNIT5] = $arrMax[UNIT5];


    if ($objRace->getRaceName() != "Oleg Hai")
    {
        $iToTrain      = array_sum($arrToTrain);
        $iNonBasics    = array_sum($arrToTrain) - $arrToTrain[UNIT1];
        $iBasicsNeeded = $iToTrain - $arrToTrain[UNIT1];
    }
    else // Oleg unit4 (elite / mercenary) doesn't get trained from soldiers
    {
        $iToTrain      = $arrToTrain[UNIT1] + $arrToTrain[UNIT2]
                       + $arrToTrain[UNIT3] + $arrToTrain[UNIT5];

        $iNonBasics    = $arrToTrain[UNIT2] + $arrToTrain[UNIT3]
                       + $arrToTrain[UNIT5];

        $iBasicsNeeded = max(0, $iToTrain - $arrToTrain[UNIT1]);
    }

    // M: Check if any numbers were provided
    if ($iToTrain <= 0 && $objRace->getRaceName() != "Oleg Hai")
    {
        header('location: main.php?cat=game&page=army&error=empty');
        exit;
    }
    // M: If there are not enough soldiers
    elseif ($iBasicsNeeded > $arrArmysHome[UNIT1])
    {
        header('location: main.php?cat=game&page=army&error=basics');
        exit;
    }

    // Training should not bring population below 800
    $iPossiblePop = $iCitizens - $arrToTrain[UNIT1];
    if ($objRace->getRaceName() == "Oleg Hai")
        $iPossiblePop = $iCitizens - ($arrToTrain[UNIT1] + $arrToTrain[UNIT4]);

    if ($iPossiblePop < 800)
    {
        header('location: main.php?cat=game&page=army&error=poplimit');
        exit;
    }

    // Total cost to train
    $iToPay = ($arrUnitCost[2] * $arrToTrain[UNIT1])
           + ($arrUnitCost[3] * $arrToTrain[UNIT2])
           + ($arrUnitCost[4] * $arrToTrain[UNIT3])
           + ($arrUnitCost[5] * $arrToTrain[UNIT4])
           + ($arrUnitCost[6] * $arrToTrain[UNIT5]);

    // Check that we have enough money
    if ($iToPay > $arrGoods[MONEY])
    {
        header('location: main.php?cat=game&page=army&error=money');
        exit;
    }

    // At the end of a lifespan you can't train or release military
    if ($arrUsers[HOURS] > $objRace->getLifespan())
    {
        echo $strReport =
            '<div class="center">' .

                "Your general is nowhere in sight and your captains seem to " .
                "be doubting your capability to judge properly what to train " .
                "or not." .
                '<br /><br />' .

                '<a href="main.php?cat=game&amp;page=army">Back To Training</a>' .
            '</div>';

        include_game_down();
        exit;
    }

    // Save to DB
    if ($objRace->getRaceName() == "Oleg Hai" && $arrToTrain[UNIT4] > 0)
    {
        // Save new army array
        $arrNewArmys = array
        (
            UNIT1_T4 => $arrArmys[UNIT1_T4] + $arrToTrain[UNIT1],
            UNIT2_T4 => $arrArmys[UNIT2_T4] + $arrToTrain[UNIT2],
            UNIT3_T4 => $arrArmys[UNIT3_T4] + $arrToTrain[UNIT3],
            UNIT4 =>    $arrArmys[UNIT4]    + $arrToTrain[UNIT4], //0 hour train
            UNIT5_T4 => $arrArmys[UNIT5_T4] + $arrToTrain[UNIT5]
        );
        $objUser->set_armys($arrNewArmys);

        // Save new army mercs
        $iOldMercs = $objUser->get_army_merc(MERC_T3);
        $iNewMercs = $iOldMercs + $arrToTrain[UNIT4];
        $objUser->set_army_merc(MERC_T3, $iNewMercs);
    }
    else
    {
        // Save new army array
        $arrNewArmys = array
        (
            UNIT1_T4 => $arrArmys[UNIT1_T4] + $arrToTrain[UNIT1],
            UNIT2_T4 => $arrArmys[UNIT2_T4] + $arrToTrain[UNIT2],
            UNIT3_T4 => $arrArmys[UNIT3_T4] + $arrToTrain[UNIT3],
            UNIT4_T4 => $arrArmys[UNIT4_T4] + $arrToTrain[UNIT4],
            UNIT5_T4 => $arrArmys[UNIT5_T4] + $arrToTrain[UNIT5]
        );
        $objUser->set_armys($arrNewArmys);
    }

    // Update basic soldiers
    $iNewBasics = max(0, $arrArmys[UNIT1] - $iBasicsNeeded);
    $objUser->set_army(UNIT1, $iNewBasics);

    // Update citizens
    $iNewCitizens = $iCitizens - $arrToTrain[UNIT1];
    if ($objRace->getRaceName() == 'Oleg Hai')
        $iNewCitizens -= $arrToTrain[UNIT4];
    $iNewCitizens = max(0, $iNewCitizens);
    $objUser->set_pop(CITIZENS, $iNewCitizens);

    // Update money
    $iNewMoney = max(0, $arrGoods[MONEY] - $iToPay);
    $objUser->set_good(MONEY, $iNewMoney);

    // Check for brood
    $iBrood = $objUser->get_spell(BROOD);
    if ($iBrood > 0)
    {
        // Training basic soldiers take 2 updates instead of 4
        $arrArmys = $objUser->get_armys();
        $arrNewArmys = array
        (
            UNIT1_T2 => $arrArmys[UNIT1_T2] + $arrArmys[UNIT1_T4],
            UNIT1_T4 => 0
        );
        $objUser->set_armys($arrNewArmys);
    }

    // Create list with units trained
    $strUnitList = '<ul>';
    $j = 1;
    foreach ($arrUnitVariables as $strUnit)
    {
        if (isset($arrToTrain[$strUnit]) && ($iTrained = $arrToTrain[$strUnit]) > 0)
        {
            $strUnitName = $arrUnitNames[$j];
            $plural      = 's';
            if ($strUnitName == 'Thief')      $strUnitName = 'Thieve';
            if ($strUnitName == 'Priestess')  $plural = 'es';
            if ($strUnitName == 'Mummy')      $strUnitName = 'Mummie';
            if ($iTrained == 1)               $plural = '';
            $strUnitList .= '<li>' . number_format($iTrained) . ' ' .
                            $strUnitName . $plural . '</li>';
        }
        $j++;
    }
    $strUnitList .= '</ul>';

    $strHarpyText = '';
    if ($objRace->getRaceName() == "Oleg Hai" && $arrToTrain[UNIT4] > 0)
    {
        $strHarpyText = '<p>The harpy mercenaries will camp with ' .
        'our army for <strong>4 months</strong>.</p>';
    }

    $strReport =
    '<div id="textMedium">' .

        '<h2>Training Report</h2>' .

        '<p>Excellent decision leader, the following military will soon join your army and are eager to prove themselves worth their upkeep.</p>' .

        $strUnitList .

        '<p>' .
            'You have trained <strong>' . number_format($iToTrain) . ' ' .
            'units</strong> costing you <strong class="indicator">' .
            number_format($iToPay) . ' crowns</strong>.' .
        '</p>' .

        $strHarpyText .

        '<p>' .
            '<a href="main.php?cat=game&amp;page=army">Back To Training</a>'.
        '</p>' .

    '</div>';
    echo $strReport;
}
?>