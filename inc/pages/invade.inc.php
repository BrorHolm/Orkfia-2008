<?php
//******************************************************************************
// pages invade.inc.php                                    Martel, July 10, 2006
//******************************************************************************
include_once('inc/functions/invade.php');

function include_invade_text()
{
    global $Host;

    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $arrSrcStats = $objSrcUser->get_stats();

    //==========================================================================
    // M: Verify Alliance & Tribe ID from GET or POST
    //==========================================================================
    $iTrgAid = $arrSrcStats[ALLIANCE];
    if (isset($_GET['atkid']))
        $iTrgAid = intval($_GET['atkid']);
    elseif (isset($_POST['iTrgAid']))
        $iTrgAid = intval($_POST['iTrgAid']);

    if ($iTrgAid < 11)
        $iTrgAid = rand(11,100);

    if (isset($_GET['tribe']))
        $_GET['tribe'] = intval($_GET['tribe']);

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser);

    //==========================================================================
    // M: Links at top of page
    //==========================================================================
    echo $topLinks =
        '<div class="center">' .
            '<a href="main.php?cat=game&amp;page=mystic">Mystics</a> | ' .
            '<a href="main.php?cat=game&amp;page=thievery">Thievery</a> | ' .
            '<b>Invasion</b>' .
        '</div>';

    //==========================================================================
    // M: Advisor Welcome Text
    //==========================================================================
    $advisorText =
        '<div id="textBig">' .
        '<p>' .
            '<img src="' . $Host . 'fighter.gif" style="float: left; margin-right: 10px;" alt="Fighter" />' .
            "<b>Your general</b> greets you: <br />" .
            "From this room we can order our military to attack other tribes. An " .
            "invasion is a way to expand your domains or take away someone " .
            "else's. Attacking someone is often considered an \"act of war\" as " .
            "no tribe appreciates losing their acres to the enemy, so be prepared " .
            "for retaliations!" .
        "</p>" .
        "<p>" .
        "The way your military fight depends on which strategy you're using. " .
        "There are several different ways to engage an enemy, either " .
        "to conquer acres of land or to inflict damage." .
        "</p>";
    if ($arrSrcStats[RACE] == "Oleg Hai")
    {
        $advisorText .= "<p> And don't forget, only harpies that has been bought this month (update) will join any invasions.</p>";
    }
    $advisorText .= "</div>";
    echo $advisorText;

    //==========================================================================
    // M: Invasion Table
    //==========================================================================

    echo $strColumnDivs =
        '<div id="columns">' .
            '<!-- Start left column -->' .
            '<div id="leftcolumn">';

    // Advisor Link
    echo $advisorLink =
        '<br />' .
        '<div class="tableLinkSmall">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=military">The General</a>' .
        '</div>';

    $strInvasionTable =
         '<table width="40%" cellpadding="0" cellspacing="0" class="small">' .
            '<tr class="header">' .
                '<th colspan="2">' . "Invasion" . '</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th colspan="2" class="center">' . "Select Target" . '</th>' .
            '</tr>' .
            '<tr class="data">' .
                '<form action="main.php?cat=game&amp;page=invade" method="post">' .
                '<input type="hidden" name="iTrgAid" value="' . $iTrgAid . '" />' .
                '<th>' . "Alliance:" . '</th>' .
                '<td>' . '<input maxlength="4" size="3" name="iTrgAid" value="' .
                         $iTrgAid . '" />' . "&nbsp;" .
                         '<input type="submit" value="Change" />' .
                '</td>' .
                '</form>' .
            '</tr>';

    if ($iTrgAid != $arrSrcStats[ALLIANCE] && $iTrgAid > 10)
    {
        $strInvasionTable .=
            '<tr class="data">' .
                '<form action="main.php?cat=game&amp;page=invade2" method="post">' .
                '<th>' . "Tribe:" . '</th>' .
                '<td>' . '<select size="1" name="TrgPlayer">' .
                         '<option value="spacer">' . '</option>';

        //======================================================================
        // New version of Damadm00's code                  Martel, July 10, 2006
        //======================================================================
        include_once('inc/classes/clsAlliance.php');
        $objTrgAlliance = new clsAlliance($iTrgAid);
        $arrTrgIUsers   = $objTrgAlliance->get_userids();

        if (!empty($arrTrgIUsers))
        {
            foreach ($arrTrgIUsers as $iUserId)
            {
                $objTmpUser = new clsUser($iUserId);
                $strTribe   = stripslashes($objTmpUser->get_rankings_personal(TRIBE_NAME));

                if (isset($_GET['tribe']) && $_GET['tribe'] == $iUserId)
                {
                    $strInvasionTable .=
                        sprintf('<option value="%d" selected>%s</option>', $iUserId ,$strTribe);

                }
                elseif (!isset($_GET['tribe']) || (isset($_GET['tribe']) && $_GET['tribe'] != $iUserId))
                {
                    $strInvasionTable .=
                        sprintf('<option value="%d">%s</option>', $iUserId ,$strTribe);
                }
                elseif (isset($_GET['tribe']))
                {
                    echo "Trying to exploit bugs/loopholes will get you suspended!";
                }
            }
        }

        $strInvasionTable .=
                        '</select>' .
                    '</td>' .
                '</tr>' .
                '<tr class="data">' .
                    '<th>' . "Strategy:" . '</th>' .
                    '<td>' .
                        '<select size="1" name="invade_type">' .
                        '<option value="' . ATTACK_STANDARD .
                            '">' . "Standard Attack" . '</option>' .
                        '<option value="' . ATTACK_RAID .
                            '">' . "Raid" . '</option>' .
                        '<option value="' . ATTACK_BARREN .
                            '">' . "Barren Grab" . '</option>' .
                        '<option value="' . ATTACK_HNR .
                            '">' . "Hit 'n' Run" . '</option>' .
                        '<option value="' . ATTACK_BC .
                            '">' . "Blasphemy Crusade" . '</option>' .
                        '</select>' .
                    '</td>' .
                '</tr>';
    }

    $strInvasionTable .=
            '</table>';

    // M: Show Invasion Table
    echo $strInvasionTable;

    echo $strColumnDivs =
        '</div>' .
        '<!-- Start right column -->' .
        '<div id="rightcolumn">' .
            '<br /><br />';

    if ($iTrgAid != $arrSrcStats[ALLIANCE] && $iTrgAid > 10)
    {

        $strPrepareTable =
             '<table cellpadding="0" cellspacing="0" class="small">' .
                '<tr class="header">' .
                    '<th colspan="3">' . "Prepare Army" . '</th>' .
                '</tr>' .
                '<tr class="subheader">' .
                    '<th>' . "Units" . '</th>' .
                    '<th class="right">' . "Owned" . '</th>' .
                    '<th class="right">' . "To Send" . '</th>' .
                '</tr>';

        // M: Get All Units with an offence Value
        include_once('inc/functions/races.php');
        $arrUnitVars    = getUnitVariables($arrSrcStats[RACE]);
        $arrUnitOffence = $arrUnitVars['offence'];
        $arrUnitDefence = $arrUnitVars['defence'];
        $arrUnitVar     = $arrUnitVars['variables'];
        $arrUnitName    = $arrUnitVars['output'];
        $arrArmyHome    = $objSrcUser->get_armys_home();

        foreach ($arrUnitOffence as $i => $iUnitOffence)
        {
            if ($iUnitOffence > 0)
            {
                // M: Military Unit Names Grammar
                $strPlural = 's';
                if ($arrUnitName[$i] == 'Swordmen') { $strPlural = '';}
                if ($arrUnitName[$i] == 'Pikemen') { $strPlural = '';}
                if ($arrUnitName[$i] == 'Crossbowmen') { $strPlural = '';}
                if ($arrUnitName[$i] == 'Longbowmen') { $strPlural = '';}
                if ($arrUnitName[$i] == 'Thief') {$arrUnitName[$i] = 'Thieve';}
                if ($arrUnitName[$i] == 'Priestess') { $strPlural = '';}
                if ($arrUnitName[$i] == 'Mummy') { $arrUnitName[$i] = 'Mummie';}

                // Oleg Hai Exception (Elites are Mercenaries)
                if ($arrSrcStats[RACE] == "Oleg Hai" && $i == 5)
                    $arrArmyHome[$i] = $objSrcUser->get_army_merc(MERC_T3);

                $strPrepareTable .=
                '<tr class="data">' .
                    '<th>' . sprintf('%s%s <span class="militstats">(%d/%d)</span>',
                                     $arrUnitName[$i], $strPlural,
                                     $arrUnitOffence[$i], $arrUnitDefence[$i]) .
                    '</th>' .
                    '<td>' . $arrArmyHome[$arrUnitVar[$i]] . '</td>' .
                    '<td>' . '<input size="6" maxlength="7" name="arrArmySent[' .
                             $arrUnitVar[$i] . ']" value="0" />' . '</td>' .
                "</tr>";
            }
        }

        $strPrepareTable .=
                '</table>' .
                '<br />' .
                '<input type="hidden" name="iTrgAid" value="$iTrgAid" />' .
                '<div class="center"><input type="submit" value="Order army into battle" /></div>' .
            '</form>';
    }

    // M: Show Prepare Table
    if (isset($strPrepareTable))
    {
        echo $strPrepareTable;
    }

    echo $strColumnDivs =
            '</div>' .
        '</div><div class="clear"><hr /></div>';
}

?>

