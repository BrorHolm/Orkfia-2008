<?php
//==============================================================================
// This file contains the Spirit race, it has a single function that creates
// the object containing all needed information.
//==============================================================================
function create_Templar_object(){
    return new clsRace(
        "Templar",
        array(1 =>'Citizen', 'Follower', 'Preacher', 'Guardian', 'Cardinal', 'Cleric'),
        array(2 => 80, 410, 1210, 665, 400),
        array(2 =>  0,   4,    0,   7,   3),
        array(2 =>  0,   0,    8,   4,   1),
        1008
    );
}
?>
