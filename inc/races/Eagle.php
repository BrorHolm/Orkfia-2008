<?php
//==============================================================================
// This file contains the Eagle race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Eagle_object(){
    return new clsRace(
        "Eagle",
        array(1 =>'Citizen', 'Nester', 'Emesen', 'Vendo', 'Anekonian', 'Razorbeak'),
        array(2 => 35, 10, 380, 1220, 265),
        array(2 =>  0,  1,   0,    2,   0),
        array(2 =>  0,  0,   4,    8,   0),
        1008
    );
}
?>
