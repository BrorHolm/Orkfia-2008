<?php
//==============================================================================
// This file contains the Nazgul race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Nazgul_object(){
    return new clsRace(
        "Nazgul",
        array(1 =>'Citizen', 'Mortal', 'Blackrider', 'Bloodpet', 'Wraith', 'Gollum'),
        array(2 => 40, 666, 666, 1600, 660),
        array(2 =>  0,   6,   0,    9,   0),
        array(2 =>  0,   0,   6,    9,   0),
        1248
    );
}
?>
