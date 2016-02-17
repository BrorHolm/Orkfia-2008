<?php
//******************************************************************************
// pages thievery.inc.php                                  Martel, July 12, 2006
// History:
// Gotland: Rewrite to use user objects
// Martel: Structured and shapened up some further. -July 12, 2006
//******************************************************************************
include_once('inc/functions/ops.php');
include_once('inc/functions/thievery.php');

function include_thievery_text()
{
    global  $Host, $opname, $action, $basecost;

    $objSrcUser  = &$GLOBALS["objSrcUser"];
    $iSrcId      = $objSrcUser->get_userid();
    $arrSrcStats = $objSrcUser->get_stats();

    //==========================================================================
    // M: Verify Alliance & Tribe ID from GET or POST
    //==========================================================================
    $iTrgAid = $arrSrcStats[ALLIANCE];
    if (isset($_GET['kd']))
        $iTrgAid = intval($_GET['kd']);
    elseif (isset($_POST['kd']))
        $iTrgAid = intval($_POST['kd']);

    if ($iTrgAid < 11)
        $iTrgAid = rand(11,100);

    if (isset($_GET['tribe']))
        $_GET['tribe'] = intval($_GET['tribe']);

    //==========================================================================
    // Stop people from avoiding the tribe page so they dont get updated
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser);

    include_once("inc/functions/races.php");
    $arrUnitNames = getUnitVariables($objSrcUser->get_stat(RACE));
    $arrUnitNames = $arrUnitNames['output'];

    set_op_vars();
    $land      = $objSrcUser->get_build(LAND);
    $thieves   = $objSrcUser->get_army(UNIT5);
    $tpa       = floor( $thieves / $land );
    $credits   = floor($objSrcUser->get_thievery(CREDITS))  ;
    $op_growth = obj_thief_op_growth($objSrcUser);
    $hideouts  = $objSrcUser->get_build(HIDEOUTS);

    // frost: age 18 mori hideout/home bonus, updated age 19 (Martel)
    if ($objSrcUser->get_stat(RACE) == "Mori Hai")
    {
        $homes = $objSrcUser->get_build(HOMES);
        $max_tp = floor((1 + ($hideouts+($homes/2.5)) * 2 / $land) * ($hideouts+($homes/2.5)));
    }
    else
    {
        $max_tp = floor((1 + $hideouts * 2 / $land) * $hideouts);
    }

    if(!$iTrgAid)
    {
        $iTrgAid = $objSrcUser->get_stat(KINGDOM);
    }

    echo $topLinks =
        '<div class="center">' .
            '<a href="main.php?cat=game&amp;page=mystic">Mystics</a> | ' .
            '<b>Thievery</b> | ' .
            '<a href="main.php?cat=game&amp;page=invade">Invasion</a>' .
        '</div>';

    //added templar check - AI
    if ($objSrcUser->get_stat(RACE) == "Templar")
    {
        echo '<div id="textMedium"><p>' .
            'Your proud Templar people will not lower themselves to thievery practices.' .
            '</p></div>';

    } else {
?>
<div id='textBig'>
    <p>
    <img src="<?= $Host; ?>thief.gif" style="float: left; margin-right: 10px;" alt="" />
    <b>The thief</b> greets you cooly:<br />
<?php
    if ($arrUnitNames[6] =='Thief') {$arrUnitNames[6] = 'Thieve';}
?>
    Normally <?= strtolower($arrUnitNames[6]); ?>s are considered scum and not wanted in a tribe, but in war-times you need every asset available. <?= $arrUnitNames[6]; ?>s can be useful in many ways, and the more of them you have on your lands, the safer you will be.
    </p>
<?php

    echo "<p>" .
            "Our hideouts can provide a maximum of <b>$max_tp thievery points</b>, right now " .
            "there are <b class=\"indicator\">" . $credits .
            "</b> left unused. Assuming you find any use for your thieves they will " .
            "provide you with <b>" . $op_growth . "</b> new thievery points each month. " .
            "With all your thieves at home we are protected by <b class=\"indicator\">$tpa TPA</b> (thieves per acre). " .
        "</p></div>";


    //==========================================================================
    // M: Thievery Table
    //==========================================================================

    echo $strColumnDivs =
        '<div id="columns">' .
            '<!-- Start left column -->' .
            '<div id="leftcolumn">';

    // Advisor Link
    echo $advisorLink =
        '<br />' .
        '<div class="tableLinkSmall">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=actions">The Thief</a>' .
        '</div>';

?>

        <table width="40%" cellpadding="0" cellspacing="0" class="small">
            <form action="main.php?cat=game&amp;page=thievery" method="post">
            <tr class="header">
                <th colspan="2"> Thievery </th>
            </tr>
            <tr class="subheader">
                <th colspan="2" class="center"> Select Target </th>
            </tr>
            <tr class="data">
                <th> Alliance: </th>
                <td>
                        <input maxlength="4" size="3" name="kd" value="<?=$iTrgAid; ?>" />
                        <input type="submit" value="Change" />
                </td>
            </tr>
            </form>
            <tr class="data">
                <form action="main.php?cat=game&amp;page=thievery2" method="post">
                <th> Tribe: </th>
                <td>
                    <select size="1" name="dplayer">
                        <option value="<?=$objSrcUser->get_userid(); ?>"></option>
<?php

    //==========================================================================
    // New version of Damadm00's code                      Martel, July 10, 2006
    //==========================================================================
    include_once('inc/classes/clsAlliance.php');
    $objTrgAlliance = new clsAlliance($iTrgAid);
    $arrTrgIUsers   = $objTrgAlliance->get_userids();

    $tableTarget = '';
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

    echo $tableTarget;

?>

                    </select>
                </td>
            </tr>
            <tr class="data">
                <td colspan="4" class="right">
                    <select size="1" name="op">

<?php

$op_level = get_op_level($objSrcUser);
for ($i = 1; $i <= $op_level; $i++)
{
    if (! empty($opname[$i]))
    {
        $cost = get_op_cost($action[$i], $land);

        echo " <option value=\"$i\"> $opname[$i] - $cost</option>";
    }
}
?>
                </select>
            </td>
        </tr>
    </table>

<?php


    echo $strColumnDivs =
        '</div>' .
        '<!-- Start right column -->' .
        '<div id="rightcolumn">' .
        '<br />' .
        '<div class="tableLinkSmall">' .
            '<a href="main.php?cat=game&amp;page=advisors&amp;show=military">The General</a>' .
        '</div>';
;

?>

            <table class="small" width="60%" cellpadding="0" cellspacing="0">
                <tr class="header">
                    <th colspan="2">Prepare Thieves</th>
                </tr>
                <tr class="subheader">
                    <th>Instruction</th>
                    <td>Select</td>
                </tr>
                <tr class="data">
                    <th>Times to Run Operation:</th>
                    <td>
                        <select size="1" name="amount">
<?php
    for ($i = 1; $i <= MAX_THIEVE_OPS; $i++)
        echo " <option value='$i'> $i </option>";
?>
                        </select>
                    </td>
                </tr>
                <tr class="data">
                    <th>
                        <?= $arrUnitNames[6]; ?>s to Send:
                    </th>
                    <td>
                        <input name="amount_sent" size="5" value="1" />
                    </td>
                </tr>
                <tr class="data">
                    <th>Available:</th>
                    <td>
                        <?= number_format($objSrcUser->get_army_home(UNIT5)); ?>
                    </td>
                </tr>
                <tr class="data">
                    <th>Stop on Success:</th>
                    <td>
                        <input type="checkbox" name="stop" value="yes" />
                    </td>
                </tr>
            </table>
            <br />
            <input type="hidden" value="yes" name="SELF_CHECK" />
            <div class="center"><input type="submit" value="Send thieves on operation" /></div>
            </form>

<?php

    echo $strColumnDivs =
            '</div>' .
        '</div>';

}
} /* from the Templar check */
?>
