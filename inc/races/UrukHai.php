<?php
//==============================================================================
// This file contains the Uruk Hai race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_UrukHai_object(){
    return new clsRace(
        "Uruk Hai",
        array(1 =>'Citizen', 'Gnome', 'Brute', 'Shaman', 'Half-Giant', 'Black Guard'),
        array(2 => 50, 500, 625, 1360, 550),
        array(2 =>  0,   7,   0,   16,   0),
        array(2 =>  0,   2,   6,    1,   0),
        1008
    );
}
?>
