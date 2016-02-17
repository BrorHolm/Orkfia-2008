<?
function call_view_market_text(){
	global $go, $alli_id,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($alli_id && $go =="yes") {
		   $result = @mysql_query("SELECT * FROM market_log WHERE alliance='$alli_id' ");
		   $arrSrcMarket = @mysql_fetch_array($result);

		   $result = @mysql_query("SELECT * FROM market_log WHERE alliance='$alli_id' ORDER BY time DESC");
		?>
		  <table cellspacing=0 cellpadding=0 class="border" width=650>
		  <tr><td colspan=7 align=center class="pd bold black dark bdown">The Market Log</td></tr>
		  <tr>
		  <td class="pd black bold darker bdown" align=center>Time</td>
		  <td class="pd black bold darker bdown" >Tribe</td>
		  <td class="pd black bold darker bdown" align=right>Action</td>
		  <td class="pd black bold darker bdown" align=right>Money</td>
		  <td class="pd black bold darker bdown" align=right>Food</td>
		  <td class="pd black bold darker bdown" align=right>Wood</td>
		  <td class="pd black bold darker bdown" align=right>Soldiers</td>
		  </tr>
		<?
		  while($arrSrcMarket = @mysql_fetch_array($result)){
				ECHO "<tr>";
				ECHO "<td class=\"blue pd bold\" align=center>$arrSrcMarket[time]</td>";
				ECHO "<td class=\"pd\">$arrSrcMarket[tribe]</td>";
				ECHO "<td class=\"pd\" align=right>".$arrSrcMarket[type]."</td>";
				ECHO "<td class=\"pd\" align=right>".number_format($arrSrcMarket[money])."</td>";
				ECHO "<td class=\"pd\" align=right>".number_format($arrSrcMarket[food])."</td>";
				ECHO "<td class=\"pd\" align=right>".number_format($arrSrcMarket[wood])."</td>";
				ECHO "<td class=\"pd\" align=right>".number_format($arrSrcMarket[unit1])."</td>";
				ECHO "</tr>";

		   }


		  ECHO "</table>";    
	}

	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input alliance id:<input type=text size=4 name=alli_id>";
    ECHO "<input type=hidden name=go value=yes>";
    ECHO "<br><input type=submit value=\"show market history\"></form>";
	
}
?>