<?

/*
Truth's Eye - Gathers the active spells on a target
Last update: 16-1-2005, By DamadmOO
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
	return "Truth's Eye";
}

function do_op(&$objSrcUser,&$objTrgUser,$cnt,$thieves,$mod)
{
	include_once("inc/pages/advisors.inc.php");

	$result["fame"] = 0;
	$result["text_screen"] = get_effecting_spells_table($objTrgUser);
	$result["text_news"] = "";

	return $result;
}

//Gotland: use the one above that takes objects instead
/*
function do_op($o_user,$d_user,$cnt,$thieves,$mod)
{
	include_once("inc/functions/spells.php");
	$objTrgUser = new clsUser ($d_user[id]);
	$arrTrgSpells = $objTrgUser->get_spells();
	$arrSpells = set_spell_vars($objTrgUser);
	$name = mysql_query("SELECT tribe, kingdom FROM stats WHERE id = $d_user[id]");
	$name = mysql_fetch_array($name);
	reset ($arrSpells);
    $blnNoSpells = TRUE;
    $temp = "This is what we found out about $name[tribe](#$name[kingdom])'s self spells..." .
		"<table width=\"40%\" cellspacing=0 cellpadding=0 class=\"border\">\n" .
		"<tr><td colspan=2 align=center class=\"dark pd bold black bdown\">Effecting Spells</td></tr>\n";
	while (list ($strSpellName, $arrSpell) = each ($arrSpells)) {
        if ($arrTrgSpells[$arrSpell[DBFIELD]] <> 0) {
            $blnNoSpells = FALSE;
            $temp .= "<tr><td class=\"pd bold blue\">" . $arrSpell[DISPLAY] . 
                "</td><td class=\"pd\" align=right>" . $arrTrgSpells[$arrSpell[DBFIELD]] .
                " hours.</td></tr>";
        }
    }

    if ($blnNoSpells) {
       $temp .= "<tr><td colspan=2 class=\"pd\"> No Spells Running</td></tr>";
    }

    $temp .= "</table>";

	$result["fame"] = 0;
	$result["text_screen"] = $temp;
	$result["text_news"] = "";

	return $result;
}
*/
?>