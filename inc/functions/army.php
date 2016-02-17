<?php
//******************************************************************************
// Functions army.php                                   Martel, October 17, 2007
//
// Modification history:
// Martel September 19, 2005 *Most* functions indented correctly and cleaned up.
// 15/04/2002 thalura     race changes for round x
//******************************************************************************

//==============================================================================
//                                                         Martel, July 09, 2006
// Updated October 18, 2007 by Martel (implemented 100% in training routines)
// Get Max units possible to train, of each class
//==============================================================================
function getMaxTrain(&$objUser)
{
    $arrArmys    = $objUser->get_armys();
    $arrGoods    = $objUser->get_goods();
    $arrArmyHome = $objUser->get_armys_home();
    $iCitizens   = $objUser->get_pop(CITIZENS);
    $strRace     = $objUser->get_stat(RACE);

    // Set max trainable for each military type to show in army.inc.php
    $objRace          = $objUser->get_race();
    $arrUnitCost      = $objRace->getUnitCosts();
    $arrUnitVariables = $objRace->getUnitVariables();

    // Max of any type Possible to Train (trained from basics)
    foreach ($arrUnitCost as $i => $iGold)
    {
        $strUnit = $arrUnitVariables[$i]; // UNIT1, UNIT2 etc...
        // Can't train units with 0 cost
        if ($iGold > 0)
        {
            // 1: we can buy as many of one type as we can afford
            $arrMaxTrain[$strUnit] = floor($arrGoods[MONEY] / $arrUnitCost[$i]);

            // 2: we can buy as many of one type as we have basic soldiers
            if ($arrMaxTrain[$strUnit] > $arrArmyHome[UNIT1])
            {
                $arrMaxTrain[$strUnit] = $arrArmyHome[UNIT1];
            }
        }
        else
        {
            $arrMaxTrain[$strUnit] = 0;
        }
    }

    // Max Basics Possible to Train (these trained from citizens)
    $arrMaxTrain[UNIT1] = floor($arrGoods[MONEY] / $arrUnitCost[2]);
    $iPopAllows     = $iCitizens * 0.3;

    if ($arrMaxTrain[UNIT1] >= $iPopAllows)
    {
        $arrMaxTrain[UNIT1] = floor($iPopAllows);
    }

    // Oleg Hai Mercenaries Concept - max 40% of current citizens
    if ($strRace == "Oleg Hai")
    {
        $normalMaxTrain      = floor($arrGoods[MONEY] / $arrUnitCost[5]);

        $arrMaxTrain[UNIT4]  = floor(($iCitizens + $arrArmys[UNIT4]) * .4);
        $arrMaxTrain[UNIT4] -= $arrArmys[UNIT4];

        //next line fixes the "but you don't have enough money!" bug
        $arrMaxTrain[UNIT4] = min($normalMaxTrain, $arrMaxTrain[UNIT4]);
        if ($arrMaxTrain[UNIT4] < 0)
        {
            $arrMaxTrain[UNIT4] = 0;
        }
    }

    return $arrMaxTrain;
}

//==============================================================================
//                                                     Martel, December 16, 2007
// Get Max units possible to release, of each class
//==============================================================================
function getMaxRelease(&$objUser)
{
    $arrArmys    = $objUser->get_armys();
    $arrArmyHome = $objUser->get_armys_home();
    $iCitizens   = $objUser->get_pop(CITIZENS);

    // Set max releaseable for each military type to show in raze_army.inc.php
    $objRace          = $objUser->get_race();
    $arrUnitCost      = $objRace->getUnitCosts();
    $arrUnitVariables = $objRace->getUnitVariables();

    // Max of any type Possible to Train (trained from basics)
    foreach ($arrUnitCost as $i => $iGold)
    {
        $strUnit = $arrUnitVariables[$i]; // UNIT1, UNIT2 etc...
        // Can't train units with 0 cost
        if ($iGold > 0)
        {
            // 1: we can release 70% military military in one go (> 10,000)
            $arrMaxRelease[$strUnit] = max(10000, 0.7 * $arrArmyHome[$strUnit]);
            if ($arrMaxRelease[$strUnit] > $arrArmyHome[$strUnit])
                $arrMaxRelease[$strUnit] = $arrArmyHome[$strUnit];

            // 2: or in training with 4 hours
            if ($arrArmys[$strUnit.'_t4'] > 0)
                $arrMaxRelease[$strUnit] = $arrArmys[$strUnit.'_t4'];

            // Floor to an even number
            $arrMaxRelease[$strUnit] = floor($arrMaxRelease[$strUnit]);
        }
        else
        {
            $arrMaxRelease[$strUnit] = 0;
        }
    }

    return $arrMaxRelease;
}


/*

//==============================================================================
Legacy functions below
//==============================================================================

*/




function owned_military($race)
{
    global $unit_var, $unit_names,$local_army, $total_army;

    $total_army = ( $local_army['unit1']
                  + $local_army['unit2']
                  + $local_army['unit3']
                  + $local_army['unit4']
                  + $local_army['unit5'] );
}

function military_cost($race)
{
    global $unit_var, $unit_names, $gold_unit;

    // 10/02/2007 AI
    //    Retrofitting this function to use clsRace
    require_once('inc/races/clsRace.php');
    $objRace = clsRace::getRace($race);
    $gold_unit = $objRace->getUnitCosts();
    $gold_unit = array_merge(array(''),$gold_unit);

    // ---------------------------------------------------------
    //
    // 19/02/2006 es: race changes
    //
    //    Set the numbers to match the manual (age 23).
    //    Changed dwarves, vikings, undeads, ravens as age changed required
    //
    // 15/04/2002 thalura     race changes for round x
    //
    //    Dark Elves elites cost 500 (instead of 450)
    //    Wood Elves defspecs 0/2 (instead of 0/4) and cost 250 (instead of 425)
    //    High Elves homes +50 people, elites 1/2 (instead of 0/2)
    //    Harpies - defspecs 0/4, cost 500, thieves cost 600, suffers double losses when attacking
    //    Spirit thieves cost 450
    //    Mori Hai +5% homes (instead of +10%), defspecs cost 300 (instead of 150)
    //
}

// Martel: replace this one with the object based version above
function max_train($race)
{
    global $objSrcUser, $max_train, $pop_allows;

    // retrofitted this function to use the object-based version.

    $pop_allows = $objSrcUser->get_pop(CITIZENS) * 0.3;
    $max_train = getMaxTrain($objSrcUser);
}
/*
function train_units($race)
{
    require_once('inc/races/clsRace.php');
    $objRace = clsRace::getRace($race);
    global  $max_train, $userid, $connection, $local_goods, $local_army,
            $army_home, $trained, $total_training_cost, $gold_unit,$current,
            $unit_names, $unit_var, $local_pop, $pop_allows, $local_user;

    $trained['unit1'] = abs(floor($trained['unit1']));
    $trained['unit2'] = abs(floor($trained['unit2']));
    $trained['unit3'] = abs(floor($trained['unit3']));
    $trained['unit4'] = abs(floor($trained['unit4']));
    $trained['unit5'] = abs(floor($trained['unit5']));

    if ($race == "Oleg Hai")
    {
        // oleg elite (mercenaries) dont get trained from soldiers
        $trained['total'] = ($trained['unit1'] + $trained['unit2'] + $trained['unit3'] + $trained['unit5']);
        $trained['nonsoldiers'] = ($trained['unit2'] + $trained['unit3'] + $trained['unit5']);
    }
    else
    {
        $trained['total'] = ($trained['unit1'] + $trained['unit2'] + $trained['unit3'] + $trained['unit4'] + $trained['unit5']);
        $trained['nonsoldiers'] = ($trained['unit2'] + $trained['unit3'] + $trained['unit4'] + $trained['unit5']);
    }

    IF ($trained['total'] <= "0" && $race != "Oleg Hai")
    {
        echo '<div class="center">' . "I'm sorry leader, you did not train anything</div>";
        include_game_down();
        exit;
    }

    IF ($trained['nonsoldiers'] > $army_home['unit1'])
    {
        echo '<div class="center">' . "Sorry you only have ".$army_home['unit1']." soldiers to train with. Maybe wait till your army gets home? Or train more soldiers.";
        echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
        include_game_down();
        exit;
    }

    if ($race == "Oleg Hai")
    {
        if (($local_army['unit4']+$trained['unit4'])>(($local_pop[citizens]+$local_army['unit4'])*0.4))
        {
            echo '<div class="center">' . "Sorry, but you aren't allowed to recruit more then 40% of your citizens as mercenaries.<br>";
            if ($local_army['unit4'] > 0)
            {
                echo "You already recruited $local_army[unit4] mercenaries.";
            }
            echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
            include_game_down();
            exit;
        }
    }

   if ($race == "Oleg Hai")
      {
      // oleg elite trainig and soldier training should not bring population below 800
      $possible_pop = ($local_pop['citizens'] - ($trained['unit1']+$trained['unit4']));
      }
   else
      {
      $possible_pop = ($local_pop['citizens'] - $trained['unit1']);
      }

   if ($possible_pop <= "799")
      {
      ECHO '<div class="center">' . " STOP! I can't let you do that sorry.<br /><br /> If you train those soldiers you
             will have trouble with your population growing.<br> Please just wait a few hours, then try again.<br> Or try again,
             just making sure you still have 800 Citizens left after training.<br /><br />";
      ECHO "<br /><br /><a href=main.php?cat=game&page=army> Try Again </a></div>";
      include_game_down();
      exit;
      }

   if ( $trained['unit1'] >$pop_allows)
      {
      ECHO '<div class="center">' . "You cannot train more then 1/3 of your citizens into soldiers per request,<br>
            however, as to not limit you you may train the max amount then train the max amount again,
            just beware that if you train all your citizens to soldiers that you will have no citizens left and thus no tribe.";
      echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
      include_game_down();
      exit;
      }

   $soldiers_needed = ($trained['total'] - $trained['unit1']);

   // oleg hai mercenaries drafted from citizens
   if ($race == "Oleg Hai")
      {
      IF ($trained['unit4'] > ($local_pop[citizens]*0.4))
         {
         echo '<div class="center">' . "Sorry, but you aren't allowed to recruit more then 40% of your citizens as mercenaries.";
         echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
         include_game_down();
         exit;
         }
      }
   // normal draft from soldiers
   else {
        if ($soldiers_needed > $army_home['unit1'])
           {
           echo '<div class="center">' . "Sorry you only have $local_army[unit1] $unit_names[1]'s and
                 you need $soldiers_needed in order to train those troops";
           echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
           include_game_down();
           exit;
           }
        }

   $total_training_cost = ( ($gold_unit[1] * $trained['unit1']) +
                            ($gold_unit[2] * $trained['unit2']) +
                            ($gold_unit[3] * $trained['unit3']) +
                            ($gold_unit[4] * $trained['unit4']) +
                            ($gold_unit[5] * $trained['unit5']) );

   if ($total_training_cost > $local_goods['money'])
     {
     echo '<div class="center">' . "Sorry you only have $local_goods[money] crowns and you need $total_training_cost crowns in order to train those troops";
     echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
     include_game_down();
     exit;
     }

     if ($local_user[HOURS] > $objRace->getLifespan())
     {
         echo '<div class="center">' . "Your general is nowhere in sight and your captains seem to be doubting " .
              "your capability to judge properly what to train or not..." .
              "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";

         include_game_down();
         exit;
     }

   $condition = TRUE;

   IF ($condition)
      {
      ECHO '<div class="center">' . " You have just trained $trained[total] units<br>";
      if ($race == "Oleg Hai" && $trained['unit4'] > 0)
         {
         ECHO "Also $trained[unit4] mercenaries joined your army. They will serve you for 4 hours.<br>";
         $result= mysql_query("UPDATE army SET
                        unit1_t4 = unit1_t4+ $trained[unit1],
                        unit2_t4 = unit2_t4+ $trained[unit2],
                        unit3_t4 = unit3_t4+ $trained[unit3],
                        unit4 = unit4+ $trained[unit4],
                        unit5_t4 = unit5_t4+ $trained[unit5]
                        WHERE id =$userid");
         // remember trained mercs for the hour they were trained
         $writeArmyMercTable = mysql_query("UPDATE army_mercs SET merc_t3=merc_t3+$trained[unit4] WHERE id =$userid");
         }
      else
         {
         $result= mysql_query("UPDATE army SET
                        unit1_t4 = unit1_t4+ $trained[unit1],
                        unit2_t4 = unit2_t4+ $trained[unit2],
                        unit3_t4 = unit3_t4+ $trained[unit3],
                        unit4_t4 = unit4_t4+ $trained[unit4],
                        unit5_t4 = unit5_t4+ $trained[unit5]
                        WHERE id =$userid");
         }

      $new['soldiers']= ($local_army['unit1'] - $soldiers_needed);
      $update['soldiers'] ="UPDATE army SET unit1 ='$new[soldiers]' WHERE id= $userid";
      $training['soldiers']= mysql_query($update['soldiers'], $connection);

      $new['citizens'] = ($local_pop['citizens'] - $trained['unit1'] );
      if ($race == "Oleg Hai")
         {
         $new['citizens'] = ($new['citizens'] - $trained['unit4']);
         }
      $update['citizens'] = "UPDATE pop SET citizens = '$new[citizens]' WHERE id = $userid";
      $training['citizens'] = mysql_query($update['citizens'], $connection);

      $new['money'] = ($local_goods['money'] - ($total_training_cost));
      $update['money'] ="UPDATE goods SET money ='$new[money]' WHERE id= $userid";
      $stats['money']= mysql_query($update['money'], $connection);

      $seek = mysql_query("Select * from spells where id = $userid");
      $seek = mysql_fetch_array($seek);
      if ($seek['brood'] > 0)
         {
         $set = mysql_query("UPDATE army SET unit1_t2 = unit1_t2 + unit1_t4 WHERE id = $userid");
         $set = mysql_query("UPDATE army SET unit1_t4 = 0 WHERE id = $userid");
         }

      ECHO"The training of these units cost you $total_training_cost crowns.";
      echo "<br /><br /><a href=\"main.php?cat=game&page=army\">Back To Training</a></div>";
      }

   } */

// use clsUser instead
function new_army_home()
{
    global  $arrArmyHome, $objSrcUser;

    // M: "retrofitted"
    $arrArmyHome = $objSrcUser->get_armys_home();
}

// use clsUser instead
function army_home()
{
    global  $unit_var, $local_army, $darmy, $local_milreturn, $army_home,
            $userid, $objSrcUser;

//     print_r();
    //  $unit_var contains: Array ( [0] => unit1 [1] => unit2 [2] => unit3 [3] => unit4 [4] => unit5 )
    for ($i = 0; $i < 5; $i++)
    {
        $current = $unit_var[$i];
        $current = trim($current);
        $sub_current1 = "$current" . "_t1";
        $sub_current2 = "$current" . "_t2";
        $sub_current3 = "$current" . "_t3";
        $sub_current4 = "$current" . "_t4";
        $army_home[$current] = ($local_army[$current]
                             -  $local_milreturn[$sub_current1]
                             -  $local_milreturn[$sub_current2]
                             -  $local_milreturn[$sub_current3]
                             -  $local_milreturn[$sub_current4] );
    }

    $local_pop = @mysql_query("SELECT * FROM pop WHERE id =$userid");
    $local_pop = @mysql_fetch_array($local_pop);

    $army_home['citizens']  = ($local_pop['citizens']
                            -  $local_milreturn['citizen_t1']
                            -  $local_milreturn['citizen_t2']
                            -  $local_milreturn['citizen_t3']
                            -  $local_milreturn['citizen_t4'] );

    return $army_home;
}

// use clsUser instead
function new_defence_home()
{
    global  $arrArmyHome, $objTrgUser;

    $arrTrgArmys = $objTrgUser->get_armys();
    $arrTrgMilreturns = $objTrgUser->get_milreturns();


    $arrArmyHome['unit1'] = $arrTrgArmys['unit1'] - $arrTrgMilreturns['unit1_t1']- $arrTrgMilreturns['unit1_t2']- $arrTrgMilreturns['unit1_t3']- $arrTrgMilreturns['unit1_t4'];
    $arrArmyHome['unit2'] = $arrTrgArmys['unit2'] - $arrTrgMilreturns['unit2_t1']- $arrTrgMilreturns['unit2_t2']- $arrTrgMilreturns['unit2_t3']- $arrTrgMilreturns['unit2_t4'];
    $arrArmyHome['unit3'] = $arrTrgArmys['unit3'] - $arrTrgMilreturns['unit3_t1']- $arrTrgMilreturns['unit3_t2']- $arrTrgMilreturns['unit3_t3']- $arrTrgMilreturns['unit3_t4'];
    $arrArmyHome['unit4'] = $arrTrgArmys['unit4'] - $arrTrgMilreturns['unit4_t1']- $arrTrgMilreturns['unit4_t2']- $arrTrgMilreturns['unit4_t3']- $arrTrgMilreturns['unit4_t4'];
    $arrArmyHome['unit5'] = $arrTrgArmys['unit5'] - $arrTrgMilreturns['unit5_t1']- $arrTrgMilreturns['unit5_t2']- $arrTrgMilreturns['unit5_t3']- $arrTrgMilreturns['unit5_t4'];
}

// use clsUser instead
function new_in_training()
{
    global  $arrArmyDue, $objSrcUser;

    $arrSrcArmys = $objSrcUser->get_armys();

    $arrArmyDue['unit1'] = $arrSrcArmys['unit1_t1'] + $arrSrcArmys['unit1_t2'] + $arrSrcArmys['unit1_t3'] + $arrSrcArmys['unit1_t4'];
    $arrArmyDue['unit2'] = $arrSrcArmys['unit2_t1'] + $arrSrcArmys['unit2_t2'] + $arrSrcArmys['unit2_t3'] + $arrSrcArmys['unit2_t4'];
    $arrArmyDue['unit3'] = $arrSrcArmys['unit3_t1'] + $arrSrcArmys['unit3_t2'] + $arrSrcArmys['unit3_t3'] + $arrSrcArmys['unit3_t4'];
    $arrArmyDue['unit4'] = $arrSrcArmys['unit4_t1'] + $arrSrcArmys['unit4_t2'] + $arrSrcArmys['unit4_t3'] + $arrSrcArmys['unit4_t4'];
    $arrArmyDue['unit5'] = $arrSrcArmys['unit5_t1'] + $arrSrcArmys['unit5_t2'] + $arrSrcArmys['unit5_t3'] + $arrSrcArmys['unit5_t4'];

    $arrArmyDue['total'] = $arrArmyDue['unit1'] + $arrArmyDue['unit2'] + $arrArmyDue['unit3'] + $arrArmyDue['unit4'] + $arrArmyDue['unit5'];
}

?>
