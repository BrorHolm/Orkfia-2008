<?

/*
SNEAK - Gathers intell on enemy unittraining (shows training-section of enemy management page)
Last update: 
6-5-2004, by Species 5618
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
	return "Sneak";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	include_once("inc/pages/advisors.inc.php");
        
	$result["fame"] = 0;
	$result["text_screen"] = get_military_training_table($objTrgUser);
	$result["text_news"] = "";

	return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
	global  $local_army,
		$userid,
		$local_pop,
           	$unit_var,
          	$unit_names,
		$local_stats;
           			
  	mysql_grab($d_user[id],local,army,stats);
    	unit_names($local_stats[race]);
		$tribename = stripslashes($local_stats[tribe]);
    	$temp = "<table class=\"border\" cellpadding=0 cellspacing=0 width=60%>\n" .
		"<tr><td class=\"dark black pd bold bdown\" colspan=7 align=center>\n" .
   		"$tribename's Military\n" .
		"</td></tr>\n" .
		"<tr><td class=\"black bold pd darker bdown\">Class</td>\n" .
		"<td class=\"black bold pd darker bdown\" align=right>Owned</td>\n" .
		"<td class=\"black bold pd darker bdown\" align=right>In Training</td>\n" .
   		"<td class=\"black bold pd darker bdown\" align=right>in 1</td>\n" .
		"<td class=\"black bold pd darker bdown\" align=right>in 2</td>\n" .
		"<td class=\"black bold pd darker bdown\" align=right>in 3</td>\n" .
		"<td class=\"black bold pd darker bdown\" align=right>in 4</td></tr>\n";

    	for ($i = 1; $i <=5; $i++)
	{
        	$a = "unit".$i."_t1";
	        $b = "unit".$i."_t2";
        	$c = "unit".$i."_t3";
	        $d = "unit".$i."_t4";
       		$local_army_training=( $local_army[$a] +  $local_army[$b] +  $local_army[$c] + $local_army[$d] );
        	$temp .= "<tr><td class=\"pd bold blue\">$unit_names[$i]:</td>\n" .
	        	 "<td align=right class=\"pd bold\">".number_format($local_army[unit.$i])."</td>\n" .
			 "<td align=right class=\"pd\">";
	        if ($local_army_training > 0)
		{
            		$temp .= "<font class='incoming'><b>";
            	}
		$temp .= "$local_army_training</td>\n" . "<td align=right class=\"pd 11\">";
        	if ($local_army[$a] > 0) { $temp .= "<font class='incoming'>"; }
	        $temp .= "$local_army[$a]</td>\n" . "<td align=right class=\"pd 11\">";
        	if ($local_army[$b] > 0) { $temp .= "<font class='incoming'>"; }
	        $temp .= "$local_army[$b]</td>\n" . "<td align=right class=\"pd 11\">";
        	if ($local_army[$c] > 0) { $temp .= "<font class='incoming'>"; }
	        $temp .= "$local_army[$c]</td>\n" . "<td align=right class=\"pd 11\">";
        	if ($local_army[$d] > 0) { $temp .= "<font class='incoming'>"; }
	        $temp .= "$local_army[$d]</td></tr>\n";
        }
        $temp .= "</table>";
        
	$result["fame"] = 0;
	$result["text_screen"] = $temp;
	$result["text_news"] = "";

	return $result;
}
*/
