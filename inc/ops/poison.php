<?php

/*
POISON WATER - Destroys enemy farms and kills citizens
Last update: 10-5-2004, by Species 5618
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
    return "Poison Water";
}

function do_op(&$objSrcUser, &$objTrgUser, $cnt, $thieves, $mod)
{
    $intPopulation = $objTrgUser->get_pop(CITIZENS);
    $land          = $objTrgUser->get_build(LAND);
    $farms         = $objTrgUser->get_build(FARMS);

    $thief_req = max($land / 3, 499);
    if ($thieves < 500)
        $ratio = 0;
    else
        $ratio = min(($thieves - 500) / ($thief_req - 500), 1);

    $totalFarms = 0;
    $totalCtz   = 0;
    for ($x = 1; $x <= $cnt; $x++)
    {
        $poisonable = floor($ratio * $mod * $farms * 0.02);
        if ($thieves < 500)
            $poisonable = 0;

        if ($poisonable < 0)
            $poisonable = 0;
        elseif ($poisonable > 20)
            $poisonable = 20;

        $intDamage = max(floor($ratio * $intPopulation * 0.008 * $mod), 15);

        $totalFarms     += $poisonable;
        $farms          -= $poisonable;
        $totalCtz       += $intDamage;
        $intPopulation  -= $intDamage;

        if ($intPopulation < 0)
            $cnt = $x;
    }

    $objTrgUser->set_build(FARMS, $farms);
    $objTrgUser->set_pop(CITIZENS, $intPopulation);

    $result["fame"] = $totalFarms + $cnt;
    $result["text_news"] = "Poison has contaminated our rivers, $totalFarms farms were destroyed and made desolate, also $totalCtz citizens were killed from drinking the water :-(";
    if ($thieves < 500)
        $result["text_screen"] = "You have tried poisoning the waters of your enemy, $totalFarms farms were destroyed by the poison, but you killed only $totalCtz citizens as the small amount of thieves could not carry enough poison, try sending more next time.";
    else
        $result["text_screen"] = "You have poisoned the waters of your enemy, $totalFarms farms were destroyed by the poison. Also, the water killed $totalCtz citizens.";


    return $result;
}


//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
    global  $local_build,
        $local_stats,
        $connection;

    include_once('inc/functions/generic.php');

    mysql_grab($d_user[id],local,build,stats,pop);
    $intPopulation = get_citizens($d_user[id]);
    $land = $local_build[land];
    $farms = $local_build[farms];

    $thief_req = max($land / 3, 499);
    if ($thieves < 500) { $ratio = 0; }
    else { $ratio = min( ($thieves - 500) / ($thief_req - 500), 1 ); }

    $totalFarms = 0;
    $totalCtz = 0;
    for ($x = 1; $x <= $cnt; $x++)
    {
        $poisonable = floor($ratio*$mod*$farms*0.02);
        if ($thieves < 500) { $poisonable = 0; }
        if ($poisonable < 0 ) { $poisonable = 0; }
        if ($poisonable > 20) { $poisonable = 20; }

        $intDamage = max(floor($ratio * $intPopulation * 0.008 * $mod),15);

        $totalFarms += $poisonable;
        $farms -= $poisonable;
        $totalCtz += $intDamage;
        $intPopulation -= $intDamage;
        if ($intPopulation < 0) {$cnt = $x;}
    }


        $res = mysql_query ("UPDATE build SET farms = $farms WHERE id = $d_user[id]");
    $res = mysql_query ("UPDATE pop SET citizens = $intPopulation WHERE id = $d_user[id]");

    $result["fame"] = $totalFarms + $cnt;
        $result["text_news"] = "Poison has contaminated our rivers, $totalFarms farms were destroyed and made desolate, also $totalCtz citizens were killed from drinking the water :-(";
    if ($thieves < 100) { $result["text_screen"] = "You have tried poisoning the waters of your enemy, $totalFarms farms were destroyed by the poison, but you killed only $totalCtz peasants as the small amount of thieves could not carry enough poison, try sending more next time."; }
    else { $result["text_screen"] = "You have poisoned the waters of your enemy, $totalFarms farms were destroyed by the poison. Also, the water killed $totalCtz peasants."; }


    return $result;
}
*/
?>
