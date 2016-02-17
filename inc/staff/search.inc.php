<?
function call_search_text(){
    global $z,$submit,$tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	IF($z){
       $resulta = mysql_query("SELECT id FROM user");
       $query = " SELECT U.username, ";
       $query .= "       S.id, ";
       $query .= "       S.tribe, ";
       $query .= "       S.name, ";
       $query .=  "       S.kingdom ";
       $query .= "FROM  user AS U, stats AS S ";
       $query .= "WHERE U.id = S.id ";
       IF($submit[username]){
           $query .= "AND U.username='$submit[username]' ";
       }
       IF($submit[tribe]){
           $query .= "AND S.tribe='$submit[tribe]' ";
       }
       IF($submit[alliance]){
           $query .= "AND S.kingdom='$submit[alliance]' ";
       }
       IF($submit[name]){
           $query .= "AND S.name='$submit[name]' ";
       }
       $result = mysql_query($query);
       $result2 =mysql_query($query);
       $num  = mysql_num_rows($result);
       IF($num < 0){
             ECHO "<br><br><a href=?p=search>Search Again</a>";
             include_game_down();
			 exit;
       }
       $find =mysql_fetch_array($result);
       ECHO "RESULTS: $num               <br>";
       ECHO "<table class=\"border\" width=400 cellpadding=0 cellspacing=0>";
       ECHO "<TD class=\"padding bold black dark bdown\"><b>ID</b></td><td class=\"padding bold black dark bdown \">Tribe</td>";
       while($find = mysql_fetch_array($result2)){
           ECHO "<TR>";
           ECHO "<TD class=\"blue padding bold \"><b>$find[id]</b></td><td class=\"padding bold \">$find[tribe] (# $find[kingdom])</td>";
           ECHO "</tr>";
       }
       ECHO "</table>";
       ECHO "<br><br><a href=main.php?cat=game&page=mod&p=search>Search Again</a>";
      include_game_down();
      exit;
    }

   ECHO "<h4>Search</h4>";
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."&z=1\">";
   ECHO "<table class=\"border\" width=400 cellpadding=0 cellspacing=0>";

   ECHO "<tr><td class=\"padding bold black dark bdown\" align=center>Type</td>";
   ECHO "<td class=\"padding bold black dark bdown\" align=center>Name</td></tr>";

   ECHO "<tr><td class=\"padding bold \" align=center>Tribe</td>";
   ECHO "<td class=\"padding bold\" align=center><input name=submit[tribe]></td></tr>";

   ECHO "<tr><td class=\"padding bold \" align=center>Alliance</td>";
   ECHO "<td class=\"padding bold \" align=center><input name=submit[alliance]></td></tr>";

   ECHO "<tr><td class=\"padding bold \" align=center>Alias</td>";
   ECHO "<td class=\"padding bold \" align=center><input name=submit[name]></td></tr>";

   ECHO "<tr><td class=\"padding bold \" align=center>Username</td>";
   ECHO "<td class=\"padding bold \" align=center><input name=submit[username]></td></tr>";

   ECHO "<tr><td class=\"padding bold\" align=center colspan=2><input type=submit value='Find Players ID'></td></tr>";

   ECHO "</form>";

   ECHO "</table>";
}
?>
