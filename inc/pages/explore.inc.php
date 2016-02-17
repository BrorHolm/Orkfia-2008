<?php
//******************************************************************************
// pages explore.inc.php                                    Martel, May 28, 2006
//
// Description: Exploration interface.
// History:
// Martel - Updated the interface - December 27, 2007
// Vay - changed "tribe, exploring" to "tribe. Exploring", added comma after another
// Vay - changed "cost" to "costs"
// Vay - changed "land, you" to "land. You"
//******************************************************************************
function include_explore_text()
{
    $objSrcUser     = &$GLOBALS["objSrcUser"];

    $objSrcRace     = $objSrcUser->get_race();
    $arrUnitNames   = $objSrcRace->getUnitNames();

    include_once('inc/functions/explore.php');
    $iMaxAcres      = getMaxExplore($objSrcUser);
    $arrExploreCost = getExploreCosts($objSrcUser);

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser);

    echo $topLinks =
            '<div class="center">' .
                '<a href="main.php?cat=game&amp;page=build">Construction</a> | ' .
                '<a href="main.php?cat=game&amp;page=army">Military Training</a> | ' .
                "<strong>Exploration</strong>" .
            '</div>';

    $advisorText =
        '<div id="textBig">' .
        '<p>' .
            '<img src="'.HOST_PICS.'explorers.gif" style="float: left; margin-right: 10px;" alt="Explorers" />' .
            "<strong>The tribe architect</strong> greets you: <br />" .
            "Attacking other tribes is not the only way to settle new " .
            "acres and expand your tribe. Exploring is another, more friendly, " .
            "way to grow. However, exploring isn't free and will require crowns, " .
            strtolower($arrUnitNames[2]) . "s and " . strtolower($arrUnitNames[1]) . "s. " .
            "To lower the costs you may consider ordering additional markets to be constructed." .
        '</p>' .
        '<p>' .
            "Sending out a team to settle <strong>1 acre</strong> costs <strong>" .
            number_format($arrExploreCost['crowns']) . " crowns</strong>, <strong>" .
            $arrExploreCost['basics'] . " " . strtolower($arrUnitNames[2]) . "s</strong> and " .
            "<strong>" . $arrExploreCost['citizens'] . " " . strtolower($arrUnitNames[1]) . "s." .
            "</strong>" .
        '</p>';

    if ($objSrcRace->getRaceName() == 'Nazgul')
    {
        $advisorText =
        '<div id="textMedium">' .
        '<p>' .
            'Your proud Nazgul army will not lower itself to explore for acres.' .
        '</p>';
    }

    $advisorText .= '</div>';
    echo $advisorText;

    // Classic Exception
    $strTip = 'Tip: You may be able to afford more, but you can only explore a ' .
              'total of no more than 25% of your current land.';
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME && $objSrcUser->get_user_info(HOURS) < PROTECTION_HOURS)
        $strTip = 'Tip: You may explore at most 1000 acres while in protection.';

    if ($objSrcRace->getRaceName() != 'Nazgul')
    {
        // Advisor Link
        echo $advisorLink =
        '<br /><div class="tableLinkMedium">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=build">Tribe Architect</a> ' .
            ':: <a href="main.php?cat=game&amp;page=market">Market</a>' .
        '</div>';

        echo $strAdvisorText =
        '<div id="textMedium" style="margin-top: 0;">' .
            '<h3>Prepare Expedition</h3>' .
            '<p>' .
                'Currently you can explore <strong><span class="indicator">' . number_format($iMaxAcres) . '</span> acres</strong>.' .
            '</p>' .
            '<form id="center" action="main.php?cat=game&amp;page=explore2" method="post" style="margin-top: 0pt";>' .
                '<label>Explore Acres: ' .
                '<input name="explored_acres" value="' . $iMaxAcres . '" size="5" maxlength="4" />' .
                '</label> ' .
                '<input type="submit" value="Send Expedition" />' .
            '</form>' .
            '<p>' .
                $strTip .
            '</p>' .
        '</div>';
    }
}

?>
