<?php

/*

Read "Making arguments be passed by reference" for an example.

http://www.php.net/manual/en/functions.arguments.php

Only modify and use the actual alliance object.
eg: $iLand = get_alliance_land($objalliance);
    function get_alliance_land(&$objAlliance) {
       function center...
    }


*/

class clsGame
{
    var $_arrGame;
    var $_arrTime;
    var $_arrSwitches;
    var $_arrRecords;

    function clsGame() {
    }

    function clear_object() {
        // re-initialising for a new instance so clear the other internal info
        unset($_arrTime);
        unset($_arrSwitches);
        unset($_arrRecords);
    }


    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function get_tbl_data($tbl) {
        $strSQL = "SELECT * " .
                  "  FROM $tbl ";
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("get_game_tbl_data:" . mysql_error());
        $arr = mysql_fetch_array($result, MYSQL_ASSOC) or die("get_game_tbl_data:" . mysql_error());
//var_dump($arr);
        return $arr;
        mysql_free_result ($result);
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function set_tbl_data($i_strTable, $i_strFieldName, $i_DataValue) {
        $strSQL = "UPDATE $i_strTable " .
                  "   SET $i_strFieldName = " . $this->quote_smart($i_DataValue);
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_game_tbl_data:" . mysql_error() );
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function set_tbl_row($i_strTable, $i_arrDataValues) {
        $sqldata = "SET ";
        $blnStarted = FALSE;
        reset($i_arrDataValues);
        while (list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if ($blnStarted) { $sqldata .= ", "; }
                if (isset($val)) {
                    $sqldata .= $col . " = " . $this->quote_smart($val);
                } else {
                    $sqldata .= $col . " = NULL";
                }
                $blnStarted = TRUE;
            }
        }
        $strSQL = "UPDATE $i_strTable " . $sqldata .
                " WHERE id = 1";
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_game_tbl_data:" . mysql_error() );
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function quote_smart($value)
    {
        if(get_magic_quotes_gpc())
        {
            $value = stripslashes($value);
        }
        if (substr($value, 0, 1) == "'"){ // Temp fix for manually added 's
            $value = substr($value, 1, -1);
        }
        if(!is_numeric($value)) {
            $value = "'" . mysql_real_escape_string($value) . "'";
        }
        return $value;
    }

    /***********************************************************************
     * game interface
     *
     * get_game_infos - return a row in an array from tblGame
     * get_game_info  - return a field from the current row of tblGame
     * set_game_infos - save the entire row to tblGame
     * set_game_info  - save a single field to tblGame
     *
     ***********************************************************************/


    function get_game_infos() {
        if (empty($this->_arrGame)) {
            $this->_arrGame = $this->get_tbl_data(TBL_GAME);
            //var_dump ($this->_arrGame);
        }
        return $this->_arrGame;
    }


    function get_game_info($i_strType) {
        //echo "INFO:\"$i_strType\"<BR>";
        if (empty($this->_arrGame)) {
            $this->_arrGame = $this->get_tbl_data(TBL_GAME);
        }

        if (isset($this->_arrGame[$i_strType])) {
            $retVal = $this->_arrGame[$i_strType];
        }
        return $retVal;
    }


    function set_game_infos($i_arrDataValues) {
//printf ("SET_GAME_INFOS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrGame)) {
            $this->_arrGame = $this->get_tbl_data(TBL_GAME);
        }
        $this->set_tbl_row(TBL_GAME, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrGame[$col] = $val;
                } else {
                    unset($this->_arrGame[$col]);
                }
            }
        }
    }

    function set_game_info($i_strType, $i_DataValue) {
        //printf ("SET_GAME_INFO:%s-%d<BR>", $i_strType, $i_DataValue);
        if (empty($this->_arrGame)) {
            $this->_arrGame = $this->get_tbl_data(TBL_GAME);
        }
        $this->set_tbl_data(TBL_GAME, $i_strType, $i_DataValue);
        $this->_arrGame[$i_strType] = $i_DataValue;
    }

    /***********************************************************************
     * time interface
     *
     * get_game_times - return a row in an array from tblGameTime
     * get_game_time  - return a field from the current row of tblGameTime
     * set_game_times - save the entire row to tblGameTime
     * set_game_time  - save a single field to tblGameTime
     *
     ***********************************************************************/


    function get_game_times() {
        if (empty($this->_arrTime)) {
            $this->_arrTime = $this->get_tbl_data(TBL_GAME_TIME);
            //var_dump ($this->_arrTime);
        }
        return $this->_arrTime;
    }


    function get_game_time($i_strType) {
        //echo "INFO:\"$i_strType\"<BR>";
        if (empty($this->_arrTime)) {
            $this->_arrTime = $this->get_tbl_data(TBL_GAME_TIME);
        }

        if (isset($this->_arrTime[$i_strType])) {
            $retVal = $this->_arrTime[$i_strType];
        }
        return $retVal;
    }


    function set_game_times($i_arrDataValues) {
//printf ("SET_GAME_TIMES:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrTime)) {
            $this->_arrTime = $this->get_tbl_data(TBL_GAME_TIME);
        }
        $this->set_tbl_row(TBL_GAME_TIME, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrTime[$col] = $val;
                } else {
                    unset($this->_arrTime[$col]);
                }
            }
        }
    }

    function set_game_time($i_strType, $i_DataValue) {
        //printf ("SET_GAME_TIME:%s-%d<BR>", $i_strType, $i_DataValue);
        if (empty($this->_arrTime)) {
            $this->_arrTime = $this->get_tbl_data(TBL_GAME_TIME);
        }
        $this->set_tbl_data(TBL_GAME_TIME, $i_strType, $i_DataValue);
        $this->_arrTime[$i_strType] = $i_DataValue;
    }

    /***********************************************************************
     * switches interface
     *
     * get_game_switches - return a row in an array from tblGameSwitches
     * get_game_switch  - return a field from the current row of tblGameSwitches
     * set_game_switches - save the entire row to tblGameSwitches
     * set_game_switch  - save a single field to tblGameSwitches
     *
     ***********************************************************************/


    function get_game_switches() {
        if (empty($this->_arrSwitches)) {
            $this->_arrSwitches = $this->get_tbl_data(TBL_GAME_SWITCHES);
            //var_dump ($this->_arrSwitches);
        }
        return $this->_arrSwitches;
    }


    function get_game_switch($i_strType) {
        //echo "INFO:\"$i_strType\"<BR>";
        if (empty($this->_arrSwitches)) {
            $this->_arrSwitches = $this->get_tbl_data(TBL_GAME_SWITCHES);
        }

        if (isset($this->_arrSwitches[$i_strType])) {
            $retVal = $this->_arrSwitches[$i_strType];
        }
        return $retVal;
    }


    function set_game_switches($i_arrDataValues) {
//printf ("SET_GAME_SWITCHES:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrSwitches)) {
            $this->_arrSwitches = $this->get_tbl_data(TBL_GAME_SWITCHES);
        }
        $this->set_tbl_row(TBL_GAME_SWITCHES, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrSwitches[$col] = $val;
                } else {
                    unset($this->_arrSwitches[$col]);
                }
            }
        }
    }

    function set_game_switch($i_strType, $i_DataValue) {
        //printf ("SET_GAME_SWITCH:%s-%d<BR>", $i_strType, $i_DataValue);
        if (empty($this->_arrSwitches)) {
            $this->_arrSwitches = $this->get_tbl_data(TBL_GAME_SWITCHES);
        }
        $this->set_tbl_data(TBL_GAME_SWITCHES, $i_strType, $i_DataValue);
        $this->_arrSwitches[$i_strType] = $i_DataValue;
    }

    /***********************************************************************
     * tblGameRecords interface
     *
     * get_records - return a row in an array from tblGameRecords
     * get_record  - return a field from the current row of tblGameRecords
     * set_records - save the entire row to tblGameRecords
     * set_record  - save a single field to tblGameRecords
     *
     ***********************************************************************/


    function get_game_records() {
        if (empty($this->_arrRecords)) {
            $this->_arrRecords = $this->get_tbl_data(TBL_GAME_RECORDS);
            //var_dump ($this->_arrRecords);
        }
        return $this->_arrRecords;
    }


    function get_game_record($i_strType) {
        //echo "INFO:\"$i_strType\"<BR>";
        if (empty($this->_arrRecords)) {
            $this->_arrRecords = $this->get_tbl_data(TBL_GAME_RECORDS);
        }

        if (isset($this->_arrRecords[$i_strType])) {
            $retVal = $this->_arrRecords[$i_strType];
        }
        return $retVal;
    }


    function set_game_records($i_arrDataValues) {
//printf ("SET_GAME_RECORDS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrRecords)) {
            $this->_arrRecords = $this->get_tbl_data(TBL_GAME_RECORDS);
        }
        $this->set_tbl_row(TBL_GAME_RECORDS, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrRecords[$col] = $val;
                } else {
                    unset($this->_arrRecords[$col]);
                }
            }
        }
    }

    function set_game_record($i_strType, $i_DataValue) {
        //printf ("SET_GAME_RECORD:%s-%d<BR>", $i_strType, $i_DataValue);
        if (empty($this->_arrRecords)) {
            $this->_arrRecords = $this->get_tbl_data(TBL_GAME_RECORDS);
        }
        $this->set_tbl_data(TBL_GAME_RECORDS, $i_strType, $i_DataValue);
        $this->_arrRecords[$i_strType] = $i_DataValue;
    }

    /***********************************************************************
     * Various methods interface
     *
     * get_year_oe - returns the current year since the orkfia epoch (year 0)
     * get_year_history - returns the most recent year saved in history rankings
     *
     ***********************************************************************/

    function get_year_oe()
    {
        $iOrkYears  = floor($this->get_game_time(HOUR_COUNTER) / 12);

        return $iOrkYears;
    }

    function get_year_history()
    {
        $strSQL = "SELECT " . YEAR .
                 " FROM " . TBL_GAME_HISTORY .
                 " ORDER BY " . YEAR . " DESC " .
                  "LIMIT 0,1";

        if ($result = mysql_query($strSQL))
        {
            $arrRes = mysql_fetch_array($result);
            return $arrRes[YEAR];
        }
        else
            return 0;
    }

    /***********************************************************************
     * tblGameHistory interface
     * info - once working maybe put the new private functions at top
     *
     * get_historys - return a row in an array from tblGameHistory
     * set_historys - save the entire row to tblGameHistory
     *
     ***********************************************************************/

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function get_tbl_data2($tbl, $year) {
        $strSQL = "SELECT * " .
                  "  FROM $tbl " .
                  "WHERE year = $year";
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("get_game_tbl_data:" . mysql_error());
        $arr = @mysql_fetch_array($result, MYSQL_ASSOC);
//var_dump($arr);
        return $arr;
        mysql_free_result ($result);
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function set_tbl_row2($i_strTable, $i_arrDataValues) {
        $sqldata = "SET ";
        $blnStarted = FALSE;
        reset($i_arrDataValues);
        while (list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if ($blnStarted) { $sqldata .= ", "; }
                if (isset($val)) {
                    $sqldata .= $col . " = " . $this->quote_smart($val);
                } else {
                    $sqldata .= $col . " = NULL";
                }
                $blnStarted = TRUE;
            }
        }
        $strSQL = "INSERT INTO $i_strTable " . $sqldata;
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_game_tbl2_data:" . mysql_error() );
    }


    function get_historys($year)
    {
        $arrRow = $this->get_tbl_data2(TBL_GAME_HISTORY, $year);

        return $arrRow;
    }


    function set_historys($i_arrDataValues) {
//printf ("SET_HISTORYS:%s-%d<BR>", $i_arrDataValues);
        $this->set_tbl_row2(TBL_GAME_HISTORY, $i_arrDataValues);
        reset($i_arrDataValues);
    }

}



?>
