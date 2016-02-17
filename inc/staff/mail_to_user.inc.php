<?

function call_mail_to_user_text(){
    global $tool;
	
	include_once('inc/functions/resort_tools.php');
	if (! user_has_access($tool))
	{
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
	$id = $_POST['id'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	$subject = $_POST['subject'];
	IF ($subject && $message && $email) {
		mail("$email","$subject","$message \n\n\nThis email is php generated, you cannot successfully reply." , "From: admins@orkfia.org");
        ECHO "<b><i>sent</b></i><br>";
	} else if($subject && $message && $id) {
		$seek = mysql_query("Select * from preferences where id = $id");
		$seek = mysql_fetch_array($seek);
		mail("$seek[email]","$subject","$message \n\n\nThis email is php generated, you cannot successfully reply." , "From: admins@orkfia.org");
        ECHO "<b><i>sent to $seek[email]</b></i><br>";
	}

	ECHO "<br><br><b>Only 1 address or id at a time....</b><br> Resort heads, this is not overly for your use, but in emergencies or when you really need to mail a user, you can use this, instead of your actual email addys, it does go without saying, if there is any abuse at all, it will no longer be availible.<br><br>";
	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">";
	ECHO "Email: <input name=email size=40> OR ID: <input name=id size=5>";
	ECHO "<br><br>Subject: <input name=subject size=40><br>";
	ECHO "<br>Message: <br>     <textarea rows=7 cols=50 wrap=on name=message></textarea><br>";
	ECHO "<input type=submit value=Send name=confirm>";
    ECHO "</form>";
}
?>