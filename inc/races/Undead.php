<?php
//==============================================================================
// This file contains the Undead race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Undead_object(){
    return new clsRace(
        "Undead",
        array(1 =>'Citizen', 'Skeleton', 'Zombie', 'Mummy', 'Vampire', 'Imp'),
        array(2 => 120, 700, 400, 1130, 460),
        array(2 =>   0,   7,   0,    7,   0),
        array(2 =>   0,   0,   4,    9,   0),
        1008
    );
}
?>
