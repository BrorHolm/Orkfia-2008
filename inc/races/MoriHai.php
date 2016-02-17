<?php
//==============================================================================
// This file contains the Mori Hai race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_MoriHai_object(){
    return new clsRace(
        "Mori Hai",
        array(1 =>'Citizen', 'Gnome', 'Goblin', 'Axethrower', 'Ogre', 'Assassin'),
        array(2 => 60, 300, 425, 650, 800),
        array(2 =>  0,   3,   0,   4,   6),
        array(2 =>  0,   0,   4,   5,   0),
        1008
    );
}
?>
