<?php
//==============================================================================
// This file contains the Raven race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Raven_object(){
    return new clsRace(
        "Raven",
        array(1 =>'Citizen', 'Nester', 'Blackclaw', 'Razorwing', 'Blackwing', 'Screecher'),
        array(2 => 10, 150, 700, 800, 350),
        array(2 =>  0,   2,   0,   5,   0),
        array(2 =>  0,   0,   5,   5,   0),
        1008
    );
}
?>
