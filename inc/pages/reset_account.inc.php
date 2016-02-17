<?php
//******************************************************************************
// Page reset_account.inc.php                              Martel, June 17, 2006
//
// Note: perhaps move out functions to separate file eg- reset_account2.inc.php
//******************************************************************************
include_once('inc/functions/races.php');
include_once('inc/functions/build.php');

function include_reset_account_text()
{
    global  $Host;

    $objSrcUser = &$GLOBALS["objSrcUser"];
    $arrStats   = $objSrcUser->get_stats();
    $iUserid    = $objSrcUser->get_userid();
    $arrRaces   = getActiveRaces();

    if ($arrStats[RESET_OPTION] == 'yes' || $arrStats[KILLED] > 0)
    {
        if (isset($_POST['submit']))
        {
            $tribe = $_POST['tribe'];
            $tribe = addslashes(strip_tags(trim($tribe)));
            $race  = $_POST['race'];

            // Leader should only be submitted 1 time, right after signing up
            if (isset($_POST['alias']) && !empty($_POST['alias']))
            {
                $alias = $_POST['name'];
                $alias = addslashes(strip_tags(trim($alias)));
            }
            else
                $alias = $arrStats[NAME];

            // Validate form input
            if (!empty($tribe) && !empty($race) && in_array($race, $arrRaces))
            {
                // Check that the new tribe name isn't already taken
                $check = mysql_query("SELECT * FROM " . TBL_STAT . " WHERE tribe = '$tribe' AND id != $iUserid");
                $check = mysql_fetch_array($check);
                $stat_search2 = mysql_query("SELECT * FROM " . TBL_STAT . " WHERE name = '$alias' AND id != $iUserid");
                $stat_search2 = mysql_fetch_array($stat_search2);
                if (isset($check[TRIBE]))
                {
                    $strDiv =
                    '<div id="textSmall">' .
                        '<p>' .
                        'Sorry, but that tribe name is already taken.' .
                        '<br /><br />' .
                        '<a href="main.php?cat=game&amp;page=reset_account">' .
                        'Try Again?' . '</a>' .
                        '</p>' .
                    '</div>';
                    echo $strDiv;
                }
                elseif (isset($stat_search2[NAME]))
                {
                    $strDiv =
                    '<div id="textSmall">' .
                        '<p>' .
                        'Sorry, but that leader name is already taken.' .
                        '<br /><br />' .
                        '<a href="main.php?cat=game&amp;page=reset_account">' .
                        'Try Again?' . '</a>' .
                        '</p>' .
                    '</div>';
                    echo $strDiv;
                }
                else
                {
                    //==========================================================
                    //                       Recoded November 07, 2007 by Martel
                    // Infinity: bonus on age death, Classic: bonus if killed
                    // Cause of death, 2=killed, 1=age, 0=reset
                    //==========================================================
                    $bBonus = FALSE;
                    if ($_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME && $arrStats[KILLED] == 1)
                        $bBonus = TRUE;
                    elseif ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME && $arrStats[KILLED] == 2)
                        $bBonus = TRUE;

                    // Race housing effeciency
                    $arrBuildVariables = getBuildingVariables($race);
                    $homes_hold        = $arrBuildVariables['housing'][1];
                    $ratio             = 1;

                    // Death Bonus "Heritage"          Martel, November 07, 2007
                    if ($bBonus && $objSrcUser->get_kill(LAND) > STARTING_LAND)
                    {
                        $arrKill = $objSrcUser->get_kills();
                        $ratio   = $homes_hold / $arrKill[POP];
                        $iLand   = $arrKill[LAND];
                        $arrCost = getUnitVariables($race);
                        $arrCost = $arrCost['gold'];

                        // New Heritage     (Age9) AI+Martel, September 25, 2007
                        $iLand = round(pow(max(0, $iLand - STARTING_LAND), 0.80459611995) + STARTING_LAND);
                        if ($iLand > 2000)
                            $iLand = 2000;
                        elseif ($iLand < STARTING_LAND)
                            $iLand = $iLand;

                        // Buildings
                        $arrBonusValues[LAND]     = $iLand;

                        // Goods
                        $arrBonusValues[RESEARCH] = 0; //round(($arrKill[RESEARCH] / $arrKill[LAND]) * $land);

                        // Citizens
                        $arrBonusValues[CITIZENS] = round($iLand * 15 * $ratio);

                        // Fame
                        $arrBonusValues[FAME]     = round($arrKill[FAME] * 0.04);

                        // Market Goods
                        $arrBonusValues[MONEY]    = round($arrKill[CASH] * $ratio);
                        $arrBonusValues[UNIT1]    = round($arrKill[BASICS] * $ratio);
                        $arrBonusValues[CREDITS]  = round(2 * $arrBonusValues[MONEY] + (2 * $arrCost[2] + 15) * $arrBonusValues[UNIT1]);
                    }
                    else
                    {
                        $arrBonusValues[LAND] = 0;
                        $arrBonusValues[RESEARCH] = 0;
                        $arrBonusValues[CITIZENS] = 0;
                        $arrBonusValues[FAME] = 0;
                    }

                    //==========================================================
                    // Reset Tables (excluded from this reset is TBL_USER,
                    // TBL_STATS and TBL_PREFERENCES)
                    //==========================================================
                    $iNewLand = STARTING_LAND + $arrBonusValues[LAND];
                    include_once('inc/functions/reset_account.php');
                    $arrStartValues = getStartValues($race, $iNewLand);

                    // User table (save old but set updates to start now)
                    $arrUsers = $objSrcUser->get_user_infos();
                    $arrNewUsers = array
                    (
                        HOURS            => 0,
                        LAST_LOGIN       => date('Y-m-d H:i:s'),
                        LAST_UPDATE_HOUR => date('H'),
                        LAST_UPDATE_DAY  => date('d')
                    );
                    $objSrcUser->set_user_infos($arrNewUsers);

                    // Build Table
                    mysql_query("DELETE FROM build WHERE id = $iUserid") or die("build1");
                    mysql_query("INSERT INTO build SET id = $iUserid") or die("build2");

                    // add land + buildings (overrides hardcoded DB defaults)
                    $arrBuildsNew = array
                    (
                        LAND => $arrStartValues[LAND],
                        HOMES => $arrStartValues[HOMES],
                        FARMS => $arrStartValues[FARMS],
                        MARKETS => $arrStartValues[MARKETS],
                        YARDS => $arrStartValues[YARDS],
                        GUILDS => $arrStartValues[GUILDS],
                        HIDEOUTS => $arrStartValues[HIDEOUTS]
                    );
                    $objSrcUser->set_builds($arrBuildsNew);

                    // Army Table
                    mysql_query("DELETE FROM army WHERE id = $iUserid") or die("army");
                    mysql_query("INSERT INTO army SET id = $iUserid") or die("army2");

                    // add military units (overrides hardcoded DB defaults)
                    $arrArmysNew = array
                    (
                        UNIT1 => $arrStartValues[UNIT1],
                        UNIT2 => $arrStartValues[UNIT2],
                        UNIT3 => $arrStartValues[UNIT3],
                        UNIT4 => $arrStartValues[UNIT4],
                        UNIT5 => $arrStartValues[UNIT5]
//                         UNIT6 => $arrStartValues[UNIT6]
                    );
                    $objSrcUser->set_armys($arrArmysNew);

                    // ArmyMercs Table
                    mysql_query("DELETE FROM army_mercs WHERE id = $iUserid") or die("army");
                    if ($race == "Oleg Hai" || $race == "Mori Hai")
                    {
                        mysql_query("INSERT INTO army_mercs SET id = $iUserid") or die("army2");

                        if ($race == "Oleg Hai")
                        {
                            mysql_query("UPDATE army SET unit4 = 0 WHERE id = $iUserid");
                        }
                    }

                    // Milreturn Table
                    mysql_query("DELETE FROM milreturn WHERE id = $iUserid") or die("milreturn");
                    mysql_query("INSERT INTO milreturn SET id = $iUserid") or die("milreturn2");

                    // Population Table
                    $iNewCitz = $arrStartValues[CITIZENS] + $arrBonusValues[CITIZENS];
                    $objSrcUser->set_pop(CITIZENS, $iNewCitz);

                    // Personal Rankings
                    mysql_query("DELETE FROM rankings_personal WHERE id = $iUserid") or die("milreturn");
                    mysql_query("INSERT INTO rankings_personal SET id = $iUserid") or die("milreturn2");

                    // Goods Table
                    mysql_query("DELETE FROM goods WHERE id = $iUserid") or die("goods");
                    mysql_query("INSERT INTO goods SET id = $iUserid") or die("goods2");

                    // Add modified starting values to Goods
                    $arrGoodsNew = array
                    (
                        MONEY => $arrStartValues[MONEY],
                        FOOD => $arrStartValues[FOOD],
                        WOOD => $arrStartValues[WOOD],
                        RESEARCH => $arrStartValues[RESEARCH] + $arrBonusValues[RESEARCH]
                    );
                    $objSrcUser->set_goods($arrGoodsNew);

                    // Spells Table
                    mysql_query("DELETE FROM spells WHERE id = $iUserid") or die("spells");
                    mysql_query("INSERT INTO spells SET id = $iUserid") or die("spells2");

                    // add basic self spells (overrides hardcoded DB defaults)
                    $arrSpellsNew = array
                    (
                        POPULATION => $arrStartValues[POPULATION],
                        GROWTH     => $arrStartValues[GROWTH],
                        FOOD       => $arrStartValues['matawaska'],
                        INCOME     => $arrStartValues[INCOME]
                    );
                    $objSrcUser->set_spells($arrSpellsNew);

                    // Thievery Table
                    mysql_query("DELETE FROM thievery WHERE id = $iUserid") or die("thievery");
                    mysql_query("INSERT INTO thievery SET id = $iUserid") or die("thievery2");

                    // Kill Table
                    mysql_query("DELETE FROM kills WHERE id = $iUserid");
                    mysql_query("INSERT INTO kills SET id = $iUserid");

                    // Stats table (save old but update new tribe name & race)
                    $arrStats = $objSrcUser->get_stats();
                    $arrStatsNew = array
                    (
                        KILLED => 0,
                        TRIBE => $tribe,
                        RACE => $race,
                        FAME => $arrStartValues[FAME] + $arrBonusValues[FAME],
                        RESET_OPTION => "no",
                        INVESTED => 0,
                        KILLS => 0,
                        TWG_VOTE => 0
                    );
                    $objSrcUser->set_stats($arrStatsNew);

                    //==========================================================
                    // Add bonus to build, tribe goods & alliance market
                    //==========================================================
                    if ($bBonus)
                    {
                        // add kill bonus to market credits
                        $arrGoodsNew = array
                        (
                            MARKET_MONEY    => $arrBonusValues[MONEY],
                            MARKET_SOLDIERS => $arrBonusValues[UNIT1],
                            CREDITS         => $arrBonusValues[CREDITS]
                        );
                        $objSrcUser->set_goods($arrGoodsNew);

                        // alliance object
                        include_once('inc/classes/clsAlliance.php');
                        $iAllianceId    = $objSrcUser->get_stat(ALLIANCE);
                        $objSrcAlliance = new clsAlliance($iAllianceId);

                        // add bonus to alliance market
                        $arrAllianceInfos = $objSrcAlliance->get_alliance_infos();
                        $arrNewAllianceInfos = array
                        (
                            MONEY => ($arrAllianceInfos[MONEY] + $arrBonusValues[MONEY]),
                            SOLDIERS => ($arrAllianceInfos[SOLDIERS] + $arrBonusValues[UNIT1])
                        );
                        $objSrcAlliance->set_alliance_infos($arrNewAllianceInfos);
                    }

                    // Update rankings (forced = yes)
                    include_once('inc/functions/update_ranking.php');
                    doUpdateRankings($objSrcUser, 'yes');

                    $strDiv =
                    '<div id="textSmall">' .
                        '<p>' .
                        'Account updated =)' .
                        '<br /><br />' .
                        '<a href="main.php?cat=game&amp;page=tribe">' .
                        'Continue' . '</a>' .
                        '</p>' .
                    '</div>';
                    echo $strDiv;
                }
            }
            else
            {
                $strDiv =
                '<div id="textSmall">' .
                    '<p>' .
                    'You forgot to enter a new tribe name and/or forgot to ' .
                    'choose a race.' .
                    '<br /><br />' .
                    '<a href="main.php?cat=game&amp;page=reset_account">' .
                    'Try Again?' . '</a>' .
                    '</p>' .
                '</div>';
                echo $strDiv;
            }
        }
        else
        {
            $picture = 'defeat';
            if ($arrStats[KILLED] == 2)
            {
                $strMessage =
                    '<p>' .
                    stripslashes($arrStats[NAME]) . ', it seems that <strong class="negative">your last ' .
                    'citizens have left your tribe</strong>. ' .
                    'With them the last of your power is gone and so is your physical presence in the lands of Orkfia. But, within ' . PROTECTION_HOURS . ' ' .
                    'months you could once again do battle with your allies. ' .
                    '</p>';
                $picture = 'defeat';
            }
            elseif ($arrStats[KILLED] == 0)
            {
                $strMessage =
                    '<p>' .
                    stripslashes($arrStats[NAME]) . ', it seems that <strong class="negative">you have ' .
                    'decided to reset</strong>. ' .
                    'The last of your power is gone and so is your physical presence in the lands of Orkfia. But, within ' . PROTECTION_HOURS . ' ' .
                    'months you could once again do battle with your allies. ' .
                    '</p>';
                $picture = 'defeat';
            }
            elseif ($arrStats[KILLED] == 1)
            {
                $strMessage =
                    '<p>' .
                    stripslashes($arrStats[NAME]) . ', it seems that <strong class="positive">the leader ' .
                    'of your tribe has died due to age</strong>. ' .
                    'The last of your power is gone and so is your physical presence in the lands of Orkfia. But, within ' . PROTECTION_HOURS . ' ' .
                    'months you could once again do battle with your allies. ' .
                    '</p>';
                $picture = 'victory';
            }
            elseif ($arrStats[KILLED] == 3)
            {
                $strMessage =
                    '<p>' .
                    'Welcome! It seems that <strong class="positive">you are ' .
                    'about to create your first tribe in Orkfia</strong>. ' .
                    'Currently you have no physical presence in these lands. But, within ' . PROTECTION_HOURS . ' ' .
                    'months you could do battle together with your allies. ' .
                    '</p>' .

                    '<p>' .
                    '<label for="1">' . 'Leader Name: ' . '</label>' .
                    '<input id="1" name="name" size="20" maxLength="20">' .
                    '</p>';
                $picture = 'victory';
            }

            $strForm =
                '<form method="POST" ' .
                'action="main.php?cat=game&amp;page=reset_account">' .
                '<div id="textMedium">' .

                    '<p style="text-align: center">' .
                        '<img src="' . $Host . $picture . '_small.gif">' .
                    '</p>' .

                    $strMessage .

                    '<p>' .
                    'Your account is now paused, time will start running once you submit the information below.' .
                    '</p>' .

                    '<p>' .
                    '<label for="2">' . 'New Tribe Name: ' . '</label>' .
                    '<input id="2" name="tribe" size="20" maxLength="20" value="' .
                    stripslashes($arrStats[TRIBE]) . '">' .
                    '</p>' .

                    '<p>' .
                    '<label for="3">' . 'Select Race: ' . '</label>' .
                    '<select id="3" size="1" name="race">';

            foreach ($arrRaces as $strCurRace)
            {
                $strAdd = '';
                if ($arrStats[RACE] == $strCurRace)
                    $strAdd = ' selected';

                $strForm .=
                    "<option value=\"$strCurRace\"$strAdd>" . $strCurRace . "</option>";
            }

            $strForm .=
                    '</select> ' .
                    '<a href="http://guide.orkfia.org/races.php?chapter=4" class="newWindowLink" target="_blank" style="cursor: help">Races</a>' .
                    '</p>' .

                    '<p>' .
                    '<input type="submit" name="submit" value="Return to Orkfia!">' .
                    '</p>' .

                    '</div>' .
                '</form>';

            echo $strForm;
        }
    }
    else
    {
        // Kick someone in the butt for finding this page through address field
        include_once("inc/pages/logout.inc.php");
        include_logout_text();
    }
}
?>
