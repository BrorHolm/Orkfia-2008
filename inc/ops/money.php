<?php

/*
TEMPLU AMPLO - Produces money
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
        return "Templu Amplo";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
    $made = 0;
    for ($x = 1; $x <= $cnt; $x++)
    {
        $random = rand(10,27);
        if ($random == "26") { $random = "35"; }
        if ($random == "27") { $random = "50"; }
        $made += floor($thieves*$random * (2/3));
    }

    $money = $objSrcUser->get_good(MONEY);
    $objSrcUser->set_good(MONEY, $money + $made);

    $result["fame"] = 0;
    $result["text_screen"] = "Your thieves journey to Templu Amplo and return with <strong class=indicator>" . number_format($made) . " cr</strong> to the growth of your tribe";
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
                if ($random == "27") { $random = "50"; }
                $made += floor($thieves*$random*2/3);

        }

        mysql_query("UPDATE goods SET money = money+$made WHERE id=$o_user[id]");

        $result["fame"] = 0;
        $result["text_screen"] = "Your Thieves visit your citizens and collect $made cr in donations to the growth of your tribe";
        $result["text_news"] = "";

        return $result;
}
*/
?>