<?php

/*
THIEVES REBELLION - Burns enemy churches
update: 13-5-2004, by Species 5618
update: 19-11-2004, by Species 5618 -- changed guardhouses to churches (Age 15 age-changes)
*/

function get_op_type()
{
        return "aggressive";
}

function get_op_chance()
{
        return 60;
}

function get_op_name()
{
        return "Thieves rebellion";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
    $ch = $objTrgUser->get_build(CHURCHES);
    $percentage = 0.02;
    $totalBurned = 0;

    for ($x = 1; $x <= $cnt; $x++)
    {
        $max_burn = min(floor($ch * $percentage * $mod),20);
        if ($thieves <= 50 || $max_burn < 0)
        {
                $max_burn = 0;
        }
        $burnable = min(floor(($thieves / $objTrgUser->get_build(LAND)) * $max_burn),$max_burn);

        $ch -= $burnable;
        $totalBurned += $burnable;
    }

    $objTrgUser->set_build(CHURCHES, $ch);

    $result["fame"] = floor($totalBurned * 2);
    $result["text_screen"] = "our thieves have burned $totalBurned acres of churches!";
    $result["text_news"] = "Leader! We have found $totalBurned churches burned to the ground.";

    return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
        global  $local_build,
                $local_stats,
                $connection;

        mysql_grab($d_user[id],local,build,stats);

        $ch = $local_build[churches];
        $percentage = 0.02;
        $totalBurned = 0;

        for ($x = 1; $x <= $cnt; $x++)
        {
                $max_burn = min(floor($ch * $percentage * $mod),20);
                if ($amount_sent >= 50 && $max_burn <= "0")
                {
                        $max_burn = "0";
                }
                $burnable = min(floor(($thieves / $local_build[land]) * $max_burn),$max_burn);

                $ch -= $burnable;
                $totalBurned += $burnable;
        }

        mysql_query("UPDATE build SET churches =$ch WHERE id =$d_user[id]");

        $result["fame"] = floor($totalBurned * 2);
        $result["text_screen"] = "our thieves have burned $totalBurned acres of churches!";
        $result["text_news"] = "Leader! We have found $totalBurned churches burned to the ground.";

        return $result;
}
*/
?>
