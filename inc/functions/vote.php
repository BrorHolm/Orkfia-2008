<?

/*
 * make a select option list from query result
 * the field i_strDisplayField is used to display
 * if the field i_strValueField is set, it is used for value=
 * if i_Key is set, it is used to set a selected option
 */
function render_option_list(&$i_resResult, $i_strDisplayField, $i_strValueField, $i_Key)
{
    $strRet = "";
    while ($arr = (mysql_fetch_array($i_resResult, MYSQL_ASSOC)))
    {
        /* make the value bit */
        if ($i_strValueField <> "")
        {
            $strValue = " value=\"" . $arr[$i_strValueField] . "\" ";
        }

        /* make the selected bit */
        $strSelected = "";
        if ($i_Key <> "")
        {
            if (($i_strValueField <> "") and ($arr[$i_strValueField] == $i_Key))
            {
                $strSelected = "SELECTED";
            }
            elseif ($arr[$i_strDisplayField] == $i_Key)
            {
                $strSelected = "SELECTED";
            }
        }

        /* put it all together */
        $strRet .= "<option" . $strValue . $strSelected . ">";
        $strRet .= $arr[$i_strDisplayField] . "</option>";
    }
    return $strRet;
}


/*
 * Count the votes for the alliance. elder is user with most votes, simple as
 * that. Martel: A little change, at a tie user with lower ID "wins"
 */
function count_votes($i_intAlliance)
{
    $strSQL = "SELECT COUNT(id) AS playercount " .
              "  FROM stats " .
              " WHERE kingdom = '$i_intAlliance' " .
              "   AND vote != 0";

    $result = mysql_query ($strSQL) or die("include_vote_text3:" . mysql_error());

    $intPlayerCount = 100; // fail-safe number, if we don't get the no. of the tribes

    if (mysql_num_rows($result) == 1)
    {
        $intPlayerCount = mysql_result ($result, 0, "playercount");
    }

    // this is a such a cool query. in 1 query I find the person with the max
    // votes in an alliance
    $strSQL = "SELECT vote, COUNT(vote) AS votecount " .
              "  FROM stats " .
              " WHERE kingdom = $i_intAlliance " .
              "   AND vote > 0 " .
              " GROUP BY vote" .
              " ORDER BY votecount DESC, id ASC " .
              " LIMIT 1";

    $result = mysql_query ($strSQL) or die("include_vote_text4:" . mysql_error());

    if (mysql_num_rows($result) == 1)
    {
        $intNewElder = mysql_result ($result, 0, "vote");
        $intVoteCount = mysql_result ($result, 0, "votecount");

        // set the current elder to a player
        $strSQL = "UPDATE stats " .
                  "   SET type = 'player' " .
                  " WHERE kingdom = '$i_intAlliance' " .
                  "   AND type = 'elder'";
        $result = mysql_query ($strSQL) or die("include_vote_text5:" . mysql_error());
        $strSQL = "UPDATE rankings_personal " .
                  "   SET player_type = 'player' " .
                  " WHERE alli_id = '$i_intAlliance' " .
                  "   AND player_type = 'elder'";
        $result = mysql_query ($strSQL) or die("include_vote_text_rank:" . mysql_error());

        // set the new elder
        if ($intVoteCount > floor((double) $intPlayerCount * 0.6))
        {
            $strSQL = "UPDATE stats " .
                      "   SET type = 'elder' " .
                      " WHERE id = '$intNewElder' ";
                    $result = mysql_query ($strSQL) or die("include_vote_text6:" . mysql_error());
            $strSQL = "UPDATE rankings_personal " .
                      "   SET player_type = 'elder' " .
                      " WHERE id = '$intNewElder' ";
            $result = mysql_query ($strSQL) or die("include_vote_text_ranking:" . mysql_error());
        }
    }
    else
    {
        // set the current elder to a player
        $strSQL = "UPDATE stats " .
                  "   SET type = 'player' " .
                  " WHERE kingdom = '$i_intAlliance' " .
                  "   AND type = 'elder'";
        $result = mysql_query ($strSQL) or die("include_vote_text:" . mysql_error());
        $strSQL = "UPDATE rankings_personal " .
                  "   SET player_type = 'player' " .
                  " WHERE alli_id = '$i_intAlliance' " .
                  "   AND player_type = 'elder'";
        $result = mysql_query ($strSQL) or die("include_vote_text_rank:" . mysql_error());

        // set the current co-elders to players
        unset_coelders($i_intAlliance);
    }
}

function unset_coelders($intUserKingdom)
{
    $strSQL = "UPDATE stats " .
              "   SET type = 'player' " .
              " WHERE kingdom = '$intUserKingdom' " .
              "   AND type = 'coelder'";
    $result = mysql_query ($strSQL) or die("include_vote_text8:" . mysql_error());
    $strSQL = "UPDATE rankings_personal " .
              "   SET player_type = 'player' " .
              " WHERE alli_id = '$intUserKingdom' " .
              "   AND player_type = 'coelder'";
    $result = mysql_query ($strSQL) or die("include_vote_text_rank:" . mysql_error());
}

function set_coelder($intUserKingdom, $intTribeID)
{
    $strSQL = "UPDATE stats " .
              "   SET type = 'coelder' " .
              " WHERE kingdom = '$intUserKingdom' " .
              "   AND type != 'elder' " .
              "   AND id = '$intTribeID' ";
            $result = mysql_query ($strSQL) or die("include_vote_text9:" . mysql_error());
    $strSQL = "UPDATE rankings_personal " .
              "   SET player_type = 'coelder' " .
              " WHERE alli_id = '$intUserKingdom' " .
              "   AND id = '$intTribeID' ";
    $result = mysql_query ($strSQL) or die("include_vote_text_ranking:" . mysql_error());
}

// Fix the "external vote" issue                             Martel May 18, 2007
function unset_external_votes($iAlliance)
{
    include_once('inc/classes/clsAlliance.php');
    $objAlliance = new clsAlliance($iAlliance);
    $arrUserids = $objAlliance->get_userids();

    // Unset votes for tribes that are no longer in the alliance
    foreach ($arrUserids as $iUserid)
    {
        $objTempUser = new clsUser($iUserid);
        if (! in_array($objTempUser->get_stat(VOTE), $arrUserids))
            $objTempUser->set_stat(VOTE, 0);
    }
}

?>
