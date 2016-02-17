<?php
//==============================================================================
// Class clsAge.php                                     Martel, October 02, 2006
//==============================================================================

class clsAge
{
    var $_iAgeNumber;
    var $_iYearStart;
    var $_iYearEnd;

    //==========================================================================
    // Constructor                                                        Martel
    //==========================================================================
    function clsAge($iNewAgeNumber = 0, $iNewYearStart = 0, $iNewYearEnd = 0)
    {
        $this->_iAgeNumber = $iNewAgeNumber;
        $this->_iYearStart = $iNewYearStart;
        $this->_iYearEnd   = $iNewYearEnd;
    }

    //==========================================================================
    // SQL Interface Methods                                              Martel
    //
    // loadAge($iRequestedAge)  - load an age from the database, or return false
    // saveAge()                - save an age to the database
    // deleteAge()              - delete an age from the database
    //==========================================================================

    // Returns true or false to catch errors
    function loadAge($iRequestedAge)
    {
        // Load from DB
        $strSQL = "SELECT * FROM ages WHERE age_number = $iRequestedAge";
        $resSQL = mysql_query($strSQL);
        if (!isset($resSQL))
            return FALSE;
        $arrRES = mysql_fetch_array($resSQL);
        if (empty($arrRES)) // Catch empty results
            return FALSE;

        // Update Internal Variables
        $this->_iAgeNumber = $arrRES['age_number'];
        $this->_iYearStart = $arrRES['year_start'];
        $this->_iYearEnd   = $arrRES['year_end'];

        return TRUE;
    }

    function saveAge()
    {
        $strSQL = "INSERT INTO ages SET " .
                  "age_number = " . $this->_iAgeNumber .
                  ", " .
                  "year_start = " . $this->_iYearStart .
                  ", " .
                  "year_end   = " . $this->_iYearEnd;
        $resSQL = mysql_query($strSQL) or die(mysql_error());
    }

    function deleteAge()
    {
        if (!empty($this->_iAgeNumber))
            mysql_query("DELETE FROM ages WHERE age_number = " . $this->_iAgeNumber);
        else
            return FALSE;
    }

    //==========================================================================
    // Get Methods                                                        Martel
    //
    // getAgeNumber()           - returns age integer
    // getFirstYear()           - returns year started integer
    // getLastYear()            - returns year end integer
    // getAgeLength()           - returns sum years integer
    //==========================================================================

    function getAgeNumber()
    {
        if (!empty($this->_iAgeNumber))
            return $this->_iAgeNumber;
        else
            return FALSE;
    }

    function getFirstYear()
    {
        if (!empty($this->_iYearStart))
            return $this->_iYearStart;
        else
            return FALSE;
    }

    function getLastYear()
    {
        if (!empty($this->_iYearEnd))
            return $this->_iYearEnd;
        else
            return FALSE;
    }

    // Returns length of age in years
    function getAgeLength()
    {
        if (!empty($this->_iYearEnd) && !empty($this->_iYearStart))
            return abs($this->_iYearEnd - $this->_iYearStart);
        else
            return FALSE;
    }
}

?>