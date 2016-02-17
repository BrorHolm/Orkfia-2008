<?php
//==============================================================================
// This file contains the Viking race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Viking_object(){
    return new clsRace(
        "Viking",
        array(1 =>'Citizen', 'Soldier', 'Swordmen', 'Archer', 'Berserker', 'Looter'),
        array(2 => 120, 180, 530, 850, 330),
        array(2 =>   1,   3,   2,   8,   0),
        array(2 =>   1,   1,   6,   6,   0),
        1008
    );
}
?>
