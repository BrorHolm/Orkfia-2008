<?php
//==============================================================================
// This file contains the Spirit race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Spirit_object(){
    return new clsRace(
        "Spirit",
        array(1 =>'Citizen', 'Ghost', 'Phantom', 'Poltergeist', 'Ghoul', 'Apparition'),
        array(2 => 50, 250, 810, 600, 275),
        array(2 =>  0,   2,   2,   6,   0),
        array(2 =>  0,   0,   7,   3,   0),
        1008
    );
}
?>
