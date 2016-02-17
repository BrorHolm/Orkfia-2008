<?
function call_checkmail_text()
{
    global $id, $op, $mid, $set, $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
    ECHO "Input user id: <input name=id size=5><br>";
    ECHO "<input type=submit value=Check Mail name=op>";
    ECHO "</form>";
    IF($op && $id){
       IF(!$set){ 
		   $set = "view"; 
	   }
	   $result = mysql_query ("SELECT * from messages WHERE (for_user ='$id' OR from_user='$id') AND action = 'received' ORDER BY date DESC") or die(mysql_error());
	   $num_mail = mysql_num_rows($result);
	   IF($num_mail <= "0"){
		   ECHO "There is no mail in this inbox.<br>";
		   include_game_down();
		   exit;
	   }
	   IF($set == "view"){
		  ECHO "<table width=\"85%\" cellpadding=0 cellspacing=0 class='border'>";
		  ECHO "<tr><td class='dark black bold pd bdown'>Subject</td><td class='dark black bold pd bdown'>From</td><td class='dark black bold pd bdown'>To</td><td class='dark black bold pd bdown'>Date</td><td class='dark black bold pd bdown'>Status</td></tr>";
		  while ($mail =(mysql_fetch_array($result))){
			 $name = mysql_query("SELECT tribe,kingdom FROM stats WHERE id = $mail[from_user]");
		     $name = mysql_fetch_array($name);
		     $name2 = mysql_query("SELECT tribe,kingdom FROM stats WHERE id = $mail[for_user]");
		     $name2 = mysql_fetch_array($name2);ECHO "<tr><td class='pd'><a href=\"".$_SERVER['REQUEST_URI']."&amp;set=read&amp;mid=$mail[id]&amp;op=yes&amp;id=1\">$mail[subject]</a></td>";
			 ECHO "<td class='pd'>$name[tribe](#$name[kingdom])</td>";
			 ECHO "<td class='pd'>$name2[tribe](#$name2[kingdom])</td>";
			 ECHO "<td class='pd'>$mail[date]</td>";
			 ECHO "<td class='pd'>$mail[new]</td></tr>";
			 ECHO "<tr><th height='5' colspan='5'><hr size = 0></th></tr>";
 		  } 
		  ECHO "</table>";
		}
		IF($set == "read"){
		   $result = mysql_query ("SELECT * from messages WHERE id = '$mid'");
		   $read =mysql_fetch_array($result);
		   $name = mysql_query("SELECT name,kingdom FROM stats WHERE id = $read[from_user]");
		   $name = mysql_fetch_array($name);
		   $name2 = mysql_query("SELECT name,kingdom FROM stats WHERE id = $read[for_user]");
		   $name2 = mysql_fetch_array($name2);
		   $read[subject] = stripslashes(stripslashes($read[subject]));
		   $read[text] = stripslashes(stripslashes($read[text]));
		   ECHO "<table width=65% cellpadding=0 cellspacing=0 class='border'>";
		   ECHO "<tr><td class=\"dark black bold pd bdown\">Message from: $name[name](#$name[kingdom])</td></tr>";
		   ECHO "<tr><td class=\"dark black bold pd bdown\">Message to: $name2[name](#$name2[kingdom])</td></tr>";
		   ECHO "<tr><td><br>Subject: $read[subject]<br><br><br>$read[text]</td></tr>";
		   ECHO "</table><br>";
		   
		}
    }
}
?>
