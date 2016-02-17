<?php
//==============================================================================
// This file contains the Dwarf race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Dwarf_object(){
    return new clsRace(
        "Dwarf",
        array(1 =>'Citizen', 'Grunt', 'Hammer Smasher', 'Stone Thrower', 'Grey Beard', 'Short Cloak'),
        array(2 => 60, 520, 350, 660, 350),
        array(2 =>  0,   6,   0,   4,   0),
        array(2 =>  1,   1,   4,   6,   0),
        1008
    );
}
?>
