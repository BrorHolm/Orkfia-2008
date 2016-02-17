<?php
//==============================================================================
// This file contains the Oleg Hai race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_OlegHai_object(){
    return new clsRace(
        "Oleg Hai",
        array(1 =>'Citizen', 'Gnome', 'Wolfrider', 'White Skull', 'Harpie', 'Thief'),
        array(2 => 50, 550, 700,  80, 440),
        array(2 =>  0,   7,   0,   4,   0),
        array(2 =>  1,   0,   6,   1,   0),
        1008
    );
}
?>
