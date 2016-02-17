<?php
//==============================================================================
// Changing over all the selfspell/allispell/warspell hacks to a
//  proper system: the 'type' attribute of each spell  - AI 30/09/06
// explanation:
// SPELL_SELF can only be cast on self
// SPELL_ALLIANCE can be cast on allimates (and on self)
// SPELL_ALL can be cast on anyone but self
// SPELL_ENEMY can only be cast on other alliances
// SPELL_WAR can only be cast on wartarget                      - AI 01/10/06
//==============================================================================
require_once('inc/classes/clsBlock.php');

function make_magic2(&$objSrcUser, $i_intTargetid, &$arrSpells, $i_strSpellName, $i_intSpelltimes, $i_blnStopOnSuccess, $i_blnMinHours, $i_minHours)
{
    $iUserID = $objSrcUser->get_userid();
    $damageModifier = 1;

    mt_srand((double)microtime()*1000000);

    $objTrgUser = new clsUser ($i_intTargetid);
    $arrSrcStats = $objSrcUser->get_stats();
    $arrTrgStats = $objTrgUser->get_stats();
    $arrTrgBuild = $objTrgUser->get_builds();
    $intCasterMageLevel = get_mage_level($objSrcUser);
    $intTargetMageLevel = get_mage_level($objTrgUser);

    if ($arrTrgStats[ALLIANCE] == "0")
    {
        echo "This Player Has Either Been Deleted Or Suspended";
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // war check
    include_once("inc/functions/war.php");
    // Gotland was here
    $warmodifier = war_alli($objTrgUser->get_stat(ALLIANCE) , $objSrcUser->get_stat(ALLIANCE));
    if ($warmodifier > 1)
    {
        $res = mysql_query("SELECT defiance FROM spells WHERE id = $iUserID");
        $line = mysql_fetch_assoc($res);
        $damageModifier =  $damageModifier * 1.1;
        if ($line["defiance"] > 0) { $damageModifier = $damageModifier * 1.1; }
    }
    $target = war_target($arrTrgStats[ALLIANCE]);
    if ( $target != 0 && $damageModifier == 0) { //what is this supposed to do? right now it does nothing at all - AI
        $damageModifier = $damageModifier * 0.95;
    }
    $lastWar = mysql_query("SELECT last_target, last_end FROM war WHERE id = " . $arrSrcStats[ALLIANCE]);
    $lastWar  = mysql_fetch_array($lastWar);
    $timeCounter = mysql_query("SELECT hour_counter FROM admin_global_time");
    $timeCounter = mysql_fetch_array($timeCounter);

    // Spell type = SPELL_SELF, SPELL_ALLIANCE, SPELL_ENEMY etc (integers)
    $strSpellType = $arrSpells[$i_strSpellName]['type'];

    // Spell Display = full name of spell
    $strSpellDisplay = $arrSpells[$i_strSpellName]['display'];

    if ($arrTrgStats[ALLIANCE] == $lastWar['last_target'] && $strSpellType == SPELL_ENEMY)
    {
        if ($timeCounter['hour_counter'] <= ($lastWar['last_end']+12))
        {
            echo '<div class="center">The war is not even over for 12 hours. Give them some time to recover!</div>';
            free_casting_now($iUserID);
            include_game_down();
            exit;
        }
    }

    include_once('inc/functions/update.php');
    check_to_update($objTrgUser->get_userid());

    // Include the code for the spell about to be cast
    require_once("inc/spells/" . $i_strSpellName . ".php");

    // Check for casting "harmful" spells on yourself
    if ($iUserID == $objTrgUser->get_userid() && ($strSpellType == SPELL_ENEMY || $strSpellType == SPELL_ALL || $strSpellType == SPELL_WAR))
    {
        echo '<div class="center">' ."I'm sorry you cannot cast " . $strSpellDisplay . " upon yourself.\n";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&magekd=" . $objSrcUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a></div>";
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // Martel: Heal may only target alliance members
    // SPELL_ALLIANCE only on allimates
    $iSrcAlli = $objSrcUser->get_stat(ALLIANCE);
    $iTrgAlli = $objTrgUser->get_stat(ALLIANCE);

    if ($strSpellType == SPELL_ALLIANCE && $iTrgAlli != $iSrcAlli)
    {
        echo '<div class="center">' ."Sorry but you cannot cast ". $strSpellDisplay . " on non-allies.<br />";
        echo "<br /><br /><br /><a href=\"main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            "\">Back to Mystics</a></div>";
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // SPELL_ENEMY not on own alli
    if ($strSpellType == SPELL_ENEMY && $iTrgAlli == $iSrcAlli)
    {
        echo '<div class="center">' ."Sorry, but I refuse to do harm to our alliance members.<br />";
        echo "<br /><br /><br /><a href=\"main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            "\">Back to Mystics</a></div>";
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // Check for target protection period
    if ($objTrgUser->get_user_info(HOURS) < PROTECTION_HOURS)
    {
        // Removed code-reuse of the copy-paste variety            - AI 30/09/06
        if ($strSpellType != SPELL_SELF)
        {
            $iRemaining = PROTECTION_HOURS - $objTrgUser->get_user_info(HOURS);
            echo
                '<div id="textMedium"><p>' .
                    'It appears that the tribe you wish to target is still ' .
                    'materializing. Our Mage estimates that it will take another ' .
                    $iRemaining . ' updates for the area to become a stable part of ' .
                    'our reality.' .
                    "</p><p>" .
                    '<a href="main.php?cat=game&page=mystic&amp;magekd=' . $objTrgUser->get_stat(ALLIANCE) . '">Back to Mystics</a>' .
                    '</p>' .
                '</div>';

            free_casting_now($iUserID);
            include_game_down();
            exit;
        }
    }

    // Check for own protection period (this is also checked in mystic2.inc.php
    if ($objSrcUser->get_user_info(HOURS) < PROTECTION_HOURS)
    {
        if ($strSpellType != SPELL_SELF)
        {
            echo
                '<div id="textMedium"><p>' .
                    'You are still under protection.' .
                    "</p><p>" .
                    '<a href="main.php?cat=game&page=mystic&amp;magekd=' . $objSrcUser->get_stat(ALLIANCE) . '">Back to Mystics</a>' .
                    '</p>' .
                '</div>';

            free_casting_now($iUserID);
            include_game_down();
            exit;
        }
    }

    // Check for visioning a spirit
    if (($objTrgUser->get_stat(RACE) == 'Spirit') && $i_strSpellName == 'vision')
    {
        echo
            '<div id="textMedium"><p>' .
                "Your mystics are confused, they can't see anything at all." .
                "</p><p>" .
                '<a href="main.php?cat=game&page=mystic&amp;magekd=' . $objSrcUser->get_stat(ALLIANCE) . '">Back to Mystics</a>' .
                '</p>' .
            '</div>';

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // Check for casting jura on a Templar - AI 11/02/2007
    if (($objTrgUser->get_stat(RACE) == 'Templar') && ($i_strSpellName == "juranimosity"))
    {
        echo "Sorry, but " . $objTrgUser->get_stat(TRIBE) . " does not have any thieves for me to disband.";
        echo "<br /><br /><br /><a href=\"main.php?cat=game&amp;page=mystic&amp;magekd=" .
            $objTrgUser->get_stat(ALLIANCE) . ">Back to Mystics</a>";

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // check the user has cast it at lest 1 time
    if ($i_intSpelltimes <= 0) {
        echo "Sorry but you must cast this spell at least 1 time.<br />";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a>";

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // Added by Genia4, checks that the user didnt ask to cast a self-spell until it succeeds for less than 1 hours.
    if ($i_blnMinHours&&$i_minHours<=0)
    {
        echo "Sorry but you must tell your mage for how much time you want the self spell.<br />";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a>";

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // check the user has cast it no more than X times
    if ($i_intSpelltimes > MAX_SPELL_CASTS)
    {
        echo "Sorry but you can't auto-cast more than " . MAX_SPELL_CASTS . " times in a row in the interests of reducing server lag.<br />";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a>";

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // check they aren't trying to cast a spell beyond their level, they must've modified the
    // form directly to do this....TSKTSK!
    $iMageLevel = get_mage_level($objSrcUser);

    // Martel: Added age 22 to implement high acreage spells
    // And removed again - AI 30/09/06
    //$arrSrcBuild = $objSrcUser->get_builds();
    //$iTotalAcres = $arrSrcBuild[LAND];
    if ($iMageLevel < $arrSpells[$i_strSpellName]['level']) //|| $iTotalAcres < $arrSpells[$i_strSpellName]["acres"]
    {
        echo "I'm sorry, you cannot cast " . $strSpellDisplay . ".";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&magekd=" . $objSrcUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a>";

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    if ((!clsBlock::isOpAllowed($objSrcUser, $objTrgUser)) && ($strSpellType != SPELL_SELF))
    {
        echo '<div id="textMedium"><p>' .
            'Someone else from the same IP has already opped this tribe during the last 8 hours.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=mystic">Return</a>' .
            '</p></div>';

        clsBlock::reportOp($objSrcUser, $objTrgUser, 'Spell: ' . $i_strSpellName, false);

        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    $intOldPower = $objSrcUser->get_spell(POWER);

    //Check for Thwart
    $trgThwart = 1;
    if ($strSpellType != SPELL_SELF)
    {
        $arrTrgSpells = $objTrgUser->get_spells();
        if ($arrTrgSpells[THWART] > 0) $trgThwart = 0.75;
    }

    //Churches
    // Tragedy: april 20th 2002:
    // adding a cap of max 80% effectiveness on churches, ergo max 20% of land
    $church_percentage = min(0.2, ($arrTrgBuild['churches'] / $arrTrgBuild['land']));
    //frost: added high elves church bonus
    if ($objTrgUser->get_stat(RACE) == 'High Elf')
        $church_bonus = $church_percentage * 4;
    else
        $church_bonus = $church_percentage * 3.5;

    // Skathen: May 10th 2002:
    // Stop churches affecting self spells
    // Stop selected target affecting self spells
    // Martel: version 2.0, same purpose
    if ($strSpellType == SPELL_SELF)
    {
        //next line a small hack to prevent possible future bugs
        $objTrgUser = $objSrcUser;
        $church_bonus = 0;
        $trgThwart = 1;
        $intTargetMageLevel = 12 - $intCasterMageLevel;
        if ($intTargetMageLevel <= 4)
            $intTargetMageLevel = 4;
        if ($objSrcUser->get_user_info(HOURS) < PROTECTION_HOURS)
            $intTargetMageLevel = 3;
    }

    $chance_to_cast = formulate_chance($intCasterMageLevel, $intTargetMageLevel, $arrSpells[$i_strSpellName]);

    //==========================================================================
    // Main block - calculates success-rates, calls the specific spell-function
    //==========================================================================

    //How many spells can we cast ?
    //since we don't have 'infinity', we'll use an arbitrarily large number
    $totalAvailable = $arrSpells[$i_strSpellName]['cost'] > 0 ? floor($intOldPower / $arrSpells[$i_strSpellName]['cost']) : 9999999999;
    if ($totalAvailable > $i_intSpelltimes)
        $totalAvailable = $i_intSpelltimes;

    /* check the tribe still has the power to cast one time*/
    if ($totalAvailable <= 0)
    {
        echo '<div class="center">' . "I'm sorry, you don't have enough Magic Power to cast that spell.";
        echo "<br /><br /><br /><a href=main.php?cat=game&page=mystic&amp;magekd=" . $objTrgUser->get_stat(ALLIANCE) .
            ">Back to Mystics</a></div>";
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    /* check for casting on a Nazgul, but only if its not a self spell */
    if (($strSpellType != SPELL_SELF) && ($objTrgUser->get_stat(RACE) == "Nazgul"))
        $nazgulBonus = 0.2;
    else
        $nazgulBonus = 0;

    // added nazgul casting failures
    if ($objSrcUser->get_stat(RACE) == "Nazgul")
        $nazgulPenalty = 0.25;
    else
        $nazgulPenalty = 0;

     /* check for casting on a Dragon, casting on dragon gives 50% less damage */
    if ($objTrgUser->get_stat(RACE) == "Dragon")
        $damageModifier *= 0.5;

    // Roar of the horde fireball
    if (($strSpellType == SPELL_ENEMY) && (($objTrgUser->get_stat(RACE) == "Uruk Hai") || ($objTrgUser->get_stat(RACE) == "Oleg Hai") ||($objTrgUser->get_stat(RACE) == "Mori Hai")))
    {
        $id = $objTrgUser->get_userid();
        $seek = mysql_query("Select * from spells where id = $id");
        $seek = mysql_fetch_array($seek);
        if ($seek['roar'] > 0 && $seek['forest'] == 0) $rothBonus = 1/7;
        else $rothBonus = 0;
    }
    else $rothBonus = 0;

    //Formulate the independent failure-chances. ML=magelevel, CH=Church-protection, race=race-protection, etc...
    $P_ML = (1 - $trgThwart*min($chance_to_cast,290)/300);
    $P_CH = $church_bonus;
    //$P_race1 -- used to be dragon protection
    $P_race2 = $nazgulBonus;
    $P_race3 = $nazgulPenalty;
    $P_roth = $rothBonus;

    // Martel: Adding exceptions here (alliance spell)
    if ($strSpellType == SPELL_ALLIANCE)
    {
        $P_CH = 0;
        $P_roth = 0;
    }

    //Calculate total chance of success per spell
    $P_success = (1 - $P_ML)*(1 - $P_CH)*(1 - $P_race2)*(1 - $P_race3)*(1 - $P_roth);

    //Loop through the number of spells casted, randomly decide wether it succeeds or fails.
    //When it fails, randomly choose a reason based on the relative failure-rates of all possible failure-reasons
    //Note that Stop-On-Success will be dealt with later on, by the spell-include-function
    $cntSpellSuccess = 0;
    $cntSF_total = 0;
    $cntSF_ML = 0;
    $cntSF_CH = 0;
    $cntSF_race2 = 0;
    $cntSF_race3 = 0;
    $cntSF_roth = 0;

    //Don't worry too much about the math behind it. It's correct and assures a fair distribution over the various 'reasons for failure'
    $P_fail_Total = $P_ML+$P_CH+$P_race2+$P_race3+$P_roth;

    $P_fail_ML = $P_ML / $P_fail_Total;
    $P_fail_CH = $P_CH / $P_fail_Total;
    $P_fail_race2 = $P_race2 / $P_fail_Total;
    $P_fail_race3 = $P_race3 / $P_fail_Total;
    $P_fail_roth = $P_roth / $P_fail_Total;

    if (($i_blnStopOnSuccess && $i_blnMinHours) && ($strSpellType == SPELL_SELF )) $i_blnStopOnSuccess = FALSE;
    if (($i_blnMinHours) && ($strSpellType != SPELL_SELF)) $i_blnStopOnSuccess = TRUE;

    for ($x = 1; $x <= $totalAvailable; $x++)
    {
        $random  = rand(1,10000)/10000;

        if ($random < $P_success)
        {
            $cntSpellSuccess++;
            //Stop-On-Success check
            if ($i_blnStopOnSuccess == 1)
            {
                $totalAvailable = $x;
                break;
            }
        }
        else
        {
            $cntSF_total++;

            //Why did the spell fail ? Default ML-difference, CHs, race-protection, roth-protection, etc...

            $random = rand(1,10000)/10000;
            if ($random <= $P_fail_ML) $cntSF_ML++;
            if ($random > $P_fail_ML && $random <= $P_fail_ML+$P_fail_CH) $cntSF_CH++;
            if ($random > $P_fail_ML+$P_fail_CH && $random <= $P_fail_ML+$P_fail_CH+$P_fail_race2) $cntSF_race2++;
            if ($random > $P_fail_ML+$P_fail_CH+$P_fail_race2 && $random <= $P_fail_ML+$P_fail_CH+$P_fail_race2+$P_fail_race3) $cntSF_race3++;
            if ($random > $P_fail_ML+$P_fail_CH+$P_fail_race2+$P_fail_race3) $cntSF_roth++;
        }
    }

    // Ok, now we're done with calcing how many spells will succeed and why they will fail, we proceed to actually casting the spells

    // Call with: SpellCaster-object, Target-object, Spellname, Times-to-cast, Minimum-hours
    if (!$i_blnMinHours)
        $minHours = 0;
    else
        $minHours = $i_minHours;

    if ($cntSpellSuccess > 0) {
        $spellResult = cast_spell($objSrcUser,$objTrgUser,$arrSpells[$i_strSpellName],$cntSpellSuccess,$minHours,$damageModifier);
    } else {
        // Gotland: initialize the spellresult to avoid error message in case all attempts failed
        $spellResult["casted"] = 0;
        $spellResult["damage"] = 0;
        $spellResult["text_news"] = "";
        $spellResult["text_screen"] = "";
    }

    // $spellResult structure: (it's an array)
    // ["damage"] = 'Damage' done, could be used to calculate fame, not used for that right now.
    // ["casted"] = Amount of spells casted
    // ["text_screen"] = Return-text for the spell, to be outputted on the screen
    // ["text_news"] = text for the tribenews of the victim

    // Spend the mana and save back to to the db
    $manaSpent = ($spellResult["casted"]+$cntSF_total)*$arrSpells[$i_strSpellName]['cost'];
    $objSrcUser->set_spell(POWER, $intOldPower-$manaSpent);

    $dtTimestamp = date(TIMESTAMP_FORMAT);

    // Print out spell-casting-report
    if ($cntSpellSuccess == 0) $spellResult["casted"] = 0;

    $strReport =
        "<p>" .
            "Your mage has casted the spell ".($spellResult["casted"]+$cntSF_total)." times.<br />" .
            "He succeeded ".$spellResult["casted"]." times and failed ".$cntSF_total." times.<br />";

    if ($cntSF_ML > 0)
        $strReport .= $cntSF_ML . " failures he blames on lack of training.<br />";

    if ($cntSF_CH > 0)
        $strReport .= $cntSF_CH . " of his cast-attempts were stopped by the Gods.<br />";

    if ($cntSF_race2 > 0)
        $strReport .= $cntSF_race2 . " spells failed due to ancient Nazgul protection.<br />";

    if ($cntSF_race3 > 0)
        $strReport .= $cntSF_race3 . " times your mage was hindered by our Nazgul curse.<br />";

    if ($cntSF_roth > 0)
    {
        // Roar of the Hoard "fireball bonus"
        // M: Updated to use objects. August 05, 2007
        $citizens    = $objSrcUser->get_pop(CITIZENS);
        $totalKilled = 0;
        for ($x = 1; $x <= $cntSF_roth; $x++)
        {
            $killed = ceil($citizens * 0.05);

            if (($citizens - $killed) < 2000)
                $killed = rand(10,45);

            if (($citizens - $killed) < 100)
                $killed = rand(2,4);

            if (($citizens - $killed) < 50)
                $killed = 0;

            $citizens -= $killed;
            $totalKilled += $killed;
        }
        $objSrcUser->set_pop(CITIZENS, $citizens);
        $strReport .=
            '</p><p>Those orks must be under the influence of some spell. ' .
            $cntSF_roth . ' spells were returned by them in the form of ' .
            'fireballs! <strong class="negative">' . number_format($totalKilled) .
            '</strong> citizens were killed.</p><p>';
    }

    if ($cntSpellSuccess > 0)
    {
        $strReport .= "</p><p>Your mage reports the following results:<br />";
        $strReport .= $spellResult["text_screen"] . "<br />";
    }

    if ($spellResult["damage"] == 0)
    {
        $intFameWon = fame_win($objSrcUser, $objTrgUser, 0);
    }
    else
    {
        if ($i_strSpellName == "enforced") {
            $fame = floor( $spellResult["damage"] * 0.1 );
        } else {
            $fame = floor( $spellResult["casted"] * $arrSpells[$i_strSpellName][FAME] );
        }

        $intFameWon = fame_win($objSrcUser, $objTrgUser, $fame);

        $strReport .= "</p><p>Your Mage gained a total of <strong class='positive'>" . number_format($intFameWon) . " fame</strong>.</p><p>";
    }

    // Add spell-message to target tribenews
    if (isset($spellResult["text_news"]))
    {
        if ($spellResult["text_news"] != "" && $spellResult["casted"] > 0)
        {
            // Insert upwards compatibility with spells that do allinews
            //                                              - AI 02/12/06
            $strAlliMsgTemp = "";

            if (isset($spellResult["alli_news"]))
                $strAlliMsgTemp = $spellResult["alli_news"];

            $strMsgTemp = $spellResult["text_news"];

            insert_news_item($i_strSpellName, $objTrgUser->get_userid(),
                             $iUserID, 2, $strMsgTemp,
                             $strAlliMsgTemp);

            //trigger news flag of defender
            $objTrgUser->set_user_info(LAST_NEWS, 1);
        }
    }

    // Add failed-spells message to target tribenews
    if (($strSpellType != SPELL_SELF && $i_strSpellName != "vision") && ($cntSF_total > 0))
    {
        if ($cntSF_total > 1) $plural = "s";
        else $plural = "";

        $strMsgTemp = "Our Mage has detected $cntSF_total failed " . $strSpellDisplay .
                      " spell$plural coming from " . $arrSrcStats[TRIBE] . "(#" . $arrSrcStats[ALLIANCE] . ").";

        $strAlliMsgTemp = "";

        insert_news_item($i_strSpellName, $objTrgUser->get_userid(),
                         $iUserID, 2, $strMsgTemp,
                         $strAlliMsgTemp);

        //trigger news flag of defender
        $objTrgUser->set_user_info(LAST_NEWS, 1);
    }

    // Check for kill-by-fireball.
    if ($spellResult["damage"] <= -100)
        obj_test_for_kill($objTrgUser, $objSrcUser);

    // AI's block system
    if ($strSpellType != SPELL_SELF)
        clsBlock::logOp($objSrcUser, $objTrgUser, 'Spell: ' . $i_strSpellName);

    $strReport .=
        "</p>" .
        "<p>" .
            "<a href=main.php?cat=game&page=mystic&magekd=" . $objTrgUser->get_stat(ALLIANCE) . ">Back to Mystics</a>" .
        "</p>";

    // Print out the Report
    echo
        '<div id="textBig">' .
            '<h2>' . "Mystics Report " . '</h2>' .
            $strReport .
        '</div>';

    // As requested... Show spells on success. Will people ever be satisfied? :p
    if ($spellResult["casted"] > 0 && $strSpellType == SPELL_SELF)
    {
        include_once('inc/pages/advisors.inc.php');
        echo '<br/>' . get_effecting_spells_table($objSrcUser);
    }

    free_casting_now($iUserID);
    include_game_down();
    exit;
}

//frost: added update of "casting_now" field as function so it can get called before include_game_down
function free_casting_now($iUserID)
{
    mysql_query("UPDATE spells SET casting_now='free' WHERE id=$iUserID");
}

function get_mage_level(&$objSrcUser)
{
    $arrBuild   = $objSrcUser->get_builds();
    $strRace    = $objSrcUser->get_stat(RACE);
    $iMageLevel = 100 * ($arrBuild[ACADEMIES] / $arrBuild[LAND]);

    if ($strRace == 'Dark Elf')
        $iMageLevel *= 1.25;
    //Templars can alternatively get a magelevel through 'mystic units' - AI
    if ($strRace == 'Templar'){
        $iMystics = $objSrcUser->get_army_home(UNIT5);
        $iMysticLevel = $iMystics / ( $arrBuild[LAND] * 1.5 );
        $iMageLevel = max($iMageLevel, $iMysticLevel);
    }

    // Max ML depends on tribe size, still you may have up to ML 5 on < 500 land
    $iMax       = ($arrBuild[LAND] / 100);
    //Templars need 20% less acres - AI
    if ($strRace == 'Templar')
        $iMax  /= .8;
    $iMax       = round($iMax, 0);
    $iMax       = max($iMax, 5);

    $iMageLevel = round($iMageLevel, 0);
    if ($iMageLevel > $iMax)
        $iMageLevel = $iMax;

    return max($iMageLevel, 1);
}


function formulate_chance($i_intSrcMageLevel, $i_intDestMageLevel,$arrSpellType)
{
    $add = 0;
    if ($i_intSrcMageLevel > $i_intDestMageLevel)
    {
        $add = $i_intSrcMageLevel - $i_intDestMageLevel;
    }
    $intChance = ((10+$i_intSrcMageLevel+$add ) / (10+$i_intDestMageLevel ));
    $intMod = $arrSpellType['chance'];
    $intChance = $intChance*$intMod;
    return $intChance;
}


function formulate_chancex($local_chance)
{
    global $mage_level,$dmage_level,$magic_chance;
    echo "<br /><br />DEBUG : $dmage_level / $mage_level) * $local_chance";
    $magic_chance = floor ( ($dmage_level / $mage_level) * $local_chance );
}


function fame_win(&$objSrcUser, &$objTrgUser, $i_intFame)
{
    include_once('inc/functions/get.php');

    if ($objSrcUser->get_stat(FAME) > $objTrgUser->get_stat(FAME))
    {
        $intRetFameWin = floor($i_intFame);
    }
    else
    {
        $intRetFameWin = floor($i_intFame * 1.5);
    }

    $TargetFame = $objTrgUser->get_stat(FAME);

    // frost: experimental addition of fame being relative to size difference
    // added jan04
    $AttackerLand = get_total_land($objSrcUser->get_userid());
    $DefenderLand = get_total_land($objTrgUser->get_userid());
    if ($AttackerLand != "0" && $DefenderLand != "0")
    {
        $percentvalue = ($DefenderLand*100)/$AttackerLand;
        if ($percentvalue > "400")
        {
            $intRetFameWin = round($intRetFameWin*0.0);   // 0% fame gains
        }
        elseif ($percentvalue >= "134" && $percentvalue <= "400")
        {
            $intRetFameWin = round($intRetFameWin*0.5);   // 50% fame gains
        }
        elseif ($percentvalue >= "75" && $percentvalue < "134")
        {
            //normal gains ... no change
        }
        elseif ($percentvalue >= "50" && $percentvalue < "75")
        {
            $intRetFameWin = round($intRetFameWin*0.5);   // 50% fame gains
        }
        elseif ($percentvalue >= "25" && $percentvalue < "50")
        {
            $intRetFameWin = round($intRetFameWin*0.25);  // 25% fame gains
        }
        elseif ($percentvalue < "25")
        {
            $intRetFameWin = round($intRetFameWin*0.0);   // 0% fame gains
        }
    }
    // frost: end ------------------------------------------------------------

    // Tragedy: no fame win from 0 fame tribes
    if ($intRetFameWin > $TargetFame )
        $intRetFameWin = $TargetFame;

    $intNewFame = $objSrcUser->get_stat(FAME) + $intRetFameWin;
    $objSrcUser->set_stat(FAME, $intNewFame);

    $intNewFame = $objTrgUser->get_stat(FAME) - $intRetFameWin;
    $objTrgUser->set_stat(FAME, $intNewFame);

    return $intRetFameWin;
}

?>
