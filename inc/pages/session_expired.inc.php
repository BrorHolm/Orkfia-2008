<?php
//******************************************************************************
// pages session_expired.inc.php                           Martel, July 18, 2006 
// Graphic (c) Martel
//******************************************************************************

function include_session_expired_text()
{
    global  $Host;
    
    echo $strDiv =
        '<div id="textSmall"><p align="center">' .
            '<img src="' . $Host . 'ravens_small.gif"/>' .
            '</p><p>' .
            'You have been logged in for <b>4 hours</b>, you will need to login ' .
            'again to keep playing.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=logout">Continue</a>' .
        '</p></div>';
}
?>

