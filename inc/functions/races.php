<?php
//******************************************************************************
// functions races.php                                      Martel, May 25, 2006
//
// Description: Sooner or later we have these objectified, until then this is
//              what we got.
//******************************************************************************

//==============================================================================
// Moving this stuff to inc/races/clsRace.php,
//  at this point it's just commented out         - AI 30/01/2007
// Aliases below, where these are used, add clsRace:: in front.
//==============================================================================
require_once('inc/races/clsRace.php');

function getActiveRaces()
{
    return clsRace::getActiveRaces();
}
function getRaces($strFamily = 'All')
{
    return clsRace::getRaces($strFamily);
}
/****************** start comment block ***********************
//============================================================================
// Races you can actually create with                       - AI 14/01/2006
//============================================================================
function getActiveRaces()
{
    return array('',
                'Uruk Hai', 'Oleg Hai', 'Mori Hai',
                'Dark Elf', 'Wood Elf', 'High Elf',
                'Dwarf', 'Viking', 'Brittonian',
                'Raven', 'Dragon', 'Eagle',
                'Nazgul', 'Undead', 'Spirit');
}

//==============================================================================
// Available Races                                          Martel, May 25, 2006
// Purpose: Number and name all races 1-15.
//==============================================================================
function getRaces($strFamily = 'All')
{
    $arrOrks    = array(1 => 'Uruk Hai', 'Oleg Hai', 'Mori Hai');
    $arrElves   = array(4 => 'Dark Elf', 'Wood Elf', 'High Elf'); 
    $arrHumans  = array(7 => 'Dwarf', 'Viking', 'Brittonian');
    $arrWinged  = array(10 => 'Raven', 'Dragon', 'Eagle');
    $arrCursed  = array(13 => 'Nazgul', 'Undead', 'Spirit');
    $arrAll     = array_merge((array)$arrBunnies='', $arrOrks, $arrElves, 
                                            $arrHumans, $arrWinged, $arrCursed);
    
    $strReturn = 'arr'.$strFamily;
    
    return $$strReturn;
}
******************* end comment block  ********************/

//------------------------------------------------------------------------------
// Race Unit Variables                                      Martel, May 25, 2006
//------------------------------------------------------------------------------
function getUnitVariables($strRace)
{

    /*

****************** same here, use the objects ***************
    switch ($strRace) 
    {
        case "Uruk Hai":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);            
            $arrUnitNames     = array(1 => 'Citizen', 'Gnome', 'Brute', 'Shaman', 
            'Half-Giant', 'Black Guard');
            $arrUnitCost      = array(2 => 50, 500, 625, 1360, 550);
            $arrUnitOffence   = array(2 => 0, 7, 0, 16, 0);
            $arrUnitDefence   = array(2 => 0, 2, 6, 1, 0);
            
        break;
        case "Oleg Hai":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Gnome', 'Wolfrider', 
            'White Skull', 'Harpie', 'Thief');
            $arrUnitCost      = array(2 => 50, 550, 700, 80, 440);
            $arrUnitOffence   = array(2 => 0, 7, 0, 4, 0);
            $arrUnitDefence   = array(2 => 1, 0, 6, 1, 0);
            
        break;
        case "Mori Hai":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Gnome', 'Goblin', 
            'Axethrower', 'Ogre', 'Assassin');
            $arrUnitCost      = array(2 => 60, 300, 425, 650, 800);
            $arrUnitOffence   = array(2 => 0, 3, 0, 4, 6);
            $arrUnitDefence   = array(2 => 0, 0, 4, 5, 0);
            
        break;
        case "Dark Elf":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Slinger', 'Nightstalker', 
            'Sorcerer', 'Nightrider', 'Assassin');
            $arrUnitCost      = array(2 => 50, 200, 350, 810, 330);
            $arrUnitOffence   = array(2 => 0, 2, 0, 3, 0);
            $arrUnitDefence   = array(2 => 1, 0, 4, 7, 0);
            
        break;
        case "Wood Elf":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Slinger', 'Woodrider', 
            'Druid', 'Tree Ent', 'Grassrunner');
            $arrUnitCost      = array(2 => 20, 440, 330, 1400, 300);
            $arrUnitOffence   = array(2 => 0, 4, 0, 0, 0);
            $arrUnitDefence   = array(2 => 0, 0, 4, 9, 0);
            
        break;
        case "High Elf":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Slinger', 'Sage', 
            'Longbowmen', 'Priestess', 'Rogue');
            $arrUnitCost      = array(2 => 85, 700, 820, 350, 275);
            $arrUnitOffence   = array(2 => 0, 6, 0, 3, 0);
            $arrUnitDefence   = array(2 => 0, 0, 6, 3, 0);
            
        break;
        case "Dwarf":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Grunt', 'Hammer Smasher', 
            'Stone Thrower', 'Grey Beard', 'Short Cloak');
            $arrUnitCost      = array(2 => 60, 520, 350, 660, 350);
            $arrUnitOffence   = array(2 => 0, 6, 0, 4, 0);
            $arrUnitDefence   = array(2 => 1, 1, 4, 6, 0);
            
        break;
        case "Viking":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Soldier', 'Swordmen', 
            'Archer', 'Berserker', 'Looter');
            $arrUnitCost      = array(2 => 120, 180, 530, 850, 330);
            $arrUnitOffence   = array(2 => 1, 3, 2, 8, 0);
            $arrUnitDefence   = array(2 => 1, 1, 6, 6, 0);
            
        break;
        case "Brittonian":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Soldier', 'Pikemen', 
            'Crossbowmen', 'Knight', 'Rogue');
            $arrUnitCost      = array(2 => 100, 200, 900, 500, 500);
            $arrUnitOffence   = array(2 => 1, 2, 0, 3, 0);
            $arrUnitDefence   = array(2 => 0, 0, 4, 1, 0);
            
        break;
        case "Raven":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Nester', 'Blackclaw', 
            'Razorwing', 'Blackwing', 'Screecher');
            $arrUnitCost      = array(2 => 10, 150, 700, 800, 350);
            $arrUnitOffence   = array(2 => 0, 2, 0, 5, 0);
            $arrUnitDefence   = array(2 => 0, 0, 5, 5, 0);
            
        break;
        case "Dragon":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Baby Dragon', 'Green Dragon', 
            'Yellow Dragon', 'Red Dragon', 'White Dragon');
            $arrUnitCost      = array(2 => 50, 550, 800, 1350, 440);
            $arrUnitOffence   = array(2 => 1, 25, 0, 50, 0);
            $arrUnitDefence   = array(2 => 1, 0, 32, 15, 0);
            
        break;
        case "Eagle":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Nester', 'Emesen', 'Vendo', 
            'Anekonian', 'Razorbeak');
            $arrUnitCost      = array(2 => 35, 10, 380, 1200, 300);
            $arrUnitOffence   = array(2 => 0, 1, 0, 2, 0);
            $arrUnitDefence   = array(2 => 0, 0, 4, 8, 0);
            
        break;
        case "Nazgul":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Mortal', 'Blackrider', 
            'Bloodpet', 'Wraith', 'Gollum');
            $arrUnitCost      = array(2 => 40, 666, 666, 1375, 660);
            $arrUnitOffence   = array(2 => 0, 2, 0, 9, 0);
            $arrUnitDefence   = array(2 => 0, 0, 2, 9, 0);
            
        break;
        case "Undead":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Skeleton', 'Zombie', 
            'Mummy', 'Vampire', 'Imp');
            $arrUnitCost      = array(2 => 120, 700, 400, 1130, 460);
            $arrUnitOffence   = array(2 => 0, 7, 0, 7, 0);
            $arrUnitDefence   = array(2 => 0, 0, 4, 9, 0);
            
        break;
        case "Spirit":
            
            $arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, 
            UNIT5);        
            $arrUnitNames     = array(1 => 'Citizen', 'Ghost', 'Phantom', 
            'Poltergeist', 'Ghoul', 'Apparition');
            $arrUnitCost      = array(2 => 50, 250, 810, 600, 275);
            $arrUnitOffence   = array(2 => 0, 2, 2, 6, 0);
            $arrUnitDefence   = array(2 => 0, 0, 7, 3, 0);
            
        break;
        default:
        */
            $objRace = clsRace::getRace($strRace);
            $arrUnitVariables = $objRace->getUnitVariables();
            $arrUnitNames     = $objRace->getUnitNames();
            $arrUnitCost      = $objRace->getUnitCosts();
            $arrUnitOffence   = $objRace->getUnitOffences();
            $arrUnitDefence   = $objRace->getUnitDefences();
        /*
        break;
    }
    */
    $arrUnits['variables'] = $arrUnitVariables;
    $arrUnits['output']    = $arrUnitNames;
    $arrUnits['gold']      = $arrUnitCost;
    $arrUnits['offence']   = $arrUnitOffence;
    $arrUnits['defence']   = $arrUnitDefence;
    
    return $arrUnits;
}

?>
