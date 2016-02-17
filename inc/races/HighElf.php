<?php
//==============================================================================
// This file contains the High Elf race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_HighElf_object(){
    return new clsRace(
        "High Elf",
        array(1 =>'Citizen', 'Slinger', 'Sage', 'Longbowmen', 'Priestess', 'Rogue'),
        array(2 => 85, 700, 820, 350, 275),
        array(2 =>  0,   6,   0,   3,   0),
        array(2 =>  0,   0,   6,   3,   0),
        1008
    );
}
?>
