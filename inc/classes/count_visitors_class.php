<?php
/************************************************************************
Counter & visitor statistics version 2.06 -
Easy to use system to track users and visitor statistics

Copyright (c) 2004 - 2006, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com
Comments & suggestions: http://www.webdigity.com/index.php/board,77.0.html,ref.olaf

Updates:
version 2.0 - Please read the documentation file about all new features.

version 2.01 - I forgot to remove an old variable: $remote_adr, this var is replaced by $_SERVER['REMOTE_ADDR']. The error will not occur anymore.

version 2.02 - I set a filter inside the method to get the referer array, the results from the own domain name are removed.

version 2.03 - I modified the class a little because if there are visits from more then a year ago the days are counted for last 12 month too. The method stats_totals() is written to show only the dat from the last year. To fix this modfied the "monthly" SQL inside the method get_data_array(). The table row height at the begin of a new month for the daily statistics was very large, I fixed this inside the method stats_monthly() with the new parameter $max_height.

version 2.04 - In this version it's possible to show the daily average number visits inside the monthly visitors table. There also some modifications inside these methods: month_last_year() and build_rows_totals(). These methods are new: average_visits_day() and show_max_visited_day. The table names for the ip2nation table data has to be configured now inside the config.php file (constant variables). In the same time I fixed the "mini" bug about the different spelling of the tablename "ip2nationCountries".

version 2.05 - There is some new function to display detailed data for month. It's possible to show the number of visitors by single countries and by the referer information. Inside the new file "monthly_details.php" is a small extenstion with custom methods and also an example table for the data presentation.

version 2.06 - To create statistics for several host on one web server all statistic methods are modified to query the database for the current host if the variable $report_by_host is set to true. If you use this version in an existing application you need to alter the visits table for an extra column "hostname" (check the visits.sql file). I added some style to the reporting page.

*************************************************************************/

//error_reporting(E_ALL);
define("DB_TABLE", "visits");
define("IP_TABLE", "ip2nation");
define("IP_COUNTRY_TABLE", "ip2nationCountries");
define("IMG", "./pics/1px.png"); // change this constant to use dif. image/path

class Count_visitors {

    var $table_name = DB_TABLE;
    var $referer;
    var $delay = 1;
    var $report_by_host = true;

    // niet vergeten visits ouder dan een jaar te verwijderen
    function Count_visitors() {
        $this->referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";
        $_SERVER['HTTP_HOST'] = "www.".ltrim($_SERVER['HTTP_HOST'], "www.");
    }
    function check_last_visit() {
        $check_sql = sprintf("SELECT time + 0 FROM %s WHERE visit_date = CURDATE() AND ip_adr = '%s' ORDER BY time DESC LIMIT 0, 1", $this->table_name, $_SERVER['REMOTE_ADDR']);
        $check_visit = mysql_query($check_sql);
        $check_row = mysql_fetch_array($check_visit);
        if (mysql_num_rows($check_visit) != 0) {
            $last_hour = date("H") - $this->delay;
            $check_time = date($last_hour."is");
            if ($check_row[0] < $check_time) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    function get_country() {
        $country_sql = sprintf("SELECT country FROM %s WHERE ip < INET_ATON('%s') ORDER BY ip DESC LIMIT 0,1", IP_TABLE, $_SERVER['REMOTE_ADDR']);
        $country_res = mysql_query($country_sql);
        $country = mysql_result($country_res, 0, "country");
        return $country;
    }
    function insert_new_visit() {
        if ($this->check_last_visit()) {
            $insert_sql = sprintf("INSERT INTO %s (id, ip_adr, referer, country, client, visit_date, time, on_page, hostname) VALUES (NULL, '%s', '%s', '%s', '%s', CURDATE(), CURTIME(), '%s', '%s')", $this->table_name, $_SERVER['REMOTE_ADDR'], $this->referer, $this->get_country(), $_SERVER['HTTP_USER_AGENT'], $_SERVER['PHP_SELF'], $_SERVER['HTTP_HOST']);
            mysql_query($insert_sql);
        }
    }
    function show_all_visits() {
        $sql = "SELECT COUNT(*) AS count FROM ".$this->table_name;
        if ($this->report_by_host) $sql .= " WHERE hostname = '".$_SERVER['HTTP_HOST']."'";
        $result = mysql_query($sql);
        $visits = mysql_result($result, 0, "count");
        return $visits;
    }
    function show_visits_today() { //
        $sql_today = sprintf("SELECT COUNT(*) AS count FROM %s WHERE visit_date = NOW()", $this->table_name);
        if ($this->report_by_host) $sql_today .= " AND hostname = '".$_SERVER['HTTP_HOST']."'";
        $res_today = mysql_query($sql_today);
        $today = mysql_result($res_today, 0, "count");
        return $today;
    }
    function show_max_visited_day() {
        $sql_max = "SELECT COUNT(*) AS count FROM ".$this->table_name;
        if ($this->report_by_host) $sql_max .= " WHERE hostname = '".$_SERVER['HTTP_HOST']."'";
        $sql_max .= " GROUP BY visit_date ORDER BY count DESC";
        $res_max = mysql_query($sql_max);
        return mysql_result($res_max, 0, "count");
    }
    function average_visits_day($month, $year) {
        $data = $this->results_by_day($month, $year);
        $totals = array_sum($data);
        $days = count($data);
        $avg = ceil($totals / $days);
        return $avg;
    }
    function first_last_visit($type = "last") {
        $order_dir = ($type == "last") ? "DESC" : "ASC";
        $sql = "SELECT visit_date, time FROM ".$this->table_name;
        if ($this->report_by_host) $sql .= " WHERE hostname = '".$_SERVER['HTTP_HOST']."'";
        $sql .= " ORDER BY visit_date ".$order_dir." LIMIT 0,1";
        $result = mysql_query($sql);
        $first_last = mysql_result($result, 0, "visit_date");
        $first_last .= " ".mysql_result($result, 0, "time");
        return $first_last;
    }
    function results_by_day($res_month, $res_year) {
        $sql = sprintf("SELECT DAYOFMONTH(visit_date) AS visit_day, COUNT(*) AS visits_count FROM %s WHERE MONTH(visit_date) = %s AND YEAR(visit_date) = %s", $this->table_name, $res_month, $res_year);
        if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'";
        $sql .= " GROUP BY visit_date";
        $result = mysql_query($sql);
        $visits_daily = array();
        while ($obj = mysql_fetch_object($result)) {
            $visits_daily[$obj->visit_day] = $obj->visits_count;
        }
        return $visits_daily;
    }
    function get_data_array($what, $limit = 0) {
        $is_year = false;
        switch ($what) {
            case "monthly":
            $is_year = true;
            $sql = sprintf("SELECT MONTH(visit_date) AS variable, YEAR(visit_date) AS month_year, COUNT(*) AS value FROM %s
            WHERE UNIX_TIMESTAMP(visit_date) >= %d ", $this->table_name, mktime(0, 0, 0, date("m"), 1, date("Y")-1));
            if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'";
            $sql .= " GROUP BY MONTH(visit_date), YEAR(visit_date) ORDER BY MONTH(visit_date)";
            break;
            case "country":
            $sql = sprintf("SELECT ip_country.country AS variable, COUNT(*) AS value FROM %s AS tbl LEFT JOIN %s AS ip_country ON ip_country.code = tbl.country WHERE tbl.country <> ''", $this->table_name, IP_COUNTRY_TABLE);
            if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'";
            $sql .= " GROUP BY tbl.country ORDER BY 2 DESC LIMIT 0, ".$limit;
            break;
            case "referer":
            $sql = sprintf("SELECT COUNT(*) AS value, TRIM(LEADING 'www.' FROM SUBSTRING_INDEX(TRIM(LEADING 'http://' FROM referer), '/', 1)) AS variable FROM %s WHERE referer <> '' AND referer NOT LIKE '%s%%'", $this->table_name, ltrim($_SERVER['HTTP_HOST'], "www."));
            if ($this->report_by_host) $sql .= " AND hostname = '".$_SERVER['HTTP_HOST']."'";
            $sql .= " GROUP BY variable ORDER BY value DESC LIMIT 0, ".$limit;
            break;
        }
        $result = mysql_query($sql);
        $data = array();
        while ($obj = mysql_fetch_object($result)) {
            $data[$obj->variable] = $obj->value;
        }
        mysql_free_result($result);
        return $data;
    }
    function get_days($from_month, $from_year) {
        $last_day = date("t", mktime(0,0,0,$from_month,1,$from_year));
        $day_count = 1;
        while ($day_count <= $last_day) {
            $days_array[] = $day_count;
            $day_count++;
        }
        return $days_array;
    }
    function create_date($month2, $year2) {
        $date_str = date("M y", mktime(0, 0, 0, $month2, 1, $year2));
        return $date_str;
    }
    function month_last_year() {
        $i = 0;
        $curr_year = date("Y");
        $curr_month = date("n");
        while ($i < 12) {
            $time_val = mktime(0,0,0,$curr_month,15,$curr_year);
            $twelve_month[] = date("n", $time_val)."|".date("Y", $time_val);
            $curr_month = $curr_month-1;
            $curr_year = ($curr_month == 12) ? $curr_year-1 : $curr_year;
            $i++;
        }
        return $twelve_month;
    }
    function build_rows_totals($array_labels, $array_values, $is_date = false) {
        $all_values = array_sum($array_values);
        $row = "";
        foreach($array_labels as $key) {
            if ($is_date) {
                $parts = explode("|", $key);
                $key = $parts[0];
                $year = $parts[1];
            }
            if (isset($array_values[$key])) {
                $row .= "  <tr class=data>\n";
                $row .= "      <th>".$key."</th>\n";
                if ($is_date) $row .= "    <td>".$this->average_visits_day($key, $year)."</td>\n";
                $width = ($array_values[$key]*100)/$all_values;
                $row .= "      <td><img src=\"".IMG."\" width=\"".round($width*3, 0)."\" height=\"10\"></td>\n";
                $row .= "      <td width=45>".$array_values[$key]."</td>\n";
                $row .= "  </tr>\n";
            }
        }
        return $row;
    }
    function stats_totals() {
        $month_array = $this->month_last_year();
        krsort($month_array);
        reset($month_array);
        $all_visits_month = $this->get_data_array("monthly");
        $total_tbl = "<h2>Visits last ".count($all_visits_month)." month</h2>\n";
        $total_tbl .= "<table class=big cellspacing=0 cellpadding=0>\n";
        $total_tbl .= "  <tr class=subheader>\n";
        $total_tbl .= "    <th>Month</th>\n";
        $total_tbl .= "    <th class=right>Daily average</th>\n";
        $total_tbl .= "    <th>&nbsp;</th>\n";
        $total_tbl .= "    <th class=right>Visits</th>\n";
        $total_tbl .= "  </tr>\n";
        $total_tbl .= $this->build_rows_totals($month_array, $all_visits_month, true);
        $total_tbl .= "</table>\n";
        return $total_tbl;
    }
    function stats_country($limit = 10) {
        $country_visits = $this->get_data_array("country", $limit);
        $country_array = array_keys($country_visits);
        $country_tbl = "<h2>Visits by country (Top ".count($country_array).")</h2>\n";
        $country_tbl .= "<table class=big cellspacing=0 cellpadding=0>\n";
        $country_tbl .= "  <tr class=subheader>\n";
        $country_tbl .= "    <th>Country</th>\n";
        $country_tbl .= "    <th>&nbsp;</th>\n";
        $country_tbl .= "    <th class=right>Visits</th>\n";
        $country_tbl .= "    </tr>\n";
        $country_tbl .= $this->build_rows_totals($country_array, $country_visits);
        $country_tbl .= "</table>\n";
        return $country_tbl;
    }
    function stats_top_referer($limit = 15) {
        $referer_domains = $this->get_data_array("referer", $limit);
        $domain_array = array_keys($referer_domains);
        $refer_tbl = "<h2>Visits by Referer (Top ".count($domain_array).")</h2>\n";
        $refer_tbl .= "<table class=big cellspacing=0 cellpadding=0>\n";
        $refer_tbl .= "  <tr class=subheader>\n";
        $refer_tbl .= "    <th>Referer domain</th>\n";
        $refer_tbl .= "    <th>&nbsp;</th>\n";
        $refer_tbl .= "    <th class=right>Visits</th>\n";
        $refer_tbl .= "  </tr>\n";
        $refer_tbl .= $this->build_rows_totals($domain_array, $referer_domains);
        $refer_tbl .= "</table>\n";
        return $refer_tbl;
    }
    function stats_monthly($month, $year, $max_height = 200) {
        $my_visits = $this->results_by_day($month, $year);
        $total_visits = array_sum($my_visits);
        $month_tbl = "<h2>Visits in ".$this->create_date($month, $year)." (total: ".$total_visits.")</h2>\n";
        $month_tbl .= "<table class=big cellspacing=0 cellpadding=0>\n";
        $month_tbl .= "  <tr class=subheader>\n";
        foreach($this->get_days($month, $year) as $day) {
            $month_tbl .= "    <td>".$day."</td>\n";
        }
        $month_tbl .= "  </tr>\n";
        $month_tbl .= "  <tr height=\"".$max_height."\">\n";
        foreach($this->get_days($month, $year) as $day) {
            if (isset($my_visits[$day])) {
                $height = ($my_visits[$day]*100)/$total_visits*2;
                $month_tbl .= "    <td align=\"center\" valign=\"bottom\"><img src=\"".IMG."\" width=\"10\" height=\"".round($height, 0)."\"></td>\n";
            } else {
                $month_tbl .= "    <td>&nbsp;</td>\n";
            }
        }
        $month_tbl .= "  </tr>\n";
        $month_tbl .= "  <tr class=data>\n";
        foreach($this->get_days($month, $year) as $day) {
            if (isset($my_visits[$day])) {
                $month_tbl .= "    <th>".$my_visits[$day]."</th>\n";
            } else {
                $month_tbl .= "    <th>&nbsp;</th>\n";
            }
        }
        $month_tbl .= "  </tr>\n";
        $month_tbl .= "</table>\n";
        return $month_tbl;
    }
}
?>