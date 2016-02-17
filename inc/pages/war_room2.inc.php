<?php
//******************************************************************************
// New war interface for elders                         February 28, 2008 Martel
//  other sources of the war system: /pages/alliance.inc.php,
//                                   /functions/war.php
//******************************************************************************
require_once('inc/functions/war.php');

function include_war_room2_text()
{
    // Secure / Validate Input
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        require_once('inc/pages/logout.inc.php');
        include_logout_text();
    }

    $objSrcUser = $GLOBALS['objSrcUser'];
    $strType    = $objSrcUser->get_stat('type');
    $objSrcAlli = $objSrcUser->get_alliance();
    $arrSrcWars = $objSrcAlli->get_wars();
    $iSrcTarget = $arrSrcWars['target'];

    // M: Surrender war
    if (isset($_POST['surrender']) && $iSrcTarget != 0 && isElder($strType))
    {
        $objTrgAlli = new clsAlliance($iSrcTarget);

        // Surrender
        $arrLosses = doWarSurrender($objSrcAlli, $objTrgAlli);
        $strLosses = getSurrenderReport($arrLosses);

        $strReport = "<p>You surrender in the war with alliance #$iSrcTarget!" .
                     "</p>" . $strLosses;
    }
    // M: Declare war
    elseif (isset($_POST['declare']) && intval($_POST['target']) > 0 && $iSrcTarget == 0 && isElder($strType))
    {
        // Check if war is possible
        $iNewTarget     = intval($_POST['target']);
        $objTrgAlli     = new clsAlliance($iNewTarget);
        $arrWarPossible = testWarPossible($objSrcAlli, $objTrgAlli);
        $bWarPossible   = $arrWarPossible[0];
        if ($_SERVER['SERVER_NAME'] != DEV_SERVER_NAME && !$bWarPossible)
        {
            $strReport = $arrWarPossible[1];
        }
        else
        {
            doWarDeclare($objSrcAlli, $objTrgAlli);

            $strReport = "<p>We have declared war with alliance #" .
                         $objTrgAlli->get_allianceid() . "!</p>";
        }
    }
    // M: Claim Victory
    elseif (isset($_POST['victory']) && $iSrcTarget != 0)
    {
        // Check if war is won
        $objTrgAlli = new clsAlliance($iSrcTarget);
        if (($arrGains = testWarVictory($objSrcAlli, $objTrgAlli)))
        {
            $strLosses = getVictoryReport($arrGains);
            $strReport = "<p>You have won the war with alliance #$iSrcTarget!" .
                         "</p>" . $strLosses;
        }
        else
        {
            $strReport =
            "<p>You can't claim victory since you haven't any war.</p>";
        }
    }
    // M: Accept truce
    elseif (isset($_POST['atruce']) && $iSrcTarget != 0 && isElder($strType))
    {
        $objTrgAlli = new clsAlliance($iSrcTarget);
        doWarTruce($objSrcAlli, $objTrgAlli);

        $strReport = "<p>You accept the truce and the war with alliance #" .
                     "$iSrcTarget is over.</p>";
    }
    // M: Withdraw truce
    elseif (isset($_POST['ctruce']) && $iSrcTarget != 0 && isElder($strType))
    {
        $objSrcAlli->set_war('truce_offer', 0);

        $strReport = "<p>We have withdrawn our offer to truce with alliance #" .
                     "$iSrcTarget.</p>";
    }
    // M: Offer truce
    elseif (isset($_POST['otruce']) && $iSrcTarget != 0 && isElder($strType))
    {
        $objSrcAlli->set_war('truce_offer', 1);

        $strReport =
        "<p>An offer to truce has been sent to alliance #$iSrcTarget.</p>";
    }
    else
    {
        header('location: main.php?cat=game&page=war_room');
        return;
    }

    // M: Show report
    echo
    '<div id="textMedium">' .
        '<h2>War Room Report</h2>' .
        $strReport .
        '<p><a href="main.php?cat=game&amp;page=war_room">Continue</a></p>' .
    '</div>';
}

function isElder($strType)
{
    if ($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME)
        return TRUE;
    else
        return ($strType == "elder" || $strType == "coelder");
}

function getVictoryReport($arrGains)
{
    // M: arrGains contains 2 arrays (identical to those in clsAlliance)
    $iResearch = array_sum($arrGains[0]); // Sums all branches into a total
    $arrMarket = $arrGains[1];

    $strMarket =
    '<h3>Alliance Market</h3>' .
    '<ul>' .
        '<li>' . '<strong>Money:</strong> <span class="positive">+' . number_format($arrMarket['money']) .
        ' cr' . '</span></li>' .

        '<li>' . '<strong>Food:</strong> <span class="positive">+' . number_format($arrMarket['food']) .
        ' kgs' . '</span></li>' .

        '<li>' . '<strong>Wood:</strong> <span class="positive">+' . number_format($arrMarket['wood']) .
        ' logs' . '</span></li>' .

        '<li>' . '<strong>Soldiers:</strong> <span class="positive">+' . number_format($arrMarket['soldiers']) .
        ' units' . '</span></li>' .
    '</ul>';

    $strResearch =
    '<h3>Alliance Research</h3>' .
    '<ul>' .
        '<li>' . '<strong>Total:</strong> <span class="positive">+' . number_format($iResearch) .
        ' rps' . '</span> (divided on all branches)</li>' .
    '</ul>';

    $strFame =
    '<h3>Fame</h3>' .
    '<ul>' .
        '<li>' . '<strong>Each tribe:</strong> <span class="positive">+' . number_format(WAR_VICTORY_FAME) .
        ' fame' . '</span></li>' .
    '</ul>';

    return $strMarket . $strResearch . $strFame;
}

function getSurrenderReport($arrLosses)
{
    // M: arrLosses contains 2 arrays (identical to those in clsAlliance)
    $iResearch = array_sum($arrLosses[0]); // Sums all branches into a total
    $arrMarket = $arrLosses[1];

    $strMarket =
    '<h3>Alliance Market</h3>' .
    '<ul>' .
        '<li>' . '<strong>Money:</strong> <span class="negative">-' . number_format($arrMarket['money']) .
        ' cr' . '</span></li>' .

        '<li>' . '<strong>Food:</strong> <span class="negative">-' . number_format($arrMarket['food']) .
        ' kgs' . '</span></li>' .

        '<li>' . '<strong>Wood:</strong> <span class="negative">-' . number_format($arrMarket['wood']) .
        ' logs' . '</span></li>' .

        '<li>' . '<strong>Soldiers:</strong> <span class="negative">-' . number_format($arrMarket['soldiers']) .
        ' units' . '</span></li>' .
    '</ul>';

    $strResearch =
    '<h3>Alliance Research</h3>' .
    '<ul>' .
        '<li>' . '<strong>Total:</strong> <span class="negative">-' . number_format($iResearch) .
        ' rps' . '</span>(divided on all branches)</li>' .
    '</ul>';

    $strFame =
    '<h3>Fame</h3>' .
    '<ul>' .
        '<li>' . '<strong>Each tribe:</strong> <span class="negative">-' . number_format(WAR_SURRENDER_FAME) .
        ' fame' . '</span></li>' .
    '</ul>';

    return $strMarket . $strResearch . $strFame;
}

?>
