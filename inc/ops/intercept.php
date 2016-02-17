<?

/*
INTERCEPT - Gathers intell on enemy troops returning from combat. Effectively shows the military-section of the Advisors->Actions page.
Last update: 
7-5-2004, by Species 5618
July 12, 2006, by Martel
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
	return "Intercept";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	include_once("inc/pages/advisors.inc.php");

	$result["fame"] = 0;
	$result["text_screen"] = get_military_returning_table($objTrgUser);
	$result["text_news"] = "";

	return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{  
	global  $local_goods,
           	$local_army,
           	$userid,
           	$local_pop,
           	$local_stats,
           	$unit_var,
           	$unit_names,
           	$local_milreturn;
        
    mysql_grab($d_user[id],local,goods,build,stats,pop,army,user,milreturn);
    unit_names($local_stats[race]);
    owned_military($local_stats[race]);
    army_home();
	$tribename = stripslashes($local_stats[tribe]);


    	$temp =	"This is what we found out about $tribename(#$local_stats[kingdom])'s military..." .
		"<table class=\"border\" cellpadding=0 cellspacing=0 width=60%>\n" .
   		"<tr><td class=\"dark pd bold black bdown\" colspan=6 align=center>\n" .
    		"Military Status" .
    		"</td></tr>" .
    		"<tr><td class=\"darker pd bold black bdown\">Class</td>\n" .
		"<td class=\"darker pd bold black bdown\" align=right>Home</td>\n" .
		"<td class=\"darker pd bold black bdown\" align=right>Back in 1</td>\n" .
		"<td class=\"darker pd bold black bdown\" align=right>in 2</td>\n" .
		"<td class=\"darker pd bold black bdown\" align=right>in 3</td>\n" .
		"<td class=\"darker pd bold black bdown\" align=right>in 4</td></tr>\n";

	for ($i = 1; $i <= 5; $i++)
      	{
		$current = "unit"."$i";
      		$sub_current1 = "$current" . "_t1";
      		$sub_current2 = "$current" . "_t2";
      		$sub_current3 = "$current" . "_t3";
      		$sub_current4 = "$current" . "_t4";

      		$local_army_away = ( $local_milreturn[$sub_current1] + $local_milreturn[$sub_current2] 
				   + $local_milreturn[$sub_current3] + $local_milreturn[$sub_current4] );

		$local_army_home = ( $local_army[$current] - $local_army_away );

	      	$temp .= "<tr><td class=\"pd blue bold\">$unit_names[$i]</td>\n" .
			 "<td align=right class=\"pd bold\">$local_army_home</td>\n" .
			 "<td align=right class=\"pd\">";
		if ($local_milreturn[$sub_current1] > 0) { $temp .= "<font class='incoming'>"; }
                $temp .= "$local_milreturn[$sub_current1]</td>\n" . "<td align=right class=\"pd\">";
		if ($local_milreturn[$sub_current2] > 0) { $temp .= "<font class='incoming'>"; }
                $temp .= "$local_milreturn[$sub_current2]</td>\n" . "<td align=right class=\"pd\">";
		if ($local_milreturn[$sub_current3] > 0) { $temp .= "<font class='incoming'>"; }
                $temp .= "$local_milreturn[$sub_current3]</td>\n" . "<td align=right class=\"pd\">";
		if ($local_milreturn[$sub_current4] > 0) { $temp .= "<font class='incoming'>"; }
                $temp .= "$local_milreturn[$sub_current4]</td>\n";
      	}
     	$temp .= "</table>";
             
	$result["fame"] = 0;
	$result["text_screen"] = $temp;
	$result["text_news"] = "";

	return $result;
}
*/

?>
