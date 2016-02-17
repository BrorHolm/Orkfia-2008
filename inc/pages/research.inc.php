<?php
//******************************************************************************
// Pages research.inc.php                Major overhaul December 25, 2007 Martel
//  History
//  June 03, 2005 - Interface makeover - Martel
//******************************************************************************

function include_research_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $arrStats   = $objSrcUser->get_stats();

    // M: Navigation links
    $strPurchase = '';
    if ($arrStats[TYPE] == "elder" || $arrStats[TYPE] == "coelder")
        $strPurchase = "<a href='main.php?cat=game&amp;page=research&amp;action=buy'>Purchase</a> | ";

    $topLinks =
        '<div class="center">' .
            '| <a href="main.php?cat=game&amp;page=research&amp;action=view">View/Invest</a> | ' .
            $strPurchase .
            '<a href="main.php?cat=game&amp;page=research&amp;action=history">History</a> |' .
        '</div>';
    echo $topLinks;

    // M: User Tables
    $arrGoods     = $objSrcUser->get_goods();
    $arrUserInfos = $objSrcUser->get_user_infos();

    // M: Alliance Tables
    $objSrcAlli = $objSrcUser->get_alliance();
    $arrAlli    = $objSrcAlli->get_alliance_infos();
    $arrSci     = $objSrcAlli->get_alliance_sciences();

    // M: Get rounded %'s
    foreach ($arrSci as $str => $iBonus)
        $arrSciMod[$str] = round($iBonus * 100, 2);


    $do = '';
    if (isset($_GET['do']) && !empty($_GET['do']))
        $do = strval($_GET['do']);

    $action = 'view';
    if (isset($_GET['action']) && !empty($_GET['action']))
        $action = strval($_GET['action']);
    switch ($action)
    {
        case 'view':

            if ($do == "invest")
            {
                $invest = max(0, $arrGoods[RESEARCH]);

                if ($arrUserInfos[HOURS] < PROTECTION_HOURS)
                {
                    echo '<br /><div class="center">' . "Sorry, you can only invest research points once you have left protection.</div><br />";
                    return;
                }
                elseif ($invest == 0)
                {
                    echo '<br /><div class="center">' . "I'm sure you meant well, but investing 0 rps will do nothing for our research.</div><br />";
                    return;
                }
                else
                {
                    $arrStats[INVESTED] += $invest;
                    $arrAlli[RESEARCH]  += $invest;

                    $objSrcUser->set_good(RESEARCH, 0);
                    $objSrcUser->set_stat(INVESTED, $arrStats[INVESTED]);
                    $objSrcAlli->set_alliance_info(RESEARCH, $arrAlli[RESEARCH]);

                    header('location:main.php?cat=game&page=research&action=view&do=thank');
                    exit;
                }
            }

            if ($arrGoods[RESEARCH] > 0)
            {
                $strMessage =
                    "Our researchers have been working very hard." .
                    "<br />" . "Would you like to invest their produced research points?" .
                    "<form method=\"post\" action=\"main.php?cat=game&amp;page=research&amp;action=view&amp;do=invest\">" .
                        '<label>Available: </label><strong><span class="indicator">' . $arrGoods[RESEARCH] . '</span> rps</strong> ' .
                        "<input type=\"submit\" value=\"Invest\" name=\"invest\" />" .
                    "</form>";
            }
            elseif ($do == 'thank')
            {
                $strMessage =
                    "You have invested your research. Your alliance thanks you!";
            }
            else
            {
                $strMessage =
                    "We have no research points for you to invest at this moment.";
            }

            $strAdvisorText =
                '<div id="textMedium">' .
                    "<p>Good morning " . stripslashes($arrStats[NAME]) . "!<br />" .
                    $strMessage .
                    '</p>' .
                '</div><br />';
            echo $strAdvisorText;

            // M: Show alliance research (new function shared with spells)
            echo get_alliance_science_table($objSrcAlli);

            // M: Show guide link + advice
            include_once('inc/pages/advisors.inc.php');
            echo get_guide_link($objSrcUser, 'research', 'textMedium');

        break;
        case "buy":

            // M: Restrict access to elders and co-elders
            if ($arrStats[TYPE] == "player")
            {
                echo "<div class=\"center\">Only your elected elder can purchase the research points you have invested.<br /><br />";
                echo "| <a  href=main.php?cat=game&amp;page=research&action=view style=\"text-decoration:none\">Return To Investing</a> |</div>";

                return;
            }

            // M: Handle POST action
            if ($do == "buy")
            {
                // M: Clean POST-data
                $invest_prod = max(0, floor(intval($_POST['invest_prod'])));
                $invest_eng  = max(0, floor(intval($_POST['invest_eng'])));
                $invest_def  = max(0, floor(intval($_POST['invest_def'])));
                $invest_off  = max(0, floor(intval($_POST['invest_off'])));
                $iToPurchase = $invest_off + $invest_def
                             + $invest_eng + $invest_prod;

                if ($iToPurchase == 0)
                {
                    echo "<div class=\"center\">I'm sure you meant well, but purchasing 0 rps will do nothing for our science.</div><br />";
                }
                elseif ($iToPurchase < 0 || $iToPurchase > 9999999 || $invest_eng < 0 || $invest_eng > 9999999 ||    $invest_prod < 0 || $invest_prod > 9999999 || $invest_def < 0 || $invest_def > 9999999 || $invest_off < 0 || $invest_off > 9999999)
                {
                    echo "<div class=\"center\">It's impossible to invest that amount.</div><br />";
                }
                elseif ($iToPurchase > $arrAlli[RESEARCH])
                {
                    echo "<div class=\"center\">How can you purchase research points that you don't have?</div><br />";
                }
                else
                {
                    // M: New research
                    $iNewResearch    = max(0,$arrAlli[RESEARCH] - $iToPurchase);
                    $arrNewAlliInfos = array(
                        RESEARCH      => $iNewResearch,
                        HOME_BONUS    => $arrAlli[HOME_BONUS]    + $invest_eng,
                        INCOME_BONUS  => $arrAlli[INCOME_BONUS]  + $invest_prod,
                        DEFENCE_BONUS => $arrAlli[DEFENCE_BONUS] + $invest_def,
                        OFFENCE_BONUS => $arrAlli[OFFENCE_BONUS] + $invest_off
                    );

                    // M: Save to DB
                    $objSrcAlli->set_alliance_infos($arrNewAlliInfos);

                    // M: Show report
                    $strReport =
                        '<div id="textMedium">' .
                            '<h2>Purchase Report</h2>' .

                            "<p>" .
                                "Thank you " . stripslashes($arrStats[NAME]) .
                                ", our research has been updated!" .
                            "</p>" .
                            "<p>" .
                                "You had <strong>" . number_format($arrAlli[RESEARCH]) .
                                " research points</strong> and you used up " .
                                "<strong>" . number_format($iToPurchase) .
                                "</strong>." .
                            "</p>" .
                            "<p>" .
                                '<a href="main.php?cat=game&amp;page=research' .
                                '&amp;action=buy">Return To Purchasing</a>' .
                            '</p>' .
                        '</div>';
                    echo $strReport;

                    include_game_down();
                    exit;
                }
            }

            // M: Show "advisor" text
            if ($arrAlli['research'] != 0)
            {
                $strMessage =
                    "<br />Our alliance has been working very hard.<br />".
                    "In what branch of sciences would you like to purchase?";
            }
            else
            {
                $strMessage =
                    "<br />We have no research points for you to " .
                    "purchase at this moment.";
            }

            $strAdvisorText =
                '<div id="textMedium">' .
                    '<p>Good morning ' . $arrStats[NAME] . '!' .
                    $strMessage .
                    '</p>' .
                '</div><br />';
            echo $strAdvisorText;

            // M: Show purchase interface
            $left  = '<img src="' . HOST_PICS . 'bar_left.gif" alt="" />' .
                     '<img src="' . HOST_PICS . 'bar_mid.gif" height="12" width="';
            $right = '%" alt="" /><img src="' . HOST_PICS . 'bar_right.gif" alt="" />';

            $researchPurchase =
                "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
                "<form method=\"post\" action=\"main.php?cat=game&amp;page=research&amp;action=buy&amp;do=buy\">" .
                    "<tr class=\"header\">" .
                        "<th colspan=\"4\">Alliance Research</th>" .
                    "</tr>" .

                    "<tr class=\"subheader\">" .
                        "<th width=\"33%\">" . "Type" . "</th>" .
                        "<th>" . "Amount" . "</th>" .
                        "<th width=\"10%\" class=\"center\">" . "%" . "</th>" .
                        "<th width=\"20%\" class=\"right\">" . "Purchase" . "</th>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Production:" . "</th>" .
                        "<td class=\"left\">" . $left . floor($arrSciMod['prod'] * 1.4 ) . $right . "</td>" .
                        "<td class=\"center\">" . $arrSciMod['prod'] . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"invest_prod\" size=\"8\" maxlength=\"7\" value=\"0\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Engineering:" . "</th>" .
                        "<td class=\"left\">" . $left . floor($arrSciMod['eng'] * 1.4 ) . $right . "</td>" .
                        "<td class=\"center\">" . $arrSciMod['eng'] . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"invest_eng\" size=\"8\" maxlength=\"7\" value=\"0\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "Defence Tactics:" . "</th>" .
                        "<td class=\"left\">" . $left . floor($arrSciMod['def'] * 1.4 ) . $right . "</td>" .
                        "<td class=\"center\">" . $arrSciMod['def'] . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"invest_def\" size=\"8\" maxlength=\"7\" value=\"0\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th>" . "War Tactics:" . "</th>" .
                        "<td class=\"left\">" . $left . floor($arrSciMod['war'] * 1.4 ) . $right . "</td>" .
                        "<td class=\"center\">" . $arrSciMod['war'] . "</td>" .
                        "<td>" . "<input type=\"text\" name=\"invest_off\" size=\"8\" maxlength=\"7\" value=\"0\" />" . "</td>" .
                    "</tr>" .

                    "<tr class=\"data\">" .
                        "<th class=\"bsup\">" . "Available:" . "</th>" .
                        "<td class=\"bsup left\" colspan=\"2\"><strong><span class=\"indicator\">" . number_format($arrAlli['research']) . "</span> rps</strong>" . "</td>" .
                        "<td class=\"bsup\">" . "<input type=\"submit\" value=\"Purchase\" name=\"submit\" />" . "</td>" .
                    "</tr>" .
                "</form>" .
                "</table>";
            echo $researchPurchase;

        break;
        case "history":

            echo '<br />';

            $arrUserids = $objSrcAlli->get_userids();
            $objTmpUser = new clsUser(0);
            $strTribesInvested = '';
            foreach ($arrUserids as $iTmpUserid)
            {
                // M: Clear temporary object (first time trying this method :))
                $objTmpUser->set_userid($iTmpUserid);
                $arrTmpStats = $objTmpUser->get_stats();
                $iTmpAcres   = $objTmpUser->get_build(LAND);

                $strTribesInvested .=
                    "<tr class=\"data\">" .
                        "<th>" . stripslashes($arrTmpStats[TRIBE]) . "</th>" .
                        "<td>" . round($arrTmpStats[INVESTED] / $iTmpAcres) . "</td>" .
                        "<td>" . number_format($arrTmpStats[INVESTED]) . " rps</td>" .
                    "</tr>";
            }

            $researchHistoryTable =
                '<table cellspacing="0" cellpadding="0" class="medium">' .

                    "<tr class=\"header\">" .
                        "<th colspan=\"4\">Investment History</th>" .
                    "</tr>" .

                    "<tr class=\"subheader\">" .
                        "<th>" . "Tribe" . "</th>" .
                        "<th class=\"right\">" . "RPA" . "</th>" .
                        "<th class=\"right\">" . "Amount" . "</th>" .
                    "</tr>" .

                    $strTribesInvested .

                '</table>';
            echo $researchHistoryTable;

            echo '<div id="textMedium">' .
                    '<h3>What does it mean?</h3>' .
                    '<ul><li>RPA - Research Per Acre' . '</li>' .
                    '<li>rps - research points' . '</li><ul>' .
                 '</div>';

        break;
    }
}

function get_alliance_science_table(&$objSrcAlli)
{
    $arrAlli = $objSrcAlli->get_alliance_infos();
    $arrSci  = $objSrcAlli->get_alliance_sciences();

    // M: Get rounded %'s
    foreach ($arrSci as $str => $iBonus)
        $arrSciMod[$str] = round($iBonus * 100, 2);
    $arrSciMod['def'] = round($arrSciMod['def']);
    $arrSciMod['war'] = round($arrSciMod['war']);

    $left  = '<img src="' . HOST_PICS . 'bar_left.gif" alt="" />' .
             '<img src="' . HOST_PICS . 'bar_mid.gif" height="12" width="';
    $right = '%" alt="" /><img src="' . HOST_PICS . 'bar_right.gif" alt="" /> ';

    $strSciTable =
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"3\">Alliance Research - " . stripslashes($arrAlli[NAME]) . " (#" . $arrAlli[ID] . ")</th>" .
            "</tr>" .

            "<tr class=\"subheader\">" .
                "<th width=\"33%\">" . "Branch" . "</th>" .
                "<th>" . "Amount" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Production " . "</th>" .
                "<td class=\"left\">" . $left . floor($arrSciMod['prod'] * 1.15 ) . $right . $arrSciMod['prod'] . " %</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Engineering " . "</th>" .
                "<td class=\"left\">" . $left . floor($arrSciMod['eng'] * 1.15 ) . $right . $arrSciMod['eng'] . " %</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "Defence Tactics " . "</th>" .
                "<td class=\"left\">" . $left . floor($arrSciMod['def'] * 1.15 ) . $right . $arrSciMod['def'] . " %</td>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<th>" . "War Tactics " . "</th>" .
                "<td class=\"left\">" . $left . floor($arrSciMod['war'] * 1.15 ) . $right . $arrSciMod['war'] . " %</td>" .
            "</tr>" .

            "<tr class=\"data\" style=\"line-height: 2em;\">" .
                "<th class=\"bsup\">" . "Alliance Surplus: " . "</th>" .
                "<td class=\"left bsup\">" . number_format($arrAlli['research']) . " rps" . "</td>" .
            "</tr>" .

        "</table>";
    return $strSciTable;
}

?>
