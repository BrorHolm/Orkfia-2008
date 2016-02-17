<?php

function include_mystic2_text()
{
    include_once("inc/functions/magic.php");
    include_once("inc/functions/spells.php");
    include_once("inc/functions/news.php");

    $strSpellName       = strval($_POST["selSpellname"]);
    $intSpellTimes      = intval($_POST["txtAmount"]);
    $iTrgUserid         = intval($_POST["selTarget"]);
    $minHours           = intval($_POST["minHours"]);

    $blnStopOnSuccess   = FALSE;
    if (isset($_POST["chkStop"]) && $_POST["chkStop"] == "yes")
        $blnStopOnSuccess = TRUE;

    // Species5618 19-2-2004: added processing of Minimum hours checkbox
    $blnMinHours        = FALSE;
    if (isset($_POST["chkMin"]) && $_POST["chkMin"] == "yes")
        $blnMinHours = TRUE;

    $objSrcUser   = &$GLOBALS["objSrcUser"];
    $arrSrcStats  = $objSrcUser->get_stats();

    $arrSpells    = set_spell_vars($objSrcUser);
    $strSpellType = $arrSpells[$strSpellName]['type'];

    // Can't cast damaging spells in protection
    if ($arrSpells[$strSpellName]['fame'] > 0)
       obj_check_protection($objSrcUser, "magic");

    if (empty($iTrgUserid) || $iTrgUserid == $objSrcUser->get_userid())
    {
        if ($strSpellType == SPELL_SELF || $strSpellType == SPELL_ALLIANCE)
        {
            $iTrgUserid = $objSrcUser->get_userid();
        }
        else
        {
            ECHO '<div class="center">Please select a target.</div>';

            include_game_down();
            exit;
        }
    }

    include_once('inc/functions/update.php');
    check_to_update($iTrgUserid);

    $objTrgUser = new clsUser($iTrgUserid);
    $arrTrgStats = $objTrgUser->get_stats();

    include_once("inc/functions/war.php");
    $modifier = war_alli($arrSrcStats[ALLIANCE], $arrTrgStats[ALLIANCE]);
    if ($modifier < 1 && $strSpellType == SPELL_WAR)
    {
        echo
            '<div class="center">' .
                'War only spell, you cannot cast it on this target!' .
            '</div>';

        include_game_down();
        exit;
    }

    // M: Allow self spells even if target is paused
    if (($arrTrgStats[KILLED] > 0 || $arrTrgStats[RESET_OPTION] == 'yes') && $strSpellType != SPELL_SELF)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This tribe is dead or has reset.' .
            '<br /><br />' .
            '<a href="main.php?cat=game&amp;page=mystic">' . 'Try Again ?' . '</a>' .
        '</p></div>';
    }
    elseif ($objTrgUser->isPaused() && $strSpellType != SPELL_SELF)
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This tribe is paused.' .
            '<br /><br />' .
            '<a href="main.php?cat=game&amp;page=mystic">' . 'Try Again ?' . '</a>' .
        '</p></div>';
    }
    else
    {
        // Doubleclick-protection. Sets a field in the DB when the script loads,
        // field will be turned "off" when the thing is done.
        // Added by Species5618, 10-3-2004
        $magic_busy = $objSrcUser->get_spell(CASTING_NOW);
        if ($magic_busy == BUSY)
        {
            echo $strDiv =
                '<div class="center">' .
                    "Spells are being processed at the moment, please don't " .
                    "doubleclick the button, even though the server might not " .
                    "respond immediately." .
                    '<br /><br />' .
                    '<a href="main.php?cat=game&amp;page=mystic">' . 'Try Again ?' . '</a>' .
                '</div>';

            include_game_down();
            exit;
        }
        else
        {
            $objSrcUser->set_spell(CASTING_NOW, BUSY);

            make_magic2($objSrcUser, $iTrgUserid, $arrSpells, $strSpellName,
                        $intSpellTimes, $blnStopOnSuccess, $blnMinHours,
                        $minHours);
            // frost: 'free' casting_now is in inc/functions/magic.php, because
            // here it's getting ignored
        }
    }
}

?>
