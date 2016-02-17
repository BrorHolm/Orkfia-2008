<?php
//******************************************************************************
// staff tool visitors_m.inc.php                        Martel, October 05, 2006 
//******************************************************************************
include("inc/classes/count_visitors_class.php");

function call_visitors_m_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    error_reporting(E_ALL);
    class Month_detail extends Count_visitors {
	
    	var $full_month_name;
    	var $poss_search_vars = array("referer", "country"); // add more table fields if you like
    	var $start_date;
    	var $end_date;
    
    	function create_date_select($element_name = "monthSelect") {
    		$month_array = $this->month_last_year();
    		$menu = "<select name=\"".$element_name."\" id=\"".$element_name."\">\n";
    		if (empty($_REQUEST[$element_name])) {
    			$curr_val = date("n|Y");
    		} else {
    			$curr_val = $_REQUEST[$element_name];
    		}
    		foreach ($month_array as $value) {
    			$menu .= "  <option value=\"".$value."\"";
    			$menu .= ($value == $curr_val) ? " selected=\"selected\">" : ">";
    			$date_parts = explode("|", $value);
    			$date_full_txt = date("M y", mktime(0, 0, 0, $date_parts[0], 1, $date_parts[1]));
    			$menu .= $date_full_txt."</option>\n";
    		}
    		$menu .= "</select>\n"; 
    		return $menu;	
    	}
    	function convert_date($month) {
    		if (empty($month)) $month = date("n|Y");
    		$date_parts = explode("|", $month);
    		$time_value = mktime(0, 0, 0, $date_parts[0], 1, $date_parts[1]);
    		$this->full_month_name = date("F Y", $time_value);
    		$this->start_date = date("Y-m-d", $time_value);
    		$this->end_date = date("Y-m-t", $time_value);
    	}
    	// use for $what referer or country
    	function month_detail_count($search_val = "nl", $what = "country") { //
    		if (!in_array($what, $this->poss_search_vars)) return "#error!";
    		$sql = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE visit_date BETWEEN '%s' AND '%s'", $this->table_name, $this->start_date, $this->end_date);
    		if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'"; 
    		if ($what == "country") {
    			$sql .= sprintf(" AND country = '%s'", trim($search_val));
    		} else {
    			$sql .= sprintf(" AND %s LIKE '%%%s%%'", $what, trim($search_val));
    		}
    		$result = mysql_query($sql);
    		$det_value = mysql_result($result, 0, "counter");
    		mysql_free_result($result);
    		return $det_value;
    	}
    	function total_this_month() { //
    		$sql = sprintf("SELECT COUNT(*) AS counter FROM %s WHERE visit_date BETWEEN '%s' AND '%s'", $this->table_name, $this->start_date, $this->end_date);
    		if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'"; 
    		$result = mysql_query($sql);
    		$total = mysql_result($result, 0, "counter");
    		mysql_free_result($result);
    		return $total;
    	}
    }
    
    $req_month = (!empty($_POST['monthSelect'])) ? $_POST['monthSelect'] : "";
    $detail = new Month_detail;
    $detail->convert_date($req_month);
?>

<h1>Monthly totals</h1>
<form action="main.php?cat=game&amp;page=resort_tools&amp;tool=visitors_m" method="post">
  <div style="padding:10px 0;">
    <label for="month">Select a month</label>
	<?php echo $detail->create_date_select(); ?>
	<input type="submit" name="Submit" value="OK">
  </div>
</form>
<table id="medium" border="0" cellspacing="0" cellpadding="0">
  <tr class="header">
    <th colspan="2">Results for <?php echo $detail->full_month_name; ?></th>
  </tr>
  <tr class="data">
    <th width="20%">Total visits (all)  </th>
    <td width="35%"><?php echo $detail->total_this_month(); ?></td>
  </tr>
  <tr class="subheader">
    <th>Visits by country:</th>
    <td>&nbsp;</td>
  </tr>
  <tr class="data">
    <th>Sweden</th>
    <td><?php echo $detail->month_detail_count("se", "country"); ?></td>
  </tr>
  <tr class="data">
    <th>France</th>
    <td><?php echo $detail->month_detail_count("fr", "country"); ?></td>
  </tr>
  <tr class="data">
    <th>Germany</th>
    <td><?php echo $detail->month_detail_count("de", "country"); ?></td>
  </tr>
  <tr class="data">
    <th>The Netherlands </th>
    <td><?php echo $detail->month_detail_count("nl", "country"); ?></td>
  </tr>
  <tr class="data">
    <th>United Kingdom </th>
    <td><?php echo $detail->month_detail_count("uk", "country"); ?></td>
  </tr>
  <tr class="data">
    <th>Greece</th>
    <td><?php echo $detail->month_detail_count("gr", "country"); ?></td>
  </tr>  
  <tr class="data">
    <th>Belgium</th>
    <td><?php echo $detail->month_detail_count("be", "country"); ?></td>
  </tr>
  <tr class="subheader">
    <th>Visits by referer: </th>
    <td>&nbsp;</td>
  </tr>
  <tr class="data">
    <th>www.google.xx</th>
    <td><?php echo $detail->month_detail_count("www.google.", "referer"); ?></td>
  </tr>
  <tr class="data">
    <th>yahoo.com/</th>
    <td><?php echo $detail->month_detail_count("yahoo.com/", "referer"); ?></td>
  </tr>
  <tr class="data">
    <th>search.msn.xx</th>
    <td><?php echo $detail->month_detail_count("search.msn.", "referer"); ?></td>
  </tr>
</table>

<?php
}
?>