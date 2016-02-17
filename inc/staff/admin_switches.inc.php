<?php
//******************************************************************************
// staff tool admin_switches.inc.php                      Martel August 26, 2006
//******************************************************************************
function call_admin_switches_text()
{
    global $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    include_once('inc/classes/clsGame.php');
    $objGame = new clsGame();

    if (isset($_POST[ON]))
    {
        $strTool = $_POST['tool'];
        $objGame->set_game_switch($strTool, ON);

        echo '<p>' .
                 "Switch '$strTool' is now turned on." .
                 '<br /><br />' .
                 '<a href="main.php?cat=game&amp;page=resort_tools&amp;' .
                 'tool=admin_switches">' . 'Return to Tool' . '</a>' .
             '</p>';
    }
    elseif (isset($_POST[OFF]))
    {
        $strTool = $_POST['tool'];
        $objGame->set_game_switch($strTool, OFF);

        echo '<p>' .
                 "Switch '$strTool' is now turned off." .
                 '<br /><br />' .
                 '<a href="main.php?cat=game&amp;page=resort_tools&amp;' .
                 'tool=admin_switches">' . 'Return to Tool' . '</a>' .
             '</p>';
    }
    else
    {

        $strDiv = '<h2>' . 'Admin Switches' . '</h2>';

        $arrGameSwitches = $objGame->get_game_switches();
        foreach ($arrGameSwitches as $strName => $strValue)
        {
            switch($strValue)
            {
                case ON:

                    $strDiv .=
                    '<form method="post" action="main.php?cat=game&amp;page=' .
                        'resort_tools&amp;tool=admin_switches"><p><b class="positive">' .
                        $strName . ' is ON. <br />' .
                        '<input type="hidden" name="tool" value="' . $strName . '">' .
                        '<input type="submit" name="' . OFF . '" value="Switch OFF ' . $strName . '">' .
                    '</b></p></form>';

                break;
                case OFF:

                    $strDiv .=
                    '<form method="post" action="main.php?cat=game&amp;page=' .
                        'resort_tools&amp;tool=admin_switches"><p><b class="negative">' .
                        $strName . ' is OFF. <br />' .
                        '<input type="hidden" name="tool" value="' . $strName . '">' .
                        '<input type="submit" name="' . ON . '" value="Switch ON ' . $strName . '">' .
                    '</b></p></form>';

                break;
            }
        }

        echo $strDiv;
    }
}
?>

