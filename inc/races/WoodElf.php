<?php
//==============================================================================
// This file contains the Wood Elf race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_WoodElf_object(){
    return new clsRace(
        "Wood Elf",
        array(1 =>'Citizen', 'Slinger', 'Woodrider', 'Druid', 'Tree Ent', 'Grassrunner'),
        array(2 => 20, 440, 335, 1400, 300),
        array(2 =>  0,   4,   0,    0,   0),
        array(2 =>  0,   0,   4,    9,   0),
        1008
    );
}
?>
