<?php
//==============================================================================
// This file contains the Dark Elf race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_DarkElf_object(){
    return new clsRace(
        "Dark Elf",
        array(1 =>'Citizen', 'Slinger', 'Nightstalker', 'Sorcerer', 'Nightrider', 'Assassin'),
        array(2 => 50, 200, 350, 810, 330),
        array(2 =>  0,   2,   0,   3,   0),
        array(2 =>  1,   0,   4,   7,   0),
        1008
    );
}
?>
