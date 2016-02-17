<?php
//******************************************************************************
// pages explore2.php                                       Martel, May 28, 2006
//
// This page can only be reached using the POST method. (eg forms)
//******************************************************************************
function include_explore2_text()
{
    global  $Host;

    $objSrcUser     = &$GLOBALS["objSrcUser"];
    $explored_acres = intval($_POST['explored_acres']);

    include_once('inc/functions/explore.php');
    $iMaxAcres      = getMaxExplore($objSrcUser);
    $arrExploreCost = getExploreCosts($objSrcUser);
    $money_used     = $explored_acres * $arrExploreCost['crowns'];
    $used_citizens  = $explored_acres * $arrExploreCost['citizens'];
    $used_basics    = $explored_acres * $arrExploreCost['basics'];

    include_once('inc/functions/races.php');
    $arrUnitVars    = getUnitVariables($objSrcUser->get_stat(RACE));
    $arrUnitNames   = $arrUnitVars['output'];


    $strRace  = $objSrcUser->get_stat(RACE);
    if ($strRace == 'Nazgul')
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Your proud nazgul military will not lower itself to exploring for land." .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($explored_acres < 1)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "You tried to explore 0 acres." .
            '<br /><br />' .
            '<a href="main.php?cat=game&page=explore">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    $iCitz          = $objSrcUser->get_pop(CITIZENS);
    $iNewCitz       = $iCitz - $used_citizens;
    if ($iNewCitz < 800)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Exploring this many acres would result in less than 800 citizens " .
            "remaining on your lands. Your citizens refuse to venture forth." .
            '<br /><br />' .
            '<a href="main.php?cat=game&page=explore">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    $arrSpells = $objSrcUser->get_spells();
    if ($arrSpells[STUNTED_GROWTH] > 0)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "Bewitched by your enemies, your exploration team do not dare " .
            "prospecting new lands for yet another " . $arrSpells[STUNTED_GROWTH] .
            " months." .
            '<br /><br />' .
            '<a href="main.php?cat=game&page=explore">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    $iMaxExplore = getMaxExplore($objSrcUser);
    if ($explored_acres > $iMaxExplore)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            "You tried to explore $explored_acres acres, however you can " .
            "only explore $iMaxExplore acres. " .
            "<br /><br />" .
            "You can send expeditions out to explore a maximum of 25% of " .
            "your current acres plus incoming." .
            '<br /><br />' .
            '<a href="main.php?cat=game&page=explore">' . "Try Again ?" . "</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }
    else
    {
        if (doExplore($objSrcUser, $explored_acres)) // does the explore!
        {
            // Return HTML Output
            $plural = 's';
            if ($explored_acres == 1)
                $plural = '';

            echo $strDiv =
            '<div id="textMedium">' .
            '<h2>' . 'Explore Report' . '</h2>' .
            '<p>' .
                "Congratulations, you have explored " .
                "<b>" . number_format($explored_acres) . " acre$plural</b>." .
            '</p><p>' .
                "This expedition cost you <b>" .
                number_format($money_used) . " crowns</b> and required <b>" .
                number_format($used_citizens) . " " .
                strtolower($arrUnitNames[1]) . "s</b> and <b>" .
                number_format($used_basics) . " " .
                strtolower($arrUnitNames[2]) . "s</b> to send off." .
            '</p><p align="center">' .
                '<img src="' . $Host . 'explorers_medium.gif" width="375" />' .
            '</p><p>' .
                "Your new land will be ready for construction in <b>4 months</b>." .
            '</p><p>' .
                '<a href="main.php?cat=game&amp;page=explore">Continue</a>' .
            "</p></div>";

        }
        else
        {
            echo '<div class="center">' .
                 "Your explore seem to have failed, please check your tribe " .
                 "for any eventual errors caused by this, and report these " .
                 "together with your tribe name and alliance # to an admin." .
                 "<br />Thank you, and sorry for any inconvenience caused by " .
                 "this.";
            echo "<br><br><a href=main.php?cat=game&amp;page=explore>Return to Exploration</a></div";
        }
    }
}

?>