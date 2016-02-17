<?php
//============================================================================
// Unsuspend a tribe                                      - AI 30/10/06
//============================================================================
function call_unsuspend_text()
{
    $tool = $_GET['tool'];
    include_once('inc/functions/resort_tools.php');
    check_access($tool);
    echo "<form method='post' action='{$_SERVER['REQUEST_URI']}'>" .
        "User id to unsuspend: <input name='id' size='5' /><br /><br />" .
        "<input type='submit' value='Unsuspend' name='op' />" .
        "</form>";
    if(isset($_POST['op']) && !empty($_POST['id']))
    {
        $id = $_POST['id'];
        $objTrgUser = new clsUser($id);
        $arrTrgUser = array
        (
            PAUSE_ACCOUNT => 0
        );
        $objTrgUser->set_user_infos($arrTrgUser);

        // Forced Ranking Update
        include_once('inc/functions/update_ranking.php');
        doUpdateRankings($objTrgUser, 'yes');

        echo "<br /><br />User " . $objTrgUser->get_userid() . " unsuspended =)";
    }
}
?>
