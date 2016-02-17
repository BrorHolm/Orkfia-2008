<?php

/*
WEATHERS LIGHT - Produces food
Last update: 19-11-2004, by Species 5618
*/

function get_op_type()
{
        return "self";
}

function get_op_chance()
{
        return 75;
}

function get_op_name()
{
        return "Weather\'s Light";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
    $made = 0;

    // Since 6 thief units would normally eat 1kg we want triple? to be average

    for ($x = 1; $x <= $cnt; $x++)
    {
        $random = rand(10,27);
        if ($random == 26) { $random = 35; }
        if ($random == 22) { $random = 50; }
        $made += floor($thieves * $random * 2/3);
    }
    $made = floor(($made) / 20);

    $food = $objSrcUser->get_good(FOOD);
    $objSrcUser->set_good(FOOD, $food + $made);

    $result["fame"] = 0;
    $result["text_screen"] = "Your thieves help your citizens to harvest, together they manage to collect <strong class=positive>" . number_format($made) . " kgs</strong> of food.";
    $result["text_news"] = "";

    return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
        global  $connection,
                $local_goods;

        $made = 0;
        for ($x = 1; $x <= $cnt; $x++)
        {
                $random = rand(10,27);
                if ($random == "26") { $random = "35"; }
                if ($random == "22") { $random = "50"; }
                $made += floor($thieves*$random*2/3);

        }
        $made = floor($made * 2 / 3);

        mysql_query("UPDATE goods SET food = food+$made WHERE id=$o_user[id]");

        $result["fame"] = 0;
        $result["text_screen"] = "Your thieves help your farmers to harvest the food, they collected $made kgs of grain.";
        $result["text_news"] = "";

        return $result;
}
*/
?>
