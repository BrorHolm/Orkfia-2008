<?php

/*
    Page: inc/functions/thievery.php
    Last Update:  02.05.2001
    by: sativa
*/


//==============================================================================
// Name: get_op_level()
// Action: This will determine what "Operations" a player will get.
//         they start off with 4 basic ops, and the only way to get more
//         is to grow.  if you have 600 acres you have 5 ops, if you are on
//         1200 acres you have 6 ops, etc.  see inc/functions/ops.php for the
//         list of operations.
// Update:       12.25.2001 by: Michael
// Update2:      Gotland: rewrite to use objects
//==============================================================================

require_once('inc/classes/clsBlock.php');

function get_op_level(&$objUser)
{
    $land = $objUser->get_build(LAND);
    return 5 + (floor($land / 300));
}

//Gotland pass the user objects as parameters
function make_thievery(&$objSrcUser, &$objTrgUser, $local_action, $amount_sent, $amount_ops, $stop_on_success)
{
    global  $HTTP_SERVER_VARS, $connection;

    check_to_update($objTrgUser->get_userid());

    if ($objTrgUser->get_stat(ALLIANCE) == 0)
    {
            echo "This player has been either deleted or suspended";
            include_game_down();
            exit;
    }

    //frost: global protection routine
    $global_protection = mysql_query("SELECT global_protection FROM admin_switches");
    $global_protection = mysql_fetch_array($global_protection);
    if ($global_protection['global_protection'] == "on")
    {
        ECHO "<br /><br /><br />Because of a global event all tribes in ORKFiA are under protection.<br />Please read the announcement in the forum.";
        include_game_down();
        exit;
    }

    mt_srand((double)microtime()*1000000);

    $amount_sent = floor($amount_sent);

    if (!file_exists("inc/ops/" . $local_action. ".php"))
    {
        echo "Missing file: inc/ops/$local_action.php";
        include_game_down();
        exit;
    }

    $fn = "inc/ops/" . $local_action . ".php";
    include $fn;

    $kingdom = $objTrgUser->get_stat(ALLIANCE);
    if ($amount_sent <= 0)
    {
        echo "Not sending ANY thieves on a thievery mission doesn't accomplish much<br /><br />";
        echo "<a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again</a>";
        include_game_down();
        exit;
    }

    if ($objSrcUser->get_userid() == $objTrgUser->get_userid() && get_op_type() == "aggressive")
    {
        echo "You must choose an appropriate target.<br /><br />";
        echo "<a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again ?</a>";
        include_game_down();
        exit;
    }

    if ($objTrgUser->get_user_info(hours) < PROTECTION_HOURS && get_op_type() != "self")
    {
        $iRemaining = PROTECTION_HOURS - $objTrgUser->get_user_info(HOURS);
        $strProtectionMsg =
        '<div id="textMedium"><p>' .
            'It appears that the tribe you wish to target is still ' .
            'materializing. The head of our thieves estimates that it will ' .
            'take another ' . $iRemaining . ' updates for the area to become ' .
            'a stable part of reality.';

        echo $strProtectionMsg . "</p><p>";
        echo "<a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again ?</a>" .
        '</p></div>';

        include_game_down();
        exit;
    }

    if ($amount_ops <= 0)
    {
        echo '<div id="textMedium"><p>' .
             "You didn't do anything..." .
             "<p><a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again</a>" .
             '</p></div>';
        include_game_down();
        exit;
    }

    $credits = $objSrcUser->get_thievery(CREDITS);
    $op_cost = get_op_cost($local_action, $objSrcUser->get_build(LAND));
    if ($credits < $op_cost)
    {
        echo '<div id="textMedium"><p>' .
             "Sorry, every aggressive thievery operation " .
             "requires thievery points and you don't have enough to perform this type of operation.</p>" .
             "<p><a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again</a>" .
             '</p></div>';
        include_game_down();
        exit;
    }

    // Tried to send more operations than have op-credits
    if ($credits < ($amount_ops*$op_cost) )
        $amount_ops = floor($credits / $op_cost);

    if ($amount_sent > $objSrcUser->get_army_home(UNIT5))
    {
        echo '<div id="textMedium"><p>' .
             "We don't have enough thieves to send even 1 operation in the way you've requested, please specify a different amount.</p>" .
             "<p><a href=main.php?cat=game&page=thievery&kd=$kingdom>Try again</a>" .
             '</p></div>';
        include_game_down();
        exit;
    }
    if ( (! clsBlock::isOpAllowed($objSrcUser, $objTrgUser)) && get_op_type() != "self")
    {
        echo '<div id="textMedium"><p>' .
            'Someone else from the same IP has already opped this tribe during the last 8 hours.' .
            '</p><p>' .
            '<a href="main.php?cat=game&amp;page=thievery">Return</a>' .
            '</p></div>';
        clsBlock::reportOp($objSrcUser, $objTrgUser, 'Thief op: ' . $local_action, false);
        free_casting_now($iUserID);
        include_game_down();
        exit;
    }

    // Dragon 50% damage reduction. For war-bonusses change this modifier as well, instead of directly editing the ops-files.
    // -Martel wuz here- (50% reduction works now)
    include_once('inc/functions/get.php');

    if($objTrgUser->get_stat(RACE) == 'Dragon') { $modifier = 0.5;}
    else { $modifier = 1; }

    // War-check
    include_once("inc/functions/war.php");
    $warmodifier = war_alli($objTrgUser->get_stat(ALLIANCE) , $objSrcUser->get_stat(ALLIANCE));
    if ($warmodifier > 1)
    {
        $modifier *= 1.1;
        if ($objSrcUser->get_spell(DEFIANCE) > 0)
            $modifier *= 1.1;
    }

    $target = war_target($objTrgUser->get_stat(ALLIANCE));
    if ($target != 0 && $warmodifier == 0)
        $modifier *= 0.95;

    $total_sent = $amount_ops * $amount_sent;

    // Ugly way of doing it... but here the div for the report starts. Timesaver.
    echo '<div id="textBig">' .
            '<h2>' . 'Thievery Report' . '</h2>' .
            '<p>';

    if ($total_sent > $objSrcUser->get_army_home(UNIT5))
    {
        $amount_ops = floor($objSrcUser->get_army_home(UNIT5) / $amount_sent);
        echo "We don't have enough thieves to send that many operations, we've sent less instead.<br /><br />";
    }

    // GUARDHOUSES
    // Tragedy: april 20th 2002:
    // adding a cap of max 80% effectiveness on guardhouses, ergo max 20% of land
    $guard_percentage = min(0.2, ($objTrgUser->get_build(GUARDHOUSES) / $objTrgUser->get_build(LAND)));
    if (get_op_type() != "self")
        $P_guard = $guard_percentage * 3.5;
    else
        $P_guard = 0;

    //THIEVES TRAP
    //Species 5618: 17-01-2005
    //special self-op provides 15% thievery protection
    if ($objTrgUser->get_thievery(TRAP) > 0 && get_op_type() != "self")
        $P_trap = 0.15;
    else
        $P_trap = 0;

    $defthiefs = $objTrgUser->get_army_home(UNIT5);
    if ($defthiefs < 10)
        $defthiefs = 10;

    $d_user_tpa = $defthiefs / $objTrgUser->get_build(LAND);

    //Templars don't have thieves - AI 10/02/2007
    if ($objTrgUser->get_stat(RACE) == 'Templar')
    {
        $d_user_tpa = 0;
    }

    $off_thieves = $objSrcUser->get_army_home(UNIT5);

    $thieves_lost = 0;
    $cntOpSuccess = 0;
    $cntOF_total  = 0;
    $cntOF_tpa    = 0;
    $cntOF_gh     = 0;
    $cntOF_trap   = 0;

    $d_land = $objTrgUser->get_build(LAND);
    $o_land = $objSrcUser->get_build(LAND);

    for ($x = 1; $x <= $amount_ops; $x++)
    {
        if (get_op_type() == "aggressive")
        {
            $o_user_tpa = $off_thieves / $o_land;

            if ($d_user_tpa > 0.25)
                $tpa_vs_tpa = ($o_user_tpa / $d_user_tpa) / 1.5;
            else
                $tpa_vs_tpa = 1;

            $tpa_vs_tpa = min(max($tpa_vs_tpa, 0.05), 1);

            $chance = (get_op_chance() / 100) * $tpa_vs_tpa;
            if (($o_land < 0.5 * $d_land) || ( $o_land > 2 * $d_land))
                $chance /= 2;

            $P_tpa = (1 - $chance);
        }
        else { $P_tpa = 1 - get_op_chance() / 100; }

        $P_success = (1 - $P_tpa) * (1 - $P_guard) * (1 - $P_trap);

        //Randomly decide wether the op succeeds or fails.
        //When it fails, randomly choose a reason based on the relative failure-rates of all possible failure-reasons
        //Don't worry too much about the math behind it. It's correct and assures a fair distribution over the various 'reasons for failure'
        $P_fail_Total = $P_tpa + $P_guard + $P_trap;

        $P_fail_tpa   = $P_tpa   / $P_fail_Total;
        $P_fail_guard = $P_guard / $P_fail_Total;
        $P_fail_trap  = $P_trap  / $P_fail_Total;

        $random  = rand(1, 10000) / 10000;

        if ($random < $P_success)
        {
            $cntOpSuccess++;
            //Stop-On-Success check
            if ($stop_on_success == "yes")
            {
                $amount_ops = $x;
                break;
            }
        }
        else
        {
            $cntOF_total++;

            //Why did the op fail ? TPA-diff or GH failure or Thieves Trap?
            $random = rand(1,10000)/10000;
            if ($random <= $P_fail_trap)
            {
                $cntOF_trap++;

                $thieves_lost += ceil($amount_sent * 0.05 * 1.3);
            }
            elseif ($random <= ($P_fail_trap + $P_fail_guard))
            {
                $cntOF_gh++;

                $thieves_lost += ceil($amount_sent * (rand(5,15) / 100));
            }
            else
            {
                $cntOF_tpa++;

                $thieves_lost += ceil($amount_sent * 0.05);
            }
        }

        //Lower amount of thieves so chances are correctly recalculated during the next iteration.
        $off_thieves -= $amount_sent;
    }

    if (get_op_type() == "self" && $local_action != "trap")
        $thieves_lost = 0;

    //Now call the actual op.
    //The $opResult return-value is an array consisting of 3 values:
    //- $opResult["fame"], fame gained/lost
    //- $opResult["text_screen"], text to be shown on screen
    //- $opResult["text_news"], text to be shown in enemy tribenews
    if ($local_action == "ambush" && $cntOpSuccess > 0)
    {
        $cntOpSuccess = 1;
    }

    if ($cntOpSuccess > 0)
        $opResult = do_op($objSrcUser,$objTrgUser,$cntOpSuccess,$amount_sent,$modifier);
    else
        $opResult["fame"] = 0;

    $total_sent = $amount_ops * $amount_sent;

    if ($thieves_lost > $total_sent)
        $thieves_lost = $total_sent;

    $plural1 = ""; $plural2 = "was"; if ($amount_ops > 1) { $plural1 = "s"; $plural2 = "were"; }
    $plural3 = "thief"; if ($total_sent > 1) { $plural3 = "thieves"; }
    echo "$amount_ops operation$plural1, a total of $total_sent $plural3, $plural2 sent on its way.<br />";
    if ($cntOpSuccess == 1) {$plural = ""; $plural3 = "was"; } else { $plural = "s"; $plural3 = "were"; }
    if ($cntOF_total == 1) { $plural2 = "has"; } else { $plural2 = "have"; }
    echo "$cntOpSuccess operation$plural $plural3 reported to be successful, $cntOF_total $plural2 failed.<br /><br />";

    if ($cntOF_tpa == 1) { $plural = ""; $plural2 = "has"; } else { $plural = "s"; $plural2 = "have"; }
    if ($cntOF_tpa > 0 && get_op_type() == "aggressive" && $d_user_tpa != 0) echo "$cntOF_tpa operation$plural $plural2 failed because the enemy thieves intercepted ours.<br />";
    elseif ($cntOF_tpa > 0 && get_op_type() == "aggressive" && $d_user_tpa == 0) echo "$cntOF_tpa operation$plural $plural2 failed because our thieves got spotted by enemy military.<br />";
    if ($cntOF_gh == 1) {$plural = ""; $plural2= "has"; } else { $plural = "s"; $plural2 = "have"; }
    if ($cntOF_gh > 0) echo "Enemy guardstations caused $cntOF_gh operation$plural to fail<br />";
    if ($cntOF_trap == 1) {$plural = ""; $plural2= "was"; } else { $plural = "s"; $plural2 = "were"; }
    if ($cntOF_trap > 0) echo "$cntOF_trap of our operations were caught by traps the enemy had set for us<br />";

    if ($opResult["fame"] != 0)
    {
        $trgFame = $objTrgUser->get_stat(FAME);
        if ($opResult["fame"] > $trgFame)
            $opResult["fame"] = $trgFame;
        $newDFame = $trgFame - $opResult["fame"];
        $objTrgUser->set_stat(FAME, $newDFame);

        $newFame = $objSrcUser->get_stat(FAME) + $opResult["fame"];
        $objSrcUser->set_stat(FAME, $newFame);
    }

    if ($amount_ops != 1) {$plural = "these"; $plural2 = "s";} else {$plural = "this"; $plural2 = "";}
    if ($thieves_lost == 1) { $plural3 = 'f'; } else { $plural3 = 'ves'; }
    echo "With $plural operation$plural2 we have gained <b class=positive>" . $opResult["fame"] . " fame</b> and lost " . number_format($thieves_lost) . " thie" . $plural3 . ".<br /><br />";

    $dplayer = $objTrgUser->get_userid();
    $userid  = $objSrcUser->get_userid();
    $tribe   = stripslashes($objSrcUser->get_stat(TRIBE));
    $iSrcAid = $objSrcUser->get_stat(ALLIANCE);
    if ($cntOF_total > 0 && get_op_type() == "aggressive")
    {
        if ($cntOF_total > 1) { $plural = 's'; }
        else { $plural = ''; }
        $timestamp = date(TIMESTAMP_FORMAT);
        $ip =$HTTP_SERVER_VARS["REMOTE_ADDR"];

        $create['event'] = "INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`)
                          VALUES ('', '$timestamp', '$ip', '$local_action', '$dplayer', '$userid', 'fail',
                          'We have caught $cntOF_total operation$plural of enemy thieves from $tribe (#$iSrcAid) tresspassing on our land.','') ";
        $created['event'] = mysql_query($create['event'], $connection);
        //trigger news flag of defender
        $objTrgUser->set_user_info(LAST_NEWS, $timestamp);
    }

    if ($cntOpSuccess > 0)
    {
        if (get_op_type() == "aggressive")
        {
            // echo name and alli
            $strTrgTribe = stripslashes($objTrgUser->get_stat(TRIBE));
            $iTrgAlliance  = $objTrgUser->get_stat(ALLIANCE);
            echo "As your thieves return from $strTrgTribe (#$iTrgAlliance) the foreman reports the following result:" .
                 '</p>';
        }
        else
        {
            echo "The foreman of our thieves reports the following result:" .
                 '</p>';
        }

        echo '<div style="padding: 0 15px">' .
                 $opResult["text_screen"] .
             '</div>' .
             '<p>';

        if ($opResult["text_news"] != "")
        {
            $timestamp = date(TIMESTAMP_FORMAT);
            $ip =$HTTP_SERVER_VARS["REMOTE_ADDR"];
            $create['event'] = "INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`)
                              VALUES ('', '$timestamp', '$ip', '$local_action', '$dplayer', '$userid', '1',
                              '$opResult[text_news]','') ";
            $created['event'] = mysql_query($create['event'], $connection);
            //trigger news flag of defender
            $objTrgUser->set_user_info(LAST_NEWS, $timestamp);
        }
    }

    if (get_op_type() == "aggressive" || get_op_name() == "Thieves Trap (SELF)")
    {
        $total_cost = $amount_ops * $op_cost;
        $credits    = $objSrcUser->get_thievery(CREDITS) - $total_cost;
        $objSrcUser->set_thievery(CREDITS, $credits);
    }

    $returning  = $total_sent - $thieves_lost;
    $returnTime = 3;
    if ($objSrcUser->get_stat(RACE) == 'Spirit')
        $returnTime = 2;

    $col        = UNIT5 . "_t" . $returnTime;
    $thievesout = $objSrcUser->get_milreturn($col);
    $objSrcUser->set_milreturn($col, $thievesout + $returning);

    $thievesleft = $objSrcUser->get_army(UNIT5) - $thieves_lost;
    $objSrcUser->set_army(UNIT5, $thievesleft);

    obj_test_for_kill($objTrgUser, $objSrcUser);

    if(get_op_type() != "self")
        clsBlock::logOp($objSrcUser, $objTrgUser, 'Thief op: ' . $local_action);

    echo    '</p>' .
            '<p>' .
                '<a href="main.php?cat=game&amp;page=thievery&amp;kd=' .
                $kingdom . '">Return</a>' .
            '</p>' .
        '</div>';
}

?>
