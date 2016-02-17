<?php
/*
    Page: inc/pages/thievery2.inc.php
    Last Update:  12.24.2001
    by: Michael
*/

// Gotland: rewritten function to use user objects
function include_thievery2_text()
{
    global  $op, $dplayer, $action, $amount, $amount_sent, $stop;

    include_once('inc/functions/ops.php');
    include_once('inc/functions/thievery.php');

    $objSrcUser = &$GLOBALS["objSrcUser"];
    if ($objSrcUser->get_stat(RACE) == 'Templar')
    {
        echo '<div id="textMedium"><p>' .
            'Your proud Templar people will not lower themselves to thievery practices.' .
            '</p></div>';
        include_game_down();
        exit;
    }

    if(!$dplayer)
        $objTrgUser = $objSrcUser;
    else
        $objTrgUser = new clsUser ($dplayer);

    $arrTrgStats = $objTrgUser->get_stats();
    if ($arrTrgStats[KILLED] == 0 && $arrTrgStats[RESET_OPTION] != 'yes' && ! $objTrgUser->isPaused())
    {
        set_op_vars();

        // Verify attacker's status
        obj_check_protection($objSrcUser, 'thievery');

        $op_level = get_op_level($objSrcUser);
        if ($op > $op_level)
        {
            echo "Please Do Not Edit Forms";
            // make something to notifiy the admin if this happens
            include_game_down();
            exit;
        }
        make_thievery($objSrcUser, $objTrgUser, $action[$op], $amount_sent, $amount, $stop);

        //======================================================================
        // Report: War effects
        //======================================================================
        require_once('inc/functions/war.php');
        $objSrcAlliance = $objSrcUser->get_alliance();
        if (checkWarBetween($objSrcAlliance, $objTrgUser->get_stat(ALLIANCE)))
        {
            $objTrgAlliance = $objTrgUser->get_alliance();
            if (($arrGains = testWarVictory($objSrcAlliance, $objTrgAlliance)))
            {
                // Append war-win message
                require_once('inc/pages/war_room2.inc.php');
                echo $strReport =
                '<div id="textMedium">' .
                '<p><strong class="positive">Your alliance has won the war!</strong></p>' .
                getVictoryReport($arrGains) .
                '</div>';
            }
        }
    }
    else
    {
        echo $strDiv =
        '<div id="textMedium"><p>' .
            'This tribe is either dead or paused.' .
            '<br /><br />' .
            '<a href="main.php?cat=game&page=thievery">' . 'Return' . '</a>' .
        '</p></div>';
    }
}

?>
