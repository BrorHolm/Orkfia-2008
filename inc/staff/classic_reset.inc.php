<?php
function call_classic_reset_text()
{
    global $check_inactives, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }


    if (isset($_POST['confirm']) && !empty($_POST['confirm']))
    {
        $objSrcUser = $GLOBALS['objSrcUser'];

        // HARD RESET
//         mysql_query("UPDATE stats SET " . ALLIANCE . " = 0 WHERE " . ALLIANCE . " > 9");

        // SOFT RESET
        mysql_query("UPDATE stats SET invested = 0, reset_option = 'yes', killed = 0 WHERE kingdom > 9");
        mysql_query("UPDATE " . ALLIANCE . " SET name = 'Unitled', image = '', private = 'no', money = 0, food = 0, research = 0, wood = 0, soldiers = 0, income_bonus = 0, home_bonus = 0, offence_bonus = 0, defence_bonus = 0, war_target = 0, vote_count = 0, description = '', market_hour = 120, research_hour = 120 WHERE id > 10");
        mysql_query("UPDATE rankings_personal SET race = 'Unknown', land = " . STARTING_LAND . ", fame = 5000, nw = 6000, hours = 0, tribe_name = 'Untitled' WHERE alli_id > 9");
        mysql_query("UPDATE rankings_alliance SET alli_name = 'Untitled', alli_desc = '', land = 0, nw = 0, fame = 0, last_update = 120 WHERE id > 10");
        mysql_query("UPDATE war SET target = 0, war_started = 0, truce = 0, surrender = 0, defeat = 0, victory = 0, last_target = 0, last_outgoing = 0, last_end = 0, event_id = 0, start_land = 0, truce_offer = 0");

        // BOTH SOFT OR HARD RESET
        mysql_query("TRUNCATE TABLE blocks;");
        mysql_query("UPDATE admin_global_time SET hour_counter = 120");
        mysql_query("TRUNCATE TABLE rankings_history");
        mysql_query("UPDATE records SET agestart = " . strtotime($_POST['time']) . ", online = 0, grab = 0, grab_id = 0, fireball = 0, fireball_id = 0, arson = 0, arson_id = 0, killed = 0, killed_id = 0, pest = 0 WHERE records.id = 1 LIMIT 1");

        // GLOBAL NEWS
        mysql_query("INSERT INTO news SET time = NOW(), type = 'global', kingdom_text='<span class=\"admin\"><strong>The game has been reset! The age will begin " . $_POST['time'] . " Server Time ~" . $objSrcUser->get_stat(TRIBE) . "</strong></span>'");

        echo
            '<p>' .
                'SOFT RESET - ORKFiA Classic has been reset.' .
                '</p><p>' .
                'IMPORTANT: Remember to <a href="main.php?cat=game&amp;page=resort_tools&amp;tool=manual_updater">Update Users -> [Update]</a> <strong>the same hour, BEFORE you switch OFF global_pause, which in turn is done AFTER 00:00:00. Easy as pie =)</strong>.' .
            '</p>';
    }
    else
    {
        echo '<h2>ORKFiA Classic Reset</h2>' .

             '<h3>Step 1:</h3>' .

             '<p><a href="main.php?cat=game&amp;page=resort_tools&amp;tool=admin_switches">Switch ON login_stopper</a>.</p>' .

             '<h3>Step 2:</h3>' .

             '<p><a href="main.php?cat=game&amp;page=resort_tools&amp;tool=admin_switches">Switch ON global_pause</a>.</p>' .

             '<form id="center" method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=classic_reset">' .

                 '<table cellpadding="0" cellspacing="0" border="0" class="small">' .

                     '<tr class="header">' .
                         '<th>' . 'Step 3: Prepare Mystics' . '</th>' .
                     '</tr>' .

                     '<tr class="subheader">' .
                         '<th>' . 'Action' . '</th>' .
                     '</tr>' .

                     '<tr class="data">' .
                         '<th>' . '<label>Spell: <select><option>Time (GOD) - 10000</option></select></label>' . '</th>' .
                     '</tr>' .

                     '<tr class="data">' .
                          '<th>' . '<label>Age Start: <input type="text" length="40" name="time" value="' . date('Y-m-d H:i:s') . '" /></label>' . '</th>' .
                     '</tr>' .

                     '<tr class="data">' .
                          '<th>' . '<label>Confirm resetting ORKFiA: <input type="checkbox" name="confirm" /></label>' . '</th>' .
                     '</tr>' .

                 '</table><br />' .

                 '<input type="submit" value="Request mystics to cast spell" />' .

             '</form>' .

             '<h3>Step 4:</h3>' .

             '<p>Do stuff with the "Age Tool" that has to be re-coded for classic... Perhaps a working solution is to just go with the orkfia calendar, and create ages based on it just like current procedure for infinity?</p>';
    }
}
?>