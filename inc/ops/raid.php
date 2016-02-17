<?php

/*
HWIGHTON RAID - Burns enemy magic power
Last update: 13-5-2004, by Species 5618
*/

function get_op_type()
{
        return "aggressive";
}

function get_op_chance()
{
        return 80;
}

function get_op_name()
{
        return "Hwighton Raid";
}

function do_op(&$objSrcUser, &$objTrgUser, $cnt, $thieves, $mod)
{
    $mp          = $objTrgUser->get_spell(POWER);
    $totalBurned = 0;

    for ($x = 1; $x <= $cnt; $x++)
    {
        $max_burn     = floor($mp / 7);
        $burnable     = min(floor(($thieves / 50) * $mod), $max_burn);

        $mp          -= $burnable;
        $totalBurned += $burnable;
    }

    $objTrgUser->set_spell(POWER, $mp);
    $tribe = stripslashes($objTrgUser->get_stat(TRIBE));

    $result["fame"]        = floor($totalBurned * 0.2);
    $result["text_screen"] = "You have burned $totalBurned of $tribe's magic power.";
    $result["text_news"]   = "Our mage seems to have lost <span class=negative>$totalBurned</span> magic power.";

    return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
        global  $local_build,
                $local_stats,
                $connection,
                $local_spells;

        mysql_grab($d_user[id],local,goods,build,stats,pop,user,spells);
        $mp = $local_spells[power];
        $totalBurned = 0;

        for ($x = 1; $x <= $cnt; $x++)
        {
                $max_burn = floor($mp/7);
                $burnable = min(floor($thieves/50*$mod),$max_burn);
                if ($thieves <= 50 && $max_burn <= 1)
                {
                        $max_burn = "0";
                }
                $mp -= $burnable;
                $totalBurned += $burnable;

        }
        mysql_query ("UPDATE spells SET power ='$mp' WHERE id ='$d_user[id]'");

        $result["fame"] = floor($totalBurned * 0.2);
        $result["text_screen"] = "You have burned $totalBurned of $local_stats[tribe]'s magic power.";
        $result["text_news"] = "Our mage seems to have lost $totalBurned magic power";

        return $result;
}
*/
?>
