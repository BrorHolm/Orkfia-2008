<?php
//******************************************************************************
// staff tools: eldervotes                              November 08, 2007 Martel
//******************************************************************************

function call_eldervotes_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $go = '';
    $reset_id = 0;

    if (isset($_POST['go']) && !empty($_POST['go']))
        $go = $_POST['go'];

    if (isset($_POST['reset_id']) && !empty($_POST['reset_id']))
        $reset_id = intval($_POST['reset_id']);

    if ($go != "sure" && $go != "yes")
    {
        echo $strResetForm =
            '<h2>Reset elder votes of an alliance</h2>' .

            '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=eldervotes">' .
                '<label for="alli_id">Alliance #</label>: ' .
                '<input type="text" size="5" name="reset_id" id="alli_id" />' .
                '<input type="hidden" name="go" value="sure" /> ' .
                '<input type="submit" value="Check alliance" />' .
            '</form>';
    }

    if ($reset_id > 0 && $go == 'sure')
    {
        $name = @mysql_query("SELECT id, name FROM " . TBL_ALLIANCE . " WHERE id = $reset_id");
        $name = mysql_fetch_array($name);

        echo $strResetForm2 =
            '<h2>Reset elder votes of an alliance</h2>' .

            '<h3>Confirm alliance</h3>' .
            '<p>' . stripslashes($name[NAME]) . ' (#' . $name[ID] . ')</p>' .

            '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=eldervotes">' .
                '<input type="hidden" name="go" value="yes" />' .
                '<input type="hidden" name="reset_id" value="' . $reset_id.  '" /> ' .
                '<input type="submit" value="Reset elder votes">' .
            '</form>';
    }

    if ($reset_id && $go == "yes")
    {
        echo '<h2>Reset elder votes of an alliance</h2>';

        // Reset votes (an automatic routine will remove it from alliance #0)
        mysql_query("UPDATE " . TBL_STAT . " SET " . VOTE . " = 0 WHERE " .
        ALLIANCE . " = $reset_id") or die('There was an error with the query.');

        echo "<p><strong>Reset successful!</strong> - Alliance #$reset_id has had their elder vote reset.</p>";
    }
}

?>
