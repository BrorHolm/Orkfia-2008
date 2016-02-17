<?php

/*

I'll put this into the special $GLOBALS[] array...
So get to it via $GLOBALS[$user], but also pass it
around as a reference to a function:

Read "Making arguments be passed by reference" for an example.

http://www.php.net/manual/en/functions.arguments.php

Only modify and use the actual user object.
eg: $iMageLevel = get_mage_level($objUser); // $i at the start means integer;
    function get_mage_level(&$objUser) {
       function center...
    }


*/

class clsUser
{
    var $_iUserId;
    var $_arrPreferences;
    var $_arrRankingsPersonal;
    var $_arrRegCheck;
    var $_arrDesign;
    var $_arrGameStats;
    var $_arrOnline;
    var $_arrBuild;
    var $_arrStat;
    var $_arrUserInfo;
    var $_arrSpell;
    var $_arrGood;
    var $_arrArmy;
    var $_arrArmyMercs;
    var $_arrMilReturn;
    var $_arrPop;
    var $_arrThievery;
    var $_arrKills;
    var $_objAlliance;
    var $_iStrength;
    var $_objRace;


    function clsUser($iUserId) {
        $this->_iUserId = $iUserId;
    }


    function set_userid($userid) {
        $this->_iUserId = $userid;
        // re-initialising for a new user so clear the other internal info
        unset($this->_arrPreferences);
        unset($this->_arrRankingsPersonal);
        unset($this->_arrRegCheck);
        unset($this->_arrDesign);
        unset($this->_arrGameStats);
        unset($this->_arrOnline);
        unset($this->_arrBuild);
        unset($this->_arrStat);
        unset($this->_arrUserInfo);
        unset($this->_arrSpell);
        unset($this->_arrGood);
        unset($this->_arrArmy);
        unset($this->_arrArmyMercs);
        unset($this->_arrMilReturn);
        unset($this->_arrPop);
        unset($this->_arrThievery);
        unset($this->_arrKills);
        unset($this->_objAlliance);
        unset($this->_iStrength);
        unset($this->_objRace);
    }


    function get_userid() {
        return $this->_iUserId;
    }


    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function get_tbl_data($tbl) {
        $strSQL = "SELECT * " .
                  "  FROM $tbl " .
                  " WHERE id = ".$this->get_userid();
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("get_user_tbl_data:" . mysql_error());
        $arr = mysql_fetch_array($result, MYSQL_ASSOC) or die("get_user_tbl_data:" . mysql_error());
//var_dump($arr);
        return $arr;
        mysql_free_result ($result);
    }

    /* THIS IS A PRIVATE FUNCTION, NEVER CALL IT DIRECTLY */
    function set_tbl_data($i_strTable, $i_strFieldName, $i_DataValue) {
        $strSQL = "UPDATE $i_strTable " .
                  "   SET $i_strFieldName = " . $this->quote_smart($i_DataValue) .
                  " WHERE id =". $this->get_userid();
//echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_user_tbl_data:" . mysql_error() );
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
                " WHERE id = " . $this->get_userid();
// echo "SQL:\"$strSQL\"<BR>";
        $result = mysql_query ($strSQL) or die("set_user_tbl_data:" . mysql_error() );
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
     * preferences interface
     *
     * get_preferences - return a row in an array from tblPreference
     * get_preference  - return a field from the current row of tblPreferences
     * set_preferences - save the entire row to tblPreferences
     * set_preference  - save a single field to tblPrefernces
     *
     ***********************************************************************/
    function get_preferences() {
        if (empty($this->_arrPreferences)) {
            $this->_arrPreferences = $this->get_tbl_data(TBL_PREFERENCES);
//var_dump ($this->_arrPreferences);
        }
        return $this->_arrPreferences;
    }


    function get_preference($i_strPreferenceType) {
//echo "INFO:\"$i_strPreferenceType\"<BR>";
        if (empty($this->_arrPreferences)) {
            $this->_arrPreferences = $this->get_tbl_data(TBL_PREFERENCES);
        }

        if (isset($this->_arrPreferences[$i_strPreferenceType])) {
            $retVal = $this->_arrPreferences[$i_strPreferenceType];
        }
        return $retVal;
    }


    function set_preferences($i_arrDataValues) {
//printf ("SET_PREFERENCES:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrPreferences)) {
            $this->_arrPreferences = $this->get_tbl_data(TBL_PREFERENCES);
        }
        $this->set_tbl_row(TBL_PREFERENCES, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrPreferences[$col] = $val;
                } else {
                    unset($this->_arrPreferences[$col]);
                }
            }
        }
    }

    function set_preference($i_strPreferenceType, $i_DataValue) {
//printf ("SET_PREFERENCES:%s-%d<BR>", $i_strPreferenceType, $i_DataValue);
        if (empty($this->_arrPreferences)) {
            $this->_arrPreferences = $this->get_tbl_data(TBL_PREFERENCES);
        }
        $this->set_tbl_data(TBL_PREFERENCES, $i_strPreferenceType, $i_DataValue);
        $this->_arrPreferences[$i_strPreferenceType] = $i_DataValue;
    }


    /***********************************************************************
     * rankings_personal interface
     *
     * get_rankings_personals - return a row in an array from tblRankings_Personal
     * get_rankings_personal  - return a field from the current row of tblRankings_Personal
     * set_rankings_personals - save the entire row to tblRankings_Personal
     * set_rankings_personal  - save a single field to tblRankings_Personal
     *
     ***********************************************************************/
    function get_rankings_personals() {
        if (empty($this->_arrRankingsPersonal)) {
            $this->_arrRankingsPersonal = $this->get_tbl_data(TBL_RANKINGS_PERSONAL);
//var_dump ($this->_arrRankingsPersonal);
        }
        return $this->_arrRankingsPersonal;
    }


    function get_rankings_personal($i_strRankingPersonalType) {
//echo "INFO:\"$i_strRankingPersonalType\"<BR>";
        if (empty($this->_arrRankingsPersonal)) {
            $this->_arrRankingsPersonal = $this->get_tbl_data(TBL_RANKINGS_PERSONAL);
        }

        if (isset($this->_arrRankingsPersonal[$i_strRankingPersonalType])) {
            $retVal = $this->_arrRankingsPersonal[$i_strRankingPersonalType];
        }
        return $retVal;
    }


    function set_rankings_personals($i_arrDataValues) {
//printf ("SET_RANKINGS_PERSONAL:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrRankingsPersonal)) {
            $this->_arrRankingsPersonal = $this->get_tbl_data(TBL_RANKINGS_PERSONAL);
        }
        $this->set_tbl_row(TBL_RANKINGS_PERSONAL, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrRankingsPersonal[$col] = $val;
                } else {
                    unset($this->_arrRankingsPersonal[$col]);
                }
            }
        }
    }

    function set_rankings_personal($i_strRankingPersonalType, $i_DataValue) {
//printf ("SET_RANKINGS_PERSONAL:%s-%d<BR>", $i_strRankingPersonalType, $i_DataValue);
        if (empty($this->_arrRankingsPersonal)) {
            $this->_arrRankingsPersonal = $this->get_tbl_data(TBL_RANKINGS_PERSONAL);
        }
        $this->set_tbl_data(TBL_RANKINGS_PERSONAL, $i_strRankingPersonalType, $i_DataValue);
        $this->_arrRankingsPersonal[$i_strRankingPersonalType] = $i_DataValue;
    }


    /***********************************************************************
     * reg_check interface
     *
     * get_reg_checks - return a row in an array from tblReg_Check
     * get_reg_check  - return a field from the current row of tblReg_Check
     * set_reg_checks - save the entire row to tblReg_Check
     * set_reg_check  - save a single field to tblReg_Check
     *
     ***********************************************************************/
    function get_reg_checks() {
        if (empty($this->_arrRegCheck)) {
            $this->_arrRegCheck= $this->get_tbl_data(TBL_REG_CHECK);
//var_dump ($this->_arrRegCheck);
        }
        return $this->_arrRegCheck;
    }


    function get_reg_check($i_strRegCheckType) {
//echo "INFO:\"$i_strRegCheckType\"<BR>";
        if (empty($this->_arrRegCheck)) {
            $this->_arrRegCheck = $this->get_tbl_data(TBL_REG_CHECK);
        }

        if (isset($this->_arrRegCheck[$i_strRegCheckType])) {
            $retVal = $this->_arrRegCheck[$i_strRegCheckType];
        }
        return $retVal;
    }


    function set_reg_checks($i_arrDataValues) {
//printf ("SET_REG_CHECK:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrRegCheck)) {
            $this->_arrRegCheck = $this->get_tbl_data(TBL_REG_CHECK);
        }
        $this->set_tbl_row(TBL_REG_CHECK, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrRegCheck[$col] = $val;
                } else {
                    unset($this->_arrRegCheck[$col]);
                }
            }
        }
    }

    function set_reg_check($i_strRegCheckType, $i_DataValue) {
//printf ("SET_REG_CHECK:%s-%d<BR>", $i_strRegCheckType, $i_DataValue);
        if (empty($this->_arrRegCheck)) {
            $this->_arrRegCheck = $this->get_tbl_data(TBL_REG_CHECK);
        }
        $this->set_tbl_data(TBL_REG_CHECK, $i_strRegCheckType, $i_DataValue);
        $this->_arrRegCheck[$i_strRegCheckType] = $i_DataValue;
    }


    /***********************************************************************
     * design interface
     *
     * get_designs - return a row in an array from tblDesign
     * get_design  - return a field from the current row of tblDesign
     * set_designs - save the entire row to tblDesign
     * set_design  - save a single field to tblDesign
     *
     ***********************************************************************/
    function get_designs() {
        if (empty($this->_arrDesign)) {
            $this->_arrDesign= $this->get_tbl_data(TBL_DESIGN);
//var_dump ($this->_arrDesign);
        }
        return $this->_arrDesign;
    }


    function get_design($i_strDesignType) {
//echo "INFO:\"$i_strDesignType\"<BR>";
        if (empty($this->_arrDesign)) {
            $this->_arrDesign = $this->get_tbl_data(TBL_DESIGN);
        }

        if (isset($this->_arrDesign[$i_strDesignType])) {
            $retVal = $this->_arrDesign[$i_strDesignType];
        }
        return $retVal;
    }


    function set_designs($i_arrDataValues) {
//printf ("SET_DESIGN:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrDesign)) {
            $this->_arrDesign = $this->get_tbl_data(TBL_DESIGN);
        }
        $this->set_tbl_row(TBL_DESIGN, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrDesign[$col] = $val;
                } else {
                    unset($this->_arrDesign[$col]);
                }
            }
        }
    }

    function set_design($i_strDesignType, $i_DataValue) {
//printf ("SET_DESIGN:%s-%d<BR>", $i_strDesignType, $i_DataValue);
        if (empty($this->_arrDesign)) {
            $this->_arrDesign = $this->get_tbl_data(TBL_DESIGN);
        }
        $this->set_tbl_data(TBL_DESIGN, $i_strDesignType, $i_DataValue);
        $this->_arrDesign[$i_strDesignType] = $i_DataValue;
    }


    /***********************************************************************
     * gamestats interface
     *
     * get_gamestats - return a row in an array from tblGameStats
     * get_gamestat  - return a field from the current row of tblGameStats
     * set_gamestats - save the entire row to tblGameStats
     * set_gamestat  - save a single field to tblGameStats
     *
     ***********************************************************************/
    function get_gamestats() {
        if (empty($this->_arrGameStats)) {
            $this->_arrGameStats= $this->get_tbl_data(TBL_GAMESTATS);
//var_dump ($this->_arrGameStats);
        }
        return $this->_arrGameStats;
    }


    function get_gamestat($i_strGameStatType) {
//echo "INFO:\"$i_strGameStatType\"<BR>";
        if (empty($this->_arrGameStats)) {
            $this->_arrGameStats = $this->get_tbl_data(TBL_GAMESTATS);
        }

        if (isset($this->_arrGameStats[$i_strGameStatType])) {
            $retVal = $this->_arrGameStats[$i_strGameStatType];
        }
        return $retVal;
    }


    function set_gamestats($i_arrDataValues) {
//printf ("SET_GAMESTATS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrGameStats)) {
            $this->_arrGameStats = $this->get_tbl_data(TBL_GAMESTATS);
        }
        $this->set_tbl_row(TBL_GAMESTATS, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrGameStats[$col] = $val;
                } else {
                    unset($this->_arrGameStats[$col]);
                }
            }
        }
    }

    function set_gamestat($i_strGameStatType, $i_DataValue) {
//printf ("SET_GAMESTATS:%s-%d<BR>", $i_strGameStatType, $i_DataValue);
        if (empty($this->_arrGameStats)) {
            $this->_arrGameStats = $this->get_tbl_data(TBL_GAMESTATS);
        }
        $this->set_tbl_data(TBL_GAMESTATS, $i_strGameStatType, $i_DataValue);
        $this->_arrGameStats[$i_strGameStatType] = $i_DataValue;
    }


    /***********************************************************************
     * online interface
     *
     * get_onlines - return a row in an array from tblOnline
     * get_online  - return a field from the current row of tblOnline
     * set_onlines - save the entire row to tblOnline
     * set_online  - save a single field to tblOnline
     *
     ***********************************************************************/
    function get_onlines() {
        if (empty($this->_arrOnline)) {
            $this->_arrOnline= $this->get_tbl_data(TBL_ONLINE);
//var_dump ($this->_arrOnline);
        }
        return $this->_arrOnline;
    }


    function get_online($i_strOnlineType) {
//echo "INFO:\"$i_strGameStatType\"<BR>";
        if (empty($this->_arrOnline)) {
            $this->_arrOnline = $this->get_tbl_data(TBL_ONLINE);
        }

        if (isset($this->_arrOnline[$i_strOnlineType])) {
            $retVal = $this->_arrOnline[$i_strOnlineType];
        }
        return $retVal;
    }


    function set_onlines($i_arrDataValues) {
//printf ("SET_ONLINE:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrOnline)) {
            $this->_arrOnline = $this->get_tbl_data(TBL_ONLINE);
        }
        $this->set_tbl_row(TBL_ONLINE, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrOnline[$col] = $val;
                } else {
                    unset($this->_arrOnline[$col]);
                }
            }
        }
    }

    function set_online($i_strOnlineType, $i_DataValue) {
//printf ("SET_ONLINE:%s-%d<BR>", $i_strOnlineType, $i_DataValue);
        if (empty($this->_arrOnline)) {
            $this->_arrOnline = $this->get_tbl_data(TBL_ONLINE);
        }
        $this->set_tbl_data(TBL_ONLINE, $i_strOnlineType, $i_DataValue);
        $this->_arrOnline[$i_strOnlineType] = $i_DataValue;
    }


    /***********************************************************************
     * build interface
     *
     * get_builds - return a row in an array from tblBuild
     * get_build  - return a field from the current row of tblBuild
     * set_builds - save the entire row to tblBuild
     * set_build  - save a single field to tblBuild
     * get_wall_bonus    - calculate and return users wall bonus on defence
     * get_weapon_bonus  - calculate and return users weapon bonus on offence
     * get_barren        - return users number of barren acres
     * get_number_build_types - return users number of different buildings user can have
     *
     ***********************************************************************/
    function get_builds() {
        if (empty($this->_arrBuild)) {
            $this->_arrBuild = $this->get_tbl_data(TBL_BUILD);
//var_dump ($this->_arrBuild);
        }
        return $this->_arrBuild;
    }


    function get_build($i_strBuildType) {
//echo "INFO:\"$i_strBuildType\"<BR>";
        if (empty($this->_arrBuild)) {
            $this->_arrBuild = $this->get_tbl_data(TBL_BUILD);
        }

        if (isset($this->_arrBuild[$i_strBuildType])) {
            $retVal = $this->_arrBuild[$i_strBuildType];
        }
        return $retVal;
    }


    function set_builds($i_arrDataValues) {
//printf ("SET_BUILD:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrBuild)) {
            $this->_arrBuild = $this->get_tbl_data(TBL_BUILD);
        }
        $this->set_tbl_row(TBL_BUILD, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrBuild[$col] = $val;
                } else {
                    unset($this->_arrBuild[$col]);
                }
            }
        }
    }

    function set_build($i_strBuildType, $i_DataValue) {
//printf ("SET_BUILD:%s-%d<BR>", $i_strBuildType, $i_DataValue);
        if (empty($this->_arrBuild)) {
            $this->_arrBuild = $this->get_tbl_data(TBL_BUILD);
        }
        $this->set_tbl_data(TBL_BUILD, $i_strBuildType, $i_DataValue);
        $this->_arrBuild[$i_strBuildType] = $i_DataValue;
    }

    function get_barren()
    {
        $arrBuilds = $this->get_builds();
        $intBarren =  $arrBuilds[LAND] -
                        ( $arrBuilds[HOMES] + $arrBuilds[HOMES_T1] + $arrBuilds[HOMES_T2] + $arrBuilds[HOMES_T3] + $arrBuilds[HOMES_T4]
                        + $arrBuilds[FARMS] + $arrBuilds[FARMS_T1] + $arrBuilds[FARMS_T2] + $arrBuilds[FARMS_T3] + $arrBuilds[FARMS_T4]
                        + $arrBuilds[WALLS] + $arrBuilds[WALLS_T1] + $arrBuilds[WALLS_T2] + $arrBuilds[WALLS_T3] + $arrBuilds[WALLS_T4]
                        + $arrBuilds[GUILDS] + $arrBuilds[GUILDS_T1] + $arrBuilds[GUILDS_T2] + $arrBuilds[GUILDS_T3] + $arrBuilds[GUILDS_T4]
                        + $arrBuilds[WEAPONRIES] + $arrBuilds[WEAPONRIES_T1] + $arrBuilds[WEAPONRIES_T2] + $arrBuilds[WEAPONRIES_T3] + $arrBuilds[WEAPONRIES_T4]
                        + $arrBuilds[MINES] + $arrBuilds[MINES_T1] + $arrBuilds[MINES_T2] + $arrBuilds[MINES_T3] + $arrBuilds[MINES_T4]
                        + $arrBuilds[MARKETS] + $arrBuilds[MARKETS_T1] + $arrBuilds[MARKETS_T2] + $arrBuilds[MARKETS_T3] + $arrBuilds[MARKETS_T4]
                        + $arrBuilds[LABS] + $arrBuilds[LABS_T1] + $arrBuilds[LABS_T2] + $arrBuilds[LABS_T3] + $arrBuilds[LABS_T4]
                        + $arrBuilds[CHURCHES] + $arrBuilds[CHURCHES_T1] + $arrBuilds[CHURCHES_T2] + $arrBuilds[CHURCHES_T3] + $arrBuilds[CHURCHES_T4]
                        + $arrBuilds[GUARDHOUSES] + $arrBuilds[GUARDHOUSES_T1] + $arrBuilds[GUARDHOUSES_T2] + $arrBuilds[GUARDHOUSES_T3] + $arrBuilds[GUARDHOUSES_T4]
                        + $arrBuilds[HIDEOUTS] + $arrBuilds[HIDEOUTS_T1] + $arrBuilds[HIDEOUTS_T2] + $arrBuilds[HIDEOUTS_T3] + $arrBuilds[HIDEOUTS_T4]
                        + $arrBuilds[ACADEMIES] + $arrBuilds[ACADEMIES_T1] + $arrBuilds[ACADEMIES_T2] + $arrBuilds[ACADEMIES_T3] + $arrBuilds[ACADEMIES_T4]
                        + $arrBuilds[YARDS] + $arrBuilds[YARDS_T1] + $arrBuilds[YARDS_T2] + $arrBuilds[YARDS_T3] + $arrBuilds[YARDS_T4]
                        + $arrBuilds[BANKS] + $arrBuilds[BANKS_T1] + $arrBuilds[BANKS_T2] + $arrBuilds[BANKS_T3] + $arrBuilds[BANKS_T4]
                        );

        return $intBarren;
    }
    function get_wall_bonus()
    {
        $wall_bonus  = $this->get_build(WALLS) / $this->get_build(LAND);
        if ($wall_bonus > 0.2)
        {
            $wall_bonus = 0.2;
        }
        return $wall_bonus;
    }

    function get_weapon_bonus()
    {
        $weapon_bonus = $this->get_build(WEAPONRIES) / $this->get_build(LAND);
        if ($weapon_bonus > 0.2)
        {
            $weapon_bonus = 0.2;
        }
        // damamdoo : implemeting dwarf thingie
        if ($this->get_stat(RACE) == 'Dwarf')
        {
            $weapon_bonus = $weapon_bonus * 1.35;
        }
        return $weapon_bonus;
    }

    function get_number_build_types()
    {
    //Gotland and Martel: For now return 14, but prepare possibilty for dynamic build types
        return 14;
    }



    /***********************************************************************
     * stat interface
     *
     * get_stats - return a row in an array from tblStat
     * get_stat  - return a field from the current row of tblStat
     * set_stats - save the entire row to tblStat
     * set_stat  - save a single field to tblStat
     *
     ***********************************************************************/

    function get_stats() {
        if (empty($this->_arrStat)) {
            $this->_arrStat = $this->get_tbl_data(TBL_STAT);
//var_dump ($this->_arrStat);
        }
        return $this->_arrStat;
    }


    function get_stat($i_strStatType) {
//echo "STAT:\"$i_strStatType\"<BR>";
        if (empty($this->_arrStat)) {
            $this->_arrStat = $this->get_tbl_data(TBL_STAT);
        }
        if (isset($this->_arrStat[$i_strStatType])) {
            $retVal = $this->_arrStat[$i_strStatType];
        }
        return $retVal;
    }


    function set_stats($i_arrDataValues) {
//printf ("SET_STAT:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrStat)) {
            $this->_arrStat = $this->get_tbl_data(TBL_STAT);
        }
        $this->set_tbl_row(TBL_STAT, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrStat[$col] = $val;
                } else {
                    unset($this->_arrStat[$col]);
                }
            }
        }
    }


    function set_stat($i_strStatType, $i_DataValue) {
//printf ("SET_STAT:%s-%d<BR>", $i_strStatType, $i_DataValue);
        if (empty($this->_arrStat)) {
            $this->_arrStat = $this->get_tbl_data(TBL_STAT);
        }
        $this->set_tbl_data(TBL_STAT, $i_strStatType, $i_DataValue);
        $this->_arrStat[$i_strStatType] = $i_DataValue;
    }

    /***********************************************************************
     * user_info interface
     *
     * get_user_infos - return a row in an array from tblUser
     * get_user_info  - return a field from the current row of tblUser
     * set_user_infos - save the entire row to tblUser
     * set_user_info  - save a single field to tblUser
     *
     ***********************************************************************/

    function get_user_infos() {
        if (empty($this->_arrUserInfo)) {
            $this->_arrUserInfo = $this->get_tbl_data(TBL_USER);
//var_dump ($this->_arrUserInfo);
        }
        return $this->_arrUserInfo;
    }


    function get_user_info($i_strInfoType) {
//echo "INFO:\"$i_strInfoType\"<BR>";
        if (empty($this->_arrUserInfo)) {
            $this->_arrUserInfo = $this->get_tbl_data(TBL_USER);
        }
        if (isset($this->_arrUserInfo[$i_strInfoType])) {
            $retVal = $this->_arrUserInfo[$i_strInfoType];
        }
        return $retVal;
    }


    function set_user_infos($i_arrDataValues) {
//printf ("SET_USER:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrUserInfo)) {
            $this->_arrUserInfo = $this->get_tbl_data(TBL_USER);
        }
        $this->set_tbl_row(TBL_USER, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrUserInfo[$col] = $val;
                } else {
                    unset($this->_arrUserInfo[$col]);
                }
            }
        }
    }


    function set_user_info($i_strInfoType, $i_DataValue) {
//printf ("SET_USER:%s-%d<BR>", $i_strInfoType, $i_DataValue);
        if (empty($this->_arrUserInfo)) {
            $this->_arrUserInfo = $this->get_tbl_data(TBL_USER);
        }
        $this->set_tbl_data(TBL_USER, $i_strInfoType, $i_DataValue);
        $this->_arrUserInfo[$i_strInfoType] = $i_DataValue;
    }


    /***********************************************************************
     * spell interface
     *
     * get_spells - return a row in an array from tblSpell
     * get_spell  - return a field from the current row of tblSpell
     * set_spells - save the entire spells row to tblSpell
     * set_spell  - save a single field to tblSpell
     *
     ***********************************************************************/
    function get_spells() {
        if (empty($this->_arrSpell)) {
            $this->_arrSpell = $this->get_tbl_data(TBL_SPELL);
//var_dump ($this->_arrSpell);
        }
        return $this->_arrSpell;
    }


    function get_spell($strSpellType) {
//echo "INFO:\"$strSpellType\"<BR>";
        if (empty($this->_arrSpell)) {
            $this->_arrSpell = $this->get_tbl_data(TBL_SPELL);
        }

        if (isset($this->_arrSpell[$strSpellType])) {
            $retVal = $this->_arrSpell[$strSpellType];
        }
        return $retVal;
    }


    function set_spells($i_arrDataValues) {
//printf ("SET_SPELL:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrSpell)) {
            $this->_arrSpell = $this->get_tbl_data(TBL_SPELL);
        }
        $this->set_tbl_row(TBL_SPELL, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrSpell[$col] = $val;
                } else {
                    unset($this->_arrSpell[$col]);
                }
            }
        }
    }

    function set_spell($strSpellType, $i_DataValue) {
// printf ("<br><br>SET_SPELL:%s-%d<BR><br><br>", $strSpellType, $i_DataValue);
        if (empty($this->_arrSpell)) {
            $this->_arrSpell = $this->get_tbl_data(TBL_SPELL);
        }
        $this->set_tbl_data(TBL_SPELL, $strSpellType, $i_DataValue);
        $this->_arrSpell[$strSpellType] = $i_DataValue;
    }

    /***********************************************************************
     * goods interface
     *
     * get_goods - return a row in an array from tblGoods
     * get_good  - return a field from the current row of tblGoods
     * set_goods - save the entire goods row to tblGoods
     * set_good  - save a single field to tblGoods
     *
     ***********************************************************************/
    function get_goods() {
        if (empty($this->_arrGood)) {
            $this->_arrGood = $this->get_tbl_data(TBL_GOODS);
//var_dump ($this->_arrGood);
        }
        return $this->_arrGood;
    }


    function get_good($i_strGoodType) {
//echo "INFO:\"$i_strGoodType\"<BR>";
        if (empty($this->_arrGood)) {
            $this->_arrGood = $this->get_tbl_data(TBL_GOODS);
        }

        if (isset($this->_arrGood[$i_strGoodType])) {
            $retVal = $this->_arrGood[$i_strGoodType];
        }
        return $retVal;
    }


    function set_goods($i_arrDataValues) {
//printf ("SET_GOODS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrGood)) {
            $this->_arrGood = $this->get_tbl_data(TBL_GOODS);
        }
        $this->set_tbl_row(TBL_GOODS, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrGood[$col] = $val;
                } else {
                    unset($this->_arrGood[$col]);
                }
            }
        }
    }

    function set_good($i_strGoodType, $i_DataValue) {
//printf ("SET_GOOD:%s-%d<BR>", $i_strGoodType, $i_DataValue);
        if (empty($this->_arrGood)) {
            $this->_arrGood = $this->get_tbl_data(TBL_GOODS);
        }
        $this->set_tbl_data(TBL_GOODS, $i_strGoodType, $i_DataValue);
        $this->_arrGood[$i_strGoodType] = $i_DataValue;
    }


    /***********************************************************************
     * pop interface
     *
     * get_pops - return a row in an array from tblPop
     * get_pop  - return a field from the current row of tblPop
     * set_pops - save the entire pop row to tblPop
     * set_pop  - save a single field to tblPop
     *
     ***********************************************************************/
    function get_pops() {
        if (empty($this->_arrPop)) {
            $this->_arrPop = $this->get_tbl_data(TBL_POP);
//var_dump ($this->_arrPop);
        }
        return $this->_arrPop;
    }


    function get_pop($i_strPopType) {
//echo "INFO:\"$i_strPopType\"<BR>";
        if (empty($this->_arrPop)) {
            $this->_arrPop = $this->get_tbl_data(TBL_POP);
        }

        if (isset($this->_arrPop[$i_strPopType])) {
            $retVal = $this->_arrPop[$i_strPopType];
        }
        return $retVal;
    }


    function set_pops($i_arrDataValues) {
//printf ("SET_POP:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrPop)) {
            $this->_arrPop = $this->get_tbl_data(TBL_POP);
        }
        $this->set_tbl_row(TBL_POP, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrPop[$col] = $val;
                } else {
                    unset($this->_arrPop[$col]);
                }
            }
        }
    }

    function set_pop($i_strPopType, $i_DataValue) {
//printf ("SET_POP:%s-%d<BR>", $i_strPopType, $i_DataValue);
        if (empty($this->_arrPop)) {
            $this->_arrPop = $this->get_tbl_data(TBL_POP);
        }
        $this->set_tbl_data(TBL_POP, $i_strPopType, $i_DataValue);
        $this->_arrPop[$i_strPopType] = $i_DataValue;
    }

    /***********************************************************************
     * armys interface
     *
     * get_armys - return a row in an array from tblArmys
     * get_army  - return a field from the current row of tblArmys
     * set_armys - save the entire armys row to tblArmys
     * set_army  - save a single field to tblArmys
     *
     ***********************************************************************/
    function get_armys() {
        if (empty($this->_arrArmy)) {
            $this->_arrArmy = $this->get_tbl_data(TBL_ARMY);
//var_dump ($this->_arrArmy);
        }
        return $this->_arrArmy;
    }


    function get_army($i_strArmyType) {
//echo "INFO:\"$i_strArmyType\"<BR>";
        if (empty($this->_arrArmy)) {
            $this->_arrArmy = $this->get_tbl_data(TBL_ARMY);
        }

        if (isset($this->_arrArmy[$i_strArmyType])) {
            $retVal = $this->_arrArmy[$i_strArmyType];
        }
        return $retVal;
    }


    function set_armys($i_arrDataValues) {
//printf ("SET_ARMYS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrArmy)) {
            $this->_arrArmy = $this->get_tbl_data(TBL_ARMY);
        }
        $this->set_tbl_row(TBL_ARMY, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrArmy[$col] = $val;
                } else {
                    unset($this->_arrArmy[$col]);
                }
            }
        }
    }

    function set_army($i_strArmyType, $i_DataValue) {
//printf ("SET_ARMY:%s-%d<BR>", $i_strArmyType, $i_DataValue);
        if (empty($this->_arrArmy)) {
            $this->_arrArmy = $this->get_tbl_data(TBL_ARMY);
        }
        $this->set_tbl_data(TBL_ARMY, $i_strArmyType, $i_DataValue);
        $this->_arrArmy[$i_strArmyType] = $i_DataValue;
    }

    /***********************************************************************
     * army_mercs interface
     *
     * get_army_mercs - return a row in an array from tblArmy_Mercs
     * get_army_merc  - return a field from the current row of tblArmy_Mercs
     * set_army_mercs - save the entire row to tblArmy_Mercs
     * set_army_merc  - save a single field to tblArmy_Mercs
     *
     ***********************************************************************/
    function get_army_mercs() {
        if (empty($this->_arrArmyMercs)) {
            $this->_arrArmyMercs= $this->get_tbl_data(TBL_ARMY_MERCS);
//var_dump ($this->_arrArmyMercs);
        }
        return $this->_arrArmyMercs;
    }


    function get_army_merc($i_strArmyMercsType) {
//echo "INFO:\"$i_strArmyMercsType\"<BR>";
        if (empty($this->_arrArmyMercs)) {
            $this->_arrArmyMercs = $this->get_tbl_data(TBL_ARMY_MERCS);
        }

        if (isset($this->_arrArmyMercs[$i_strArmyMercsType])) {
            $retVal = $this->_arrArmyMercs[$i_strArmyMercsType];
        }
        return $retVal;
    }


    function set_army_mercs($i_arrDataValues) {
//printf ("SET_ARMY_MERCS:%s-%d<BR>", $i_strArmyMercsType, $i_DataValue);
        if (empty($this->_arrArmyMercs)) {
            $this->_arrArmyMercs = $this->get_tbl_data(TBL_ARMY_MERCS);
        }
        $this->set_tbl_row(TBL_ARMY_MERCS, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrArmyMercs[$col] = $val;
                } else {
                    unset($this->_arrArmyMercs[$col]);
                }
            }
        }
    }

    function set_army_merc($i_strArmyMercsType, $i_DataValue) {
//printf ("SET_ARMY_MERC:%s-%d<BR>", $i_strArmyMercsType, $i_DataValue);
        if (empty($this->_arrArmyMercs)) {
            $this->_arrArmyMercs = $this->get_tbl_data(TBL_ARMY_MERCS);
        }
        $this->set_tbl_data(TBL_ARMY_MERCS, $i_strArmyMercsType, $i_DataValue);
        $this->_arrArmyMercs[$i_strArmyMercsType] = $i_DataValue;
    }


    /***********************************************************************
     * milreturns interface
     *
     * get_milreturns - return a row in an array from tblMilreturn
     * get_milreturn  - return a field from the current row of tblMilreturn
     * set_milreturns - save the entire milreturn row to tblMilreturn
     * set_milreturn  - save a single field to tblMilreturn
     *
     ***********************************************************************/

    function get_milreturns() {
        if (empty($this->_arrMilReturn)) {
            $this->_arrMilReturn = $this->get_tbl_data(TBL_MILRETURN);
//var_dump ($this->_arrMilReturn);
        }
        return $this->_arrMilReturn;
    }


    function get_milreturn($i_strMilReturnType) {
//echo "INFO:\"$i_strMilReturnType\"<BR>";
        if (empty($this->_arrMilReturn)) {
            $this->_arrMilReturn = $this->get_tbl_data(TBL_MILRETURN);
        }

        if (isset($this->_arrMilReturn[$i_strMilReturnType])) {
            $retVal = $this->_arrMilReturn[$i_strMilReturnType];
        }
        return $retVal;
    }


    function set_milreturns($i_arrDataValues) {
//printf ("SET_MILRETURNS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrMilReturn)) {
            $this->_arrMilReturn = $this->get_tbl_data(TBL_MILRETURN);
        }
        $this->set_tbl_row(TBL_MILRETURN, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrMilReturn[$col] = $val;
                } else {
                    unset($this->_arrMilReturn[$col]);
                }
            }
        }
    }

    function set_milreturn($i_strMilReturnType, $i_DataValue) {
//printf ("SET_MILRETURN:%s-%d<BR>", $i_strMilReturnType, $i_DataValue);
        if (empty($this->_arrMilReturn)) {
            $this->_arrMilReturn = $this->get_tbl_data(TBL_MILRETURN);
        }
        $this->set_tbl_data(TBL_MILRETURN, $i_strMilReturnType, $i_DataValue);
        $this->_arrMilReturn[$i_strMilReturnType] = $i_DataValue;
    }

    /***********************************************************************
     * thievery interface
     *
     * get_thieverys - return a row in an array from tblThievery
     * get_thievery  - return a field from the current row of tblThievery
     * set_thieverys - save the entire milreturn row to tblThievery
     * set_thievery  - save a single field to tblThievery
     *
     ***********************************************************************/

    function get_thieverys() {
        if (empty($this->_arrThievery)) {
            $this->_arrThievery = $this->get_tbl_data(TBL_THIEVERY);
// var_dump ($this->_arrThievery);
        }
        return $this->_arrThievery;
    }


    function get_thievery($i_strThieveryType) {
//echo "INFO:\"$i_strThieveryType\"<BR>";
        if (empty($this->_arrThievery)) {
            $this->_arrThievery = $this->get_tbl_data(TBL_THIEVERY);
        }

        if (isset($this->_arrThievery[$i_strThieveryType])) {
            $retVal = $this->_arrThievery[$i_strThieveryType];
        }
        return $retVal;
    }


    function set_thieverys($i_arrDataValues) {
//printf ("SET_THIEVERYS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrThievery)) {
            $this->_arrThievery = $this->get_tbl_data(TBL_THIEVERY);
        }
        $this->set_tbl_row(TBL_THIEVERY, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrThievery[$col] = $val;
                } else {
                    unset($this->_arrThievery[$col]);
                }
            }
        }
    }

    function set_thievery($i_strThieveryType, $i_DataValue) {
//printf ("SET_THIEVERY:%s-%d<BR>", $i_strThieveryType, $i_DataValue);
        if (empty($this->_arrThievery)) {
            $this->_arrThievery = $this->get_tbl_data(TBL_THIEVERY);
        }
        $this->set_tbl_data(TBL_THIEVERY, $i_strThieveryType, $i_DataValue);
        $this->_arrThievery[$i_strThieveryType] = $i_DataValue;
    }


    /***********************************************************************
     * kills interface
     *
     * get_kills - return a row in an array from tblKills
     * get_kill  - return a field from the current row of tblKills
     * set_kills - save the entire milreturn row to tblKills
     * set_kill  - save a single field to tblKills
     *
     ***********************************************************************/

    function get_kills() {
        if (empty($this->_arrKills)) {
            $this->_arrKills = $this->get_tbl_data(TBL_KILLS);
var_dump ($this->_arrKills);
        }
        return $this->_arrKills;
    }


    function get_kill($i_strKillType) {
//echo "INFO:\"$i_strKillType\"<BR>";
        if (empty($this->_arrKills)) {
            $this->_arrKills = $this->get_tbl_data(TBL_KILLS);
        }

        if (isset($this->_arrKills[$i_strKillType])) {
            $retVal = $this->_arrKills[$i_strKillType];
        }
        return $retVal;
    }


    function set_kills($i_arrDataValues) {
//printf ("SET_KILLS:%s-%d<BR>", $i_arrDataValues);
        if (empty($this->_arrKills)) {
            $this->_arrKills = $this->get_tbl_data(TBL_KILLS);
        }
        $this->set_tbl_row(TBL_KILLS, $i_arrDataValues);
        reset($i_arrDataValues);
        while(list($col, $val) = each($i_arrDataValues)) {
            if (isset($col)) {
                if (isset($val)) {
                    $this->_arrKills[$col] = $val;
                } else {
                    unset($this->_arrKills[$col]);
                }
            }
        }
    }

    function set_kill($i_strKillType, $i_DataValue) {
//printf ("SET_KILLS:%s-%d<BR>", $i_strKillType, $i_DataValue);
        if (empty($this->_arrKills)) {
            $this->_arrKills = $this->get_tbl_data(TBL_KILLS);
        }
        $this->set_tbl_data(TBL_KILLS, $i_strKillType, $i_DataValue);
        $this->_arrKills[$i_strKillType] = $i_DataValue;
    }


    /***********************************************************************
     * alliance interface
     *
     * get_alliance - return an object of the users alliance
     *
     ***********************************************************************/

    function get_alliance() {
        if (empty($this->_objAlliance)) {
            $aid = $this->get_stat(ALLIANCE);
            require_once("inc/classes/clsAlliance.php");
            $_objAlliance = new clsAlliance($aid);
        }
        return $_objAlliance;
    }


    /***********************************************************************
     * army home interface
     *
     * get_armys_home - return an array with the armies being home
     * get_army_home  - return the number of army type being home
     *
     ***********************************************************************/

    function get_armys_home() {
        $arrSrcArmys = $this->get_armys();
        $arrSrcMilreturns = $this->get_milreturns();

        $arrArmyHome[UNIT1] = $arrSrcArmys[UNIT1] - $arrSrcMilreturns[UNIT1_T1]- $arrSrcMilreturns[UNIT1_T2]- $arrSrcMilreturns[UNIT1_T3]- $arrSrcMilreturns[UNIT1_T4];
        $arrArmyHome[UNIT2] = $arrSrcArmys[UNIT2] - $arrSrcMilreturns[UNIT2_T1]- $arrSrcMilreturns[UNIT2_T2]- $arrSrcMilreturns[UNIT2_T3]- $arrSrcMilreturns[UNIT2_T4];
        $arrArmyHome[UNIT3] = $arrSrcArmys[UNIT3] - $arrSrcMilreturns[UNIT3_T1]- $arrSrcMilreturns[UNIT3_T2]- $arrSrcMilreturns[UNIT3_T3]- $arrSrcMilreturns[UNIT3_T4];
        $arrArmyHome[UNIT4] = $arrSrcArmys[UNIT4] - $arrSrcMilreturns[UNIT4_T1]- $arrSrcMilreturns[UNIT4_T2]- $arrSrcMilreturns[UNIT4_T3]- $arrSrcMilreturns[UNIT4_T4];
        $arrArmyHome[UNIT5] = $arrSrcArmys[UNIT5] - $arrSrcMilreturns[UNIT5_T1]- $arrSrcMilreturns[UNIT5_T2]- $arrSrcMilreturns[UNIT5_T3]- $arrSrcMilreturns[UNIT5_T4];
        $arrArmyHome[UNIT6] = $arrSrcArmys[UNIT6] - $arrSrcMilreturns[UNIT6_T1]- $arrSrcMilreturns[UNIT6_T2]- $arrSrcMilreturns[UNIT6_T3]- $arrSrcMilreturns[UNIT6_T4];

        return $arrArmyHome;
    }

    function get_army_home($i_strMilReturnType) {
        $arrSrcArmys = $this->get_armys();
        $arrSrcMilreturns = $this->get_milreturns();

        $t1 = $i_strMilReturnType . "_t1";
        $t2 = $i_strMilReturnType . "_t2";
        $t3 = $i_strMilReturnType . "_t3";
        $t4 = $i_strMilReturnType . "_t4";

        return $arrSrcArmys[$i_strMilReturnType] - $arrSrcMilreturns[$t1]- $arrSrcMilreturns[$t2]- $arrSrcMilreturns[$t3]- $arrSrcMilreturns[$t4];
    }

    /***********************************************************************
     * army out interface
     *
     * get_armys_out - return an array with the armies being out
     *
     ***********************************************************************/


    function get_armys_out()
    {
        $arrSrcMilreturns = $this->get_milreturns();

        $arrArmyOut[UNIT1] = $arrSrcMilreturns[UNIT1_T1] + $arrSrcMilreturns[UNIT1_T2] + $arrSrcMilreturns[UNIT1_T3] + $arrSrcMilreturns[UNIT1_T4];
        $arrArmyOut[UNIT2] = $arrSrcMilreturns[UNIT2_T1] + $arrSrcMilreturns[UNIT2_T2] + $arrSrcMilreturns[UNIT2_T3] + $arrSrcMilreturns[UNIT2_T4];
        $arrArmyOut[UNIT3] = $arrSrcMilreturns[UNIT3_T1] + $arrSrcMilreturns[UNIT3_T2] + $arrSrcMilreturns[UNIT3_T3] + $arrSrcMilreturns[UNIT3_T4];
        $arrArmyOut[UNIT4] = $arrSrcMilreturns[UNIT4_T1] + $arrSrcMilreturns[UNIT4_T2] + $arrSrcMilreturns[UNIT4_T3] + $arrSrcMilreturns[UNIT4_T4];
        $arrArmyOut[UNIT5] = $arrSrcMilreturns[UNIT5_T1] + $arrSrcMilreturns[UNIT5_T2] + $arrSrcMilreturns[UNIT5_T3] + $arrSrcMilreturns[UNIT5_T4];

        return $arrArmyOut;
    }

    /***********************************************************************
     * army due interface
     *
     * get_armys_due - return an array with the armies in training
     *
     ***********************************************************************/


    function get_armys_due()
    {
        $arrSrcArmys = $this->get_armys();

        $arrArmyDue['unit1'] = $arrSrcArmys['unit1_t1'] + $arrSrcArmys['unit1_t2'] + $arrSrcArmys['unit1_t3'] + $arrSrcArmys['unit1_t4'];
        $arrArmyDue['unit2'] = $arrSrcArmys['unit2_t1'] + $arrSrcArmys['unit2_t2'] + $arrSrcArmys['unit2_t3'] + $arrSrcArmys['unit2_t4'];
        $arrArmyDue['unit3'] = $arrSrcArmys['unit3_t1'] + $arrSrcArmys['unit3_t2'] + $arrSrcArmys['unit3_t3'] + $arrSrcArmys['unit3_t4'];
        $arrArmyDue['unit4'] = $arrSrcArmys['unit4_t1'] + $arrSrcArmys['unit4_t2'] + $arrSrcArmys['unit4_t3'] + $arrSrcArmys['unit4_t4'];
        $arrArmyDue['unit5'] = $arrSrcArmys['unit5_t1'] + $arrSrcArmys['unit5_t2'] + $arrSrcArmys['unit5_t3'] + $arrSrcArmys['unit5_t4'];

        $arrArmyDue['total'] = $arrArmyDue['unit1'] + $arrArmyDue['unit2'] + $arrArmyDue['unit3'] + $arrArmyDue['unit4'] + $arrArmyDue['unit5'];

        return $arrArmyDue;
    }

    //==============================================================================
    // New strength calc by Aldorin                             June 26, 2006 Martel
    //==============================================================================
    function get_strength()
    {
        if (empty($this->_iStrength))
        {
            $arrArmys   = $this->get_armys();
            $strRace    = $this->get_stat(RACE);

            // Strength from Military Units ====================================

            include_once('inc/functions/races.php');
            $arrUnitVars = getUnitVariables($strRace);
            $arrUnitOff  = $arrUnitVars['offence'];
            $arrUnitDef  = $arrUnitVars['defence'];
            $arrVars     = $arrUnitVars['variables'];

            $military_str   = 0;
            $temp_str       = 0;

            // iterates through all units with offence value specified (also if it is 0)
            foreach ($arrUnitOff as $key => $iUnitOff)
            {
                $temp_str = $arrArmys[$arrVars[$key]]
                          * calcUnitStr($this, $arrUnitOff[$key], $arrUnitDef[$key]);

                // possibly add some (unit_str *= 1.025 ?) if the unit is immortal here

                // ravens get double strength from their elites
                if ($strRace == 'Raven' &&  $arrVars[$key] == UNIT4)
                    $temp_str *= 2;

                $military_str += $temp_str;
            }

            // Strength from "War Buildings" ===================================
            $arrBuilds = $this->get_builds();
            $build_str = 75
                       * ( $arrBuilds[CHURCHES] + $arrBuilds[GUARDHOUSES]
                         + $arrBuilds[HIDEOUTS] + $arrBuilds[GUILDS]
                         + $arrBuilds[ACADEMIES] );

            // Strength from Thieves ===========================================
            $tm_str   = $arrArmys[UNIT5] * 1.25;

            // Strength from .. other stuff? :P ================================
            include_once('inc/functions/population.php');
            $max_citz = getMaxPopulation($this);
            $max_citz = $max_citz['total'];

            $all_units_in_training  = getPopulation($this);
            $all_units_in_training  = $all_units_in_training['total_army'];
            $all_units_in_training -= ( $arrArmys[UNIT1] + $arrArmys[UNIT2]
                                      + $arrArmys[UNIT3] + $arrArmys[UNIT4]
                                      + $arrArmys[UNIT5]);

            $other_str = pow(($max_citz + $arrArmys[UNIT1] + $all_units_in_training)
                             / (1+$arrArmys[UNIT2] + $arrArmys[UNIT3] + $arrArmys[UNIT4]
                             + $arrArmys[UNIT5]), 0.25);

            // Calculate Total Strength ========================================
            $total_str = $other_str
                       * ( $military_str
                         + $build_str
                         + $tm_str );

            // =================================================================

    //         $strength['units']     = $military_str;
    //         $strength['buildings'] = $build_str;
    //         $strength['thieves']   = $tm_str;
    //         $strength['other']     = $other_str;
            $strength['total']     = $total_str;

            $this->_iStrength = $strength['total'];
        }
        return $this->_iStrength;
    }
    //==========================================================================
    // New isPaused method (so we can centrally change this instead of having
    //  to edit 5 files
    // Currently 1-49 is paused, 50-55 is waiting till you get paused
    //                                                          - AI 12/10/06
    // Added global game pause here                      October 29, 2007 Martel
    //==========================================================================
    function isPaused()
    {
        include_once('inc/classes/clsGame.php');
        $objGame   = new clsGame();
        $strPaused = $objGame->get_game_switch(GLOBAL_PAUSE);

        $time = $this->get_user_info(PAUSE_ACCOUNT);
        return (($time > 0 && $time <= 49) || $strPaused == ON);
    }

    //==========================================================================
    function get_ruler_age()
    {
        $iRulerAge = $this->get_user_info(HOURS);

        $iRulerAge /= 12;
        $iRulerAge += 16;
        $iRulerAge = floor($iRulerAge);

        return $iRulerAge;
    }

    /***********************************************************************
     * racee interface
     *
     * get_race - return an object of the users race
     *
     ***********************************************************************/

    function get_race() {
        if (empty($this->_objRace)) {
            $strRace = $this->get_stat(RACE);
            require_once("inc/races/clsRace.php");
            $_objRace = clsRace::getRace($strRace);
        }
        return $_objRace;
    }

}

?>
