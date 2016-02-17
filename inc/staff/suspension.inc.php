<?php
//******************************************************************************
// Staff tool suspension.inc.php                          Martel August 05, 2006
//
// Modification history:
// August 05, 2006 - v.1 Created (Martel)
//******************************************************************************

function call_suspension_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    echo $strHeader =
        '<h2>L&O: Player Suspension (aka Forced Vacation Mode)</h2>';

    echo $strForm =
        '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">' .
            'User-id: ' .
            '<input type="text" size="11" name="suspend_id">' .
            '<br />' .
            'Updates: ' .
            '<select name="suspend_updates">' .
                  '<option>12</option>' .
                  '<option>24</option>' .
                  '<option selected>48</option>' .
            '</select>' .
            '<br /><br />' .
            '<input type="submit" value="Suspend Tribe!">' .
        '</form>' .
            '<br /><br />';

    if (isset($_POST['suspend_id']) && $_POST['suspend_id'] > 1)
    {
        include_once('inc/classes/clsUser.php');
        $objTmpUser    = new clsUser($_POST['suspend_id']);

        // Pause Account Routines. Martel July 13, 2006
        $arrSrcUser    = $objTmpUser->get_user_infos();
        if ($arrSrcUser[NEXT_ATTACK] <= 1)
            $arrSrcUser[NEXT_ATTACK] = 1;

        $arrNewSrcUser = array
        (
            NEXT_ATTACK => $arrSrcUser[NEXT_ATTACK],
            PAUSE_ACCOUNT => $_POST['suspend_updates']
        );
        $objTmpUser->set_user_infos($arrNewSrcUser);

        // Empty Magic and Thievery Points
        $objTmpUser->set_spell(POWER, 0);
        $objTmpUser->set_thievery(CREDITS, 0);

        // Forced Ranking Update
        include_once('inc/functions/update_ranking.php');
        doUpdateRankings($objTmpUser, 'yes');

        echo stripslashes($objTmpUser->get_stat(TRIBE)) . " will be paused for " .
             $_POST['suspend_updates'] . " updates. Don't forget to send a notice.";

        echo
            '<div id="textSmall">' .
            '<p><b>' . 'Standard notice to copy+paste:' . '</b></p>' .
            '<p>' .
                'Your account has been suspended by the Law & Order Resort (found in alliance #2), you will not recieve updates, or be able to interact with the game, this may be because of a Code of Conduct violation or for some other action.  Usually a staff member will contact you, if they have not, it is highly advised that you contact them, especially if you are not guilty of what you have been suspended for.' .
            '</p>' .
            '</div>';
    }
}

?>
