<?php

function include_alliance_text()
{
    // This bugs pretty badly if the alliance tables are missing
    // Same thing further down too, but for target alliance obj.
    $objSrcUser = &$GLOBALS["objSrcUser"];
    $objSrcAlli = $objSrcUser->get_alliance();

    //==========================================================================
    // Martel: Validate the alliance number so we get an absolutely safe one
    //==========================================================================
    $iAid = intval($objSrcUser->get_stat(ALLIANCE));

    if (isset($_POST['aid']))
    {
        $iAid = intval($_POST['aid']);
        if (strlen($_POST['aid']) > 4)
        {
            require_once('inc/pages/logout.inc.php');
            include_logout_text();
        }
    }
    elseif (isset($_GET['aid']))
    {
        $iAid = intval($_GET['aid']);
        if (strlen($_GET['aid']) > 4)
        {
            require_once('inc/pages/logout.inc.php');
            include_logout_text();
        }
    }

    if ($iAid < 1)
    {
        $iAid = 1;
    }

    //==========================================================================

    $result = mysql_query("SELECT alli_id FROM rankings_personal WHERE alli_id < $iAid ORDER BY alli_id DESC LIMIT 1");
    if (mysql_num_rows($result) == '0') {
        $prevAlli['0'] = $iAid;
    } else {
        $prevAlli=mysql_fetch_row($result);
    }

    $result = mysql_query("SELECT alli_id FROM rankings_personal WHERE alli_id > $iAid ORDER BY alli_id ASC LIMIT 1");
    if (mysql_num_rows($result) == '0') {
        $nextAlli['0'] = $iAid;
    } else {
        $nextAlli=mysql_fetch_row($result);
    }

    echo $chooseAlliance =
        "<div class=\"tableLinkMini\">" .
            "<a href=\"main.php?cat=game&amp;page=alliance\">Home</a>" .
        "</div>" .

        "<form method=\"post\" action=\"main.php?cat=game&amp;page=alliance\" style=\"margin-top: 0pt;\">" .

        "<table cellpadding=\"0\" cellspacing=\"0\" class=\"mini\">" .
            "<tr class=\"header\">" .
                "<th colspan=\"4\">" . "Alliance" . "</th>" .
            "</tr>" .

            "<tr class=\"data\">" .
                "<td>" .
                    "<a id=\"arrowl\" href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $prevAlli[0] . "\">" .
                        "<span class=\"alt\">" . "&#60;" . "</span>" .
                    "</a>"  .
                "</td>" .
                "<td>" .
                    "<input type=\"text\" name=\"aid\" size=\"4\" maxlength=\"4\" value=\"" . $iAid . "\" />" .
                "</td>" .
                "<td>" .
                    "<input type=\"submit\" name=\"submit\" value=\"View\" />" .
                "</td>" .
                "<td>" .
                    "<a id=\"arrowr\" href=\"main.php?cat=game&amp;page=alliance&amp;aid=" . $nextAlli[0] . "\">" .
                        "<span class=\"alt\">" . "&#62;" . "</span>" .
                    "</a>"  .
                "</td>" .
            "</tr>" .
        "</table>" .
        "</form>";

    $res = mysql_query("SELECT COUNT(id) AS cnt FROM rankings_personal WHERE alli_id = $iAid");
    $line = mysql_fetch_assoc($res);
    if ($line["cnt"] == '0')
    {
        $res = mysql_query("SELECT id FROM rankings_alliance ORDER BY id DESC LIMIT 1");
        $max = mysql_fetch_assoc($res);
        $res = mysql_query("SELECT id FROM rankings_alliance ORDER BY id ASC LIMIT 1");
        $min = mysql_fetch_assoc($res);
        echo "Selections range from " . $min["id"] . "-" . $max["id"];

        include_game_down();
        exit;
    }

?>
    <div class="center">
<?php

    //==========================================================================
    // Alliance Banner
    //==========================================================================
    $objTrgAlli  = new clsAlliance($iAid);
    $arrAlliance = $objTrgAlli->get_alliance_infos();
    $arrRankingsAlliance = $objTrgAlli->get_rankings_alliances();

    if (trim($arrAlliance[IMAGE]) != '')
    {
        $arrAlliance[IMAGE] = stripslashes($arrAlliance[IMAGE]);
        $arrAlliance[IMAGE] = htmlspecialchars($arrAlliance[IMAGE]);
        $arrAlliance[IMAGE] = escapeshellcmd($arrAlliance[IMAGE]);

        echo "<img src =\"" . $arrAlliance[IMAGE] . "\" width =\"" .
             $arrAlliance[IMAGEWIDTH] . "\" height =\"" . $arrAlliance[IMAGEHEIGHT] .
             "\" alt=\"\" /><br />";
    }

    //==========================================================================
    // War status display
    //==========================================================================
    $arrSrcWar  = $objTrgAlli->get_wars();
    if ($arrSrcWar[TARGET] != 0)
    {
        $targetLink = " (<a href=\"main.php?cat=game&amp;page=alliance&amp;aid=" .
                      $arrSrcWar[TARGET] . "\">#" .
                      $arrSrcWar[TARGET] . "</a>)";

        $objWarTrg   = new clsAlliance($arrSrcWar[TARGET]);
        $strAlliName = stripslashes($objWarTrg->get_rankings_alliance('alli_name'));
        echo '<br /><strong class="negative">At war with ' . $strAlliName . $targetLink . '</strong>';
    }

    //==========================================================================

    if ($iAid < 10)
    {
        $iSpan = 3;
?>
        <span style="font-size: 0.8em"><a href="main.php?cat=game&amp;page=message&amp;alliance=<?php echo $iAid; ?>">Submit a report here</a>.</span>
        <br />
<?php

    }
    else
    {
        $iSpan = 6;
        if ($iAid == $objSrcUser->get_stat(ALLIANCE))
            $iSpan = 7;

        // bootcamp indication
        if ($arrAlliance[BOOTCAMP] == 'yes')
        {

?>
        <br /><strong class='positive'>[ Boot Camp ]</strong><br />

<?php

        }

        // M: Graveyard indication
        if ($iAid == 10)
            echo '<h2>The Graveyard in #10 - Beware of the DEAD.</h2>';

?>

<?php
    }
?>
    </div>
    <br />
    <table cellpadding="0" cellspacing="0" class="big">
        <tr class="header">
            <th colspan="<?php echo $iSpan; ?>">
<?php

    $strAllianceName = stripslashes($arrRankingsAlliance[ALLI_NAME]);
    echo "$strAllianceName (#$iAid)";

    if (trim($arrRankingsAlliance[ALLI_DESC]) != '')
    {
        $allianceDesc = stripslashes(trim($arrRankingsAlliance[ALLI_DESC]));

?>
                <br /><span style="font-size: 0.8em;"><?php echo $allianceDesc;?></span>
<?php

    }

    if($iAid < 10)
    {

?>
            </th>
        </tr>
        <tr class="subheader">
            <th width="17"></th>
            <td align="left" class="left" width="34%">Staff Tribe</td>
            <td align="left" class="left">Online < 48 hours ago</td>
        </tr>
<?php
        // Martel: Sort 1-10 on staff ranks and name instead of acres
        $res = mysql_query("SELECT rankings_personal.id, alli_id, tribe_name, hours, rankings_personal.race, land, nw, rankings_personal.fame, player_type, stats.level FROM rankings_personal, stats WHERE alli_id = $iAid AND stats.id = rankings_personal.id ORDER BY level DESC, tribe_name ASC");
    }
    else
    {
        // Output alliance Rankings
        include_once('inc/functions/alli_ranking.php');
        if ($iAid != 10)
        {
            $arrRanking = get_rank_data($iAid);
            echo "<br />Land: " . $arrRanking[LAND] . " &nbsp;&nbsp; Strength: " .
             $arrRanking[STRENGTH] . " &nbsp;&nbsp; Fame: " . $arrRanking[FAME];
        }

?>
            </th>
        </tr>
        <tr class="subheader">
            <th width="17"></th>
            <th>Tribe Name</th>
<?php
        if ($iAid == $objSrcUser->get_stat(ALLIANCE))
            echo '
            <td><em>Ruler Age</em></td>
            ';
?>
            <td>Race</td>
            <td>Size</td>
            <td>Strength</td>
            <td>Fame</td>
        </tr>
<?php

        // Sort tribes by acreage, strength, fame
        $res = mysql_query("SELECT id FROM rankings_personal WHERE alli_id = $iAid ORDER BY land DESC, nw DESC, fame DESC");
    }

    $inactivitycheck = $objSrcUser->get_rankings_personals();
    $iCount = 0;
    $inactiveCounter = 0;
    $objTmpUser = new clsUser(0);

    while ($line = mysql_fetch_assoc($res))
    {
        // Clear the temporary object to assign a new user
        $iUserid          = $line[ID];
        $objTmpUser->set_userid($iUserid);
        $arrTmpRanking = $objTmpUser->get_rankings_personals();

        $arrTmpRanking[TRIBE_NAME] = stripslashes($arrTmpRanking[TRIBE_NAME]);

        $strFame = (string) number_format($arrTmpRanking[FAME]);
        if ($arrTmpRanking[FAME] < 5000)
            $strFame = '<span class="negative">' . $strFame . '</span>';
        elseif ($arrTmpRanking[FAME] > 5000)
            $strFame = '<span class="positive">' . $strFame . '</span>';

?>
        <tr class="data">
            <th>
<?php
        $online = $objTmpUser->get_onlines();
        $old    = date(TIMESTAMP_FORMAT, strtotime('-5 minutes'));

        // Martel: New inactivity check.
        // Works with both month changes and leap years ;)
        $inactive = date(TIMESTAMP_FORMAT, strtotime('-2 days'));

        if ($online['time'] < $inactive && $inactivitycheck[PLAYER_TYPE] == "elder" && $inactivitycheck[ALLI_ID] == $iAid )
        {

?>
                <img src="<?php echo HOST_PICS; ?>tribe_inactive.gif" alt="*" height="13" width="13" />
<?php

        } elseif ($online['time'] < $old)
        {

?>
                <img src="<?php echo HOST_PICS; ?>tribe_offline.gif" alt="" height="13" width="13" />
<?php

        } else
        {
            $inactiveCounter++;

?>
                <img src="<?php echo HOST_PICS; ?>tribe_online.gif" alt="»" height="13" width="13" />
<?php

        }

?>
            </th>
            <th>
<?php

        // Begin output of <tr> for each tribe
        if ($iAid >= 10)
        {
            $strClass = $arrTmpRanking[PLAYER_TYPE]."";
            if ($objTmpUser->isPaused() || $arrTmpRanking[HOURS] < PROTECTION_HOURS)
                $strClass = "protected";

            echo '<a href="main.php?cat=game&amp;page=external_affairs&amp;tribe=' .
                 $arrTmpRanking[ID] . "&amp;aid=" . $arrTmpRanking[ALLI_ID] .
                 '" class="' . $strClass . '">' . $arrTmpRanking[TRIBE_NAME] .
                 '</a></th>';

            if ($iAid == $objSrcUser->get_stat(ALLIANCE))
                echo "<td><em>" . $objTmpUser->get_ruler_age() . "</em></td>";

            echo "<td>" . $arrTmpRanking[RACE] . "</td>" .
                 "<td>" . number_format($arrTmpRanking[LAND]) . "</td>" .
                 "<td>" . number_format($arrTmpRanking[NW]) . "</td>" .
                 "<td>" . $strFame . "</td>" .
            "</tr>";
        }
        else
        {
            $strClass = "staff";
            if ($objTmpUser->get_stat(LEVEL) > 5)
                $strClass = "admin";
            elseif($objTmpUser->get_stat(LEVEL) == 5)
                $strClass = "head";

            echo '<a href="main.php?cat=game&amp;page=message&amp;tribe=' .
                 $arrTmpRanking[ID] . "&amp;alliance=" . $arrTmpRanking[ALLI_ID] .
                 '" class="' . $strClass . '">' . $arrTmpRanking[TRIBE_NAME] .
                 '</a></th>';

            if ($online['time'] < $inactive)
                echo '<td class="left"><em>No</em></td>';
            else
                echo '<td class="left"><em class="positive">Yes</em></td>';

            echo '</tr>';
        }
        $iCount++;
    }

?>
    </table>

    <div class="center" style="font-size: 0.8em">
        There are <?php echo $iCount; ?> tribes in this alliance.
    </div>

    <br />

    <table cellpadding="0" cellspacing="0" class="small">
        <tr class="header">
            <th colspan="2">Legend</th>
        </tr>
        <tr class="subheader">
            <th>Mark</th>
            <td>Meaning</td>
        </tr>
<?php
    if ($iAid >= 10)
    {
?>
        <tr class="data">
            <th class="elder">Gold:</th>
            <td>Elder</td>
        </tr>
        <tr class="data">
            <th class="coelder">Copper:</th>
            <td>Co-elder</td>
        </tr>
        <tr class="data">
            <th class="protected">Light Green:</th>
            <td>In Protection</td>
        </tr>

<?php
    } else {
?>
        <tr class="data">
            <th class="admin">White:</th>
            <td>Orkfian God</td>
        </tr>
        <tr class="data">
            <th class="head">Pink:</th>
            <td>Orkfian Lord</td>
        </tr>
        <tr class="data">
            <th class="staff">Blue:</th>
            <td>ORKFiA Staff</td>
        </tr>
<?php
    }
?>
        <tr class="data">
            <th><img src="<?php echo HOST_PICS; ?>tribe_online.gif" alt="»" height="13" width="13" /></th>
            <td>Online</td>
        </tr>
<?php
    if ($inactivitycheck[PLAYER_TYPE] == "elder"  && $inactivitycheck['alli_id'] == $iAid && $inactiveCounter > 0)
    {
?>
        <tr class="data">
            <th><img src="<?php echo HOST_PICS; ?>tribe_inactive.gif" alt="*" height="13" width="13" /></th>
            <td>Inactive</td>
        </tr>
<?php
    }
?>
    </table>

<?php
}
?>
