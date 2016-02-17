<?php
//******************************************************************************
// Pages xmas2.inc.php                                  November 27, 2007 Martel
// Only accepts POST requests
//     $iDay   = intval($_GET['day']); // Uncomment to test
//     $iDay   = 2; // Uncomment to test
//******************************************************************************

function include_xmas2_text()
{
    $iDay   = date('j');
    $iMonth = date('n');

    // M: Secure / Validate Input
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        echo
            '<div id="textMedium">' .
            '<p>' .
                'Look for this little creature again tomorrow but on another ' .
                'page, as he will not be at the same spot twice.'.
            '</p>' .

            '<p>' .
                '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            '</p>' .
            '</div>';
        return;
    }
    elseif ($_POST['piss_off_bots'] != md5('xmas' . $iDay . $iMonth))
    {
        echo
            '<div id="textMedium">' .
            '<p>' .
                'Look for this little creature again tomorrow but on another ' .
                'page, as he will not be at the same spot twice.'.
            '</p>' .

            '<p>' .
                '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            '</p>' .
            '</div>';
        return;
    }

    // M: Secure actual day
    $objUser  = &$GLOBALS["objSrcUser"];
    $iXmasDay = $objUser->get_stat('xmas_day');
    if ($iXmasDay < $iDay && $iMonth == 12)
    {
        $objUser->set_stat('xmas_day' , $iDay);
    }
    else
    {
        echo
            '<div id="textMedium">' .
            '<p>' .
                'Look for this little creature again tomorrow but on another ' .
                'page, as he will not be at the same spot twice.'.
            '</p>' .

            '<p>' .
                '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            '</p>' .
            '</div>';
        return;
    }

    $iAcres    = $objUser->get_build(LAND);
    $iHours    = $objUser->get_user_info(HOURS);
    $iModifier = 2;
    $objRace   = $objUser->get_race();
    if ($objRace->getRaceName() == 'Dragon')
        $iModifier *= .5;

    switch ($iDay)
    {
        case 1:

            $iGift     = round($iHours * .05 * $iModifier); // ~25 acres
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift) . " acres</strong>!";
            $iOld = $objUser->get_build(LAND);
            $objUser->set_build(LAND, $iOld + $iGift);

        break;
        case 2:

            $iGift     = round($iHours * 1000 * $iModifier); // ~500,000 cr
            $iOld      = $objUser->get_good(MONEY);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " crowns</strong>!";
            $objUser->set_good(MONEY, $iOld + $iGift);

        break;
        case 3:

            $iGift     = round($iHours * 10 * $iModifier); // 10=~5,000 citz
            $iOld      = $objUser->get_pop(CITIZENS);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " citizens</strong>!";
            $objUser->set_pop(CITIZENS, $iOld + $iGift);

        break;
        case 4:

            $iGift     = round($iHours * .1 * $iModifier); // .1=~50 mp
            $iOld      = $objUser->get_spell(POWER);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " mana points</strong>!";
            $objUser->set_spell(POWER, $iOld + $iGift);

        break;
        case 5:

            $iGift     = $objUser->get_stat(KILLS); // # kills
            $strReport = "Santa's little ork helper knows. You made <strong>" .
                         number_format($iGift). " kills</strong>!";

        break;
        case 6:

            $iGift     = round($iHours * .1 * $iModifier); // .1=~50 tp
            $iOld      = $objUser->get_thievery(CREDITS);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " thievery points</strong>!";
            $objUser->set_thievery(CREDITS, $iOld + $iGift);

        break;
        case 7:

            $iGift     = $objUser->get_user_info(LOGINS); // # logins
            $strReport = "Santa's little ork helper knows. You've made <strong>" .
                         number_format($iGift). " logins</strong>!";

        break;
        case 8:

            $iGift     = round($iHours * 2 * $iModifier); // 2=~1,000 basics
            $iOld      = $objUser->get_army(UNIT1);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " basics</strong>!";
            $objUser->set_army(UNIT1, $iOld + $iGift);

        break;
        case 9:

            $iGift     = round($iHours * .05 * $iModifier); // ~25 acres
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift) . " acres</strong>!";
            $iOld = $objUser->get_build(LAND);
            $objUser->set_build(LAND, $iOld + $iGift);

        break;
        case 10:

            $iGift     = round($iHours * 3 * $iModifier); // ~1,500 kgs
            $iOld      = $objUser->get_good(FOOD);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " kgs</strong> of food!";
            $objUser->set_good(FOOD, $iOld + $iGift);

        break;
        case 11:

            $iGift     = round($iHours * 10 * $iModifier); // 10=~5,000 citz
            $iOld      = $objUser->get_pop(CITIZENS);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " citizens</strong>!";
            $objUser->set_pop(CITIZENS, $iOld + $iGift);

        break;
        case 12:

            $iGift     = 10; // 10 hours
            $strReport = "Santa's little ork helper gave you Seal of " .
                         "Deflection for <strong>" . number_format($iGift) .
                         " months</strong>!";
            $objUser->set_spell(THWART, $iGift);

        break;
        case 13:

            $iGift     = 5; // 5 hours
            $strReport = "Santa's little ork helper gave you Thieves " .
                         "Trap for <strong>" . number_format($iGift) .
                         " months</strong>!";
            $objUser->set_thievery(TRAP, $iGift);

        break;
        case 14:

            $iGift     = round($iHours * 1 * $iModifier); // 1=~500 off specs
            $iOld      = $objUser->get_army(UNIT2);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " off specs</strong>!";
            $objUser->set_army(UNIT2, $iOld + $iGift);

        break;
        case 15:

            $iGift     = round($iHours * .05 * $iModifier); // ~25 acres
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift) . " acres</strong>!";
            $iOld = $objUser->get_build(LAND);
            $objUser->set_build(LAND, $iOld + $iGift);

        break;
        case 16:

            $iGift     = round($iHours * 1.5 * $iModifier); // ~750 rps
            $iOld      = $objUser->get_good(RESEARCH);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " rps</strong>!";
            $objUser->set_good(RESEARCH, $iOld + $iGift);

        break;
        case 17:

            $iGift     = round($iHours * 10 * $iModifier); // 10=~5,000 citz
            $iOld      = $objUser->get_pop(CITIZENS);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " citizens</strong>!";
            $objUser->set_pop(CITIZENS, $iOld + $iGift);

        break;
        case 18:

            $iGift     = 1; // 1 hour
            $iOld      = $objUser->get_spell(VIRUS);
            $strReport = "Santa's little ork helper has infected you with a " .
                         "virus of some kind for <strong>" . number_format($iGift) .
                         " month</strong>!";
            $objUser->set_spell(VIRUS, $iOld + $iGift);

        break;
        case 19:

            $iGift     = round($iHours * .1 * $iModifier); // .1=~50 tp
            $iOld      = $objUser->get_thievery(CREDITS);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " thievery points</strong>!";
            $objUser->set_thievery(CREDITS, $iOld + $iGift);

        break;
        case 20:

            $iGift     = round($iHours * 1 * $iModifier); // 1=~500 def specs
            $iOld      = $objUser->get_army(UNIT3);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " def specs</strong>!";
            $objUser->set_army(UNIT3, $iOld + $iGift);

        break;
        case 21:

            $iGift     = round($iHours * .05 * $iModifier); // ~25 acres
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift) . " acres</strong>!";
            $iOld = $objUser->get_build(LAND);
            $objUser->set_build(LAND, $iOld + $iGift);

        break;
        case 22:

            $iGift     = round($iHours * 9 * $iModifier); // ~4500 wood
            $iOld      = $objUser->get_good(WOOD);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " logs</strong>!";
            $objUser->set_good(WOOD, $iOld + $iGift);

        break;
        case 23:

            $iGift     = 1; // 1 hour
            $iOld      = $objUser->get_spell(VIRUS);
            $strReport = "Santa's little ork helper has infected you with a " .
                         "virus of some kind for <strong>" . number_format($iGift) .
                         " month</strong>!";
            $objUser->set_spell(VIRUS, $iOld + $iGift);

        break;
        case 24:

            $iGift     = round($iHours * 15 * $iModifier); // ~7500 rps
            $iOld      = $objUser->get_good(RESEARCH);
            $strReport = "Santa's little ork helper gave you <strong>" .
                         number_format($iGift). " rps</strong>!";
            $objUser->set_good(RESEARCH, $iOld + $iGift);

        break;
//         case 27:

//             $iGift     = round($iHours * .2 * $iModifier); // .1=~50, 100=~50000
//             $strReport = "Santa's little ork helper gave you <strong>" .
//                          number_format($iGift) . " acres</strong>!";
//             $iOld = $objUser->get_build(LAND);
//             $objUser->set_build(LAND, $iOld + $iGift);

//         break;
    }

    $strDetails =
        'Look for this little creature again tomorrow but on another ' .
        'page, as he will not be at the same spot twice.';
    if ($iDay == 24)
    {
        $strDetails =
        'Congratulations, you got his last gift! <br />We\'d like to wish ' .
        'all our players, old and new, and all of ' .
        'this year\'s <a href="main.php?cat=game&amp;page=sponsors">ORKFiA ' .
        'Dragons</a> a happy holiday!<br />~ The ORKFiA Staff Team';
    }


    $strReport =
    '<div id="textMedium">' .

        '<h2>' . date('jS \of F') . ':</h2>' .

        '<p>' .
            $strReport .
        '</p>' .

        '<p>' .
            $strDetails .
        '</p>' .

        '<p>' .
            '<a href="main.php?cat=game&amp;page=tribe">Continue</a>'.
        '</p>' .

    '</div>';
    echo $strReport;
}
?>