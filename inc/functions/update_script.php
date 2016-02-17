<?php
//******************************************************************************
// Function update_script.php                           Martel February 27, 2008
//
// Martel: We are now using the user object for this... so phase out all old
// ways... eg direct manipulations of the db, the get.php file etc.
//
// NEVER CALL THIS FUNCTION BY ITSELF ! :)  this :has: to be called from the
// check_to_update() function
// $number_updates = the number of times the player is to be updated.
// $objUser        = the User Object of the player being updated.
//******************************************************************************

function generate_updates(&$objUser, $number_updates)
{
    // Skip staff alliances
    if ($objUser->get_stat(ALLIANCE) > 10)
    {
        // M: New includes (instead of the $prod array-monster)
        include_once("inc/functions/population.php");
        include_once("inc/functions/production.php");
        include_once("inc/functions/bonuses.php");

        $iUserid = $objUser->get_userid();
        $strRace = $objUser->get_stat(RACE);

        for ($i = 1; $i <= $number_updates; $i++)
        {
            /* Updating Military Training Here
               Basically just moves each unit up 1 hour. if you had 10 soldiers and 10 due in 1 hour
               and 5 due in 4 hours,  after this script has run you will have 20 soldiers and 5 due
               in 3 hours.     */

            $arrArmy = $objUser->get_armys();
            $arrNewArmy = array(
                UNIT1    => $arrArmy[UNIT1] + $arrArmy[UNIT1_T1],
                UNIT1_T1 => $arrArmy[UNIT1_T2], UNIT1_T2 => $arrArmy[UNIT1_T3],
                UNIT1_T3 => $arrArmy[UNIT1_T4], UNIT1_T4 => 0,
                UNIT2    => $arrArmy[UNIT2] + $arrArmy[UNIT2_T1],
                UNIT2_T1 => $arrArmy[UNIT2_T2], UNIT2_T2 => $arrArmy[UNIT2_T3],
                UNIT2_T3 => $arrArmy[UNIT2_T4], UNIT2_T4 => 0,
                UNIT3    => $arrArmy[UNIT3] + $arrArmy[UNIT3_T1],
                UNIT3_T1 => $arrArmy[UNIT3_T2], UNIT3_T2 => $arrArmy[UNIT3_T3],
                UNIT3_T3 => $arrArmy[UNIT3_T4], UNIT3_T4 => 0,
                UNIT4    => $arrArmy[UNIT4] + $arrArmy[UNIT4_T1],
                UNIT4_T1 => $arrArmy[UNIT4_T2], UNIT4_T2 => $arrArmy[UNIT4_T3],
                UNIT4_T3 => $arrArmy[UNIT4_T4], UNIT4_T4 => 0,
                UNIT5    => $arrArmy[UNIT5] + $arrArmy[UNIT5_T1],
                UNIT5_T1 => $arrArmy[UNIT5_T2], UNIT5_T2 => $arrArmy[UNIT5_T3],
                UNIT5_T3 => $arrArmy[UNIT5_T4], UNIT5_T4 => 0,
                UNIT6    => $arrArmy[UNIT6] + $arrArmy[UNIT6_T1],
                UNIT6_T1 => $arrArmy[UNIT6_T2], UNIT6_T2 => $arrArmy[UNIT6_T3],
                UNIT6_T3 => $arrArmy[UNIT6_T4], UNIT6_T4 => 0
            );
            $objUser->set_armys($arrNewArmy);

            /* Updates All The Buildings
               Exactly the same to military training, read its description. */

            $arrBuild = $objUser->get_builds();
            $arrNewBuild = array(
                HOMES    => $arrBuild[HOMES] + $arrBuild[HOMES_T1],
                HOMES_T1 => $arrBuild[HOMES_T2], HOMES_T2 => $arrBuild[HOMES_T3],
                HOMES_T3 => $arrBuild[HOMES_T4], HOMES_T4 => 0,
                FARMS    => $arrBuild[FARMS] + $arrBuild[FARMS_T1],
                FARMS_T1 => $arrBuild[FARMS_T2], FARMS_T2 => $arrBuild[FARMS_T3],
                FARMS_T3 => $arrBuild[FARMS_T4], FARMS_T4 => 0,
                WALLS    => $arrBuild[WALLS] + $arrBuild[WALLS_T1],
                WALLS_T1 => $arrBuild[WALLS_T2], WALLS_T2 => $arrBuild[WALLS_T3],
                WALLS_T3 => $arrBuild[WALLS_T4], WALLS_T4 => 0,
                WEAPONRIES    => $arrBuild[WEAPONRIES] + $arrBuild[WEAPONRIES_T1],
                WEAPONRIES_T1 => $arrBuild[WEAPONRIES_T2], WEAPONRIES_T2 => $arrBuild[WEAPONRIES_T3],
                WEAPONRIES_T3 => $arrBuild[WEAPONRIES_T4], WEAPONRIES_T4 => 0,
                GUILDS    => $arrBuild[GUILDS] + $arrBuild[GUILDS_T1],
                GUILDS_T1 => $arrBuild[GUILDS_T2], GUILDS_T2 => $arrBuild[GUILDS_T3],
                GUILDS_T3 => $arrBuild[GUILDS_T4], GUILDS_T4 => 0,
                MINES    => $arrBuild[MINES] + $arrBuild[MINES_T1],
                MINES_T1 => $arrBuild[MINES_T2], MINES_T2 => $arrBuild[MINES_T3],
                MINES_T3 => $arrBuild[MINES_T4], MINES_T4 => 0,
                MARKETS    => $arrBuild[MARKETS] + $arrBuild[MARKETS_T1],
                MARKETS_T1 => $arrBuild[MARKETS_T2], MARKETS_T2 => $arrBuild[MARKETS_T3],
                MARKETS_T3 => $arrBuild[MARKETS_T4], MARKETS_T4 => 0,
                LABS    => $arrBuild[LABS] + $arrBuild[LABS_T1],
                LABS_T1 => $arrBuild[LABS_T2], LABS_T2 => $arrBuild[LABS_T3],
                LABS_T3 => $arrBuild[LABS_T4], LABS_T4 => 0,
                CHURCHES    => $arrBuild[CHURCHES] + $arrBuild[CHURCHES_T1],
                CHURCHES_T1 => $arrBuild[CHURCHES_T2], CHURCHES_T2 => $arrBuild[CHURCHES_T3],
                CHURCHES_T3 => $arrBuild[CHURCHES_T4], CHURCHES_T4 => 0,
                GUARDHOUSES    => $arrBuild[GUARDHOUSES] + $arrBuild[GUARDHOUSES_T1],
                GUARDHOUSES_T1 => $arrBuild[GUARDHOUSES_T2], GUARDHOUSES_T2 => $arrBuild[GUARDHOUSES_T3],
                GUARDHOUSES_T3 => $arrBuild[GUARDHOUSES_T4], GUARDHOUSES_T4 => 0,
                BANKS    => $arrBuild[BANKS] + $arrBuild[BANKS_T1],
                BANKS_T1 => $arrBuild[BANKS_T2], BANKS_T2 => $arrBuild[BANKS_T3],
                BANKS_T3 => $arrBuild[BANKS_T4], BANKS_T4 => 0,
                HIDEOUTS    => $arrBuild[HIDEOUTS] + $arrBuild[HIDEOUTS_T1],
                HIDEOUTS_T1 => $arrBuild[HIDEOUTS_T2], HIDEOUTS_T2 => $arrBuild[HIDEOUTS_T3],
                HIDEOUTS_T3 => $arrBuild[HIDEOUTS_T4], HIDEOUTS_T4 => 0,
                ACADEMIES    => $arrBuild[ACADEMIES] + $arrBuild[ACADEMIES_T1],
                ACADEMIES_T1 => $arrBuild[ACADEMIES_T2], ACADEMIES_T2 => $arrBuild[ACADEMIES_T3],
                ACADEMIES_T3 => $arrBuild[ACADEMIES_T4], ACADEMIES_T4 => 0,
                YARDS    => $arrBuild[YARDS] + $arrBuild[YARDS_T1],
                YARDS_T1 => $arrBuild[YARDS_T2], YARDS_T2 => $arrBuild[YARDS_T3],
                YARDS_T3 => $arrBuild[YARDS_T4], YARDS_T4 => 0,
                LAND    => $arrBuild[LAND] + $arrBuild[LAND_T1],
                LAND_T1 => $arrBuild[LAND_T2], LAND_T2 => $arrBuild[LAND_T3],
                LAND_T3 => $arrBuild[LAND_T4], LAND_T4 => 0
            );
            $objUser->set_builds($arrNewBuild);

            /* Oleg hai mercenary updates */

            if ($strRace == "Oleg Hai")
            {
                $arrMercs       = $objUser->get_army_mercs();
                $mercsToRelease = $objUser->get_milreturn(UNIT4_T1);
                $totalmercs     = $mercsToRelease + $arrMercs[MERC_T0];

                $citizens       = $objUser->get_pop(CITIZENS) + $totalmercs;
                $objUser->set_pop(CITIZENS, $citizens);

                $elites         = $objUser->get_army(UNIT4) - $totalmercs;
                $objUser->set_army(UNIT4, $elites);

                $arrNewMercs    = array(
                    MERC_T0 => $arrMercs[MERC_T1],
                    MERC_T1 => $arrMercs[MERC_T2],
                    MERC_T2 => $arrMercs[MERC_T3],
                    MERC_T3 => 0
                );
                $objUser->set_army_mercs($arrNewMercs);
            }
            elseif ($strRace == "Mori Hai")
            {
                // Species5618 (18-1-2005) -- Abuse the merc-table to store some
                // info on mori armies
                $arrMercs    = $objUser->get_army_mercs();
                $arrNewMercs = array(
                    MERC_T0 => $arrMercs[MERC_T1],
                    MERC_T1 => $arrMercs[MERC_T2],
                    MERC_T2 => $arrMercs[MERC_T3],
                    MERC_T3 => 0
                );
                $objUser->set_army_mercs($arrNewMercs);
            }

            /* Updates the military return table, Moved everything up 1 hour
               Again same idea as the above queries     */

            $arrRets = $objUser->get_milreturns();
            $arrNewRets = array(
                UNIT1_T1 => $arrRets[UNIT1_T2], UNIT1_T2 => $arrRets[UNIT1_T3],
                UNIT1_T3 => $arrRets[UNIT1_T4], UNIT1_T4 => 0,
                UNIT2_T1 => $arrRets[UNIT2_T2], UNIT2_T2 => $arrRets[UNIT2_T3],
                UNIT2_T3 => $arrRets[UNIT2_T4], UNIT2_T4 => 0,
                UNIT3_T1 => $arrRets[UNIT3_T2], UNIT3_T2 => $arrRets[UNIT3_T3],
                UNIT3_T3 => $arrRets[UNIT3_T4], UNIT3_T4 => 0,
                UNIT4_T1 => $arrRets[UNIT4_T2], UNIT4_T2 => $arrRets[UNIT4_T3],
                UNIT4_T3 => $arrRets[UNIT4_T4], UNIT4_T4 => 0,
                UNIT5_T1 => $arrRets[UNIT5_T2], UNIT5_T2 => $arrRets[UNIT5_T3],
                UNIT5_T3 => $arrRets[UNIT5_T4], UNIT5_T4 => 0,
                UNIT6_T1 => $arrRets[UNIT6_T2], UNIT6_T2 => $arrRets[UNIT6_T3],
                UNIT6_T3 => $arrRets[UNIT6_T4], UNIT6_T4 => 0
            );
            $objUser->set_milreturns($arrNewRets);

            //==================================================================
            // News items for duration self spells
            //==================================================================
            $arrSpells = $objUser->get_spells();

            // Set Timestamp for updates previous hours
            $h = date("H");
            $m = date("m");
            $d = date("d");
            $y = date("Y");
            $iOldHour = $h - $number_updates + $i;
            if ($iOldHour < 0)  {   $iOldHour += 24;    $d -= 1;}

            // talk about ugly code, this is almost guarantee of bugs every
            //  month. Anyway, this should fix it                  - AI 06/10/06

            // using keys to make clear which month it is, even though they're not neccessary
            $monthdays = array ( 0 => 31, 1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31 );
            // leap years!!
            if ( ($y%4) == 0 && ( ($y/100) != 0 || ($y/400) == 0) ) { $monthdays[2] = 29; }
            // both 0 and 12 because we haven't adjusted for that yet
            if ($d < 1) { $m -= 1; $d = $monthdays[$m]; }
            // hopefully, 12 months and the yearsystem should never have to be changed
            if ($m < 1) { $m = 12; $y -= 1; }
            // this should do it
            $bleh = mktime($iOldHour, 0, 0, $m, $d, $y); //hours,mins,secs,month,day,year
            $bleh = date(TIMESTAMP_FORMAT, $bleh);

            if ($arrSpells[BROOD] > 0 || $arrSpells[FOREST] > 0)
            {
                $iLand = $objUser->get_build(LAND);

                $free_expl = rand(1,5);
                if ($iLand > 999 && $iLand < 2000)
                    $free_expl = rand(1,6);
                elseif ($iLand > 1999 && $iLand < 3000)
                    $free_expl = rand(1,7);
                elseif ($iLand > 2999)
                    $free_expl = rand(1,8);

                $iNewLand = $iLand + $free_expl;
                $objUser->set_build(LAND, $iNewLand);

                $string     = "Due to magical influence our tribe has expanded! We have gained <span class=\"positive\">$free_expl</span> acres!";
                $new_news   = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$bleh', '', 'land', '$iUserid', '', '1', '$string','')");
                $objUser->set_user_info(LAST_NEWS, 1);
            }

            if ($arrSpells[PEST] > 9)
            {
                if ($strRace != "Undead" && $strRace != "Nazgul" && $strRace != "Spirit")
                {
                    $string     = "<span class=\"negative\">Leader! Pestilence has plagued our citizens. The roads are flooded with the smell of death.</span>";
                    $new_news   = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$bleh', '', 'pest', '$iUserid', '', '1', '$string','')");
                    $objUser->set_user_info(LAST_NEWS, 1);
                }
            }

            if ($arrSpells[VIRUS] > 0)
            {
                $arrArmysHome = $objUser->get_armys_home();

                $arrArmyLost[UNIT1] = round($arrArmysHome[UNIT1] * 0.1);
                $arrArmyLost[UNIT2] = round($arrArmysHome[UNIT2] * 0.006);
                $arrArmyLost[UNIT3] = round($arrArmysHome[UNIT3] * 0.006);
                $arrArmyLost[UNIT4] = round($arrArmysHome[UNIT4] * 0.005);
                $arrArmyLost[UNIT5] = round($arrArmysHome[UNIT5] * 0.015);
                $arrArmyLost[UNIT6] = round($arrArmysHome[UNIT6] * 0.015);
                $total  = array_sum($arrArmyLost);

                $iCitizens    = $objUser->get_pop(CITIZENS);
                $citz         = round($iCitizens * 0.1);
                $iNewCitizens = $iCitizens - $citz;
                $objUser->set_pop(CITIZENS, $iNewCitizens);

                // M: Bah, letting this one remain
                // G: Bah, no way ;)
                // M: allrighty then :p
                $arrArmy = $objUser->get_armys();
                $arrNewArmy = array(
                    UNIT1 => $arrArmy[UNIT1] - $arrArmyLost[UNIT1],
                    UNIT2 => $arrArmy[UNIT2] - $arrArmyLost[UNIT2],
                    UNIT3 => $arrArmy[UNIT3] - $arrArmyLost[UNIT3],
                    UNIT4 => $arrArmy[UNIT4] - $arrArmyLost[UNIT4],
                    UNIT5 => $arrArmy[UNIT5] - $arrArmyLost[UNIT5],
                    UNIT6 => $arrArmy[UNIT6] - $arrArmyLost[UNIT6]
                );
                $objUser->set_armys($arrNewArmy);

                $string   = "Leader! The virus that plague our lands has claimed many lives, we estimate <span class=\"negative\">" . number_format($citz) . "</span> citizens and <span class=\"negative\">" . number_format($total) . "</span> military units were killed.";
                $new_news = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$bleh', '', 'virus', '$iUserid', '', '1', '$string','')");
                $objUser->set_user_info(LAST_NEWS, 1);
            }

            //==================================================================
            // Population
            //==================================================================
            //                 *** IMPORTANT ***
            // I fixed an early-starvation-bug by moving this block to above
            //  the goods block.
            // The problem was that it compares the 'current' stats, which are
            //  the before-update ones here, but the after-update ones there.
            //                                                  - AI 25/11/2006
            //==================================================================

            // Citizens
            $arrMaxPop = getMaxPopulation($objUser);
            $iNewCitz  = $arrMaxPop['total_citizens'];
            $objUser->set_pop(CITIZENS, $iNewCitz);

            //==================================================================
            // Production of Goods/Wares
            //==================================================================
            $arrGoods             = $objUser->get_goods();

            // Wood
            $arrYards             = getWoodProduction($objUser);
            $produced['wood']     = $arrYards['total'];
            $iNewWood             = $arrGoods[WOOD] + $produced['wood'];

            // Research
            $arrLabs              = getResearchProduction($objUser);
            $produced['research'] = $arrLabs['total'];
            $iNewResearch         = $arrGoods[RESEARCH] + $produced['research'];

            // Food
            $arrFarms             = getFoodProduction($objUser);
            $produced['food']     = $arrFarms['total'];
            $iNewFood             = $arrGoods[FOOD] + $produced['food'];

            if ($iNewFood < 0)
                $iNewFood = 0;

            // Money
            $arrIncome            = getTotalIncome($objUser);
            $produced['income']   = $arrIncome['total'];
            $iNewMoney            = $arrGoods[MONEY] + $produced['income'];

            if ($iNewMoney < 0)
                $iNewMoney = 0;

            $arrNewGoods = array(
                MONEY    => $iNewMoney,
                FOOD     => $iNewFood,
                RESEARCH => $iNewResearch,
                WOOD     => $iNewWood
            );
            $objUser->set_goods($arrNewGoods);

            // News Items For Negative Money and Negative Income
            if (($arrGoods[MONEY] + $produced['income']) < 0)
            {
                $arrArmy      = $objUser->get_armys();
                $arrArmysHome = $objUser->get_armys_home();

                $arrArmyLost[UNIT1] = round($arrArmysHome[UNIT1] * 0.1);
                $arrArmyLost[UNIT2] = round($arrArmysHome[UNIT2] * 0.006);
                $arrArmyLost[UNIT3] = round($arrArmysHome[UNIT3] * 0.006);
                $arrArmyLost[UNIT4] = round($arrArmysHome[UNIT4] * 0.005);
                $arrArmyLost[UNIT5] = round($arrArmysHome[UNIT5] * 0.015);
                $arrArmyLost[UNIT6] = round($arrArmysHome[UNIT6] * 0.015);
                $total  = array_sum($arrArmyLost);

                // M: Bah, letting this one remain
                // G: Bah, no way ;)
                // M: allrighty then ^^
                $arrArmy = $objUser->get_armys();
                $arrNewArmy = array
                (
                    UNIT1 => $arrArmy[UNIT1] - $arrArmyLost[UNIT1],
                    UNIT2 => $arrArmy[UNIT2] - $arrArmyLost[UNIT2],
                    UNIT3 => $arrArmy[UNIT3] - $arrArmyLost[UNIT3],
                    UNIT4 => $arrArmy[UNIT4] - $arrArmyLost[UNIT4],
                    UNIT5 => $arrArmy[UNIT5] - $arrArmyLost[UNIT5],
                    UNIT6 => $arrArmy[UNIT6] - $arrArmyLost[UNIT6]
                );
                $objUser->set_armys($arrNewArmy);

                $string   = "You are out of money! <span class=\"negative\">" . number_format($total) . "</span> military units leave the tribe.";
                $new_news = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$bleh', '', 'negcash', '$iUserid', '', '1', '$string','')");
                $objUser->set_user_info(LAST_NEWS, 1);
            }

            if ((($arrGoods[FOOD] + $produced['food']) < 0) && $strRace != "Spirit")
            {
                // frost: add tribe news on citizen loss @ negative food
                $string   = "You are out of food! Some of your citizens leave the tribe.";
                $new_news = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`,
                                                            `text`, `kingdom_text`) VALUES ('', '$bleh', '', 'negfood',
                                                            '$iUserid', '', '1', '$string','')");
                $objUser->set_user_info(LAST_NEWS, 1);
            }

            //==================================================================
            // Updating Magic Power. Read the magic power function in  inc/functions/spells.php
            // because you will forget how this works, Each hour you gain Magic power but you
            // can only hold an equal amount of magic power as you have Mage Guilds.  Each hour
            // all spells effecting you will be lowered one hour. so if you had a spell cast
            // on you that lasted 10 hours, after an update it will last 9 hours. make sure
            // that the table for spells is set to "UNSIGNED" because if not you will have
            // people with NEGITIVE hours remaining on a spell. Oh And mage_power_growth()
            // has to be called with the users #.
            //==================================================================
            $mp_growth  = obj_mage_power_growth($objUser);
            $guilds     = $objUser->get_build(GUILDS);
            $land       = $objUser->get_build(LAND);
            $max_mp     = (1 + $guilds / (2 * $land)) * $guilds;

            if ($strRace == "Eagle")
                $max_mp *= 1.3;

            $max_mp     = round($max_mp);
            $arrSpells  = $objUser->get_spells();

            // this should fix the rounding bugs - AI 25/11/2006
            // solution is now in array() call

            $arrNewSpells = array(
                POWER          => ( $arrSpells[POWER] + $mp_growth > $max_mp) ? $max_mp : $arrSpells[POWER] + $mp_growth,
                OFFENCE        => $arrSpells[OFFENCE] - 1,
                DEFENCE        => $arrSpells[DEFENCE] - 1,
                POPULATION     => $arrSpells[POPULATION] - 1,
                INCOME         => $arrSpells[INCOME] - 1,
                GROWTH         => $arrSpells[GROWTH] - 1,
                FOOD           => $arrSpells[FOOD] - 1,
                THWART         => $arrSpells[THWART] - 1,
                STUNTED_GROWTH => $arrSpells[STUNTED_GROWTH] - 1,
                SALEM          => $arrSpells[SALEM] - 1,
                FOUNTAIN       => $arrSpells[FOUNTAIN] - 1,
                ROAR           => $arrSpells[ROAR] - 1,
                FOREST         => $arrSpells[FOREST] - 1,
                MORTALITY      => $arrSpells[MORTALITY] - 1,
                BROOD          => $arrSpells[BROOD] - 1,
                PEST           => $arrSpells[PEST] - 1,
                VIRUS          => $arrSpells[VIRUS] - 1,
                DEFIANCE       => $arrSpells[DEFIANCE] - 1
            );
            $objUser->set_spells($arrNewSpells);

            //==================================================================
            //thievery points
            //==================================================================
            $credits = $objUser->get_thievery(CREDITS);

            $tp_growth = obj_thief_op_growth($objUser);
            $land = $objUser->get_build(LAND);
            $homes = $objUser->get_build(HOMES);
            $hideouts = $objUser->get_build(HIDEOUTS);

            if ($strRace == "Mori Hai")
                $hideout_add = floor($homes / 2.5);
            else
                $hideout_add = 0;

            $max_tp = (1 + ($hideouts + $hideout_add) * 2 / $land) * ($hideouts + $hideout_add);

            // this should fix the rounding bugs - AI 25/11/2006
            //fix in array() call

            $arrThief = $objUser->get_thieverys();
            $arrNewThief = array(
                CREDITS => ($credits + $tp_growth) > $max_tp ? $max_tp : $credits + $tp_growth,
                TRAP    => $arrThief[TRAP] - 1,
                MONITOR => $arrThief[MONITOR] - 1
            );
            $objUser->set_thieverys($arrNewThief);

            // The update counter - how many updates a tribe have had
            $arrInfo = $objUser->get_user_infos();
            $arrNewInfo = array (
                HOURS => $arrInfo[HOURS] + 1,
                NEXT_ATTACK => $arrInfo[NEXT_ATTACK] - 1
            );
            $objUser->set_user_infos($arrNewInfo);
        }

        // Random events
        // Gotland: This section must be gone through before OOP
        // Martel:  Moving this to a separate file           - February 27, 2008
        if ($objUser->get_user_info(HOURS) > PROTECTION_HOURS)
        {
            require_once('inc/functions/update_events.php');
            generate_random_event($objUser, $number_updates);
        }
    }
}

?>
