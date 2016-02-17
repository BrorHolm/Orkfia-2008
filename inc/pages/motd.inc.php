<?php
function include_motd_text()
{
    global $page;

    $objSrcUser  = &$GLOBALS["objSrcUser"];

    //==========================================================================
    // Admin Message
    //==========================================================================

    $result      = mysql_query("SELECT * FROM admin WHERE id = 1");
    $local_admin = mysql_fetch_array($result);

    $motd = '';
    if ($local_admin['status'] != "none")
    {
        $motd .=
            "<div id=\"textMedium\">" .
                '<h2 style="margin-bottom: 0;">Admin Message</h2>' .
                '<p style="margin-top: 0;">' .
                    date("D d M, H:i:s") . ' ST' .
                '</p><p>' .
                nl2br($local_admin['status']) . "</p>" .
            "</div>";
    }

    if ($page != 'tribe' && $objSrcUser->get_user_info(STATUS) == 2)
    {
        $motd .=
            '<div class="center">' .
            '<br /><a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            "</div>";
    }
    elseif ($page != 'tribe')
    {
        $motd .=
            '<div class="center">' .
            '<br /><a href="main.php?cat=game&amp;page=tribe">Tribe Page</a>' .
            "</div>";
    }

    echo $motd;

    //==========================================================================
    // Force update of tribe + rankings
    //==========================================================================
    include_once('inc/functions/update_ranking.php');
    doUpdateRankings($objSrcUser, 'yes');

    //==========================================================================
    // make sure users go to this page each login.
    //==========================================================================
    $objSrcUser->set_user_info(STATUS, 1);

    //==========================================================================
    // Species5618: added a safety-mechanism for the doubleclickprotection on
    // magic, switch will be set to free whenever a tribe looks at the tribepage
    //==========================================================================
    $objSrcUser->set_spell(CASTING_NOW, "'free'");
}
?>