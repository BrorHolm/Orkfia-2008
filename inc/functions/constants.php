<?php
//******************************************************************************
// functions constants.php                                  Martel July 17, 2006
//
// Details:
// Constants for orkfia. These have GLOBAL, scope. Goes well with our objects.
// History:
// Big update April 08, 2006 - Martel & Gotland
//******************************************************************************

//==============================================================================
// DB CONSTANTS
// ID is special define it once here, leave out from individual sections
//==============================================================================
define("ID", "id");

//==============================================================================
// Game tables constants
//==============================================================================
define('TBL_GAME', 'admin');
// define('STATUS', 'status');
define('DELETIONS', 'deletions');

define('TBL_GAME_TIME', 'admin_global_time');
define('HOUR_COUNTER', 'hour_counter');
define('CURRENT_HOUR', 'current_hour');
define('EVENT_COUNTER', 'event_counter');
define('AGE_NUMBER', 'age_number');

define('TBL_GAME_SWITCHES', 'admin_switches');
define('GLOBAL_PROTECTION', 'global_protection');
define('GLOBAL_WAR', 'global_war');
define('GAME_PAUSE', 'GamePause');
define('PAUSE_MODE_ACTIVE', 'PauseModeActive');
define('MERGER_PROGRAM', 'mergers');
define('NAME_CHANGE_PROGRAM', 'names');
define('TRIBE_CREATION', 'tribe_creation');
define('ALLIANCE_CREATION', 'alliance_creation');
define('BOOTCAMP_SIGNUPS', 'bootcamp_signups');
define('GLOBAL_PAUSE', 'global_pause');
define('LOGIN_STOPPER', 'login_stopper');

define('TBL_GAME_RECORDS', 'records');
define('AGESTART', 'agestart');
define('ONLINE', 'online');
define('GRAB', 'grab');
define('GRAB_ID', 'grab_id');
define('FIREBALL', 'fireball');
define('FIREBALL_ID', 'fireball_id');
define('ARSON', 'arson');
define('ARSON_ID', 'arson_id');
define('KILLED', 'killed');
define('KILLED_ID', 'killed_id');
// define('PEST', 'pest');
define('PEST_CURRENT', 'pest_current');
define('PEST_CURSED', 'pest_cursed');

define('TBL_GAME_HISTORY', 'rankings_history');
define('YEAR', 'year');
// define('LAND', 'land');
define('RANK_FAME', 'rank_fame');
define('RANK_STRENGTH', 'rank_strength');
// define('ALLI_ID', 'alli_id');
// define('ALLI_NAME', 'alli_name');
define('ALLI_DESC', 'alli_desc');
// define('LAST_UPDATE', 'last_update');

//==============================================================================
// User tables constants
//==============================================================================
define('TBL_ARMY', 'army');
define("UNIT1", "unit1");
define("UNIT1_T1", "unit1_t1");
define("UNIT1_T2", "unit1_t2");
define("UNIT1_T3", "unit1_t3");
define("UNIT1_T4", "unit1_t4");
define("UNIT2", "unit2");
define("UNIT2_T1", "unit2_t1");
define("UNIT2_T2", "unit2_t2");
define("UNIT2_T3", "unit2_t3");
define("UNIT2_T4", "unit2_t4");
define("UNIT3", "unit3");
define("UNIT3_T1", "unit3_t1");
define("UNIT3_T2", "unit3_t2");
define("UNIT3_T3", "unit3_t3");
define("UNIT3_T4", "unit3_t4");
define("UNIT4", "unit4");
define("UNIT4_T1", "unit4_t1");
define("UNIT4_T2", "unit4_t2");
define("UNIT4_T3", "unit4_t3");
define("UNIT4_T4", "unit4_t4");
define("UNIT5", "unit5");
define("UNIT5_T1", "unit5_t1");
define("UNIT5_T2", "unit5_t2");
define("UNIT5_T3", "unit5_t3");
define("UNIT5_T4", "unit5_t4");
define("UNIT6", "unit6");
define("UNIT6_T1", "unit6_t1");
define("UNIT6_T2", "unit6_t2");
define("UNIT6_T3", "unit6_t3");
define("UNIT6_T4", "unit6_t4");

define("TBL_ARMY_MERCS", "army_mercs");
define("MERC_T0", "merc_t0");
define("MERC_T1", "merc_t1");
define("MERC_T2", "merc_t2");
define("MERC_T3", "merc_t3");

define("TBL_BUILD", "build");
define("HOMES", "homes");
define("HOMES_T1", "homes_t1");
define("HOMES_T2", "homes_t2");
define("HOMES_T3", "homes_t3");
define("HOMES_T4", "homes_t4");
define("FARMS", "farms");
define("FARMS_T1", "farms_t1");
define("FARMS_T2", "farms_t2");
define("FARMS_T3", "farms_t3");
define("FARMS_T4", "farms_t4");
define("WALLS", "walls");
define("WALLS_T1", "walls_t1");
define("WALLS_T2", "walls_t2");
define("WALLS_T3", "walls_t3");
define("WALLS_T4", "walls_t4");
define("WEAPONRIES", "weaponries");
define("WEAPONRIES_T1", "weaponries_t1");
define("WEAPONRIES_T2", "weaponries_t2");
define("WEAPONRIES_T3", "weaponries_t3");
define("WEAPONRIES_T4", "weaponries_t4");
define("GUILDS", "guilds");
define("GUILDS_T1", "guilds_t1");
define("GUILDS_T2", "guilds_t2");
define("GUILDS_T3", "guilds_t3");
define("GUILDS_T4", "guilds_t4");
define("MINES", "mines");
define("MINES_T1", "mines_t1");
define("MINES_T2", "mines_t2");
define("MINES_T3", "mines_t3");
define("MINES_T4", "mines_t4");
define("MARKETS", "markets");
define("MARKETS_T1", "markets_t1");
define("MARKETS_T2", "markets_t2");
define("MARKETS_T3", "markets_t3");
define("MARKETS_T4", "markets_t4");
define("LABS", "labs");
define("LABS_T1", "labs_t1");
define("LABS_T2", "labs_t2");
define("LABS_T3", "labs_t3");
define("LABS_T4", "labs_t4");
define("CHURCHES", "churches");
define("CHURCHES_T1", "churches_t1");
define("CHURCHES_T2", "churches_t2");
define("CHURCHES_T3", "churches_t3");
define("CHURCHES_T4", "churches_t4");
// stupid
define("GAURDS", "gaurds");
define("GAURDS_T1", "gaurds_t1");
define("GAURDS_T2", "gaurds_t2");
define("GAURDS_T3", "gaurds_t3");
define("GAURDS_T4", "gaurds_t4");
//less stupid
define("GUARDHOUSES", "gaurds");
define("GUARDHOUSES_T1", "gaurds_t1");
define("GUARDHOUSES_T2", "gaurds_t2");
define("GUARDHOUSES_T3", "gaurds_t3");
define("GUARDHOUSES_T4", "gaurds_t4");
// end stupid (change at opportunity!)
define("BANKS", "banks");
define("BANKS_T1", "banks_t1");
define("BANKS_T2", "banks_t2");
define("BANKS_T3", "banks_t3");
define("BANKS_T4", "banks_t4");
define("HIDEOUTS", "hideouts");
define("HIDEOUTS_T1", "hideouts_t1");
define("HIDEOUTS_T2", "hideouts_t2");
define("HIDEOUTS_T3", "hideouts_t3");
define("HIDEOUTS_T4", "hideouts_t4");
define("ACADEMIES", "academies");
define("ACADEMIES_T1", "academies_t1");
define("ACADEMIES_T2", "academies_t2");
define("ACADEMIES_T3", "academies_t3");
define("ACADEMIES_T4", "academies_t4");
define("YARDS", "yards");
define("YARDS_T1", "yards_t1");
define("YARDS_T2", "yards_t2");
define("YARDS_T3", "yards_t3");
define("YARDS_T4", "yards_t4");
define("LAND", "land");
define("LAND_T1", "land_t1");
define("LAND_T2", "land_t2");
define("LAND_T3", "land_t3");
define("LAND_T4", "land_t4");

define("TBL_DESIGN", "design");
define("COLOR", "color");
define("WIDTH", "width");
define("ALIGNMENT", "alignment");

define("TBL_GAMESTATS", "gamestats");
define("PLAYERS", "players");
define("ALLIANCES", "alliances");
define("SIGNUP_TIME", "signup_time");
define("SIGNUP_IP", "signup_ip");

define("TBL_GOODS", "goods");
define("MONEY", "money");
define("FOOD", "food");
define("RESEARCH", "research");
define("WOOD", "wood");
define("CREDITS", "credits");
define('MARKET_MONEY', 'market_money');
define('MARKET_FOOD', 'market_food');
define('MARKET_SOLDIERS', 'market_soldiers');
define('MARKET_WOOD', 'market_wood');

define("TBL_KILLS", "kills");
// define("LAND", "land");
define("CASH", "cash");
define("BASICS", "basics");
define("POP", "pop");

define("TBL_MILRETURN", "milreturn");
define("CITIZEN", "citizen");
define("CITIZEN_T1", "citizen_t1");
define("CITIZEN_T2", "citizen_t2");
define("CITIZEN_T3", "citizen_t3");
define("CITIZEN_T4", "citizen_t4");
// define("UNIT1_T1", "unit1_t1");
// define("UNIT1_T2", "unit1_t2");
// define("UNIT1_T3", "unit1_t3");
// define("UNIT1_T4", "unit1_t4");
// define("UNIT2_T1", "unit2_t1");
// define("UNIT2_T2", "unit2_t2");
// define("UNIT2_T3", "unit2_t3");
// define("UNIT2_T4", "unit2_t4");
// define("UNIT3_T1", "unit3_t1");
// define("UNIT3_T2", "unit3_t2");
// define("UNIT3_T3", "unit3_t3");
// define("UNIT3_T4", "unit3_t4");
// define("UNIT4_T1", "unit4_t1");
// define("UNIT4_T2", "unit4_t2");
// define("UNIT4_T3", "unit4_t3");
// define("UNIT4_T4", "unit4_t4");
// define("UNIT5_T1", "unit5_t1");
// define("UNIT5_T2", "unit5_t2");
// define("UNIT5_T3", "unit5_t3");
// define("UNIT5_T4", "unit5_t4");

define("TBL_ONLINE", "online");
define("TIME", "time");

define("TBL_POP", "pop");
define("CITIZENS", "citizens");
define("MYSTICS", "mystics");

define("TBL_PREFERENCES", "preferences");
define("EMAIL_ACTIVATION", "email_activation");
define("EMAIL", "email");
define("LAST_M", "last_m");
define("LAST_A", "last_a");
define("GUIDE_LINKS", "guide_links");
    define('ON', 'on');   // also for admin switches
    define('OFF', 'off'); // also for admin switches
define("NEW_A", "new_a"); // alliance
define("NEW_S", "new_s"); // staff
define("NEW_W", "new_w"); // world
define("NEW_N", "new_n"); // news (announcements)
define("NEW_L", "new_l"); // lno
define("NEW_O", "new_o"); // ops
define("NEW_D", "new_d"); // dev
define("NEW_G", "new_g"); // game talk
define("NEW_DR", "new_dr"); // dragon


define("TBL_RANKINGS_PERSONAL", "rankings_personal");
define("TRIBE_NAME", "tribe_name");
define("ALLI_ID", "alli_id");
define("ALLI_NAME", "alli_name");
// define("RACE", "race");
// define("LAND", "land");
// define("FAME", "fame");
define("NW", "nw");
define("STRENGTH", "nw"); // Phase out NW using this, then change to "strength"
// define("HOURS", "hours");
define("PLAYER_TYPE", "player_type");

define("TBL_REG_CHECK", "reg_check");
define("REG_VALUE", "reg_value");
define("POST_STOP", "post_stop");

define("TBL_SPELL", "spells");
define("POWER", "power");
define("OFFENCE", "offence");
define("DEFENCE", "defence");
define("POPULATION", "population");
define("INCOME", "income");
define("GROWTH", "growth");
// define("FOOD", "food");
define("THWART", "thwart");
define("STUNTED_GROWTH", "stunted_growth");
define("SALEM", "salem");
define("FOUNTAIN", "fountain");
define("VIRUS", "virus");
define("DEFIANCE", "defiance");
define("ROAR", "roar");
define("FOREST", "forest");
define("BROOD", "brood");
define("MORTALITY", "mortality");
define("PEST", "pest");
define("CASTING_NOW", "casting_now");
    define("FREE", "free");
    define("BUSY", "busy");

define("TBL_STAT", "stats");
define("TRIBE", "tribe");
define("NAME", "name");
define("RACE", "race");
define("KINGDOM", "kingdom");
define("ALLIANCE", "kingdom"); // this replace the above, then change right side
// define("FAME", "fame");
define("VOTE", "vote");
define("TYPE", "type");
// define("LEVEL", "level");
define("RESET_OPTION", "reset_option");
define("KILLS", "kills");
define("INVESTED", "invested");
// define("KILLED", "killed");
define("TWG_VOTE", "twg_vote");

define("TBL_THIEVERY", "thievery");
//define("CREDITS", "credits");
define("TRAP", "trap");
define("MONITOR", "monitor");

define("TBL_USER", "user");
define("USERNAME", "username");
define("PASSWORD", "password");
define("LOGINS", "logins");
define("LAST_LOGIN", "last_login");
define("LAST_UPDATE", "last_update");
define("LAST_NEWS", "last_news");
define("HOURS", "hours");
define("STATUS", "status");
define("MD5", "md5");
define("NEXT_ATTACK", "next_attack");
define("UPDATES_OWED", "updates_owed");
define("LAST_UPDATE_DAY", "Last_update_day");
define("LAST_UPDATE_HOUR", "Last_update_hour");
define("LAST_UPDATE_GUC", "last_update_guc");
define("TIME_LIMIT", "time_limit");
define("PAUSE_ACCOUNT", "pause_account");
define("REALNAME", "realname");
define("COUNTRY", "country");
define("DELETED", "deleted");
define("SESSION", "session");
// define("HOURS", "hours");
define("STOPGAMETRIGGER", "stopgametrigger");

//==============================================================================
// Alliance tables constants
//==============================================================================
define("TBL_ALLIANCE", "kingdom");
// define("NAME", "name");
// define("PASSWORD", "password");
define("IMAGE", "image");
define("PRIVATE", "private");
define("LAST_POST", "last_post");
// define("MONEY", "money");
// define("FOOD", "food");
// define("RESEARCH", "research");
// define("WOOD", "wood");
define("SOLDIERS", "soldiers");
define("INCOME_BONUS", "income_bonus");
define("HOME_BONUS", "home_bonus");
define("OFFENCE_BONUS", "offence_bonus");
define("DEFENCE_BONUS", "defence_bonus");
define("ELDER_MESSAGE", "elder_message");
define("PAGE_UPDATE", "page_update");
define("MARKET_DAY", "market_day");
define("MARKET_HOUR", "market_hour");
define("VOTE_COUNT", "vote_count");
define("IMAGEWIDTH", "imagewidth");
define("IMAGEHEIGHT", "imageheight");
define("BOOTCAMP", "bootcamp");
define("DESCRIPTION", "description");
/* Martel: commenting these out for the time being... (TBL_ALLIANCE)
define("SAWMILL", "sawmill");
define("GRANARY", "granary");
define("ROADS", "roads");
define("SENATE", "senate");
define("OUTPOSTS", "outposts");
define("SHIPYARDS", "shipyards");
define("SCHOOLS", "schools");
define("UNIVERSITIES", "universities");
define("ACADAMIES", "acadamies");
define("IGLOOS", "igloos");
*/

define("TBL_RANKINGS_ALLIANCE", "rankings_alliance");
// define("ALLI_NAME", "alli_name");
// define("ALLI_DESC", "alli_desc");
// define("LAND", "land");
// define("FAME", "fame");
// define("NW", "nw");

define("TBL_WAR", "war");
define("TARGET", "target");
define("WAR_STARTED", "war_started");
define("TRUCE", "truce");
define("SURRENDER", "surrender");
define("DEFEAT", "defeat");
define("VICTORY", "victory");
define("LAST_TARGET", "last_target");
define("LAST_OUTGOING", "last_outgoing");
define("LAST_END", "last_end");
define("EVENT_ID", "event_id");
define("START_LAND", "start_land");
define("TRUCE_OFFER", "truce_offer");

//==============================================================================
// NAMES of ELEMENTS in the SPELLS (not the table) array
//==============================================================================
define("COMMON", "common");
define("DISPLAY", "display");
define("DBFIELD", "dbfield");
define("LEVEL", "level");
define("CHANCE", "chance");
define("COST", "cost");
define("FAME", "fame");
define("DURMIN", "durmin");
define("DURMAX", "durmax");

//==============================================================================
// Some spell-constants over here
// note that these are integersa
// SPELL_ENEMY is unused for now, but it could be used
//  for disallowing ops on allimates or allowing
//  intelgathering when damaging isn't allowed - AI 30/09/06
//==============================================================================

define("SPELL_SELF", 0);
define("SPELL_ALLIANCE", 1);
define("SPELL_ALL", 2);
define("SPELL_ENEMY", 3);
define("SPELL_WAR", 4);

//==============================================================================
// Attacks already were integers of the 'magic numbers' kind
// making them into constants should make them more readable
//                                            - AI 01/10/06
//==============================================================================

define("ATTACK_STANDARD",1);
define("ATTACK_RAID",2);
define("ATTACK_BARREN",3);
define("ATTACK_HNR",4);
define("ATTACK_HITNRUN",4);
define("ATTACK_BC",5);

?>
