<?php
function call_tour_move_text()
{
    global $local_stats,$id,$confirm,$target,$tool;

    include_once('inc/functions/resort_tools.php');
    check_access($tool);

    $alli_id = '';
    if (isset($_POST['alli_id']))
        $alli_id = intval($_POST['alli_id']);

    echo '<h2>ORKFiA Tour!</h2>';

    $strForm =
        "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">" .
        '<label>Where is our touring tribe? Alliance #</label> ' .
        '<input name="alli_id" size="3" value="' . $alli_id . '"/><br /><br />';

    if (isset($_POST['alli_id']) && !empty($_POST['alli_id']))
    {
        $iAlliance = intval($_POST['alli_id']);

        include('inc/functions/vote.php');

        // select everyone in this alliance
        $strSQL =
            "SELECT * " .
            "  FROM stats " .
            " WHERE kingdom = " . $iAlliance .
            " ORDER BY tribe ASC";
        $result = mysql_query ($strSQL) or die("Elder Defect:" . mysql_error());

        $strForm .=
            "<label>Select the lucky tribe</label>: " .
            '<select name="id" size="1">' .
                render_option_list($result, TRIBE, ID, 0) .
            '</select><br /><br />' .
            '<label>Where will they apparate to? Alliance #</label>: ' .
            '<input name="target" size="5" /><br /><br />' .
            '<input type="submit" value="Move Tourer" name="confirm">';
    }
    else
    {
        $strForm .=
            '<input type="submit" value="Choose Alliance" name="confirm">';
    }
    $strForm .=
        "</form>";

    echo $strForm;

    if (isset($_POST['confirm']) && isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['target']) && !empty($_POST['target']))
    {
        $id = intval($_POST['id']);
        $target = intval($_POST['target']);

        $trgTmpUser = new clsUser($id);
        $strTribeName = ucwords(stripslashes($trgTmpUser->get_stat(TRIBE)));

        if ($trgTmpUser->get_build(LAND) != 1)
        {
            echo '<p>"W-T-F mate?" That was not a tourer tribe.</p></div>';
            include_game_down();
            exit;
        }
        elseif ($target < 11)
        {
            echo '<p>"W-T-F mate?" No apparating into staff alliances.</p></div>';
            include_game_down();
            exit;
        }


        echo "<p>Leader! The tribe of " . $strTribeName . " has now apparated to alliance #<strong>$target</strong>. Let us hope that neither of their citizens got splinched.</p>";

        $orkTime = date(TIMESTAMP_FORMAT);
        $search = mysql_query("SELECT * FROM stats WHERE id = $id");
        $search =  mysql_fetch_array($search);
        //mysql_query("UPDATE stats SET kingdom = $target, type ='player', invested = 0 where id = $id");
        //mysql_query("UPDATE rankings_personal SET alli_id = $target where id = $id");
        //mysql_query("UPDATE stats SET vote = 0, invested = 0 WHERE vote = $id");
        //mysql_query("UPDATE goods SET credits = 0, market_money = 0, market_food = 0, market_soldiers = 0, market_wood = 0 WHERE id = $id");
        include_once('inc/staff/move.inc.php');
        move_tribe($id, $target);
        mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'Tour Move', '0', '0', '1', '', '<span class=\"indicator\">$search[tribe] has joined our alliance. Long live Orkfia! Long live the tour!</span>', '', '$target')");
    }
}
?>
