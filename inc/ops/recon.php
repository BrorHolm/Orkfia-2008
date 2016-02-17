<?

/*
RECON - Gathers intell on enemy buildings (shows building-section of enemy management page)
Last update: 
8-4-2004, by Species 5618
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
	return "Recon";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	include_once("inc/pages/advisors.inc.php");

	$result["fame"] = 0;
	$result["text_screen"] = get_construction_table($objTrgUser);
	$result["text_news"] = "";

	return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
	global  $connection,
           	$local_build,
           	$local_stats,
               	$output_building_percent,
           	$buildings_due;

	$building_variables ="|homes|farms|walls|weaponries|guilds|mines|markets|labs|churches|gaurds|banks|hideouts|academies|yards|";
   	$building_output ="|Homes|Farms|Walls|Weaponries|Guilds|Mines|Markets|Labs|Churches|Guard Houses|Banks|Hideouts|Academies|Yards|";
	$building_variables = explode("|", $building_variables);
	$building_output = explode("|", $building_output);;

	mysql_grab($d_user[id],local,goods,build,stats,pop,army,user);
	$tribename = stripslashes($local_stats[tribe]);
	$temp = "<table width=70% class=\"border\" cellspacing=0 cellpadding=0>" .
		"<tr>" .
		"<td colspan=7 align=center class=\"pd bold black dark bdown\">$tribename(#$local_stats[kingdom])</td></tr>" .
	    	"<tr><td class=\"bold black darker pd bdown\">Type</td>" .
              	"<td class=\"bold black darker pd bdown\" align=right>Owned</td>" .
              	"<td class=\"bold black darker pd bdown\" align=right>In Progress</td>" .
              	"<td class=\"bold black darker pd bdown\" align=right>1 Hour</td>" .
              	"<td class=\"bold black darker pd bdown\" align=right>2 Hours</td>" .
              	"<td class=\"bold black darker pd bdown\" align=right>3 Hours</td>" .
                "<td class=\"bold black darker pd bdown\" align=right>4 Hours</td></tr>" .
    		"<tr>";

	$local_build[barren] = $local_build[land];
	for ($i = 1; $i <= 14; $i++)
       	{
       		$current = $building_variables[$i];
       		$current2 = $building_variables[$i];
       		$sub_current1 = "$current" . "_t1";
       		$sub_current2 = "$current" . "_t2";
       		$sub_current3 = "$current" . "_t3";
       		$sub_current4 = "$current" . "_t4";

       		$total[$current] = (
                           $local_build[$sub_current1] +
                           $local_build[$sub_current2] +
                           $local_build[$sub_current3] +
                           $local_build[$sub_current4] );


	        $total[$current2] = (
			   $local_build[$current2]     +
                           $local_build[$sub_current1] +
                           $local_build[$sub_current2] +
                           $local_build[$sub_current3] +
                           $local_build[$sub_current4] );

		$temp .= "<tr><td class=\"bold pd blue\">$building_output[$i]:</td>" .
            	"<td class=\"pd bold\" align=right>$local_build[$current]</td>" .
		"<td class=\"pd\" align=right>$total[$current]</td>" .
             	"<td class=\"pd 11\" align=right>$local_build[$sub_current1]</td>" .
             	"<td class=\"pd 11\" align=right>$local_build[$sub_current2]</td>" .
             	"<td class=\"pd 11\" align=right>$local_build[$sub_current3]</td>" .
	        "<td class=\"pd 11\" align=right>$local_build[$sub_current4]</td>" .
             	"</tr>";
        	$local_build[barren] =($local_build[barren] - $total[$current2] );
	}
    	$temp .="</table><br><br>" .
		"<table cellspacing=0 cellpadding=0 width=20% class=\"border\">" .
	        "<tr><td colspan=2 class=\"dark pd bold black bdown\" align=center>Quick Stats</td></tr>" .
                "<tr><td class=\"bold pd blue\">Total Land:</td><td class=\"pd bold\" align=right>$local_build[land]</td></tr>" .
                "<tr><td class=\"bold pd blue\">Walls:</td><td class=\"pd bold\" align=right>$local_build[walls]</td></tr>" .
                "<tr><td class=\"bold pd blue\">Weaponries:</td><td class=\"pd bold\" align=right>$local_build[weaponries]</td></tr>" .
                "<tr><td class=\"bold pd blue\">Barren:</td><td class=\"pd bold\" align=right>$local_build[barren]</td></tr>" .
        	"</table>";

	$result["fame"] = 0;
	$result["text_screen"] = $temp;
	$result["text_news"] = "";

	return $result;
}
*/