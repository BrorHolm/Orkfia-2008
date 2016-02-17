<?php
//==============================================================================
//This is supposed to be the class that all the race-objects are instances of.
//It will contain most of the stats of the race and also a static factory method
// The races themselves will be defined in inc/races/...,
//  the racename being the filename.
//
// more documentation to come as I write this thing
//      AI - 30/01/2007
// Public functions:
//  - getRace($race) - get the race object of that race
//  - getRaces() - get array of race names, don't confuse with getRaceName
//  - getActiveRaces() - get array of races that can be used to create with
// Public methods:
//  - clsRace(...) - constructor, only to be used inside inc/races/...
//  - get... - The lot of get-function
// Will probably add more later, as needed
// Currently, all private/public stuff is commented out, as php4 has no idea
// about what that means, a simple regex should add it back when we upgrade to
//  php5, even static is commented out.
//      AI - 30/01/2007
//==============================================================================

class clsRace
{
    /*private*/ var $_strRaceName;
    /*private*/ var $_arrUnitVariables = array(1 => CITIZEN, UNIT1, UNIT2, UNIT3, UNIT4, UNIT5);
    /*private*/ var $_arrUnitNames;
    /*private*/ var $_arrUnitCosts;
    /*private*/ var $_arrUnitOffences;
    /*private*/ var $_arrUnitDefences;
    /*private*/ var $_intLifespan;


    //==========================================================================
    // Nasty constructor and factory stuff here - AI
    //==========================================================================

    /*public*/ function clsRace($strRaceName,$arrUnitNames,$arrUnitCosts,$arrUnitOffences,$arrUnitDefences,$intLifespan = 1008)
    {
        //check if we got all the data
        if(!is_array($arrUnitDefences)){
            trigger_error("Properly create this class already...",E_USER_ERROR);
            die("Are we still alive here? DIE ALREADY!");
        }
        $this->_strRaceName     = $strRaceName;
        $this->_arrUnitNames    = $arrUnitNames;
        $this->_arrUnitCosts    = $arrUnitCosts;
        $this->_arrUnitOffences = $arrUnitOffences;
        $this->_arrUnitDefences = $arrUnitDefences;
        $this->_intLifespan     = $intLifespan;
    }
    /*public*/ /*static*/ function &getRace($race)
    {
        if(!isset($race))
        {
            trigger_error("Someone did some sloppy coding, pick a race!",E_USER_ERROR);
            die("Are we still alive here? DIE ALREADY!");
        }
        $cleanedRace = clsRace::cleanRaceName($race);

        //check wether we need to load this race
        if(!isset($GLOBALS['obj' . $cleanedRace]))
        {
            $filepath = 'inc/races/' . $cleanedRace . '.php';
            $racefunction = 'create_' . $cleanedRace . '_object';
            if(!file_exists($filepath))
            {
                trigger_error("Race not found: $race",E_USER_ERROR);
                die("Are we still alive here? DIE ALREADY!");
            }
            require_once($filepath);
            $GLOBALS['obj' . $cleanedRace] = $racefunction();
        }
        return $GLOBALS['obj' . $cleanedRace];

    }

    //==========================================================================
    // helper function to clean up race names - AI
    //==========================================================================
    /*private*/ /*static*/ function cleanRaceName($race)
    {
        $race = strtr($race,'- |/\\','_____');
        return preg_replace('/_+/','',$race);
    }
    //==========================================================================
    // Functions to get race names - AI
    //==========================================================================
    /*public*/ /*static*/ function getRaces($strFamily = 'All')
    {
        $arrOrks    = array('Uruk Hai', 'Oleg Hai', 'Mori Hai');
        $arrElves   = array('Dark Elf', 'Wood Elf', 'High Elf', 'Meteor Elf');
        $arrHumans  = array('Dwarf', 'Viking', 'Brittonian', 'Templar');
        $arrWinged  = array('Raven', 'Dragon', 'Eagle');
        $arrCursed  = array('Nazgul', 'Undead', 'Spirit');
        $arrAll     = array_merge($arrOrks, $arrElves,
                            $arrHumans, $arrWinged, $arrCursed);
        $strReturn = 'arr' . $strFamily;
        return $$strReturn;
    }
    /*public*/ /*static*/ function getActiveRaces()
    {
        return array('',
            'Uruk Hai', 'Oleg Hai', 'Mori Hai',
            'Dark Elf', 'Wood Elf', 'High Elf',
            'Viking', 'Brittonian', 'Templar',
            'Raven', 'Dragon', 'Eagle',
            'Nazgul', 'Undead', 'Spirit'
        );
    }

    //==========================================================================
    // Only get-functions below this line - AI
    //==========================================================================

    /*public*/ function getRaceName()
    {
        return $this->_strRaceName;
    }

    /*public*/ function getUnitVariables()
    {
        return $this->_arrUnitVariables;
    }

    /*public*/ function getUnitVariable($unit)
    {
        return $this->_arrUnitVariables[$unit];
    }

    /*public*/ function getUnitNames()
    {
        return $this->_arrUnitNames;
    }

    /*public*/ function getUnitName($unit)
    {
        return $this->_arrUnitNames[$unit];
    }

    /*public*/ function getUnitCosts()
    {
        return $this->_arrUnitCosts;
    }

    /*public*/ function getUnitCost($unit)
    {
        return $this->_arrUnitCosts[$unit];
    }

    /*public*/ function getUnitOffences()
    {
        return $this->_arrUnitOffences;
    }

    /*public*/ function getUnitOffence($unit)
    {
        return $this->_arrUnitOffences[$unit];
    }

    /*public*/ function getUnitDefences()
    {
        return $this->_arrUnitDefences;
    }

    /*public*/ function getUnitDefence($unit)
    {
        return $this->_arrUnitDefences[$unit];
    }

    /*public*/ function getLifespan()
    {
        return $this->_intLifespan;
    }

}

?>
