<?php
//==============================================================================
// This file contains the Dragon race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Dragon_object(){
    return new clsRace(
        "Dragon",
        array(1 =>'Citizen', 'Baby Dragon', 'Green Dragon', 'Yellow Dragon', 'Red Dragon', 'White Dragon'),
        array(2 => 50, 550, 800, 1350, 440),
        array(2 =>  1,  25,   0,   50,   0),
        array(2 =>  1,   0,  32,   15,   0),
        1008
    );
}
?>
