<?php
//******************************************************************************
// Pages vote.inc.php                                   December 27, 2007 Martel
//  Elect Elder
// History:
//  Martel - December 27, 2007 recode to OO and remove redundant code
//******************************************************************************

function include_vote_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $iSrcVote   = $objSrcUser->get_stat(VOTE);
    $objSrcAlli = $objSrcUser->get_alliance();
    $arrUserIds = $objSrcAlli->get_userids();

    // M: Show advisor text
    $strAdvisorText =
        '<div id="textMedium" style="margin-top: 0;">' .
        '<p>' .
            "Please vote for whom you think should be the elder and control your alliance." .
        '</p>' .
        '</div><br />';
    echo $strAdvisorText;

    // M: Populate arrVotes - this array contains information about all voters
    $objTmpUser = new clsUser(1);
    foreach ($arrUserIds as $iUserId)
    {
        $objTmpUser->set_userid($iUserId);

        $arrVotes[$iUserId]["tribename"]  = $objTmpUser->get_stat(TRIBE);
        $arrVotes[$iUserId]["playertype"] = $objTmpUser->get_stat(TYPE);
        $arrVotes[$iUserId]["votes"]      = 0;
        $arrVotes[$iUserId]["votefor"]    = '';
    }

    // M: Count their votes (also verify that they voted for an alliance member)
    $iValidVotes = 0;
    foreach ($arrUserIds as $iUserId)
    {
        $objTmpUser->set_userid($iUserId);
        $iTmpVote = $objTmpUser->get_stat(VOTE);
        if ($iTmpVote > 0 && in_array($iTmpVote, $arrUserIds))
        {
            $iValidVotes++;
            $arrVotes[$iTmpVote]["votes"]++;
            $arrVotes[$iUserId]["votefor"] = stripslashes($arrVotes[$iTmpVote]["tribename"]);
        }
    }

    // M: Create table rows for each tribe
    $strTableRows = '';
    foreach ($arrUserIds as $iUserId)
    {
        $objTmpUser->set_userid($iUserId);
        $arrTmpStats = $objTmpUser->get_stats();
        $strTmpTribe = stripslashes($arrTmpStats[TRIBE]);

        // M: Fetch information about this tribe's votes
        $strVoted    = stripslashes($arrVotes[$iUserId]["votefor"]);
        $strType     = $arrVotes[$iUserId]["playertype"];
        $iVotes      = $arrVotes[$iUserId]["votes"];

        if ($iSrcVote == $iUserId) $strChecked = " checked";
        else $strChecked = "";

        // M: Column for elder to elect co-elder
        $strCoelderTD = '';
        if ($objSrcUser->get_stat(TYPE) == 'elder')
        {
            if ($strType == "coelder") $strChecked2 = " checked";
            else $strChecked2 = "";

            $strCoelderTD =
            '<td>' .
                '<input type="checkbox" name="voteforcoelder[]" value="' .
                $iUserId . '"' . $strChecked2 . " />" .
            "</td>";
        }

        $strTableRows .=
        '<tr class="data">' .

            '<th>' .
                '<label>' .
                '<input type="radio" name="votefor" value="' . $iUserId . '"' .
                $strChecked . ' /> <span class="' . $strType . '">' .
                $strTmpTribe . '</span></label>' .
            '</th>' .

            '<td class="left">' .
                floor($iVotes / max(1, $iValidVotes) * 100) . '%' .
            '</td>' .

            '<td class="left">' . $strVoted . '</td>' .

            $strCoelderTD .

        "</tr>";
    }

    // M: Vote for donkey (default)
    if ($iSrcVote == '' || $iSrcVote == 0) $strChecked = " checked";
    else $strChecked = '';

    // M: Extra column for elder to elect co-elders
    if ($objSrcUser->get_stat(TYPE) == 'elder')
    {
        $iColumns = '4';
        $strThCoelder = "<th class=\"center\">" . "Co-elder" . "</td>";
    }
    else
    {
        $iColumns     = '3';
        $strThCoelder = '';
    }

    // M: "Elect your elder"-table
    $strElderTable =
        "<form method=\"post\" action=\"main.php?cat=game&amp;page=vote2\" id=\"center\">" .
        "<table cellspacing=\"0\" cellpadding=\"0\" class=\"medium\">" .

        "<tr class=\"header\">" .
            "<th colspan=\"" . $iColumns . "\">" . "Elect Your Elder" . "</th>" .
        "</tr>" .

        "<tr class=\"subheader\">" .
            "<th>" . "Tribe" . "</th>" .
            "<th>" . "Votes" . "</th>" .
            "<th>" . "Voted For" . "</th>" .
            $strThCoelder .
        "</tr>" .

        $strTableRows .

        "<tr class=\"data\">" .
            '<th class="bsup" colspan="' . $iColumns . '">' .
                "<input type=\"radio\" name=\"votefor\" value=\"0\" id=\"0\"" . $strChecked . " /> " .
                "<label for=\"0\">" . "No Vote" . "</label>" .
            "</th>" .
        "</tr>" .

        "</table><br />" .

        "<input type=\"submit\" value=\"Vote\" />" .
        "</form>";
    echo $strElderTable;
}

?>
