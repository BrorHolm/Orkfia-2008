<?php

/*
NAPANOMETY - Produces research points
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
        return "Napanometry";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	$made = 0;
	for ($x = 1; $x <= $cnt; $x++)
	{
		$random = rand(10,27);
		if ($random == "26") { $random = "35"; }
		if ($random == "27") { $random = "50"; }
		$made += floor($thieves*$random*2/3);
	}
	$made = floor($made / 400);

	$research = $objSrcUser->get_good(RESEARCH);
	$objSrcUser->set_good(RESEARCH, $research + $made);

	$result["fame"] = 0;
	$result["text_screen"] = "You've sent your thieves out to research for your alliance. They produced $made research points";
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
		$made = floor($made / 400);
        
        mysql_query("UPDATE goods SET research = research + $made WHERE id=$o_user[id]");

        $result["fame"] = 0;
        $result["text_screen"] = "You've sent your thieves out to research for your alliance. They produced $made research points";
        $result["text_news"] = "";

        return $result;
}
*/
?>