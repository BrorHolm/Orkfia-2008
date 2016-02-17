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

class clsAlliance
{
    var $_iAllianceId;
    var $_arrAlliance;
    var $_arrRankingAlliances;
    var $_arrWar;
    var $_arrIUsers;
    var $_arrObjUsers;


    function clsAlliance($iAllianceId) {
        $this->_iAllianceId = $iAllianceId;
    }


    function set_allianceid($allianceid) {
        $this->_iAllianceId = $allianceid;
        // re-initialising for a new alliance so clear the other internal info
        unset($this->_arrAlliance);
        unset($this->_arrRankingAlliances);
        unset($this->_arrWar);
        unset($this->_arrIUsers);
        unset($this->_arrObjUsers);
    }


    function get_allianceid() {
        return $this->_iAllianceId;
    }


    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function get_tbl_data($tbl) {
        $strSQL = "SELECT * " .
                  "  FROM $tbl " .
                  " WHERE id = ".$this->get_allianceid();
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("get_tbl_data:" . mysql_error());
        $arr = mysql_fetch_array($result, MYSQL_ASSOC) or die("get_tbl_data:" . mysql_error());
//var_dump($arr);
        return $arr;
        mysql_free_result ($result);
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function set_tbl_data($i_strTable, $i_strFieldName, $i_DataValue) {
        $strSQL = "UPDATE $i_strTable " .
                  "   SET $i_strFieldName = " . $this->quote_smart($i_DataValue) .
                  " WHERE id = ". $this->get_allianceid();
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_tbl_data:" . mysql_error() );
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
                " WHERE id = " . $this->get_allianceid();
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_tbl_data:" . mysql_error() );
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

    /***************************************************************************
     * alliance interface
     *
     * get_alliance_infos - return a row in an array from tblAlliance
     * get_alliance_info  - return a field from the current row of tblAlliance
     * set_alliance_infos - save the entire row to tblAlliance
     * set_alliance_info  - save a single field to tblAlliance
     *
     **************************************************************************/
    function get_alliance_infos() {
        if (empty($this->_arrAlliance)) {
            $this->_arrAlliance = $this->get_tbl_data(TBL_ALLIANCE);
//var_dump ($this->_arrAlliance);
        }
        return $this->_arrAlliance;
    }


    function get_alliance_info($i_strAllianceType) {
//echo "INFO:\"$i_strAllianceType\"<BR>";
        if (empty($this->_arrAlliance)) {
            $this->_arrAlliance = $this->get_tbl_data(TBL_ALLIANCE);
        }

        // so what if it's not set? at least $retVal is set to the same
        //  null-equivalent value (empty string for instance) - AI
        //if (isset($this->_arrAlliance[$i_strAllianceType])) {
            $retVal = $this->_arrAlliance[$i_strAllianceType];
        //}
        return $retVal;
    }


    function set_alliance_infos($i_arrDataValues) {
//printf ("SET_ALLIANCE_INFOS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrAlliance)) {
            $this->_arrAlliance = $this->get_tbl_data(TBL_ALLIANCE);
        }
        $this->set_tbl_row(TBL_ALLIANCE, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrAlliance[$col] = $val;
                } else {
                    unset($this->_arrAlliance[$col]);
                }
            }
        }
    }

    function set_alliance_info($i_strAllianceType, $i_DataValue) {
//printf ("SET_ALLIANCE:%s-%d<BR>", $i_strAllianceType, $i_DataValue);
        if (empty($this->_arrAlliance)) {
            $this->_arrAlliance = $this->get_tbl_data(TBL_ALLIANCE);
        }
        $this->set_tbl_data(TBL_ALLIANCE, $i_strAllianceType, $i_DataValue);
        $this->_arrAlliance[$i_strAllianceType] = $i_DataValue;
    }


    /***************************************************************************
     * rankings_alliances interface
     *
     * get_rankings_alliances - return a row in an array from tblRankings_Alliances
     * get_rankings_alliance  - return a field from the current row of tblRankings_Alliances
     * set_rankings_alliances - save the entire row to tblRankings_Alliances
     * set_rankings_alliance  - save a single field to tblRankings_Alliances
     *
     **************************************************************************/
    function get_rankings_alliances() {
        if (empty($this->_arrRankingAlliances)) {
            $this->_arrRankingAlliances = $this->get_tbl_data(TBL_RANKINGS_ALLIANCE);
//var_dump ($this->_arrRankingAlliances);
        }
        return $this->_arrRankingAlliances;
    }


    function get_rankings_alliance($i_strRankingAllianceType) {
//echo "INFO:\"$i_strRankingAllianceType\"<BR>";
        if (empty($this->_arrRankingAlliances)) {
            $this->_arrRankingAlliances = $this->get_tbl_data(TBL_RANKINGS_ALLIANCE);
        }

        if (isset($this->_arrRankingAlliances[$i_strRankingAllianceType])) {
            $retVal = $this->_arrRankingAlliances[$i_strRankingAllianceType];
        }
        return $retVal;
    }


    function set_rankings_alliances($i_arrDataValues) {
//printf ("SET_RANKINGS_ALLIANCES:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrRankingsAlliances)) {
            $this->_arrRankingsAlliances = $this->get_tbl_data(TBL_RANKINGS_ALLIANCE);
        }
        $this->set_tbl_row(TBL_RANKINGS_ALLIANCE, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrRankingsAlliances[$col] = $val;
                } else {
                    unset($this->_arrRankingsAlliances[$col]);
                }
            }
        }
    }

    function set_rankings_alliance($i_strRankingAllianceType, $i_DataValue) {
//printf ("SET_RANKINGS_ALLIANCES:%s-%d<BR>", $i_strRankingAllianceType, $i_DataValue);
        if (empty($this->_arrRankingAlliances)) {
            $this->_arrRankingAlliances = $this->get_tbl_data(TBL_RANKINGS_ALLIANCE);
        }
        $this->set_tbl_data(TBL_RANKINGS_ALLIANCE, $i_strRankingAllianceType, $i_DataValue);
        $this->_arrRankingAlliances[$i_strRankingAllianceType] = $i_DataValue;
    }


    /***************************************************************************
     * war interface
     *
     * get_wars - return a row in an array from tblWar
     * get_war  - return a field from the current row of tblWar
     * set_wars - save the entire row to tblWar
     * set_war  - save a single field to tblWar
     *
     **************************************************************************/
    function get_wars() {
        if (empty($this->_arrWar)) {
            $this->_arrWar= $this->get_tbl_data(TBL_WAR);
//var_dump ($this->_arrWar);
        }
        return $this->_arrWar;
    }


    function get_war($i_strWarType) {
//echo "INFO:\"$i_strWarType\"<BR>";
        if (empty($this->_arrWar)) {
            $this->_arrWar = $this->get_tbl_data(TBL_WAR);
        }

        if (isset($this->_arrWar[$i_strWarType])) {
            $retVal = $this->_arrWar[$i_strWarType];
        }
        return $retVal;
    }


    function set_wars($i_arrDataValues) {
//printf ("SET_WARS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrWars)) {
            $this->_arrWars = $this->get_tbl_data(TBL_WAR);
        }
        $this->set_tbl_row(TBL_WAR, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrWars[$col] = $val;
                } else {
                    unset($this->_arrWars[$col]);
                }
            }
        }
    }

    function set_war($i_strWarType, $i_DataValue) {
//printf ("SET_WAR:%s-%d<BR>", $i_strWarType, $i_DataValue);
        if (empty($this->_arrWar)) {
            $this->_arrWar = $this->get_tbl_data(TBL_WAR);
        }
        $this->set_tbl_data(TBL_WAR, $i_strWarType, $i_DataValue);
        $this->_arrWar[$i_strWarType] = $i_DataValue;
    }

    /***************************************************************************
     * users interface
     *
     * get_userids - return an array ids of the members of the alliance
     * get_userid  - return a user id from a slot in the alliance
     * add_user    - add a user to this alliance, and remove it from old alliance
     * del_user    - delete a user from this alliance
     * delete_users- delete all users in this alliance from the DB (!)
     *
     **************************************************************************/
    function get_userids() {
        if (empty($this->_arrIUsers)) {
            $this->_arrIUsers = array();
            $strSQL = "SELECT " . TBL_STAT . "." . ID . " FROM " . TBL_STAT . " LEFT JOIN " . TBL_BUILD . " ON " . TBL_STAT . ".id = " . TBL_BUILD . ".id WHERE " . ALLIANCE . " = " . $this->_iAllianceId . " ORDER BY " . LAND . " DESC";
            $result = mysql_query ($strSQL) or die("get_userids:" . mysql_error() );
            $i = 1;
            while ($myrow = mysql_fetch_array($result))
            {
                $this->_arrIUsers[$i] = $myrow[ID];
                $i++;
            }
        }
        return $this->_arrIUsers;
    }

    function get_userid($index) {
        $this->get_users();
        return $this->_arrIUsers[$index];
    }

    function get_freeslot() {
        $i = 1;
        while (isset($this->_arrObjUsers[$i])) {
            $i++;
        }
        return $i;
    }

    function add_user(&$objUser) {
        $this->get_users();

        $uid = $objUser->get_userid();
        $objOldAlliance = $objUser->get_alliance();

        //User is already in this alliance
        if ($objOldAlliance->get_allianceid() == $this->get_allianceid()) {
            return;
        }
        for ($i = 1; $i <= MAX_ALLIANCE_SIZE; $i++) {
            //User is already in this alliance
            if ($uid == $this->get_user($i)) {
                return;
            }
        }
        $i = get_freeslot();

        if ($i <= MAX_ALLIANCE_SIZE)
        {
            $objOldAlliance->del_user($objUser);
            $this->_arrObjUsers[$i] = $objUser;
            $objUser->set_stat(KINGDOM, $this->get_allianceid());
        }
    }

    function del_user(&$objUser) {
        $this->get_users();
        for ($i = 1; $i <= MAX_ALLIANCE_SIZE; $i++) {
            if ($objUser->get_userid() == $this->get_userid($i)) {
                unset($this->_arrObjUsers[$i]);
                return;
            }
        }
    }

    function delete_users()
    {
        if (empty($this->_arrIUsers)) {
            $arrUserId = $this->get_userids();
        }
        else {
            $arrUserId = $this->_arrIUsers;
        }

        $iDeleted = 0;
        if (count($arrUserId) > 0) {
            $arrUserTables = array(1 => TBL_ARMY, TBL_ARMY_MERCS, TBL_BUILD,
            TBL_DESIGN, TBL_GOODS, TBL_KILLS, TBL_MILRETURN, TBL_ONLINE, TBL_POP,
            TBL_PREFERENCES, TBL_RANKINGS_PERSONAL, TBL_SPELL, TBL_STAT,
            TBL_THIEVERY, TBL_USER);

            foreach ($arrUserId as $iId) {
                foreach ($arrUserTables as $table) {
                    mysql_query("DELETE FROM " . $table . " WHERE id = " . $iId);
                }
                $iDeleted++;
            }
        }

        return $iDeleted;
    }

    /***************************************************************************
     * general interface
     *
     * get_alliance_size - return total land of the alliance
     * do_update_ranking() - saves current alliance rankings to table
     * get_alliance_sciences() - return science levels rounded to 4 decimals
     **************************************************************************/
    function get_alliance_size()
    {
        $query  = "SELECT SUM(build.land) AS total_land FROM stats, build WHERE " .
                  "stats.id = build.id AND stats." . ALLIANCE . " = " . $this->_iAllianceId;
        $result = mysql_query($query);
        $myrow  = mysql_fetch_row($result);

        $alliance_size = 0 + $myrow[0];

        return $alliance_size;
    }

    // Update the rankings of an alliance                            Martel 2007
    function do_update_ranking()
    {
        $strSQL =
            "SELECT " . "SUM(" . LAND . ") AS " . LAND . ", " .
                        "SUM(" . FAME . ") AS " . FAME . ", " .
                        "SUM(" . STRENGTH . ") AS " . STRENGTH . " " .
            "FROM " . TBL_RANKINGS_PERSONAL . " " .
            "WHERE " .ALLI_ID . " = " . $this->_iAllianceId;
        $result = mysql_query($strSQL) or die("clsAlliance: do_update_ranking");
        $arrRes = mysql_fetch_assoc($result);

        $strAlliName = $this->get_alliance_info(NAME);
        $strAlliDesc = $this->get_alliance_info(DESCRIPTION);

        $this->_arrRankingAlliances = array
        (
            LAND => $arrRes[LAND],
            FAME => $arrRes[FAME],
            STRENGTH => $arrRes[STRENGTH],
            ALLI_NAME => $strAlliName,
            ALLI_DESC => $strAlliDesc,
            LAST_UPDATE => date(TIMESTAMP_FORMAT, time())
        );
        $this->set_rankings_alliances($this->_arrRankingAlliances);
    }

    // Get sciences with 4 decimals                     Martel December 22, 2007
    function get_alliance_sciences()
    {
        // Added research decay system - AI 07/05/07
        require_once('inc/functions/research.php');
        research_update($this->_iAllianceId);

        $iTotalLand   = $this->get_alliance_size();
        $arrAlliance  = $this->get_alliance_infos();
        $arrStr       = array('eng' => HOME_BONUS,    'prod' => INCOME_BONUS,
                              'def' => DEFENCE_BONUS, 'war'  => OFFENCE_BONUS);

        foreach ($arrStr as $str => $DB_CONSTANT)
        {
            $arrSci[$str] = 1.98 * $arrAlliance[$DB_CONSTANT] / (80
                          * $iTotalLand + $arrAlliance[$DB_CONSTANT]);
            $arrSci[$str] = round($arrSci[$str], 4);

            // M: 0.66 cap      M#2: doesn't exist.. control this in bonuses.php
            if ($arrSci[$str] > 0.66) $arrSci[$str] = 0.66;
        }

        return $arrSci;
    }

    function getSciences($aid = 0) { return $this->get_science_bonuses(); }
}



?>
