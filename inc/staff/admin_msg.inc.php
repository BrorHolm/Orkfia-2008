<?php
//******************************************************************************
// staff tool admin_msg.inc.php                           Martel August 26, 2006
//******************************************************************************
function call_admin_msg_text()
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
    
    if (isset($_POST['status']))
    {
        $strStatus = $_POST['status'];
        $objGame->set_game_info(STATUS, "'$strStatus'");
        
        echo "Admin Message Updated!" .
             '<br /><br />' .
             '<a href="main.php?cat=game&amp;page=resort_tools&amp;' .
             'tool=admin_msg">' . 'Return to Tool' . '</a>';
    }
    else
    {
    
        echo $strDiv =
            '<div id="textMedium">' .
                '<h2>' . 'Change Admin Message' . '</h2>' .
                '<p>' .
                    'Please keep this message short and purposeful, and just ' .
                    'one item at a time. For longer messages add a link that ' .
                    'point to the announcement forums.' .
                '</p>' .
                '<p>' .
                    'Write "none" without "s to show nothing.' .
                '</p>' .
            '</div>';
            
        echo '<br />';
        
        $strAdmin = $objGame->get_game_info(STATUS);
        
    	echo $strForm = 
    	    '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">' .
                '<textarea name="status" rows="8" cols="70" wrap="on">'. 
                    $strAdmin . 
                '</textarea>' .
                '<br /><br />' .
                '<input type="submit" value="Update Admin Message">' .
            '</form>';
    }
}
?>

