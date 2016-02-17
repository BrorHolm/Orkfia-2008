<?php
/*
    Last major change: September 19, 2005
    Made by: Martel
    Comments: Document indented correctly and cleaned up.
*/


/* REPLACE THIS ONE WITH inc/functions/races.php! */
function get_unit_power($race)
{
    global $unit_offence, $unit_defence;

    // Reducing redundancy, as you can see, it is still awfully ugly,
    //         so just don't use this function - AI 10/02/2007
    require_once('inc/races/clsRace.php');
    $objRace = clsRace::getRace($race);
    $unit_offence = array_merge(array(''),$objRace->getUnitOffences(),array(''));
    $unit_defence = array_merge(array(''),$objRace->getUnitDefences(),array(''));
    $unit_offence = implode("|", $unit_offence);
    $unit_defence = implode("|", $unit_defence);
    $total = array( "offence" => $unit_offence, "defence" => $unit_defence );
    return $total;
}

function sort_units()
{
    global $unit_offence,$unit_defence,$local_army,$suicidal_offence,$total_defence,$local_stats,$kingdom, $science_offence_bonus, $science_defence_bonus;

    $unit_offence = explode("|", $unit_offence);
    $unit_defence = explode("|", $unit_defence);

    $suicidal_offence =
                      ( $local_army['unit1'] * $unit_offence[1]
                      + $local_army['unit2'] * $unit_offence[2]
                      + $local_army['unit3'] * $unit_offence[3]
                      + $local_army['unit4'] * $unit_offence[4]
                      + $local_army['unit5'] * $unit_offence[5] );

    $total_defence    =
                      ( $local_army['unit1'] * $unit_defence[1]
                      + $local_army['unit2'] * $unit_defence[2]
                      + $local_army['unit3'] * $unit_defence[3]
                      + $local_army['unit4'] * $unit_defence[4]
                      + $local_army['unit5'] * $unit_defence[5] );

    $kingdom_number = $local_stats['kingdom'];
    $result  = mysql_query ("SELECT * from kingdom where id =$kingdom_number" ) or die(mysql_error());;
    $kingdom = mysql_fetch_array($result);

    include_once ("inc/functions/get.php");
    $alliance_size = get_alliance_size($kingdom_number);
    $science_war   = get_war_science($kingdom_number);

    $offence['research'] = round((1.98 * $science_war['offence_bonus']) / (80 * $alliance_size + $science_war['offence_bonus']),2);
    if ($offence['research'] > 1) {$offence['research'] = 1;}

    $defence['research'] = round((1.98 * $science_war['defence_bonus']) / (80 * $alliance_size + $science_war['defence_bonus']),2);
    if ($defence['research'] > 1) {$defence['research'] = 1;}

    $science_offence_bonus = ($suicidal_offence * $offence['research']);
    $science_defence_bonus = ($total_defence * $defence['research']);
}

function wall_bonus($id)
{
    global $build, $wall_bonus;
    include_once("inc/functions/get.php");

    // -----------------------------------
    // 15/04/2002 thalura    added changes for round x
    //            uhukhais double wall bonus
    //
    // ---------------------------------------

    $result = mysql_query ("SELECT walls,land from build where id ='$id' " ) or die(mysql_error());;
    $build  = mysql_fetch_array($result);

    $wall_bonus  = $build['walls'] / $build['land'];
    if ($wall_bonus > 0.2)
    {
        $wall_bonus = 0.2;
    }
}

function weapon_bonus($id)
{
    global $build,$weapon_bonus;
    include_once("inc/functions/get.php");

    $result = mysql_query ("SELECT weaponries,land from build where id ='$id' " ) or die(mysql_error());;
    $build  = mysql_fetch_array($result);

    $weapon_bonus = $build['weaponries'] / $build['land'];

    if ($weapon_bonus > 0.2)
    {
        $weapon_bonus = 0.2;
    }

    // damamdoo : implemeting dwarf thingie
    $race = get_race($id);
    if ($race == 'Dwarf')
    {
        $weapon_bonus = $weapon_bonus * 1.35;
    }
}

////////////////////////////////////////////////////////////////////////////////

function get_attack_value($race)
{
    global $connection,
          $local_stats,
          $userid,
          $quick_off_loss,
          $quick_def_loss,
          $standard_off_loss,
          $standard_def_loss,
          $barren_off_loss,
          $barren_def_loss,
          $quick_gains,
          $standard_gains,
          $barren_gains,
          $wait_time,
          $attack_time;
}

// Martel: Used in management advisor for "mod defence at home".
function grab_self_defence()
{
    global  $local_spells, $science_defence_bonus, $total_defence, $arrArmyHome,
            $unit_defence, $wall_bonus, $total_defence_home;

    if ($local_spells['defence'] >= "1") { $local_spells['defence'] = "0.1"; }
    else { $local_spells['defence'] = "0.0"; }

    if ($local_spells['salem'] >= "1") { $local_spells['defence'] = "0.0"; }

    // Martel: Backtracking defence research bonus through the available globals.
    $defence['research']  = ($science_defence_bonus / $total_defence);

    $total_defence_home_raw =
                            ( $arrArmyHome['unit1'] * $unit_defence[1]
                            + $arrArmyHome['unit2'] * $unit_defence[2]
                            + $arrArmyHome['unit3'] * $unit_defence[3]
                            + $arrArmyHome['unit4'] * $unit_defence[4]
                            + $arrArmyHome['unit5'] * $unit_defence[5] );
    $total_defence_home_spell =
                            floor($total_defence_home_raw * $local_spells['defence']);
    $total_defence_home_science =
                            floor($total_defence_home_raw * $defence['research']);
    $total_defence_home_walls =
                            floor($total_defence_home_raw * $wall_bonus);

    $total_defence_home = $total_defence_home_raw + $total_defence_home_spell + $total_defence_home_science + $total_defence_home_walls;
}

function grab_send_offence()
{
    global  $local_spells, $science_offence_bonus, $arrArmyOut,
            $unit_offence, $weapon_bonus, $total_offence_send, $suicidal_offence;

    $offence_spell = 0;

    if ($local_spells['offence'] >= "1") { $offence_spell = 0.15; } else { $offence_spell = 0.0; }
    if ($local_spells['roar'] >= "1") { $offence_spell = $offence_spell + 0.1; }
    if ($local_spells['mortality'] >= "1") { $offence_spell = $offence_spell + 0.05; }

    $offence['research']  = ($science_offence_bonus / $suicidal_offence);
    $total_offence_send_raw =
                        ( $arrArmyOut['unit1'] * $unit_offence[1]
                        + $arrArmyOut['unit2'] * $unit_offence[2]
                        + $arrArmyOut['unit3'] * $unit_offence[3]
                        + $arrArmyOut['unit4'] * $unit_offence[4]
                        + $arrArmyOut['unit5'] * $unit_offence[5] );
    $total_offence_send_spell =
                            floor($total_offence_send_raw * $offence_spell);
    $total_offence_send_science =
                            floor($total_offence_send_raw * $offence['research']);
    $total_offence_send_weapons =
                            floor($total_offence_send_raw * $weapon_bonus);

    $total_offence_send = $total_offence_send_raw + $total_offence_send_spell + $total_offence_send_science + $total_offence_send_weapons;
}

function after_mod_power()
{
    global $weapon_bonus,$wall_bonus,$unit_offence,$after_mod_defence,$after_mod_offence,$science_offence_bonus,$unit_defence,$suicidal_offence,$total_defence,$local_army,$local_spells,$science_defence_bonus,$offence_spell,$defence_spell,$userid;

    $result = mysql_query("SELECT * FROM spells WHERE id=$userid");
    $local_spells  = mysql_fetch_array($result);
    if ($local_spells['defence'] >= "1") { $defence_spell = 0.1; } else { $defence_spell = 0.0; }
    if ($local_spells['salem'] >= "1") { $defence_spell = 0.0; }
    if ($local_spells['offence'] >= "1") { $offence_spell = 0.15; } else { $offence_spell = 0.0; }
    if ($local_spells['roar'] >= "1") { $offence_spell = $offence_spell + 0.1; }

    $defence_spell = floor($total_defence * $defence_spell);
    $offence_spell = floor($suicidal_offence * $offence_spell);
    $wall_bonus = floor($total_defence * $wall_bonus);
    $weapon_bonus = floor($suicidal_offence * $weapon_bonus);
    $after_mod_offence = ($suicidal_offence + $science_offence_bonus +$offence_spell+ $weapon_bonus);
    $after_mod_defence = ($total_defence + $science_defence_bonus + $defence_spell+ $wall_bonus);
}
?>
