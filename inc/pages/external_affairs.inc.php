<?php
//******************************************************************************
// Page external_affairs.inc.php              Created by Martel January 14, 2007
//******************************************************************************
include_once('inc/functions/tribe.php');

function include_external_affairs_text()
{
    $objSrcUser     = &$GLOBALS['objSrcUser'];
    $objSrcAlliance = $objSrcUser->get_alliance();

    //==========================================================================
    // Validate GET input
    //==========================================================================
    if(empty($_GET['tribe']))
        $tribe = $objSrcUser->get_userid();
    else
        $tribe = intval($_GET['tribe']);

    if(empty($_GET['aid']))
        $aid = $objSrcUser->get_stat(ALLIANCE);
    else
        $aid = intval($_GET['aid']);

    //==========================================================================
    // Start Output
    //==========================================================================
    if ($aid > 10)
    {
        // Target user object
        include_once('inc/classes/clsUser.php');
        if ($tribe == $objSrcUser->get_userid())
            $objTrgUser = $objSrcUser;
        elseif ($tribe > 0)
            $objTrgUser = new clsUser($tribe);

        // Target alliance object
        $objTrgAlliance = $objTrgUser->get_alliance();
        $aid = $objTrgUser->get_stat(ALLIANCE);

        // Update user
        include_once('inc/functions/update.php');
        check_to_update($objTrgUser->get_userid());

        // Update rankings
        include_once('inc/functions/update_ranking.php');
        doUpdateRankings($objTrgUser);

        // Div starts here
?>
        <div id="columns">
            <!-- Start left column -->
            <div id="leftcolumn">
            <br />
<?php

        // Show mini-vision table
        echo get_small_tribe_table($objTrgUser);

        $arrStats = $objSrcUser->get_stats();
        $arrRankingsPersonal = $objTrgUser->get_rankings_personals();

        // Show advisor message
        echo
            '<div id="textSmall">' .
            '<p><strong>The General</strong> tells you: <br />';
        if ($tribe == $objSrcUser->get_userid())
            echo 'This is <em>our</em> tribe, leader =)';
        elseif ($objTrgUser->get_stat(KILLED) == 0 && $objTrgUser->get_stat(RESET_OPTION) == 'yes')
            echo 'This tribe has <em>reset</em>.';
        elseif ($objTrgUser->get_user_info(HOURS) < PROTECTION_HOURS)
        {
            $iRemaining = PROTECTION_HOURS - $objTrgUser->get_user_info(HOURS);
            echo 'This tribe is performing the summoning ritual and their ' .
                 'borders will be protected for another <strong>' .
                 $iRemaining . ' months.</strong>';
        }
        elseif ($objTrgUser->get_stat(KILLED) > 0 && $objTrgUser->get_stat(KILLED) != 3)
            echo 'This tribe is <em>dead</em>.';
        elseif ($objTrgUser->isPaused())
            echo 'This tribe is <em>paused</em>.';
        elseif ($objTrgUser->get_stat(ALLIANCE) == $objSrcUser->get_stat(ALLIANCE))
            echo 'This is a friendly <em>allied</em> tribe.';
        elseif ($objTrgUser->get_stat(ALLIANCE) == $objSrcAlliance->get_war(TARGET))
            echo 'We are in a declared <em class="negative">war</em> with this tribe!';
        elseif ($objTrgAlliance->get_war(TARGET) > 0)
            echo 'This tribe is in <em class="negative">war</em> with another alliance.';
        elseif ($objTrgUser->get_stat(ALLIANCE) != $objSrcUser->get_stat(ALLIANCE))
            echo 'This <em>enemy</em> tribe may remain neutral towards us if we let them be. Be careful with your actions against them leader.';

        if ($objTrgUser->get_stat(ALLIANCE) == $objSrcUser->get_stat(ALLIANCE) || $objTrgUser->get_stat(KILLED) > 0 || $objTrgUser->isPaused())
            { /* say nothing */ }
        elseif ($objTrgUser->get_build(LAND) < (0.8 * $objSrcUser->get_build(LAND)))
            echo '</p><p>Also, due to their small size we will gain less land on attacks and do less damage with our operations and spells.';
        elseif ($objTrgUser->get_build(LAND) > (2.0 * $objSrcUser->get_build(LAND)))
            echo '</p><p>Also, due to their large size our operations will have very high fail rates.';
        elseif ($objTrgUser->get_build(LAND) > (1.2 * $objSrcUser->get_build(LAND)))
            echo '</p><p>Also, due to their large size our operations and spells will do less damage and we will gain less on attacks.';

        echo
            '</p>' .
            '</div>';

?>
            </div>
            <!-- end left column -->

            <!-- start right column -->
            <div id="rightcolumn">
<?php

        echo
            '<div class="tableLinkSmall"><a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $aid .'">Back to alliance #' . $aid . '</a>' .
//             ' | ' .
//             '<a href="main.php?cat=game&amp;page=war_room">War Room</a>' .
            '</div>';

        // Show action links
        echo
            '<div id="textSmall" style="margin-top: 0px;">' .
            '<h2>Tribe Actions</h2>';
        echo
            '<ul>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=mystic&amp;magekd=' . $aid . '&amp;tribe=' . $tribe . '">Prepare Mystics</a>' . '</li>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=thievery&amp;kd=' . $aid . '&amp;tribe=' . $tribe . '">Prepare Thieves</a>' . '</li>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=invade&amp;atkid=' . $aid . '&amp;tribe=' . $tribe . '">Prepare Invasion</a>' . '</li>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=mail&amp;set=compose&amp;aid=' . $aid . '&amp;tribe=' . $tribe . '">Orkfia Mail</a>' . '</li>' .
            '</ul>';
        echo '</div>';

?>
            </div>
            <!-- End of the right column-->
        </div>
        <!-- end of 2 column layout -->
<?php

    }
    elseif ($aid < 11)
    {
        echo
            '<div class="tableLinkMedium">' .
                '<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $aid .'">Alliance #' . $aid . '</a>' .
            '</div>';

        // Show action links
        echo '<div id="textMedium" style="margin-top: 0px;">';
        echo '<p><strong>Tribe Actions</strong></p>';
        echo
            '<ul>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=message&amp;alliance=' . $aid . '&amp;tribe=' . $tribe . '">Submit a Report</a>' . '</li>' .
                '<li>' . '<a href="main.php?cat=game&amp;page=mail&amp;set=compose&amp;aid=' . $aid . '&amp;tribe=' . $tribe . '">Orkfia Mail</a>' . '</li>' .
            '</ul>';
        echo '</div>';
    }
}
?>
