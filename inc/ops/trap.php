<?php

/*
THIEVES TRAP - Gives thievery protection
Last update: 17-01-2005, by Species 5618
*/

function get_op_type()
{
        return "self";
}

function get_op_chance()
{
        return 80;
}

function get_op_name()
{
        return "Thieves Trap (SELF)";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
        if ($objSrcUser->get_build(LAND) > $thieves)
        {
            $result["text_screen"] = "Your thieves have tried to create traps, but not enough thieves were available to complete the task. Send more thieves on this operation.";
        }
        else
        {
            $random = rand(6,10);
            $objSrcUser->set_thievery(TRAP, $random);
            $result["text_screen"] = "Your thieves have created traps for the safety of your tribe. You will have increased thievery protection for $random hours.";
        }

        $result["fame"] = 0;
        $result["text_news"] = "";

        return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
        global  $connection,
                $local_goods;

        $res = mysql_query("SELECT land FROM build WHERE id = $o_user[id]");
        $line = mysql_fetch_assoc($res);
        if ($line["land"] > $thieves)
        {
            $result["text_screen"] = "Your thieves have tried to create traps, but not enough thieves were available to complete the task. Send more thieves on this operations.";
        }
        else
        {
            $random = rand(6,10);
            mysql_query("UPDATE thievery SET trap = $random WHERE id=$o_user[id]");
            $result["text_screen"] = "Your thieves have created traps for the safety of your tribe. You will have increased thievery protection for $random hours.";
        }

        $result["fame"] = 0;
        $result["text_news"] = "";

        return $result;
}
*/
?>