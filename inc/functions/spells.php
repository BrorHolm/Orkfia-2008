<?php

function set_spell_vars(&$objUser)
{
    $arrBuild = $objUser->get_builds();
    $intBase = ($arrBuild[LAND] * 0.001);
    if ($intBase <= 1)
    {
        $intBase = 1;
    }

    //==========================================================================
    // Race arrays :)                                       Martel July 02, 2006
    //==========================================================================
    include_once('inc/functions/races.php');

    //==========================================================================
    // The spells
    //==========================================================================
    $arrSpells = array(
        ///////////////////////////////////
        // Defiance - Triggered spell when
        // big tribe is killed during war.
        // Cannot be caster or voided.
        ///////////////////////////////////
        "defiance" => array(
            "common"  => "defiance",
            "display" => "Defiance",
            "dbfield" => DEFIANCE,
            "level"   => 100,
            "acres"   => 0,
            "chance"  => 0,
            "cost"    => ceil($intBase * 100),
            "fame"    => 0,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Elendian - Increased Pop
        ///////////////////////////////////
        "elendian" => array(
            "common"  => "pop",
            "display" => "Elendian",
            "dbfield" => POPULATION,
            "level"   => 1,
            "acres"   => 0,
            "chance"  => 175,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 10,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Matawaska River - Increased Birthrate
        ///////////////////////////////////
        "matawaska" => array(
            "common"  => "birth",
            "display" => "Matawaska River",
            "dbfield" => GROWTH,
            "level"   => 1,
            "acres"   => 0,
            "chance"  => 175,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 10,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Lord of Harvest - Increased food prod
        ///////////////////////////////////
        "lord_of_harvest" => array(
            "common"  => "food",
            "display" => "Lord of Harvest",
            "dbfield" => FOOD,
            "level"   => 1,
            "acres"   => 0,
            "chance"  => 200,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 10,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Quanta - Increased income
        ///////////////////////////////////
        "quanta" => array(
            "common"  => "income",
            "display" => "Quanta",
            "dbfield" => INCOME,
            "level"   => 1,
            "acres"   => 0,
            "chance"  => 160,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 10,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Blaze of Glory - Increased offence
        ///////////////////////////////////
        "blaze_of_glory" => array(
            "common"  => "offence",
            "display" => "Ciorin's Blessing",
            "dbfield" => OFFENCE,
            "level"   => 5,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 15,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // God of War - Increased defence
        ///////////////////////////////////
        "god_of_war" => array(
            "common"  => "defence",
            "display" => "Deam's hunt",
            "dbfield" => DEFENCE,
            "level"   => 5,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 15,
            "durmax"  => 28,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Vision
        ///////////////////////////////////
        "vision" => array(
            "common"  => "vision",
            "display" => "Vision",
            "dbfield" => "",
            "level"   => 1,
            "acres"   => 0,
            "chance"  => 200,
            "cost"    => ceil($intBase * 1),
            "fame"    => 0,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ALL
        ),

        ///////////////////////////////////
        // Inner Sight - spy on research
        ///////////////////////////////////
        "inner_sight" => array(
            "common"  => "inner_sight",
            "display" => "Inner Sight",
            "dbfield" => "",
            "level"   => 4,
            "acres"   => 0,
            "chance"  => 220,
            "cost"    => ceil($intBase * 2),
            "fame"    => 2,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ALL
        ),

        ///////////////////////////////////
        // Fly Over  - View defenders alliance news
        ///////////////////////////////////
        "flyover" => array(
            "common"  => "flyover",
            "display" => "Fly Over",
            "dbfield" => "",
            "level"   => 6,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 1),
            "fame"    => 1,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ALL
        ),

        ///////////////////////////////////
        // Fireball
        ///////////////////////////////////
        "fireball" => array(
            "common"  => "fireball",
            "display" => "Fireball",
            "dbfield" => "",
            "level"   => 9,
            "acres"   => 0,
            "chance"  => 150,
            "cost"    => ceil($intBase * 5),
            "fame"    => 2,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Poison
        ///////////////////////////////////
        "poison" => array(
            "common"  => "poison",
            "display" => "Poison",
            "dbfield" => "",
            "level"   => 8,
            "acres"   => 0,
            "chance"  => 180,
            "cost"    => ceil($intBase * 3),
            "fame"    => 4,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Anthrax
        ///////////////////////////////////
        "anthrax" => array(
            "common"  => "anthrax",
            "display" => "Anthrax",
            "dbfield" => "",
            "level"   => 10,
            "acres"   => 0,
            "chance"  => 150,
            "cost"    => ceil($intBase * 4),
            "fame"    => 6,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Wrath of Cyclops - destroy walls
        ///////////////////////////////////
        "cyclops" => array(
            "common"  => "cyclops",
            "display" => "Wrath of Cyclops",
            "dbfield" => "",
            "level"   => 12,
            "acres"   => 0,
            "chance"  => 125,
            "cost"    => ceil($intBase * 3),
            "fame"    => 4,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Thwart - protection from magic
        ///////////////////////////////////
        "thwart" => array(
            "common"  => "thwart",
            "display" => "Seal of Deflection",
            "dbfield" => THWART,
            "level"   => 13,
            "acres"   => 0,
            "chance"  => 70,
            "cost"    => ceil($intBase * 2),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 5,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),

        ///////////////////////////////////
        // Stunted Growth
        ///////////////////////////////////
        "stunted_growth" => array(
            "common"  => "stunted_growth",
            "display" => "Stunted Growth",
            "dbfield" => STUNTED_GROWTH,
            "level"   => 17,
            "acres"   => 0,
            "chance"  => 130,
            "cost"    => ceil($intBase * 2),
            "fame"    => 2,
            "durmin"  => 4,
            "durmax"  => 16,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Juranimosity
        ///////////////////////////////////
        "juranimosity" => array(
            "common"  => "juranimosity",
            "display" => "Juranimosity",
            "dbfield" => "",
            "level"   => 18,
            "acres"   => 0,
            "chance"  => 60,
            "cost"    => ceil($intBase * 10),
            "fame"    => 7,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Enchantres Salem - reduce target's def
        ///////////////////////////////////
        "salem" => array(
            "common"  => "salem",
            "display" => "Enchantress Salem",
            "dbfield" => SALEM,
            "level"   => 15,
            "acres"   => 0,
            "chance"  => 80,
            "cost"    => ceil($intBase * 10),
            "fame"    => 10,
            "durmin"  => 1,
            "durmax"  => 1,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Mystical Rust - destroys weaponries
        ///////////////////////////////////
        "rust" => array(
            "common"  => "rust",
            "display" => "Mystical Rust",
            "dbfield" => "",
            "level"   => 7,
            "acres"   => 0,
            "chance"  => 200,
            "cost"    => ceil($intBase * 2),
            "fame"    => 1,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Unresearch - sabotage research
        ///////////////////////////////////
        "unresearch" => array(
            "common"  => "burn_research",
            "display" => "Unresearch",
            "dbfield" => "",
            "level"   => 10,
            "acres"   => 0,
            "chance"  => 90,
            "cost"    => ceil($intBase * 5),
            "fame"    => 4,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),

        ///////////////////////////////////
        // Magical Void - remove spells
        ///////////////////////////////////
        "magical_void" => array(
            "common"  => "magical_void",
            "display" => "Magical Void",
            "dbfield" => "",
            "level"   => 20,
            "acres"   => 0,
            "chance"  => 60,
            "cost"    => ceil($intBase * 4),
            "fame"    => 6,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ALL
        ),
        ////////////////////////////////////
        // DragonApprentice - Destroys homes
        ////////////////////////////////////
        "dragonapprentice" => array(
            "common"  => "dragonapprentice",
            "display" => "DragonApprentice",
            "dbfield" => "",
            "level"   => 12,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 6),
            "fame"    => 6,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),
        ///////////////////////////////////
        // DragonMage - Destroys buildings
        ///////////////////////////////////
        "dragonmage" => array(
            "common"  => "dragonmage",
            "display" => "DragonMage",
            "dbfield" => "",
            "level"   => 25,
            "acres"   => 0,
            "chance"  => 60,
            "cost"    => ceil($intBase * 8),
            "fame"    => 12,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),
        ///////////////////////////////////
        // Fountain of Resurrection - Lowers combat losses
        ///////////////////////////////////
            "fountain" => array(
            "common"  => "fountain",
            "display" => "Fountain of Resurrection",
            "dbfield" => FOUNTAIN,
            "level"   => 22,
            "acres"   => 0,
            "chance"  => 70,
            "cost"    => ceil($intBase * 5),
            "fame"    => 0,
            "durmin"  => 2,
            "durmax"  => 8,
            "race"    => getRaces(),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // admin only: but totally worth it!
        ///////////////////////////////////
        "cheaters" => array(
            "common"  => "cheaters",
            "display" => "Fry The Cheater",
            "dbfield" => "",
            //"level"   => 1000000,
            "level"   => 100,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => 0,
            "fame"    => 1000,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => array('Dragon'),
            "type"    => SPELL_ALL
        ),
        ///////////////////////////////////
        // ORK ONLY - Roar of the Horde
        ///////////////////////////////////
        "roar" => array(
            "common"  => "roar",
            "display" => "Roar of the Horde",
            "dbfield" => ROAR,
            "level"   => 15,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 14),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 10,
            "race"    => getRaces('Orks'),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // Elf Only- Deep Forest
        ///////////////////////////////////
        "forest" => array(
            "common"  => "forest",
            "display" => "Deep Forest",
            "dbfield" => FOREST,
            "level"   => 15,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 14),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 10,
            "race"    => getRaces('Elves'),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // HUMAN ONLY - Mortality
        ///////////////////////////////////
        "mortality" => array(
            "common"  => "mortality",
            "display" => "Mortality",
            "dbfield" => MORTALITY,
            "level"   => 15,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 14),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 10,
            "race"    => getRaces('Humans'),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // Cursed Only - Pestilance
        ///////////////////////////////////
        "pest" => array(
            "common"  => "pest",
            "display" => "Pestilence",
            "dbfield" => PEST,
            "level"   => 15,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 14),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 10,
            "race"    => getRaces('Cursed'),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // Winged only - Brood
        ///////////////////////////////////
        "brood" => array(
            "common"  => "brood",
            "display" => "Brood",
            "dbfield" => BROOD,
            "level"   => 10,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 14),
            "fame"    => 0,
            "durmin"  => 1,
            "durmax"  => 10,
            "race"    => getRaces('Winged'),
            "type"    => SPELL_SELF
        ),
        ///////////////////////////////////
        // Wrath of XENE - Destroys Churches
        ///////////////////////////////////
        "XENE" => array(
            "common"  => "XENE",
            "display" => "Wrath of XENE",
            "dbfield" => "",
            "level"   => 28,
            "acres"   => 0,
            "chance"  => 120,
            "cost"    => ceil($intBase * 6),
            "fame"    => 10,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),
        ///////////////////////////////////
        // Engineered Virus
        ///////////////////////////////////
        "virus" => array(
            "common"  => "virus",
            "display" => "Engineered Virus",
            "dbfield" => VIRUS,
            "level"   => 23,
            "acres"   => 0,
            "chance"  => 75,
            "cost"    => ceil($intBase * 10),
            "fame"    => 11,
            "durmin"  => 1,
            "durmax"  => 4,
            "race"    => getRaces(),
            "type"    => SPELL_WAR
        ),
        ///////////////////////////////////
        // Enforced honesty
        ///////////////////////////////////
        "enforced" => array(
            "common"  => "enforced",
            "display" => "Enforced Honesty",
            "dbfield" => "",
            "level"   => 19,
            "acres"   => 0,
            "chance"  => 60,
            "cost"    => ceil($intBase * 12),
            "fame"    => 0,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ENEMY
        ),
        ///////////////////////////////////
        // Heal
        ///////////////////////////////////
        "heal" => array(
            "common"  => "heal",
            "display" => "Heal",
            "dbfield" => "",
            "level"   => 20,
            "acres"   => 0,
            "chance"  => 100,
            "cost"    => ceil($intBase * 2)*3, // changed from  intBase * 4 * 3
            "fame"    => 2,
            "durmin"  => 0,
            "durmax"  => 0,
            "race"    => getRaces(),
            "type"    => SPELL_ALLIANCE
        )

 );



    return $arrSpells;
}

function obj_mage_power_growth(&$objUser)
{
    $alliance = $objUser->get_alliance();

    $alliance_size = $alliance->get_alliance_size() * 80;

    $offbonus = $alliance->get_alliance_info(OFFENCE_BONUS);
    $prod['science_offence_bonus'] = round((1.98*$offbonus)/($alliance_size+$offbonus),3);
    if ($prod['science_offence_bonus'] > 1) {$prod['science_offence_bonus'] = 1;}

    $guilds = $objUser->get_build(GUILDS);
    $land = $objUser->get_build(LAND);

    // Martel: Standard mana regrowth is 1/8 + 1mp per update
    $growth = $guilds * (1/8) + 1;

    $race = $objUser->get_stat(RACE);
    if($race == "Eagle")
    {
        $growth = $guilds * (1/6) + 1;
    }
    elseif($race == "Dark Elf")
    {
        $growth *= 1.35;
    }
//     elseif($race == "Wood Elf")
//     {
//         $growth *= 0.8;
//     }

    $growth = $growth * (1 + $guilds / (2 * $land));
    $growth = round($growth * (($prod['science_offence_bonus']/2)+1));
    return $growth;
}

//Gotland: Rather use function that takes object above
function mage_power_growth($userid)
{
    include_once("inc/functions/get.php");
    $kingdom = get_kingdom_nonarray($userid);

    // Science
    $alliance_size = (get_alliance_size($kingdom)) * 80;
    $science_update_bonus = get_science_update_bonus($kingdom);

    $prod['science_offence_bonus'] = round((1.98*$science_update_bonus['offence_bonus'])/($alliance_size+$science_update_bonus['offence_bonus']),3);
    if ($prod['science_offence_bonus'] > 1) {$prod['science_offence_bonus'] = 1;}

    $build = mysql_query ("SELECT guilds,land FROM build WHERE id = '$userid' ");
    $build = mysql_fetch_array($build);

    // Martel: Standard mana regrowth is 1/8 + 1mp per update
    $growth = $build['guilds'] * (1/8) + 1;

    $race = get_race($userid);
    if($race == "Eagle")
    {
        $growth = $build['guilds'] * (1/6) + 1;
    }
    elseif($race == "Dark Elf")
    {
        $growth *= 1.35;
    }
//     elseif($race == "Wood Elf")
//     {
//         $growth *= 0.8;
//     }

    $growth = $growth * (1 + $build["guilds"] / (2 * $build["land"]));
    $growth = round($growth * (($prod['science_offence_bonus']/2)+1));
    return $growth;
}

?>
