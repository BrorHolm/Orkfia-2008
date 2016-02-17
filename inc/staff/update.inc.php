<?php
//============================================================================
// 'Update' a tribe, call doUpdateRankings and check_to_update  - AI 30/10/06
//============================================================================
function call_update_text()
{
    $tool = $_GET['tool'];
    include_once('inc/functions/resort_tools.php');
    check_access($tool);
    echo "'Updating' a tribe means updating the rankings and alliscreen, " .
        "not giving an actual update.<br /><br />" .
        "<form method='post' action='{$_SERVER['REQUEST_URI']}'>" .
        "User id to update: <input name='id' size='5' /><br /><br />" .
        "<input type='submit' value='Update' name='op' />" .
        "</form>";
    if(isset($_POST['op']) && !empty($_POST['id']))
    {
        $id = $_POST['id'];
        $op = $_POST['op'];
        include_once('inc/functions/update.php');
        include_once('inc/functions/update_ranking.php');
        $objTrgUser = new clsUser($id);
        doUpdateRankings($objTrgUser, 'yes');
        check_to_update($objTrgUser->get_userid());
        echo "<br /><br />User " . $objTrgUser->get_userid() . " updated =)";
    }
}
?>
