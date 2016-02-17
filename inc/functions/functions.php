<?
// Martel: Don't use any of these!

//ORKFiA
//Functions for MySQL-access and data retrieval
//Created: 10-11-2004 (Age 14), by Species 5618

//Note: the script assumes a MySQL-connection already exists and that the
//proper database is already selected.

//===========================================================================\\
//== DATABASE RELATED FUNCTIONS                                            ==\\
//===========================================================================\\

//db_get_data :: fetches the record in db-table $table for tribe/alliance with
//id $id. returns data in an associated array.
function db_get_data($table, $id)
{
    $query = "SELECT * FROM $table WHERE id=$id";
    $result = mysql_query($query) or die("db_get_data: " . mysql_error() .
             "<BR><BR>Please contact the staff and copy this errormessage " .
             "to them.");
    $arr = mysql_fetch_assoc($result);
    mysql_free_result($result);

    return $arr;
}

//db_update_field :: updates the database using UPDATE $table SET $field=$value
//WHERE id=$id. don't use this functions to update fields containing strings,
//since those need to be encapsulated by quotation marks.
function db_update_field($table, $field, $value, $id)
{
    $query = "UPDATE $table SET $field=$value WHERE id=$id";
    mysql_query($query);
}

//===========================================================================\\
//== COMPUTATIONAL FUNCTIONS                                               ==\\
//===========================================================================\\

//calc_sci_bonus :: computes the science-bonus for alliance $id. returns an
//array with the four science-levels as reals between 0 and 1, rounded to 4
//digits. values: prod(uction) -- eng(ineering) -- def(ense) -- war
function calc_sci_bonus($id)
{
    $query = "SELECT SUM(build.land) AS total_land FROM stats, build WHERE " .
             "stats.id = build.id AND stats.kingdom = $id";
    $result = mysql_query($query);
    $tmp = mysql_fetch_assoc($result);

    $query = "SELECT home_bonus, income_bonus, defence_bonus, offence_bonus " .
             "FROM kingdom WHERE id = $id";
    $result = mysql_query($query);
    $arr = mysql_fetch_assoc($result);

    $sci["eng"] = round( 1.98 * $arr["home_bonus"] / (80 * $tmp["total_land"] +
                         $arr["home_bonus"]), 4);
    $sci["prod"] = round( 1.98 * $arr["income_bonus"] / (80 * $tmp["total_land"] +
                         $arr["income_bonus"]), 4);
    $sci["def"] = round( 1.98 * $arr["defence_bonus"] / (80 * $tmp["total_land"] +
                         $arr["defence_bonus"]), 4);
    $sci["war"] = round( 1.98 * $arr["offence_bonus"] / (80 * $tmp["total_land"] +
                         $arr["offence_bonus"]), 4);

    return $sci;
}
