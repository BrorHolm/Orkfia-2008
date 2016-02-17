<?php
//******************************************************************************
// pages mystic.inc.php                                    Martel, July 12, 2006
//******************************************************************************
include_once("inc/functions/magic.php");
include_once("inc/functions/spells.php");

function include_mystic_text()
{
    global $Host;

    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $iSrcId      = $objSrcUser->get_userid();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrSrcBuild = $objSrcUser->get_builds();

    //==========================================================================
    // M: Verify Alliance & Tribe ID from GET or POST
    //==========================================================================
    $iTrgAid = $arrSrcStats[ALLIANCE];
    if (isset($_GET['magekd']))
        $iTrgAid = intval($_GET['magekd']);
    elseif (isset($_POST['magekd']))
        $iTrgAid = intval($_POST['magekd']);

    if ($iTrgAid < 11)
        $iTrgAid = rand(11,100);

    if (isset($_GET['tribe']))
        $_GET['tribe'] = intval($_GET['tribe']);

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser);

    $iMageLevel  = get_mage_level($objSrcUser);
    $growth      = mage_power_growth($iSrcId);
    $arrSpells   = set_spell_vars($objSrcUser);
    //==========================================================================
    // Remove 'casting now' from here, so just clicking try again should fix
    //  their problem                                              - AI 30/09/06
    //==========================================================================
    $objSrcUser->set_spell(CASTING_NOW, "'free'");

    //==========================================================================
    // Max Mana Points for Output
    //==========================================================================
    $max_mp = ( 1 + $arrSrcBuild[GUILDS] / (2 * $arrSrcBuild[LAND]) )
              * $arrSrcBuild[GUILDS];
    if ($arrSrcStats[RACE] == "Eagle")
        $max_mp *= 1.3;
    $max_mp = round($max_mp);

    //==========================================================================
    // M: Links at top of page
    //==========================================================================
    echo $topLinks =
        '<div class="center">' .
            '<strong>Mystics</strong> | ' .
            '<a href="main.php?cat=game&amp;page=thievery">Thievery</a> | ' .
            '<a href="main.php?cat=game&amp;page=invade">Invasion</a>' .
        '</div>';

    //==========================================================================
    // M: Advisor Welcome Text
    //==========================================================================
    $advisorText =
        '<div id="textBig">' .
        '<p>' .
            '<img src="'.$Host.'wizard.gif" style="float: left; margin-right: 10px;" alt="Fighter" />' .
            "<strong>Your mage</strong> greets you: <br />" .
            "Magic can be a useful and powerful tool to for both offensive " .
            "and defensive purposes. While a SELF-spell can be a blessing, an " .
            "attack from high level mystics can be devastating. Build more " .
            "academies as you grow and we will be able to channel more and " .
            "stronger spells." .
        "</p>" .
        "<p>" .
            "Our guilds can hold a maximum of <strong>$max_mp mana points</strong>, right now " .
            "they hold <strong class=\"indicator\">" . $objSrcUser->get_spell(POWER) .
            "</strong>. According to our estimation we will " .
            "regenerate <strong>" . $growth . "</strong> per month." .
        "</p>" .
        "<p>";
    if ($arrSrcStats[RACE] == 'Templar')
    {
        $advisorText .=
            'You have <strong class="indicator">' .
            round($arrSrcBuild[ACADEMIES] * 100 / $arrSrcBuild[LAND],0) .
            '</strong> academies per 100 acres and <strong class="indicator">' .
            round($objSrcUser->get_army_home(UNIT5) / ( $arrSrcBuild[LAND] * 1.5 ),0) .
            '</strong> mystics per 0.66 acres.<br />';
    }
    $advisorText .=
            'Your mystics are capable of using <strong class="indicator">level ' .
            $iMageLevel . '</strong> spells.' .
        "</p>" .
        "</div>";

    echo $advisorText;
/*
    ?>
    <table class="mini" cellspacing="0" cellpadding="0">
        <tr class="header">
            <th colspan="2">Quick Stats</td>
        </tr>
        <?
    ECHO '
        <tr class="data">
            <th>Mage Level:</th>
            <td>' . $iMageLevel . '</td>
        </tr>
        <tr class="data">
            <th>Mana Points:</th>
            <td>' . $objSrcUser->get_spell(POWER) . '</td>
        </tr>
        <tr class="data">
            <th>Regrowth:</th>
            <td>+' . $growth . '</td>
        </tr>
        <tr class="data">
            <th>Max MPs:</th>
            <td>' . $max_mp . '</td>
        </tr>';
    ?>
    </table>
    <br /><br />
    <?
*/

    //==========================================================================
    // M: Mystics Table
    //==========================================================================

    echo $strColumnDivs =
        '<div id="columns">' .
            '<!-- Start left column -->' .
            '<div id="leftcolumn">';

    // Advisor Link
    echo $advisorLink =
        '<br />' .
        '<div class="tableLinkSmall">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=actions">The Mage</a>' .
        '</div>';

    $tableTarget = '
        <table width="40%" cellpadding="0" cellspacing="0" class="small">
            <form action="main.php?cat=game&amp;page=mystic" method="post">
            <tr class="header">
                <th colspan="2">Mystics</th>
            </tr>
            <tr class="subheader">
                <th colspan="2" class="center">Select Target</th>
            </tr>
            <tr class="data">
                <th>Alliance:</th>';

    $tableTarget .= '
                <td>
                    <input maxlength="4" size="3" name="magekd" value="' . $iTrgAid . '" />
                    <input type="submit" value="Change" />';

    if ($iTrgAid < 11) { $iTrgAid=rand(11,100); }

    $tableTarget .= '
                </td>
            </tr>
            </form>
            <tr class="data">
                <form action="main.php?cat=game&amp;page=mystic2" method="post">
                <th>Tribe:</th>
                <td><select size="1" name="selTarget"><option value="spacer"></option>';

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
                $tableTarget .=
                    sprintf('<option value="%d" selected>%s</option>', $iUserId ,$strTribe);

            }
            elseif (!isset($_GET['tribe']) || (isset($_GET['tribe']) && $_GET['tribe'] != $iUserId))
            {
                $tableTarget .=
                    sprintf('<option value="%d">%s</option>', $iUserId ,$strTribe);
            }
            elseif (isset($_GET['tribe']))
            {
                echo "Trying to exploit bugs/loopholes will get you suspended!";
            }
        }
    }

    $tableTarget .= '
                </select>
                </td>
            </tr>
            <tr class="data">
                <td colspan="2" class="right">
                    <select size="1" name="selSpellname">';

    echo $tableTarget;

    reset ($arrSpells);
    while (list ($strSpellName, $arrSpell) = each ($arrSpells))
    {
        /////
        //display spells
        //////
        if ($iMageLevel >= $arrSpells[$strSpellName]['level'] && in_array($arrSrcStats['race'], $arrSpells[$strSpellName]['race']) && $arrSrcBuild[LAND] >= $arrSpells[$strSpellName]['acres'])
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
            printf ("<option value=\"%s\">%s%s - %d</option>\r\n", $strSpellName, $arrSpells[$strSpellName]['display'],
            $type, $arrSpells[$strSpellName]['cost']);
        }
    }

    $tableTarget = '
                    </select>
                </td>
            </tr>
        </table>';

    echo $tableTarget;

    echo $strColumnDivs =
        '</div>' .
        '<!-- Start right column -->' .
        '<div id="rightcolumn">' .
            '<br /><br />';

    $tableTarget2 = '
        <table class="small" width="60%" cellpadding="0" cellspacing="0">
            <tr class="header">
                <th colspan="2">Prepare Mystics</th>
            </tr>
            <tr class="subheader">
                <th>Instruction</th>
                <td>Select</td>
            </tr>
            <tr class="data">
                <th>Times to Cast Spell:</th>
                <td><select size="1" name="txtAmount">';

    for ($i = 1; $i <= 20; $i++)
    {
        $tableTarget2 .= '<option value="'.$i.'"> '.$i.' </option>';
    }

    $tableTarget2 .= '
                </select>
                </td>
            </tr>
            <tr class="data">
                <th>Stop when spell succeeds for at least <input type="text" name="minHours" value="1" size="1" /> hours:</th>
                <td><input type="checkbox" name="chkMin" value="yes" /></td>
            </tr>
            <tr class="data">
                <th>Stop on Success:</th>
                <td><input type="checkbox" name="chkStop" value="yes" /></td>
            </tr>
    </table>';

    echo $tableTarget2;
?>
    <input type="hidden" value="yes" name="SELF_CHECK" />
    <br />
    <div class="center"><input type="submit" value='Request mystics to cast spell' /></div>
    </form>
<?php
    echo $strColumnDivs =
            '</div>' .
        '</div><div class="clear"><hr /></div>';
}
?>
