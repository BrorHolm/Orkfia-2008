<?php
//==============================================================================
// This file contains the Brittonian race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Brittonian_object(){
    return new clsRace(
        "Brittonian",
        array(1 =>'Citizen', 'Soldier', 'Pikemen', 'Crossbowmen', 'Knight', 'Rogue'),
        array(2 => 100, 200, 900, 500, 500),
        array(2 =>   1,   2,   0,   3,   0),
        array(2 =>   0,   0,   4,   1,   0),
        1008
    );
}
?>
