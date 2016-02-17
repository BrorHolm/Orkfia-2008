<?php

function include_tribe_text()
{
    include_once('inc/functions/tribe.php');
    require_once('inc/classes/clsGame.php');

    $objGame     = new clsGame();
    $strSwitch   = $objGame->get_game_switch('update_button');

    $objSrcUser  = &$GLOBALS['objSrcUser'];
    $arrSrcStats = $objSrcUser->get_stats();
    $arrSrcUsers = $objSrcUser->get_user_infos();
    $objSrcAlli  = $objSrcUser->get_alliance();

    //==========================================================================
    // Elder Message
    //==========================================================================
    if (isset($objSrcAlli) && !empty($objSrcAlli))
        echo get_eldermessage_text($objSrcAlli);

    //==========================================================================
    // Free update button
    // 1st case, change for classic oop testing ## 2nd case: devork 1008 updates
    //==========================================================================
    if ($strSwitch == ON && $_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
    {
        $iHours = $arrSrcUsers[HOURS];
        if (isset($_POST['update']) && $iHours >= 0 && $iHours < PROTECTION_HOURS)
        {
            require_once('inc/functions/update_script.php');
            generate_updates($objSrcUser, 1);
            $iHours++;
        }

        if ($iHours >= 0 && $iHours < PROTECTION_HOURS)
        {
            echo
              '<form id="center" action="main.php?cat=game&amp;page=tribe" method="post">' .
                  '<input type="submit" name="update" value="Update Me" />' .
              '</form>';
        }
    }
    elseif ($strSwitch == ON && $_SERVER['SERVER_NAME'] == DEV_SERVER_NAME)
    {
        $iHours = $arrSrcUsers[HOURS];
        if (isset($_POST['update']) && $iHours >= 0 && $iHours < 1008)
        {
            require_once('inc/functions/update_script.php');
            generate_updates($objSrcUser, 1);
            $iHours++;
        }

        if ($iHours >= 0 && $iHours < 1008)
        {
            echo
              '<form id="center" action="main.php?cat=game&amp;page=tribe" method="post">' .
                  '<input type="submit" name="update" value="Update Me" />' .
              '</form>';
        }
    }

    //==========================================================================
    // Show Self Vision
    //==========================================================================
    // Link to Tribe News
    $dtLastNews = $arrSrcUsers[LAST_NEWS];
    $strNewsLink = '<br /><a href="main.php?cat=game&amp;page=news"';
    if ($dtLastNews == 0)
        $strNewsLink .= '>Tribe News</a>';
    else
        $strNewsLink .= ' class = "check_new">Our Tribe Has News!</a>';

    echo '<div class="tableLinkMedium">' .
             $strNewsLink . ' | ' .
             '<a href="main.php?cat=game&amp;page=advisors">Internal Affairs</a>' .
         '</div>';

    echo get_tribe_table($objSrcUser);

    //==========================================================================
    // Area with text below tribe table
    //==========================================================================
    $strText =
        '<div id="textMedium" style="clear: both;">' .
            '<h2>' .
                'Your head advisor greets you:' .
            '</h2>';

    // Check First 3 Logins prior to verification
    $strVerificationCode = $objSrcUser->get_preference(EMAIL_ACTIVATION);
    if ($arrSrcUsers[LOGINS] < 3 && $strVerificationCode != 'verified')
    {
        if ($arrSrcUsers[LOGINS] == 1)
            $strText .= '<p>Welcome to ORKFiA! You may login once more before you need to <a href="main.php?cat=game&amp;page=verify">verify your email address</a>.</p>';
        elseif ($arrSrcUsers[LOGINS] == 2)
            $strText .= '<p>Welcome to ORKFiA! Next time you login you will need to <a href="main.php?cat=game&amp;page=verify">verify your email address</a>.</p>';
    }

    // Check Protection Hours Remaining
    $strText .=
        '<p>' .
            obj_check_protection($objSrcUser, 'status') .
        '</p>';

    // Manual Session handler (warns 20 minutes prior to being logged out)
    $fourHoursAgo = date('Y-m-d H:i:s', strtotime('-4 hours 20 minutes'));
    if ($arrSrcUsers[LAST_LOGIN] < $fourHoursAgo)
    {
        $strText .=
        '<p>' .
            "... Less than 20 minutes before you are required to login again" .
            " ..." .
        '</p>';
    }

    // frost: added "accept truce" for elder
    // Martel: recoded to use objects
    $arrSrcWar  = $objSrcAlli->get_wars();
    if ($arrSrcWar[TARGET] != 0)
    {
        $objTrgAlli = new clsAlliance($arrSrcWar[TARGET]);
        $arrTrgWar = $objTrgAlli->get_wars();
        if ($arrTrgWar[TRUCE_OFFER] == 1)
        {
            $strText .=
                '<p>' .
                    'The enemy alliance (#' . $objTrgAlli->get_allianceid() .
                    ') has offered us a ' .
                    '<a href="main.php?cat=game&page=war_alliance">truce</a>.' .
                '</p>';
        }
        elseif ($arrSrcWar[TRUCE_OFFER] == 1)
        {
            $strText .=
                '<p>' .
                    'News from our war with alliance (#' . $objTrgAlli->get_allianceid() . '): Our alliance diplomats have been sent to negotiate a truce with the enemy.' .
                '</p>';
        }
    }

    // If account is paused
    if ($arrSrcUsers[PAUSE_ACCOUNT] > 1 && $arrSrcUsers[PAUSE_ACCOUNT] <= 48)
    {
        $strText .=
            '<p>' .
                'Your account is currently paused, it will be ' .
                'accessible for play in ' .
                ($arrSrcUsers[PAUSE_ACCOUNT] - 1) . ' updates.' . '<br />' .
                '(You may remain paused for longer if you so wish.)' .
            '</p>';
    }
    elseif ($arrSrcUsers[PAUSE_ACCOUNT] > 1 && $arrSrcUsers[PAUSE_ACCOUNT] > 48)
    {
        $strText .=
            '<p>' .
                'Your tribe is currently entering vacation mode, it will be ' .
                'fully protected in ' .
                ($arrSrcUsers[PAUSE_ACCOUNT] - 49) . ' updates.' . '<br />' .
                '(Until then you can be attacked by other players.)' .
            '</p>';
    }
    elseif ($arrSrcUsers[PAUSE_ACCOUNT] == 1)
    {
        $strText .=
            '<p>' .
                'Your account is currently paused but is accessible for ' .
                'play now. If you wish to leave protection go to Options -> ' .
                '<a href="main.php?cat=game&page=preferences&task=pause_account">' .
                'Pause Account</a>.' .
            '</p>';
    }

    $strText .=
        '<div style="float: left; margin: 0 0 10px 10px; text-align: center; overflow: hidden; border: 3px double #444; background: #FFF; height: 42px; width: 154px; ">' .
            '<a style="border: 0; display: block; color: #444; font: 15px Georgia, Times, serif; line-height: 17px; padding: 4px 0;" href="http://www.orkfiantimes.co.uk/" target="_blank">The Orkfian Times<br /><span style="font-size: 10px; border-top: #444 3px double; letter-spacing: 0.1pt;">A New Issue Every Week!</span></a>' .
        '</div>' .
        '<div style="float: left; margin: 0 0 10px 10px; text-align: center; overflow: hidden; border: 3px double #444; background: #FFF; height: 42px; width: 154px; ">' .
            '<a style="border: 0; display: block; color: #444; font: italic 16px \'Times new roman\'; line-height: 42px;" href="http://www.orkfiantimes.co.uk/faces.html" target="_blank">The Faces of ORKFiA</a>' .
        '</div>' .
        '<hr class="clear" style="visibility: hidden;">';

    echo $strText;

    // Voting Portal Links for ORKFiA
    $voteStatus = '';
    $check = md5($arrSrcStats['id'] . $arrSrcStats[RACE] . date('d'));
    if ($_SERVER['SERVER_NAME'] == 'orkfia.phpsupport.se')
    {
        $week  = date('W', strtotime('-12 hours'));

        if ($arrSrcUsers[HOURS] >= PROTECTION_HOURS)
            $iBonus = ($objSrcUser->get_build(LAND) * 1000);
        else
            $iBonus = 100000;

        if ($arrSrcStats[RACE] == "Dragon")
            $iBonus = floor($iBonus / 2);

        $strBonus = number_format($iBonus);

        if ($arrSrcStats[TWG_VOTE] == $week)
            $voteStatus = ' (already voted)';
        else
            $voteStatus = ' (<strong>' . $strBonus . ' cr</strong>).';
    }

    $strVoteLinks =
         '<p>Vote for us @ ' .
         '<a href="http://apexwebgaming.com/in/518" target="_blank">' .
         'Apex Web Gaming</a>' .

         ' and ' .

         '<a href="http://www.topwebgames.com/in.asp?id=744&amp;vuser=' .
         $arrSrcStats[ID] . '&amp;check=' . $check . '" target="_blank">' .
         'TWG' . '</a>' . $voteStatus . '</p>';
    echo $strVoteLinks;

    include_once('inc/functions/forums.php');
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME && get_sponsor_badge($arrSrcStats[ID]) == '')
    {
?>

        <hr />
        <h2>Become a Dragon - ORKFiA Classic</h2>
        <hr />

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: auto; margin-right: auto; text-align: center;">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
            <input type="hidden" name="undefined_quantity" value="1" />
            <input type="hidden" name="item_name" value="One Week Sponsorship" />
            <input type="hidden" name="item_number" value="Classic Dragon" />
            <input type="hidden" name="amount" value="2.00" />
            <input type="hidden" name="shipping" value="0.00" />
            <input type="hidden" name="no_shipping" value="1" />
            <input type="hidden" name="return" value="<?= HOST;?>main.php?cat=main&amp;page=sponsors&amp;thankyou" />
            <input type="hidden" name="cn" value="Message to admin" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="hidden" name="tax" value="0.00" />
            <input type="hidden" name="lc" value="SE" />
            <input type="hidden" name="bn" value="PP-BuyNowBF" />
            <table class="small" cellspacing="0" cellpadding="0">
                <tr class="header">
                    <th colspan="3">Become a Classic Dragon</th>
                </tr>
                <tr class="data">
                    <th>Rank 4:</th>
                    <td><span class="elder">Classic Dragon</span></td>
                    <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_classic.gif" alt="Dragon" /></td>
                </tr>
                <tr class="data">
                    <th>Donation:</th>
                    <td>$2 / week</td>
                </tr>
                <tr class="data">
                    <th><input type="hidden" name="on0" value="Login nick" /><label for="i4">Login nick:</label></th>
                    <td><input type="text" name="os0" id="i4" maxlength="60" value="<?= $arrSrcUsers[USERNAME];?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>
        <p>Help us advertise and reduce the server costs. <a href="main.php?cat=game&amp;page=sponsors">Become a Dragon</a> to support ORKFiA Classic.</p>

<?php
    }
    elseif ($_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME && get_sponsor_badge($arrSrcStats[ID]) == '')
    {
        include_once('inc/pages/sponsors.inc.php');
        echo show_sponsor_options($arrSrcUsers[USERNAME]);
    }
    elseif (($strBadge = get_sponsor_badge($arrSrcStats[ID])) != '')
    {
        echo '<hr />' .
             '<h2>Thank you for supporting ORKFiA!</h2>' .
             '</hr />' .
             '<div class="center">' . $strBadge . '</div>';
    }

    echo
        '</div>';

    //==========================================================================
    // Admin Message (message of the day)
    //==========================================================================
    $iStatus = $arrSrcUsers[STATUS];
    if ($iStatus == 2)
    {
        echo '<br /><br />';
        include_once('inc/pages/motd.inc.php');
        include_motd_text();
    }
    else
    {
        echo '<br />' .
             '<div class="center">' . '<a href="main.php?cat=game&amp;page=motd">' .
             'View Admin Message' . '</a></div>';
    }

    //==========================================================================
    // Species5618: added a safety-mechanism for the doubleclickprotection on
    // magic, switch will be set to free whenever a tribe looks at the tribepage
    //==========================================================================
    $objSrcUser->set_spell(CASTING_NOW, "'free'");

    //==========================================================================
    // Empty Database from Deleted Tribes (These are "moved" to alliance #0)
    //==========================================================================
    include_once('inc/classes/clsAlliance.php');
    $objTmpAlliance = new clsAlliance(0);
    $objTmpAlliance->delete_users();
}

?>
