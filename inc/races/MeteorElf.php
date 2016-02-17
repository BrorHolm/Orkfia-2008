<?php
//==============================================================================
// This file contains the Meteor Elf race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_MeteorElf_object(){
    return new clsRace(
        "Meteor Elf",
        array(1 =>'Citizen', 'Slinger', 'Demon Child', 'Cultist', 'Shadow Spreader', 'Heartless'),
        array(2 => 75, 1000, 705, 1000, 250),
        array(2 =>  1,    8,   0,    6,   0),
        array(2 =>  0,    0,   6,    6,   0),
        768
    );
}
?>
