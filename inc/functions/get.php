<?
// ----------------------------------------
// change history:
// 16/04/2002 thalura   corrected sql request in
//          get_army_home
//
// ------------------------------------------
function get_local_stats($user) {  
   $result = mysql_query("SELECT * FROM stats WHERE id=$user");
    return(mysql_fetch_array($result));
}

function get_local_spells($user){
 $result = mysql_query("SELECT * FROM spells WHERE id=$user");
 return (mysql_fetch_array($result));
}

function get_local_build($user){
 $result = mysql_query("SELECT * FROM build WHERE id=$user");
 return (mysql_fetch_array($result));
}

function get_citizens($user) {
 $result = mysql_query("SELECT citizens FROM pop WHERE id=$user");
$result = (mysql_fetch_array($result));
return ($result['citizens']);}
 
function get_update_spells($user) {
$result = mysql_query("SELECT income, population, food, growth, power FROM spells WHERE id=$user");
return (mysql_fetch_array($result));}

function get_race($user) {
 $result = mysql_query("SELECT race FROM stats WHERE id=$user");
$result = (mysql_fetch_array($result));
return ($result['race']);}



function get_alliance_size($kingdom){
$result = mysql_query("SELECT build.land FROM stats, build WHERE stats.id = build.id AND stats.kingdom = $kingdom");
$alliance_size = 0;
while ($myrow = mysql_fetch_row($result)) 
{$alliance_size = $myrow[0] + $alliance_size;}
return ($alliance_size);
}

function get_thievery_credits($user) {
 $result = mysql_query("SELECT credits FROM thievery WHERE id=$user");
 $result = (mysql_fetch_array($result));
 return ($result[credits]);}

function get_goods($user) {
  $result = mysql_query("SELECT money, food, research, wood FROM goods WHERE id=$user");
   return (mysql_fetch_array($result));}

// BUILDINGS
function get_homes_land($user) {
$result = mysql_query("SELECT homes FROM build WHERE id=$user");
return (mysql_fetch_array($result));}

function get_update_buildings($user) {
$result = mysql_query("SELECT * FROM build WHERE id=$user");
return (mysql_fetch_array($result));}

function get_mines_banks($user) {
$result = mysql_query("SELECT mines, banks FROM build WHERE id=$user");
return (mysql_fetch_array($result));}

function get_total_land($user) {
$result = mysql_query("SELECT land FROM build WHERE id=$user");
$result = mysql_fetch_array($result);
return ($result['land']);}


// SCIENCE
function get_science_update_bonus($kingdom) {
   $result  = mysql_query ("SELECT income_bonus, home_bonus, offence_bonus, defence_bonus FROM kingdom WHERE id = $kingdom");
 return (mysql_fetch_array($result));}

function get_science_inner($kingdom) {
   $result  = mysql_query ("SELECT name, money, food, research, income_bonus, home_bonus, offence_bonus, defence_bonus FROM kingdom WHERE id = $kingdom");
 return (mysql_fetch_array($result));}

function get_science_home_bonus($kingdom) {
   $result  = mysql_query ("SELECT home_bonus FROM kingdom WHERE id = $kingdom");
return (mysql_fetch_array($result));}

function get_war_science($kingdom) {
   $result  = mysql_query ("SELECT offence_bonus, defence_bonus FROM kingdom WHERE id = $kingdom");
 return (mysql_fetch_array($result));}


function get_military_expense($user) {
   $result = mysql_query("SELECT unit1, unit2, unit3, unit4, unit5, unit6 FROM army WHERE id=$user");
    $local_army = (mysql_fetch_array($result)); 

   $military_expense = ($local_army['unit1'] * 0.1  +
                 $local_army['unit2']  * 0.3  +
                 $local_army['unit3']  * 0.3  +
                 $local_army['unit4']  * 0.7  +
                 $local_army['unit5']  * 0.2  +
                 $local_army['unit6']  * 0.2);
    return($military_expense);
   }

function get_total_army($user) {
   $result = mysql_query("SELECT unit1, unit1_t1, unit1_t2, unit1_t3, unit1_t4, unit2, unit2_t1, unit2_t2, unit2_t3, unit2_t4, unit3, unit3_t1, unit3_t2, unit3_t3, unit3_t4, unit4, unit4_t1, unit4_t2, unit4_t3, unit4_t4, unit5, unit5_t1, unit5_t2, unit5_t3, unit5_t4, unit6, unit6_t1, unit6_t2, unit6_t3, unit6_t4 FROM army WHERE id=$user");
    return(mysql_fetch_array($result));
} 

function get_kingdom($user) {
     $result  = mysql_query ("SELECT kingdom FROM stats WHERE id = $user");
return (mysql_fetch_array($result));}


// Tragedy: added fame 
function get_fame($user) 
{
     $result = mysql_query ("SELECT fame FROM stats WHERE id = $user");
     $result = mysql_fetch_array($result);
return ($result['fame']);
}

function get_kingdom_nonarray($user) 
{
     $result = mysql_query ("SELECT kingdom FROM stats WHERE id = $user");
     $result = mysql_fetch_array($result);
return ($result['kingdom']);
}

function get_army_home($user)
{
    global  $unit_var;

    $result = mysql_query("SELECT unit1_t1, unit1_t2, unit1_t3, unit1_t4, unit2_t1, unit2_t2, unit2_t3, unit2_t4, unit3_t1, unit3_t2, unit3_t3, unit3_t4, unit4_t1, unit4_t2, unit4_t3, unit4_t4, unit5_t1, unit5_t2, unit5_t3, unit5_t4 FROM milreturn WHERE id=$user");
    $local_milreturn = mysql_fetch_array($result); 

    $result = mysql_query("SELECT unit1, unit2, unit3, unit4, unit5 FROM army WHERE id=$user");
    $local_army = mysql_fetch_array($result); 

    for ($i = 0; $i < 5; $i++)
    {
        $current = $unit_var[$i];
        $current = trim($current);
        $sub_current1 = "$current" . "_t1";
        $sub_current2 = "$current" . "_t2";
        $sub_current3 = "$current" . "_t3";
        $sub_current4 = "$current" . "_t4";
        $army_home[$current] = ($local_army[$current]
                             - $local_milreturn[$sub_current1]
                             - $local_milreturn[$sub_current2]
                             - $local_milreturn[$sub_current3]
                             - $local_milreturn[$sub_current4]);
    }
    
    return $army_home;
}
?>
