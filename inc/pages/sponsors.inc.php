<?php
function include_sponsors_text()
{
    global $cat;

    if (!isset($_GET['thankyou']))
    {
?>
    <div id="columns">
        <div id="leftcolumn">
        <div class="text" style="text-align: center; ">

        <h2>ORKFiA Dragons, week <?=date('W M Y');?></h2>
        <hr />
<?php

        if (($strDragon = get_sponsor_list('Golden Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span style="color: #FF6;">Golden Dragons</span></h3>' .
                '<p style="font-size: 1.7em; color: #FF6;">' .
                    $strDragon .
                '</p>';
            $strCheck = 'checked';
        }

        if (($strDragon = get_sponsor_list('Purple Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span class="admin">White Dragons</span></h3>' .
                '<p style="font-size: 1.7em; color: #96C;">' .
                    $strDragon .
                '</p>';
            $strCheck = 'checked';
        }

        if (($strDragon = get_sponsor_list('White Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span class="admin">White Dragons</span></h3>' .
                '<p style="font-size: 1.7em; color: #FFF;">' .
                    $strDragon .
                '</p>';
            $strCheck = 'checked';
        }

        if (($strDragon = get_sponsor_list('Black Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span style="color: #000;">Black Dragons</span></h3>' .
                '<p style="font-size: 1.7em; color: #000;">' .
                    $strDragon .
                '</p>';
            $strCheck = 'checked';
        }

        if (($strDragon = get_sponsor_list('Classic Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span class="elder">Classic Dragons</span></h3>' .
                '<p style="font-size: 1.7em;"><span class="elder">' .
                    $strDragon .
                '</span></p>';
            $strCheck = 'checked';
        }

        if (($strDragon = get_sponsor_list('Blue Dragon')) != '')
        {
            echo $strSponsors =
//                 '<h3><span class="player">Blue Dragons</span></h3>' .
                '<p style="font-size: 1.7em; color: lightblue;">' .
                    $strDragon .
                '</p>';
            $strCheck = 'checked';
        }

        if (!isset($strCheck))
            echo '<p style="font-size: 1.7em;"><em>No sightings yet this week</em></p>';

?>
        </div>
        </div>
        <div id="rightcolumn">
        <div class="text" style="text-align: center; border-top: 0;">
<?php

        // The paypal donation options
        if ($cat == 'game')
        {
            $objSrcUser = $GLOBALS['objSrcUser'];
            echo show_sponsor_options($objSrcUser->get_user_info(USERNAME));
        }
        else
            echo show_sponsor_options();
?>

        </div>
        </div>
    </div>

<?php
    }
    else
    {
?>
    <div id="textMedium" style="margin: 3% auto; text-align: left;">

        <h2 style="text-align: center;"><img src="<?=HOST_PICS;?>first_sponsors.gif" alt="Sponsors" /></h2>

        <p>We thank you for your kind donation and welcome you to our ORKFiA
        sponsor list <?echo date('Y');?>.</p>

        <p>As this game is free to play, donations are extremely appreciated.</p>

        <p>Sincerely,<br />
        Martel, the ORKFiA Staff Team, and all your fellow players</p>

        <p><a href="main.php?cat=main&amp;page=sponsors">View Sponsors</a></p>

   </div>

<?php
    }
}

function get_sponsor_list($strItemId = 'Blue Dragon')
{
    $strReturn = '';
    $objTmpUser = new clsUser(1);

    $resSQL = mysql_query("SELECT item_number as rank, quantity, option_selection1 as username, unix_timestamp, payment_gross as money FROM phpsuppo_3.paypal WHERE (item_name = 'One Week Sponsorship' OR item_name = 'Three Months Sponsorship') AND item_number = '$strItemId' AND payment_status = 'Completed' ORDER BY unix_timestamp DESC") or die(mysql_error());
    while ($arrPayPal = mysql_fetch_array($resSQL))
    {
        if (check_valid_sponsor($arrPayPal) && $arrPayPal['username'] != 'NULL')
        {
            $strSafe = quote_smart($arrPayPal['username']);
            $resSQL2  = mysql_query("SELECT id FROM " . TBL_USER .
                        " WHERE username = $strSafe") or die(mysql_error());
            $arrRes2  = mysql_fetch_array($resSQL2);
            if ($arrRes2[ID] > 0)
            {
                $objTmpUser->set_userid($arrRes2[ID]);
                $strReturn .= stripslashes($objTmpUser->get_stat(NAME)) . '<br />';
            }
            else
            {
                // Missing an acc in one server, or if deleted (missing user)
//                 $strReturn .= 'Fierce ' . $arrPayPal['rank'] . '<br />';
            }

        }
        elseif (check_valid_sponsor($arrPayPal))
        {
            $strReturn .= 'Anonymous ' . $arrPayPal['rank'] . '<br />';
        }

    }

    return $strReturn;
}

function check_valid_sponsor($arrPayPal)
{
    $strRank     = $arrPayPal['rank'];
    $iQty        = $arrPayPal['quantity'];
    $strUser     = $arrPayPal['username'];
    $iUnix       = $arrPayPal['unix_timestamp'];
    $iSum        = $arrPayPal['money'];
    $iMoney      = $iSum / $iQty;
    $iUnixExpire = strtotime("+$iQty weeks", $iUnix);

//     if ($strRank == 'Classic Dragon')
//         $iUnixExpire = strtotime("+" . ($iQty * 3) . " months", $iUnix);

    $arrAvailable = array('Blue Dragon', 'Classic Dragon', 'White Dragon', 'Purple Dragon', 'Black Dragon', 'Golden Dragon');
    $arrMoneyOptions = array(1, 3, 2, 10, 3, 4);
    if ($iUnixExpire > time() && in_array($strRank, $arrAvailable) && in_array($iMoney, $arrMoneyOptions))
    {
        $iValidSum = 99999.99;
        if ($strRank == 'Blue Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[0];
        elseif ($strRank == 'White Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[1];
        elseif ($strRank == 'Black Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[1];
        elseif ($strRank == 'Classic Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[2];
        elseif ($strRank == 'Golden Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[3];
        elseif ($strRank == 'Purple Dragon')
            $iValidSum = $iQty * $arrMoneyOptions[4];

        if ($iSum >= $iValidSum) // Everything is in order
            return TRUE;
        else
            return FALSE;
    }
    else
    {
        return FALSE;
    }
}

function show_sponsor_options($strLogin = '')
{
?>
        <hr />
        <h2>Donate &#8211;<em>become a dragon</em></h2>
        <hr />

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: auto; margin-right: auto; text-align: center;">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
            <input type="hidden" name="undefined_quantity" value="1" />
            <input type="hidden" name="item_name" value="One Week Sponsorship" />
            <input type="hidden" name="item_number" value="Blue Dragon" />
            <input type="hidden" name="amount" value="1.00" />
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
                    <th colspan="3">Become a Blue Dragon</th>
                </tr>
                <tr class="data">
                    <th>Rank 5:</th>
                    <td><span class="player">Blue Dragon</span></td>
                    <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_blue.gif" alt="Dragon" /></td>
                </tr>
                <tr class="data">
                    <th>Donation:</th>
                    <td>$1 / week</td>
                </tr>
                <tr class="data">
                    <th><input type="hidden" name="on0" value="Login nick" /><label for="i5">Login nick:</label></th>
                    <td><input type="text" name="os0" id="i5" maxlength="60" value="<?=  $strLogin;?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>

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
                    <td><input type="text" name="os0" id="i4" maxlength="60" value="<?=  $strLogin;?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: auto; margin-right: auto; text-align: center;">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
            <input type="hidden" name="undefined_quantity" value="1" />
            <input type="hidden" name="item_name" value="One Week Sponsorship" />
            <input type="hidden" name="item_number" value="Black Dragon" />
            <input type="hidden" name="amount" value="3.00" />
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
                    <th colspan="3">Become a Black Dragon</th>
                </tr>
                <tr class="data">
                    <th>Rank 3:</th>
                    <td><span style="color: #000;">Black Dragon</span></td>
                    <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_black.gif" alt="Dragon" /></td>
                </tr>
                <tr class="data">
                    <th>Donation:</th>
                    <td>$3 / week</td>
                </tr>
                <tr class="data">
                    <th><input type="hidden" name="on0" value="Login nick" /><label for="i3">Login nick:</label></th>
                    <td><input type="text" name="os0" id="i3" maxlength="60" value="<?=  $strLogin;?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: auto; margin-right: auto; text-align: center;">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
            <input type="hidden" name="undefined_quantity" value="1" />
            <input type="hidden" name="item_name" value="One Week Sponsorship" />
            <input type="hidden" name="item_number" value="Purple Dragon" />
            <input type="hidden" name="amount" value="4.00" />
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
                    <th colspan="3">Become a Purple Dragon</th>
                </tr>
                <tr class="data">
                    <th>Rank 2:</th>
                    <td><span style="color: #96C;">Purple Dragon</span></td>
                    <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_purple.gif" alt="Dragon" /></td>
                </tr>
                <tr class="data">
                    <th>Donation:</th>
                    <td>$4 / week</td>
                </tr>
                <tr class="data">
                    <th><input type="hidden" name="on0" value="Login nick" /><label for="i2">Login nick:</label></th>
                    <td><input type="text" name="os0" id="i2" maxlength="60" value="<?=  $strLogin;?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: auto; margin-right: auto; text-align: center;">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
            <input type="hidden" name="undefined_quantity" value="1" />
            <input type="hidden" name="item_name" value="One Week Sponsorship" />
            <input type="hidden" name="item_number" value="Golden Dragon" />
            <input type="hidden" name="amount" value="10.00" />
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
                    <th colspan="3">Become a Golden Dragon</th>
                </tr>
                <tr class="data">
                    <th>Rank 1:</th>
                    <td><span style="color: #FF6;">Golden Dragon</span></td>
                    <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_golden.gif" alt="Dragon" /></td>
                </tr>
                <tr class="data">
                    <th>Donation:</th>
                    <td>$10 / week</td>
                </tr>
                <tr class="data">
                    <th><input type="hidden" name="on0" value="Login nick" /><label for="i1">Login nick:</label></th>
                    <td><input type="text" name="os0" id="i1" maxlength="60" value="<?=  $strLogin;?>" /></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Sponsor ORKFiA for 1 week" />
        </form>

        <!--p style="text-align: justify;">
            As you know ORKFiA now resides on a dedicated server on its own with
            a nigh unlimited bandwidth. To help pay for this fantastic
            platform you are invited to support our continued play with a
            small donation. As a thank you, you will receive a glorious "Dragon Sponsor"
            icon next to your name in the game forums, and your name will be
            included in our Hall of Benevolent Glory.
        </p-->

        <h2>FAQ</h2>

        <p>
            1 - <strong>Can I sponsor ORKFiA for more than 1 week?</strong><br />
            Why sure! After you have clicked one of the buttons above, you may
            <em>change quantity (Qty)</em> from &#8216;1&#8217; to the number of weeks you
            wish to appear as a sponsor, and then press the button <em>Update
            Totals</em>.
        </p>
<?php
}

?>