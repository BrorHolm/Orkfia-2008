<?php
//******************************************************************************
// Pages xmashidden.inc.php                             November 27, 2007 Martel
//******************************************************************************

function include_xmashidden_text()
{
    global  $cat;

    $iDay    = date('j');
    $iMonth  = date('n');
    $objUser = &$GLOBALS["objSrcUser"];

    // Decide wether to display form or not
    $iXmasDay = $objUser->get_stat('xmas_day');
    if ($iXmasDay == $iDay && $iMonth == 12 && $iDay == 24)
    {
        // User already had his gift today
        echo
            '<div id="textMedium">' .
            '<p>' .
                '<img src="' . HOST_PICS . 'xmas_l.gif" alt="" ' .
                'style="float: left; margin-right: 10px;" />' .
                'Congratulations, you got his last gift! <br />We\'d like to wish ' .
                'all our players, old and new, and all of ' .
                'this year\'s <a href="main.php?cat=game&amp;page=sponsors">ORKFiA ' .
                ' Dragons</a> a happy holiday! <br />~ The ORKFiA Staff Team' .
            '</p>' .
            '</div>';
        return;
    }
    elseif ($iXmasDay == $iDay && $iMonth == 12)
    {
        // User already had his gift today
        echo
            '<div id="textMedium">' .
            '<p>' .
                '<img src="' . HOST_PICS . 'xmas_l.gif" alt="" ' .
                'style="float: left; margin-right: 10px;" />' .
                'Look for this little creature again tomorrow but on another ' .
                'page, as he will not be at the same spot twice. <br /><br />'.
            '</p>' .
            '</div>';
        return;
    }
    elseif ($iMonth != 12 || $iXmasDay > $iDay)
    {
        // automatic reset-routine
        $objUser->set_stat('xmas_day', 0);

        // Don't show page
        return;
    }

    $advisorText =
        '<div id="textMedium">' .
            '<p>' .
                '<img src="' . HOST_PICS . 'xmas_l.gif" alt="" ' .
                'style="float: left; margin-right: 10px;" />' .

                "<strong>Santa's little ork helper</strong> sneaks up to " .
                "you: </strong><br />" .
                "Ho, Ho, Horde! Merry Orkmas to you sir! This is how much " .
                "Santa has taken from you in taxes, though you won't get it " .
                "back&#8212;so don't bother asking for it!" .
            "</p>" .
        '<br />';
    echo $advisorText;

    $strDate = $objUser->get_gamestat(SIGNUP_TIME);
    $iHours  = abs(ceil((time() - strtotime($strDate)) / 3600));
    $iTaxes  = number_format(round($iHours * (1/180) * (1/5) * 49750));//Average

    $strTaxTable =
        '<table cellpadding="0" cellspacing="0" class="small">' .

            '<tr class="header">' .
                // 1st of December
                '<th colspan="2">' . date('jS \of F') . '</th>' .
            '</tr>' .

            '<tr class="subheader">' .
                '<th>Type</th>' .
                '<th class="right">Amount</th>' .
            '</tr>' .

            '<tr class="data">' .
                '<th>Santa\'s Taxes</th>' .
                '<td>' . $iTaxes . ' crowns</td>' .
            '</tr>' .
        '</table>';
    echo $strTaxTable;

    $strXmasForm =
        '<form id="center" action="main.php?cat=game&amp;page=xmas2" method="post">' .
            '<input type="hidden" name="piss_off_bots" value="' . md5('xmas' . $iDay . $iMonth) . '" />' .
            '<p>At least give me <em>something</em> back, you white-bearded thief!</p>' .
            '<input type="submit" value="Ask for a gift" />' .
        '</form>' .
        "<br /></div>";
    echo $strXmasForm;
}
?>