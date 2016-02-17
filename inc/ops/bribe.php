<?

/*
Bribe - Gathers intell on enemy goods. Effectively shows the goods-section of the Advisors->Resources page.
Created: April 11, 2006, by Martel
Updated: July 12, 2006, Martel
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
    return "Bribe Accountant";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	include_once("inc/pages/advisors.inc.php");
             
    $result["fame"] = 0;
    $result["text_screen"] = get_goods_table($objTrgUser);
    $result["text_news"] = "";

    return $result;
}

?>
