<?php
include_once('inc/functions/forums.php');
require_once('inc/functions/mail.php');

//============================================================================
//  Remodeling the reporting system, use a Sweets-designed click-through
// system that asks finally asks you what to report.          - AI 12/01/2006
//============================================================================


function include_message_text()
{
    //========================================================================
    // Note that if someone presses the report button, $tribe has not been
    //  set, I'll fix that for now, but the line below does not only rely
    //  on that, but also on register_globals being on, UGLY!   - AI 22/10/06
    //========================================================================
    global $Host, $tribe, $type, $userid, $action, $submit, $alliance, $message,
           $inputBody, $subject, $orkTime, $connection, $report,
           $ip, $resortforum;

//     mysql_grab($userid, 'local', 'stats');
    $objSrcUser  = &$GLOBALS['objSrcUser'];
    $reporttype = @$_GET['reporttype'];
    $arrStats = $objSrcUser->get_stats();

    if ($alliance < 11 && $reporttype != 'personal' && !$submit)
    {
        $strMenu =
            '<div class="center">' .
            "| <a href=\"main.php?cat=game&amp;page=mail&amp;set=compose\">Compose Mail</a> " .
            "| <a href=\"main.php?cat=game&amp;page=mail&amp;set=view\">View Inbox</a> " .
            "| <a href=\"main.php?cat=game&amp;page=mail&amp;set=outbox\">View Outbox</a> " .
            "| <a href=\"main.php?cat=game&amp;page=message&amp;tribe=1&amp;alliance=1\" >Send a Report</a> " .
            "| <a href=\"main.php?cat=game&amp;page=mail&amp;set=block\">Block Mail</a> ";

        if($arrStats['type'] == 'elder')
        {
             "| <a href=\"main.php?cat=game&amp;page=mail&amp;set=eldermail\" >Alliance Mail</a> ";
        }
        $strMenu .= "|</div><br />";

        echo $strMenu;

        echo "<div id=\"textBig\"><h2>Send a report</h2>";

        // new stuff starting here
        switch($reporttype){
            case 'cheatident':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Identity Cheating</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatident\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatcont':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Content</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatcont\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Paste the complete offensive messages/other things below<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatphys':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Physical Cheating</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatphys\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatcoop':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Cooperation</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatcoop\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheataccount':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Account Cheating</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheataccount\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatabuse':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Abuse</h3>";
                echo "**Please don't report bugs here, but rather use this as a report to alert us to any player you suspect abusing a bug.**<br />";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatabuse\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<br /><textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatwar':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report War Cheating</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatwar\"><p>List the alliance(s) you wish to report<br /><textarea name=\"allis\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatfarm':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Farming</h3>";
                echo "***Landfarmers must be reported. Any acres gained from a little or undefended tribe may be expropriated in the absence of a report.***<br />";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatfarm\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Paste your attack details here<br /><textarea name=\"attack\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheatmisc':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=$alliance\">Back to report cheating page</a> ::</p>";
                echo "<h3>Report Miscellaneous Cheating</h3>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatmisc\"><p>List the tribe name(s) and alliance(s) you wish to report<br /><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br />Describe the offence to be investigated plus all applicable information<textarea name=\"offence\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></p></form>";
                break;
            case 'cheating':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Cheating</h3>";
                echo "<p>Please select the type of cheating you wish to report:</p>";
                echo "<ul>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatident&amp;alliance=2\">Identities (stealing)</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatcont&amp;alliance=2\">Content (PMs, forum)</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatphys&amp;alliance=2\">Physical</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatcoop&amp;alliance=2\">Cooperation</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheataccount&amp;alliance=2\">Account (crosslogging, multiple, babysitting)</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatabuse&amp;alliance=2\">Abuse</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatwar&amp;alliance=2\">War</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatfarm&amp;alliance=2\">Farming</a></li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheatmisc&amp;alliance=2\">Miscellaneous</a></li></ul>";
                break;
            case 'sharing':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Sharing Computers</h3>";
                echo "<p>Your tribe name and alliance number: {$arrStats['tribe']}(#{$arrStats['kingdom']})</p>";
                echo "<p>List the tribe name(s) and alliance(s) you share IP's with: <br /></p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=sharing\"><textarea name=\"tribes\" rows=\"5\" cols=\"20\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Computer Sharing Report\" /></form>";
                break;
            case 'cf':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Cease Fire</h3>";
                echo "<p>A reported temporary stoppage of aggressive activity, where both alliances agree to suspend all ops and attacks for a MAXIMUM of 12 hours following an undeclared war - (or 24 hours in the case of declared war)</p><p>A 12 hour 'cool down' period is permitted following an unofficial war. To be valid these agreements must be reported to L&amp;O with associated times. L&amp;O is not responsible for policing any CF agreements.</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=cf\"><p>Your alliance number: {$arrStats['kingdom']}<br />CeaseFire with (Alliance number): <input type=\"text\" name=\"with\" size=\"4\" maxlength=\"4\" /><br /><input type=\"submit\" name=\"submit\" value=\"Send Cease Fire Report\" /></p></form>";
                break;
            case 'error':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Error</h3>";
                echo "<p>Please explain the error and also send all applicable information</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=error\"><textarea name=\"error\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Error Report\" /></form>";
                break;
            case 'sugg':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Game Suggestion</h3>";
                echo "<p>Please fully explain your suggestion</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=sugg\"><textarea name=\"suggestion\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Suggestion\" /></form>";
                break;
            case 'comp':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Complaint</h3>";
                echo "<p>Please fully explain the problem</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=comp\"><textarea name=\"complaint\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Complaint\" /></form>";
                break;
            case 'mergename':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Report Merge/Namechange issues</h3>";
                echo "<p>Please fully explain the problem</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=mergename\"><textarea name=\"message\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></form>";
                break;
//             case 'advertsugg':
//                 echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
//                 echo "<h2>Report Advertising Suggestion</h2>";
//                 echo "<p>Please fully explain your suggestion</p>";
//                 echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=advertsugg\"><textarea name=\"suggestion\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Suggestion\" /></form>";
//                 break;
//             case '4crap':
//                 echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
//                 echo "<h2>Send crap to #4</h2>";
//                 echo "<p>Please enter the crap you want to send to #4 here</p>";
//                 echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=4crap\"><textarea name=\"crap\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send crap\" /></form>";
//                 break;
            case 'qgen':
                echo "<p>:: <a href=\"main.php?cat=game&amp;page=message&amp;alliance=$alliance\">Back to reporting page</a> ::</p>";
                echo "<h3>Question / General</h3>";
                echo "<p><p><p>Enter your question below</p>";
                echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;reporttype=qgen\"><textarea name=\"question\" rows=\"10\" cols=\"60\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Send Report\" /></form>";
                break;
            default:
                echo "<p>Welcome! This will allow you to contact the ORKFiA Staff Team.</p>";
                echo "<ul><li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cheating&amp;alliance=2\">Report Cheating</a> (report to #2)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=sharing&amp;alliance=2\">Report Sharing Computers</a> (report to #2)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=cf&amp;alliance=2\">Report Cease Fire</a> (report to #2)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=error&amp;alliance=3\">Report Game Error</a> (report to #3)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=sugg&amp;alliance=3\">Report Game Suggestion</a> (report to #3)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=comp&amp;alliance=3\">Report Complaint</a> (report to #3)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=mergename&amp;alliance=3\">Report Merge/Namechange issues</a> (report to #3)</li>";
//                 echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=advertsugg&amp;alliance=4\">Report Advertising Suggestion</a> (report to #4)</li>";
//                 echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=4crap&amp;alliance=4\">Send crap to #4</a> (report to #4)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=message&amp;reporttype=qgen&amp;alliance=3\">Question / General</a> (report to #3)</li>";
                echo "<li><a href=\"main.php?cat=game&amp;page=mail&amp;set=compose&amp;aid=$alliance&amp;tribe=$tribe\">Orkfia Mail</a></li></ul>";
        }

        echo "</div>";

    }

    if ($type == "ingame" && $submit && $message)
    {
        //changed to use send_mail function - AI 10/12/2006
        send_mail($userid, $tribe, $subject, $message);
    }

    if ($type == "ingame" && $alliance > 10)
    {
        echo "<p>Message Center</p>";
        echo "<form method=\"post\" action=\"main.php?cat=game&amp;page=message&amp;type=ingame&amp;action=post&amp;tribe=$tribe&amp;alliance=$alliance\">";
        echo "<br />Subject: <input type=text name=subject size=30><br /><textarea name=message rows=20 cols=70 wrap=on></textarea><br />";
        echo "<input type=hidden name=submit value='yes'>";
        echo "<input type='submit' value='Send Message'>";
        echo "</form>";
    }

    if($submit && $reporttype)
    {
        $error = false;
        $alliance = 0;
        $resortforum = 0;
        $title = false;
        $post = false;

        switch($reporttype)
        {
            case 'cheatident':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Identity Cheating';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatcont':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Content';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatphys':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Physical Cheating';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatcoop':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Cooperation';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheataccount':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Account Cheating';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatabuse':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Abuse';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatwar':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: War Cheating';
                $post = "Reporting these alliances:\r\n" . $_POST['allis'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'cheatfarm':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Farming';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nDetails of the attack:\r\n" . $_POST['attack'];
                break;
            case 'cheatmisc':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Miscellaneous Cheating';
                $post = "Reporting these tribes:\r\n" . $_POST['tribes'] .
                        "\r\n\r\nAccusing them of the following:\r\n" . $_POST['offence'];
                break;
            case 'sharing':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: Sharing Computers';
                $post = "Declaring to share IP(s) with:\r\n" . $_POST['tribes'];
                break;
            case 'cf':
                $alliance = 2;
                $resortforum = 4;
                $title = 'Report: CeaseFire';
                $post = "Declaring a CeaseFire with: " . $_POST['with'];
                break;
            case 'error':
                $alliance = 3;
                $resortforum = 5;
                $title = 'Report: Game Error';
                $post = $_POST['error'];
                break;
            case 'sugg':
                $alliance = 3;
                $resortforum = 5;
                $title = 'Report: Game Suggestion';
                $post = $_POST['suggestion'];
                break;
            case 'comp':
                $alliance = 3;
                $resortforum = 5;
                $title = 'Report: Complaint';
                $post = $_POST['complaint'];
                break;
            case 'mergename':
                $alliance = 3;
                $resortforum = 5;
                $title = 'Report: Merge/Namechange issues';
                $post = $_POST['message'];
                break;
//             case 'advertsugg':
//                 $alliance = 4;
//                 $title = 'Report: Advertising Suggestion';
//                 $post = $_POST['suggestion'];
//                 break;
//             case '4crap':
//                 $alliance = 4;
//                 $title = 'Crap for #4';
//                 $post = $_POST['crap'];
//                 break;
            case 'qgen':
                $alliance = 3;
                $resortforum = 5;
                $title = 'Question / General';
                $post = $_POST['question'];
                break;
            case 'n00b':
                $alliance = 1;
                $resortforum = 2;
                $title = 'I am a n00b';
                $post = $_POST['n00bieness'];
                break;
            default:
                $error .= "The report was of a type that cannot be handled, " .
                          "you're either messing around or the report system " .
                          "isn't finished yet.<br />";
        }

        if($resortforum > 5 || $resortforum < 2)
        {
            $error .= "There was no valid recipient for your report, poke " .
                      "someone in Development.<br />";
        }

        if(!$error && $title && $resortforum && $post)
        {
            $post .= "\r\n\r\n***User id:" . $objSrcUser->get_userid() . "***\r\n" . $arrStats['tribe'] .'(#' . $arrStats['kingdom'] .')';
            $thread = mysql_query("SELECT post_id FROM forum WHERE poster_kd = 1 AND parent_id = 0 AND title = '$title' AND type = $resortforum") or die('mysql error: ' . mysql_error());
            if(mysql_num_rows($thread) == 0){
                mysql_query("INSERT INTO forum (type,poster_kd,title,post,date_time,updated,poster_name,poster_tribe) VALUES ($resortforum,1,'$title','Automated report thread','$orkTime','$orkTime','Reporter','Reporter')") or die('mysql error: ' . mysql_error());
                $thread = mysql_query("SELECT post_id FROM forum WHERE poster_kd = 1 AND parent_id = 0 AND title = '$title' AND type = $resortforum") or die('mysql error: ' . mysql_error());
            }
            $thread = mysql_fetch_assoc($thread);
            $thread = $thread['post_id'];
            make_post($objSrcUser->get_userid(),$thread,0,$resortforum,$post);
        }

        if($error)
        {
            echo "The following problem(s) was/were encountered while " .
                 "processing your report:<br />$error";
        }
        else
        {
            $staffmap = array(
                1 => "The Orkfian Gods / Development",
                2 => "Law and Order",
                3 => "Operations",
                4 => "Marketing"
                );
            echo "Thank you for your time, " . $staffmap[$alliance] . " has received your report.";
            echo "<br /><a href=\"main.php?cat=game&amp;page=message&amp;alliance=1\">Back to Reporting</a>";

        }
    }

    if ($submit && $type == "forums" && $resortforum < 11 && $alliance < 11 && $report)
    {
        $message = safeHTML($message);

        echo
            "<p>$report, has been received, If you have any more information " .
            "regarding <br />your report that can be entered, we would be " .
            "pleased to receive it also.</p>";

        if ($report == 'Report: Sharing Computers')
        {
            echo
                '<p>Please take special notice of the CoC rules applying ' .
                'specifically to sharing IPs. Violation of these rules ' .
                'results in account suspension and more commonly deletion. ' .
                'Ignornance of the law is no excuse.<br /><br />' .
                'Here is the link to the CoC:<br />' .
                '<a href="main.php?cat=game&amp;page=CoC">Code of Conduct</a>' .
                '<br /><br />' .
                'Section 6, and especially 6.5.1 apply to users sharing IPs.' .
                '<br /><br />' .
                'Enjoy the game =)';

            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', $resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
              VALUES ('', '$arrStats[id]', $resortforum,
              1, '$search[post_id]', '',
              '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
              '$arrStats[name]', '$arrStats[tribe]',
              '0', '$ip', '0', '0')
               ");
        }
        elseif ($report == 'Report: Cheating')
        {
            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', $resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
                          VALUES ('', '$arrStats[id]', $resortforum,
                          1, '$search[post_id]', '',
                          '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
                          '$arrStats[name]', '$arrStats[tribe]',
                          '0', '$ip', '0', '0')
                           ");
        }
        elseif ($report == 'Report: CeaseFire')
        {
            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', '$resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
                          VALUES ('', '$arrStats[id]', $resortforum,
                          1, '$search[post_id]', '',
                          '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
                          '$arrStats[name]', '$arrStats[tribe]',
                          '0', '$ip', '0', '0')
                           ");
        }
        elseif ($report == 'Report: Game Error')
        {
            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', '$resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
                          VALUES ('', '$arrStats[id]', $resortforum,
                          1, '$search[post_id]', '',
                          '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
                          '$arrStats[name]', '$arrStats[tribe]',
                          '0', '$ip', '0', '0')
                           ");
        }
        elseif ($report == 'Report: Game Suggestion')
        {
            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', '$resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
                          VALUES ('', '$arrStats[id]', $resortforum,
                          1, '$search[post_id]', '',
                          '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
                          '$arrStats[name]', '$arrStats[tribe]',
                          '0', '$ip', '0', '0')
                           ");
        }
        elseif ( $report == 'Personal Message' )
        {
            send_mail($userid,$tribe,"Personal Message from {$arrStats['tribe']}(#{$arrStats['kingdom']})",$message);
        }
        else
        {
            $search = mysql_query("select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = @mysql_fetch_array($search);
            if ($search['type'] != $resortforum) // There's no topic yet
                $insert = mysql_query("INSERT INTO forum VALUES ('', '0', '$resortforum, 1, '0', '$report','Automated report thread', '$orkTime', '$orkTime','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
            $search = mysql_query("Select * from forum where poster_kd = 1 and parent_id  = 0 and title = '$report' and type = $resortforum");
            $search = mysql_fetch_array($search);
            $insert = mysql_query("INSERT INTO forum
                          VALUES ('', '$arrStats[id]', $resortforum,
                              1, '$search[post_id]', '',
                              '$message<br /><br />***User id: $userid***<br />$arrStats[tribe] (# $arrStats[kingdom] )', '$orkTime', '$orkTime',
                              '$arrStats[name]', '$arrStats[tribe]',
                              '0', '$ip', '0', '0')
                               ");
        }

        if($report != 'Personal Message')
        {
            // M: Highlight forum users                    November 01, 2007
            $alliance = 1;
            notify_forum_users($objSrcUser, $resortforum);
        }
    }
}
?>
