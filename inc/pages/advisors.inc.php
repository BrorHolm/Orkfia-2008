<?php
//******************************************************************************
// pages advisors.inc.php                                   Martel, May 24, 2006
//******************************************************************************

function include_advisors_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];

    $show = 'population';
    if (isset($_GET['show'])) { $show = $_GET['show']; }

    echo get_advisor_links($show);

    switch($show)
    {
        case 'population':

?>
    <!-- <div id="textMedium">
        <p><b>The population advisor</b> greets you humbly:<br />Leader, no lands have ever before seen such great care that you show us. Please allow me to kiss your feet.</p>
    </div> -->
    <div id="columns">
        <!-- Start left column -->
        <div id="leftcolumn">
            <h2>Population</h2>
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=build">Construction</a>
            </div>
            <?=get_housing_table($objSrcUser); ?><br />
            <?=get_population_table($objSrcUser); ?>
            <?=get_guide_link($objSrcUser, 'advisor_population'); ?>
        </div>
        <!-- end left column -->

        <!-- start right column -->
        <div id="rightcolumn">
            <h2>Citizens</h2>
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=mystic">Mystics</a>
            </div>
            <?=get_citizen_table($objSrcUser); ?>
        </div>
        <!-- End of the right column-->
    </div>
    <!-- end of 2 column layout -->

<?php

        break; // end case population
        case 'resources':

?>
    <!-- <div id="textMedium">
        <p><b>The resource advisor</b> greets you humbly:<br />Leader, no lands have ever before seen such great care that you show us. Please allow me to kiss your feet.</p>
    </div> -->
    <div id="columns">
        <!-- Start left column -->
        <div id="leftcolumn">
            <h2>Production</h2>
            <?=get_income_table($objSrcUser); ?><br /><br />
            <?=get_wood_table($objSrcUser); ?>

            <h2>Resources</h2>
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=market&amp;action=sell">Sell Goods</a>
            </div>
            <?=get_goods_table($objSrcUser); ?>
        </div>
        <!-- end left column -->

        <!-- start right column -->
        <div id="rightcolumn">
            <h2>&nbsp;</h2>
            <?=get_food_table($objSrcUser); ?><br /><br />
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=research">Invest</a>
            </div>
            <?=get_research_table($objSrcUser); ?>

            <h2>&nbsp;</h2>
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=advisors&amp;show=build">Infrastructure</a>
            </div>
            <?=get_building_output_table($objSrcUser); ?>
        </div>
        <!-- end right column -->
    </div>
    <!-- end of 2 column layout -->
<?php

        break; // end case resources
        case 'military':

            include_once("inc/functions/tribe.php");

?>
    <!-- <div id="textMedium">
        <p><b>Your general</b> greets you humbly:<br />Leader, no lands have ever before seen such great care that you show us. Please allow me to kiss your feet.</p>
    </div> -->
    <br />
    <!-- Start 2 column layout -->
    <div id="columns">
        <!-- Start left column -->
        <div id="leftcolumn">
            <?=get_offence_table($objSrcUser); ?>
        </div>
        <!-- end left column -->

        <!-- start right column -->
        <div id="rightcolumn">
            <?=get_defence_table($objSrcUser); ?>
        </div>
        <!-- end right column -->
    </div>
    <div class="clear"><hr /></div>
    <!-- end of 2 column layout -->
    <br />
    <div class="tableLinkMedium">
        <a href="main.php?cat=game&amp;page=army">Military Training</a>
    </div>
    <?=get_military_training_table($objSrcUser); ?>
    <br />
    <div class="tableLinkMedium">
        <a href="main.php?cat=game&amp;page=invade">Invasion</a>
    </div>
    <?=get_military_returning_table($objSrcUser); ?>
    <?=get_guide_link($objSrcUser, 'advisor_military', 'textMedium'); ?>
    <br />
<?php

        break; // end case military
        case 'actions':

?>
    <!-- <div id="textMedium">
        <p><b>The advisor</b> greets you humbly:<br />Leader, no lands have ever before seen such great care that you show us. Please allow me to kiss your feet.</p>
    </div> -->
    <br />
    <div id="columns">
        <!-- Start left column -->
        <div id="leftcolumn">
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=mystic">Mystics</a>
            </div>
            <?=get_effecting_spells_table($objSrcUser); ?>
            <?=get_guide_link($objSrcUser, 'advisor_actions'); ?>
        </div>
        <!-- end left column -->

        <!-- start right column -->
        <div id="rightcolumn">
            <div class="tableLinkSmall">
                <a href="main.php?cat=game&amp;page=thievery">Thievery</a>
            </div>
            <?=get_effecting_ops_table($objSrcUser); ?>
        </div>
        <!-- end right column -->
    </div>
    <div class="clear"><hr /></div>
    <!-- end of 2 column layout -->
<?php

        break; // end case actions
        case 'build':

?>
    <!-- <div id="textMedium">
        <p><b>The tribe architect</b> greets you humbly:<br />Leader, no lands have ever before seen such great care that you show us. Please allow me to kiss your feet.</p>
    </div> -->
    <br />
    <div class="tableLinkMedium">
        <a href="main.php?cat=game&amp;page=explore">Exploration</a> ::
        <a href="main.php?cat=game&amp;page=build">Construction</a>
    </div>
    <?=get_construction_table($objSrcUser); ?>
    <?=get_guide_link($objSrcUser, 'advisor_build', 'textMedium'); ?>
<?php

        break; // end case build

    }
} // End Layout

//==============================================================================
// Links at top of page                                     Martel, May 24, 2006
//==============================================================================
function get_advisor_links($show = '')
{
    $arrPage = array(1 => 'population', 'resources', 'military', 'actions', 'build');
    $arrName = array(1 => 'Population', 'Goods', 'Army', 'Thievery &amp; Magic', 'Infrastructure');

    $str =
        '<div class="center">' .
        '| ';
    foreach ($arrPage as $key => $page )
    {
        if ($show != $page)
        {
            $str .= '<a href="main.php?cat=game&amp;page=advisors&amp;show=' .
                   $page . '">' . $arrName[$key] . '</a>';
        }
        else
        {
            $str .= '<b>' . $arrName[$key] . '</b>';
        }
        $str .= ' | ';
    }
    $str .= '</div>';
    return $str;
}

//==============================================================================
// Total housing / population
//==============================================================================
function get_housing_table(&$objUser)
{
    include_once('inc/functions/build.php');
    $arrVars      = getBuildingVariables($objUser->get_stat(RACE));
    $homes_output = $arrVars['output'][1];

    include_once('inc/functions/population.php');
    $arrMaxPop      = getMaxPopulation($objUser);

    $housing =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Housing" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . $homes_output . " Hold:" . "</th>" .
                "<td>" . number_format($arrMaxPop['homes']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Other Buildings Hold:" . "</th>" .
                "<td>" . "+" . number_format($arrMaxPop['extra_homes']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Fame Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrMaxPop['fame_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Spell Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrMaxPop['spell_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Engineering Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrMaxPop['research_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Total Max Population:" . "</th>" .
                "<td class=\"bsup\">" . number_format($arrMaxPop['total']) . "</td>" .
            "</tr>" .
        "</table>";

    return $housing;
} // End population housing advisor

//==============================================================================
// Citizens Migration Table
//==============================================================================
function get_citizen_table(&$objUser)
{
    include_once('inc/functions/population.php');
    $arrMaxPop      = getMaxPopulation($objUser);
    $arrPopulation  = getPopulation($objUser);

    // Output feature to describe growth rate in writing
    $plus = "+";
    if ($arrMaxPop['growth_rate'] <= 0.8)
        { $growth['rate'] = " <span class=\"negative\">(Starvation)</span>"; $plus = ""; }
    elseif ($arrMaxPop['growth_rate'] < 1)
        { $growth['rate'] = " <span class=\"negative\">(Dwindling)</span>"; $plus = ""; }
    /*elseif ($arrMaxPop['growth_rate'] < 1.15)
        $growth['rate'] = " (Weak)";
    elseif ($arrMaxPop['growth_rate'] == 1.15)
        $growth['rate'] = " <span class=\"positive\">(High)</span>";
    elseif ($arrMaxPop['growth_rate'] > 1.15)
        $growth['rate'] = " <span class=\"positive\">(Sky High)</span>";*/
    // UGLY fix for meteor elves - AI 22/05/07
    elseif ($arrMaxPop['growth_rate'] < 1.1)
        $growth['rate'] = " (Weak)";
    elseif ($arrMaxPop['growth_rate'] > 1.15)
        $growth['rate'] = " <span class=\"positive\">(Sky High)</span>";
    else
        $growth['rate'] = " <span class=\"positive\">(High)</span>";

    // Room left of max population for new citizens
    $room_left = $arrMaxPop['total'] - $arrPopulation['total_pop'];
    if ($room_left < 0)
        $plus = "";

    // Also something
//     $difference = ($arrPopulation['citizens'] * $arrMaxPop['growth_rate']) - $arrPopulation['citizens'];
//     $growth['balance'] = min(($arrMaxPop['total_citizens'] - $arrPopulation['citizens']), $difference);
    $growth['balance'] = ($arrPopulation['citizens'] * $arrMaxPop['growth_rate']) - $arrPopulation['citizens'];
    $growth['actual_balance']  = min($growth['balance'], $room_left);
    if ($arrMaxPop['total_citizens'] == 200)
         $growth['actual_balance'] = ((max('200', $arrPopulation['citizens'])- 200) * -1);

    // Something else
    $balanceText = "positive";
    if ($growth['actual_balance'] < '0')
        { $balanceText = "negative"; $plus = ""; }
    $balanceText .= "\">" . number_format($growth['actual_balance']);

    // Create table
    $citizens =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Citizen Migration" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

//             "<tr class=\"data\">" .
//                 "<th>" . "Max Citizens:" . "</th>" .
//                 "<td>" . number_format($arrMaxPop['max_citizens']) . " citz" . "</td>" .
//             "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Citizens:" . "</th>" .
                "<td>" . number_format($arrPopulation['citizens']) . " citz" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Room Left:" . "</th>" .
                "<td>" . number_format($room_left) . " citz" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Growth Rate:" . $growth['rate'] . "</th>" .
                "<td>" . $plus . number_format($growth['balance']) . " citz" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Expected Growth:" . "</th>" .
                "<td class=\"bsup " . $balanceText . " citz" . "</td>" .
            "</tr>" .
        "</table>";

    return $citizens;
} // End citizen advisor

//==============================================================================
// Population Table
//==============================================================================
function get_population_table(&$objUser)
{
    include_once('inc/functions/population.php');
    $arrPopulation  = getPopulation($objUser);

    $thieves = 'Thieves';
    if($objUser->get_stat(RACE) == 'Templar')
        $thieves = 'Mystics';
    $population =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Population" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Citizens:" . "</th>" .
                "<td>" . number_format($arrPopulation['citizens']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Military Units:" . "</th>" .
                "<td>" . number_format($arrPopulation['total_army']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "$thieves:" . "</th>" .
                "<td>" . number_format($arrPopulation['thieves']) . "</td>" .
            "</tr>" .

//            "<tr class=\"data\">" .
//                "<th>" . "Mystics:" . "</th>" .
//                "<td>" . number_format($arrPopulation['mystics']) . "</td>" .
//            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Total Current Population:" . "</th>" .
                "<td class=\"bsup\">" . number_format($arrPopulation['total_pop']) . "</td>" .
            "</tr>" .
        "</table>";

    return $population;
}// End population advisor

//==============================================================================
// Goods table (resources not stocked on the market)
//==============================================================================
function get_goods_table(&$objUser)
{
    include_once('inc/functions/production.php');
    $arrGoods   = $objUser->get_goods();

    $totalGoods =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Tribe Goods" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Resource" . "</th>" .
                "<td>" . "Available" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Money:" . "</th>" .
                "<td>" . number_format($arrGoods['money']) . " cr</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Food:" . "</th>" .
                "<td>" . number_format($arrGoods['food']) . " kgs</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Wood:" . "</th>" .
                "<td>" . number_format($arrGoods['wood']) . " logs</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Research:" . "</th>" .
                "<td>" . number_format($arrGoods['research']) . " rps</td>" .
            "</tr>" .
        "</table>";

    return $totalGoods;
} // End total goods advisor

//==============================================================================
// Income table
//==============================================================================
function get_income_table(&$objUser)
{
    include_once('inc/functions/production.php');
    include_once('inc/functions/population.php');
    $arrIncome      = getTotalIncome($objUser);
    $arrTaxes       = getCitizenIncome($objUser);
    $arrMineProd    = getMineProduction($objUser);
    $arrBankProd    = getBankProduction($objUser);
    $iUpkeepCost    = getMilitaryUpkeep($objUser);

    $balanceText  = "positive";
    if ($arrIncome['total'] < '0')
        $balanceText = "negative";
    $balanceText .= "\">" . number_format($arrIncome['total']);

    $ifStarving = '';
    if ($arrTaxes['starvation'] > 0)
    {
        $ifStarving =
            "<tr class=\"data\">" .
                "<th>" . "Starvation:" . "</th>" .
                "<td class=\"negative\">-" . number_format($arrTaxes['starvation']) . " cr" . "</td>" .
            "</tr>";
    }

    $moneyProduction =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Money" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Taxes:" . "</th>" .
                "<td>" . number_format($arrTaxes['raw']) . " cr" . "</td>" .
            "</tr>" .

            $ifStarving .

            "<tr class=\"data\">" .
                "<th>" . "Spell Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrTaxes['spell_bonus']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Mines Produce:" . "</th>" .
                "<td>" . "+" . number_format($arrMineProd['raw']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Banks Produce:" . "</th>" .
                "<td>" . "+" . number_format($arrBankProd['raw']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Production Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrMineProd['research_bonus'] + $arrBankProd['research_bonus']) . " cr" . "</td>" .
            "</tr>" .

//          "<tr class=\"data\">" .
//              "<th>" . "War Loss:" . "</th>" .
//              "<td>" . "-" . number_format($arrMineProd['war_loss'] + $arrBankProd['war_loss']) . " cr" . "</td>" .
//          "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Military Upkeep:" . "</th>" .
                "<td>" . "-" . number_format($iUpkeepCost) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Balance:" . "</th>" .
                "<td class=\"bsup " . $balanceText . " cr" . "</td>" .
            "</tr>" .
        "</table>";

    return $moneyProduction;
} // End money production advisor

//==============================================================================
// Wood production table
//==============================================================================
function get_wood_table(&$objUser)
{
    include_once('inc/functions/production.php');
    $arrYards     = getWoodProduction($objUser);

    $balanceText  = "positive";
    if ($arrYards['total'] < '0')
        $balanceText = "negative";
    $balanceText .= "\">" . number_format($arrYards['total']);

    $woodProduction =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Wood" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Yards Produce:" . "</th>" .
                "<td>" . number_format($arrYards['raw']) . " logs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Production Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrYards['research_bonus']) . " logs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Logs Decayed:" . "</th>" .
                "<td>" . "-" . number_format($arrYards['decayed']) . " logs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Balance:" . "</th>" .
                "<td class=\"bsup " . $balanceText . " logs" . "</td>" .
            "</tr>" .
        "</table>";

    return $woodProduction;
} // End wood production advisor

//==============================================================================
// Building output table
//==============================================================================
function get_building_output_table(&$objUser)
{
    include_once('inc/functions/production.php');
    $arrYards       = getWoodProduction($objUser);
    $arrMines       = getMineProduction($objUser);
    $arrBanks       = getBankProduction($objUser);
    $arrFarms       = getFoodProduction($objUser);
    $arrLabs        = getResearchProduction($objUser);

    $buildingOutput =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Building Output" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Building Type" . "</th>" .
                "<td>" . "One Acre Produce" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Banks:" . "</th>" .
                "<td>" . number_format($arrBanks['per_each']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Farms:" . "</th>" .
                "<td>" . number_format($arrFarms['per_each']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Mines:" . "</th>" .
                "<td>" . number_format($arrMines['per_each']) . " cr" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Labs:" . "</th>" .
                "<td>" . number_format($arrLabs['per_each']) . " rps" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Yards:" . "</th>" .
                "<td>" . number_format($arrYards['per_each']) . " logs" . "</td>" .
            "</tr>" .
        "</table>";

    return $buildingOutput;
} // End building output advisor

//==============================================================================
// Food production table
//==============================================================================
function get_food_table(&$objUser)
{
    include_once('inc/functions/build.php');
    $strRace      = $objUser->get_stat(RACE);
    $arrVars      = getBuildingVariables($strRace);
    $farms_output = $arrVars['output'][2];

    include_once('inc/functions/production.php');
    $arrFarms     = getFoodProduction($objUser);

    $balanceText  = "positive";
    if ($arrFarms['total'] < '0')
        $balanceText = "negative";
    $balanceText .= "\">" . number_format($arrFarms['total']);

    $foodProduction =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Food" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . $farms_output . " Produce:" . "</th>" .
                "<td>" . number_format($arrFarms['raw']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Spell Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrFarms['spell_bonus']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Production Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrFarms['research_bonus']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Consumed:" . "</th>" .
                "<td>" . "-" . number_format($arrFarms['used']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Food Spoiled:" . "</th>" .
                "<td>" . "-" . number_format($arrFarms['decayed']) . " kgs" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Balance:" . "</th>" .
                "<td class=\"bsup " . $balanceText . " kgs" . "</td>" .
            "</tr>" .
        "</table>";

    return $foodProduction;
} // End food production advisor

//==============================================================================
// Research production table
//==============================================================================
function get_research_table(&$objUser)
{
    include_once('inc/functions/production.php');
    $arrLabs      = getResearchProduction($objUser);

    $balanceText  = "positive";
    if ($arrLabs['total'] < '0')
        $balanceText = "negative";
    $balanceText .= "\">" . number_format($arrLabs['total']);

    $researchProduction =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Research" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Amount" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Labs Produce:" . "</th>" .
                "<td>" . number_format($arrLabs['raw']) . " rps" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Production Bonus:" . "</th>" .
                "<td>" . "+" . number_format($arrLabs['research_bonus']) . " rps" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Researching Penalty:" . "</th>" .
                "<td>" . "-" . number_format($arrLabs['penalty']) . " rps" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "War Loss:" . "</th>" .
                "<td>" . "-" . number_format($arrLabs['war_loss']) . " rps" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Balance:" . "</th>" .
                "<td class=\"bsup " . $balanceText . " rps" . "</td>" .
            "</tr>" .
        "</table>";

    return $researchProduction;
} // End research production advisor

//==============================================================================
// Effecting spells table
//==============================================================================
function get_effecting_spells_table(&$objUser)
{
    $arrSpells    = set_spell_vars($objUser);
    $arrSrcSpells = $objUser->get_spells();

    $activeSpells =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0   \">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Effecting Spells" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Name" . "</th>" .
                "<td>" . "Duration" . "</td>" .
            "</tr>";

    // Martel: May it be dynamic, but I believe this routine is handling too
    // much data in one go (possible memory eater)..
    // What it does: Combines output spell name from our spell archive with name and hours from the DB.
    // (If someone has a good solution for this, thieves ops can use it too.)

    $blnNoSpells = TRUE;
    for (reset($arrSpells); list ($strSpellName, $arrSpell) = each ($arrSpells);)
    {
        if (isset($arrSrcSpells[$arrSpell[DBFIELD]]) && $arrSrcSpells[$arrSpell[DBFIELD]] > 0)
        {
            //==============================================
            // 'tags' are dynamic from now on - AI 30/06/09
            //==============================================
            $type = "";
            switch($arrSpells[$strSpellName]['type'])
            {
                case SPELL_SELF:
                    $type = " (SELF)";
                    break;
                case SPELL_ALLIANCE:
                    $type = " (ALLIES)";
                    break;
                case SPELL_WAR:
                    $type = " (WAR)";
                    break;
                case SPELL_ENEMY:
                case SPELL_ALL:
            }

            if ($arrSrcSpells[$arrSpell[DBFIELD]] > 1)
                $s = 's';
            else
                $s = '';


            $blnNoSpells = FALSE;
            $activeSpells .=
                "<tr class=\"data\">" .
                    "<th>" . $arrSpell[DISPLAY] . $type . "</th>" .
                    "<td>" . $arrSrcSpells[$arrSpell[DBFIELD]] . " month" . $s .
                    "</td>" .
                "</tr>";
        }
    }

    if ($blnNoSpells)
    {
        $activeSpells .=
            "<tr class=\"data\">" .
                "<th colspan=\"2\">" . "No Active Spells" . "</th>" .
            "</tr>";
    }

    $activeSpells .=
        "</table>";

    return $activeSpells;
} // End effecting spells table

//==============================================================================
// Active operations table
//==============================================================================
function get_effecting_ops_table(&$objUser)
{
    $arrThievery = $objUser->get_thieverys();

    $activeOps =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0   \">" .
            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Effecting Operations" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Name" . "</th>" .
                "<td>" . "Duration" . "</td>" .
            "</tr>";

    $noOps = TRUE;
    if ($arrThievery[TRAP] > 0)
    {
        if ($arrThievery[TRAP] > 1)
            $s = 's';
        else
            $s = '';

        $activeOps .=
            "<tr class=\"data\">" .
                "<th>" . "Thieves Trap" . "</th>" .
                "<td>" . $arrThievery[TRAP] . " month" . $s . "</td>" .
            "</tr>";
        $noOps = FALSE;
    }

    if ($arrThievery[MONITOR] > 0)
    {
        if ($arrThievery[MONITOR] > 1)
            $s = 's';
        else
            $s = '';

        $activeOps .=
            "<tr class=\"data\">" .
                "<th>" . "Monitoring" . "</th>" .
                "<td>" . $arrThievery[MONITOR] . " month" . $s . "</td>" .
            "</tr>";
        $noOps = FALSE;
    }

    if ($noOps)
    {
        $activeOps .=
            "<tr class=\"data\">" .
                "<th colspan=\"2\">" . "No Active Operations" . "</th>" .
            "</tr>";
    }
    $activeOps .=
        "</table>";

    return $activeOps;
} // End effecting operations table

//==============================================================================
// Construction table
//==============================================================================
function get_construction_table(&$objUser)
{
    include_once('inc/functions/build.php');
    $arrVars   = getBuildingVariables($objUser->get_stat(RACE));
    $arrBuilds = $objUser->get_builds();
    $iBarren   = $objUser->get_barren();
    $iTotal    = $arrBuilds[LAND] - $iBarren;

    $strTd = '';
    for ($i = 1; $i <= 4; $i++)
    {
        $current  = LAND . '_t' . $i;
        $strTd   .= '<td';
        if ($arrBuilds[$current] > 0)   { $strTd .= ' class="incoming"'; }
        $strTd   .= '>' . $arrBuilds[$current] . '</td>';
    }

    // Incoming Acres Table
    $strTable1 =
        "<table class=\"medium\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"7\">" . "Incoming Acres" . "</th>" .
            "</tr>" .
            "<tr class=\"subheader\">" .
                "<th width=\"30%\">" . "Type" . "</th>" .
                "<td width=\"20%\" colspan=\"2\">" . "Ready" . "</td>" .
                "<td>" . "In 1" . "</td>" .
                "<td>" . "In 2" . "</td>" .
                "<td>" . "In 3" . "</td>" .
                "<td>" . "In 4" . "</td>" .
            "</tr>" .
            "<tr class=\"data\">" .
                "<th>" . "Barren Land:" . "</th>" .
                "<td>" . $iBarren . "</td>" .
                "<td>" .
                    "(" . getBuildInPercent($objUser, $iBarren, 'yes') . "%)" .
                "</td>" .
                $strTd .
            "</tr>" .
        "</table><br />";

    // Under Construction Table (+ explore table)
    $strTable2 = $strTable1 .
        "<table class=\"medium\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"7\">" . "Under Construction" . "</th>" .
            "</tr>" .
            "<tr class=\"subheader\">" .
                "<th width=\"30%\">" . "Type" . "</th>" .
                "<td width=\"20%\" colspan=\"2\">" . "Constructed" . "</td>" .
                "<td>" . "In 1" . "</td>" .
                "<td>" . "In 2" . "</td>" .
                "<td>" . "In 3" . "</td>" .
                "<td>" . "In 4" . "</td>" .
            "</tr>";

    for ($i = 1; $i <= count($arrVars['variables']); $i++)
    {
        $current = $arrVars['variables'][$i];

        $strTable2 .=
            "<tr class=\"data\">" .
                "<th>" . $arrVars['output'][$i] . ":" . "</th>" .
                "<td>" . number_format($arrBuilds[$current]) . "</td>" .
                "<td>" . "(" .
                    getBuildInPercent($objUser, $arrBuilds[$current], 'yes') .
                "%)" .
                "</td>";

        for ($j = 1; $j <= 4; $j++)
        {
            $current2  = $current . '_t' . $j;
            $strTable2 .= '<td';
            if ($arrBuilds[$current2] > 0)   $strTable2 .= ' class="incoming"';
            $strTable2 .= '>' . $arrBuilds[$current2] . '</td>';
        }

        $strTable2 .=
            "</tr>";
    }

    $strTable2 .=
            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Constructed Land:" . "</th>" .
                "<td class=\"bsup\">" . $iTotal . "</td>" .
                "<td class=\"bsup\">" .
                    "(" . getBuildInPercent($objUser, $iTotal, 'yes') . "%)" .
                "</td>" .
                "<td class=\"bsup\" colspan=\"4\">" . "&nbsp;" . "</td>" .
            "</tr>" .
        "</table>";

    return $strTable2;
} // End construction advisor

//==============================================================================
// Start military training advisor                          Martel, May 27, 2006
//==============================================================================
function get_military_training_table(&$objUser)
{
    $arrArmy = $objUser->get_armys();

    include_once('inc/functions/races.php');
    $arrUnitInfo  = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitVars  = $arrUnitInfo['variables'];
    $arrUnitNames = $arrUnitInfo['output'];

    $training =
        "<table class=\"medium\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"6\">" . "Military Training" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Class" . "</th>" .
                "<td>" . "Trained" . "</td>" .
                "<td>" . "In 1" . "</td>" .
                "<td>" . "In 2" . "</td>" .
                "<td>" . "In 3" . "</td>" .
                "<td>" . "In 4" . "</td>" .
            "</tr>";

    reset ($arrUnitVars);
    foreach ($arrUnitVars as $key => $value)
    {
        if ($value <> "citizen")
        {
            $plural = 's';
            if ($arrUnitNames[$key] == 'Swordmen') { $plural = ''; }
            if ($arrUnitNames[$key] == 'Pikemen') { $plural = ''; }
            if ($arrUnitNames[$key] == 'Crossbowmen') { $plural = ''; }
            if ($arrUnitNames[$key] == 'Longbowmen') { $plural = ''; }
            if ($arrUnitNames[$key] == 'Thief') { $arrUnitNames[$key] = 'Thieve'; }
            if ($arrUnitNames[$key] == 'Priestess') { $plural = '';}
            if ($arrUnitNames[$key] == 'Mummy') { $arrUnitNames[$key] = 'Mummie'; }

             $training .=
            "<tr class=\"data\">" .
                "<th>" . $arrUnitNames[$key] . $plural . "</th>" .
                "<td>" . number_format($arrArmy[$value]) . "</td>";

            for ($intUnitTimeLoop = '1'; $intUnitTimeLoop <= '4'; $intUnitTimeLoop++)
            {
                $strKey = $value . "_t" . $intUnitTimeLoop;
                $val = number_format($arrArmy[$strKey]);
                if ($val == '0')
                    $training .= "<td>" . $val . "</td>";
                else
                    $training .= "<td class=\"incoming\">" . $val . "</td>";
            }
            $training .=
            "</tr>";
        }
    }
    $training .=
        "</table>";

    return $training;
} // End military training table

//==============================================================================
// Military returning table
//==============================================================================
function get_military_returning_table(&$objUser)
{
    $arrArmy = $objUser->get_armys();
    $arrMilReturns =  $objUser->get_milreturns();

    include_once('inc/functions/races.php');
    $arrUnitInfo  = getUnitVariables($objUser->get_stat(RACE));
    $arrUnitVars  = $arrUnitInfo['variables'];
    $arrUnitNames = $arrUnitInfo['output'];

    // Start military returning advisor
    $returning =
        "<table class=\"medium\" cellpadding=\"0\" cellspacing=\"0\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"6\">" . "Military Returning" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Class" . "</th>" .
                "<td>" . "Home" . "</td>" .
                "<td>" . "In 1" . "</td>" .
                "<td>" . "In 2" . "</td>" .
                "<td>" . "In 3" . "</td>" .
                "<td>" . "In 4" . "</td>" .
            "</tr>";

    reset ($arrUnitVars);
    foreach ($arrUnitVars as $key => $value)
    {
        if ($value <> "citizen")
        {
            $plural = 's';
            if ($arrUnitNames[$key] == 'Swordmen') { $plural = '';}
            if ($arrUnitNames[$key] == 'Pikemen') { $plural = '';}
            if ($arrUnitNames[$key] == 'Crossbowmen') { $plural = '';}
            if ($arrUnitNames[$key] == 'Longbowmen') { $plural = '';}
            if ($arrUnitNames[$key] == 'Thief') {$arrUnitNames[$key] = 'Thieve';}
            if ($arrUnitNames[$key] == 'Priestess') { $plural = '';}
            if ($arrUnitNames[$key] == 'Mummy') { $arrUnitNames[$key] = 'Mummie';}

            $intArmyHome = $arrArmy[$value] - ($arrMilReturns[$value."_t1"] + $arrMilReturns[$value."_t2"] + $arrMilReturns[$value."_t3"] + $arrMilReturns[$value."_t4"]);

            $returning .=
        "<tr class=\"data\">" .
            "<th>" . $arrUnitNames[$key] . $plural . "</th>" .
            "<td>" . number_format($intArmyHome) . "</td>";
            for ($intUnitTimeLoop = '1'; $intUnitTimeLoop <= '4'; $intUnitTimeLoop++)
            {
                $strKey = $value . "_t" . $intUnitTimeLoop;
                $val = number_format($arrMilReturns[$strKey]);
                if ($val == '0')
                    $returning .= "<td>" . $val . "</td>";
                else
                    $returning .= "<td class=\"incoming\">" . $val . "</td>";
            }
            $returning .=
        "</tr>";
        }
    }

    $returning .=
        "</table>";

    return $returning;
} // End military returning table

//==============================================================================
// Military Offence Table
//==============================================================================
function get_offence_table(&$objUser)
{
    include_once('inc/functions/military.php');
    $arrArmyOffence = getArmyOffence($objUser);

    $offence =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .

            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Offence" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Power" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Offence:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['raw']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Weaponries Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['building_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Research Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['research_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Spell Bonuses:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['spell_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Fame Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['fame_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "After Mod Offence:" . "</th>" .
                "<td>" . number_format($arrArmyOffence['total']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Offence Sent Out:" . "</th>" .
                "<td class=\"bsup\">" . number_format($arrArmyOffence['total_out']) . "</td>" .
            "</tr>" .

        "</table>";
    return $offence;
}

//==============================================================================
// Start military training advisor
//==============================================================================
function get_defence_table(&$objUser)
{
    include_once('inc/functions/military.php');
    $arrArmyDefence = getArmyDefence($objUser);

    $defence =
        "<table class=\"small\" cellpadding=\"0\" cellspacing=\"0\">" .

            "<tr class=\"header\">" .
                "<th colspan=\"2\">" . "Defence" . "</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th>" . "Type" . "</th>" .
                "<td>" . "Power" . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Defence:" . "</th>" .
                "<td>" . number_format($arrArmyDefence['raw']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Walls Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyDefence['building_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Research Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyDefence['research_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Deam's Hunt Bonus:" . "</th>" .
                "<td>" . number_format($arrArmyDefence['spell_bonus']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "After Mod Defence:" . "</th>" .
                "<td>" . number_format($arrArmyDefence['total']) . "</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th class=\"bsup\">" . "Total Defence at Home:" . "</th>" .
                "<td class=\"bsup\">" . number_format($arrArmyDefence['total_home']) . "</td>" .
            "</tr>" .

        "</table>";
    return $defence;
}

//==============================================================================
// Guide Links (Text Boxes)                              Martel, August 21, 2007
//==============================================================================
function get_guide_link(&$objUser, $strPageOrChpt = '', $strClass = 'textSmall')
{
    $strSwitch = $objUser->get_preference(GUIDE_LINKS);
    if ($strSwitch == OFF)
        return "";

    $strDiv =
    '<div id="' . $strClass . '">' .
        '<h3>' . "The Guide:" . '</h3>';

    switch($strPageOrChpt)
    {
        case "war_room":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'war.php?chapter=3" target="_blank" class="newWindowLink">War</a>' . '</li>' .
            '</ul>';

        break;
        case "research":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'research.php?chapter=3" target="_blank" class="newWindowLink">Research</a>' . '</li>' .
                '<li>' . "Tip: 66% is the maximum research of each branch" . '</li>' .
            '</ul>';

        break;
        case "tools":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'tools.php?chapter=1" target="_blank" class="newWindowLink">Tools</a>' . '</li>' .
            '</ul>';

        break;
        case "advisor_military":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'whattotrain.php?chapter=1" target="_blank" class="newWindowLink">What To Train?</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'attacking.php?chapter=2" target="_blank" class="newWindowLink">Invading</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'attacks.php?chapter=2" target="_blank" class="newWindowLink">Attacks</a>' . '</li>' .
            '</ul>';

        break;
        case "advisor_actions":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'magic.php?chapter=2" target="_blank" class="newWindowLink">Mystics</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'thievery.php?chapter=2" target="_blank" class="newWindowLink">Thievery</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'spells.php?chapter=2" target="_blank" class="newWindowLink">Spells</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'operations.php?chapter=2" target="_blank" class="newWindowLink">Operations</a>' . '</li>' .
            '</ul>';

        break;
        case "advisor_build":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'whattobuild.php?chapter=1" target="_blank" class="newWindowLink">What to Build?</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'exploring.php?chapter=2" target="_blank" class="newWindowLink">Exploring</a>' . '</li>' .
            '</ul>';

        break;
        case "advisor_population":

            $strDiv .=
            '<ul>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'howtogrow.php?chapter=1" target="_blank" class="newWindowLink">How to Grow?</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'exploring.php?chapter=2" target="_blank" class="newWindowLink">Exploring</a>' . '</li>' .
                '<li>' . '<a href="' . HOST_GUIDE . 'dying.php?chapter=1" target="_blank" class="newWindowLink">Prevent Dying</a>' . '</li>' .
            '</ul>';

        break;
        default:

            $strDiv .=
            '<ol>' .
                '<li>' . '<a href="' . HOST_GUIDE . '" target="_blank" class="newWindowLink">Introduction</a>' . '</li>' .
            '</ol>';

        break;
    }

    $strDiv .=
            '</div>' .
        '<div class="center" style="font-style: italic;">' .
            '<a href="main.php?cat=game&amp;page=preferences&amp;task=layout">Hide Links</a>' .
        '</div>';

    return $strDiv;
}

?>
