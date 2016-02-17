<?php
//******************************************************************************
// pages register2.inc.php                                  Martel, May 30, 2006
//
// This page can only be reached using the POST method. (eg forms)
//******************************************************************************
include_once("inc/functions/register.php");

function include_register2_text()
{
    $arrRegister               = $_POST['register'];
    $arrRegister['login']      = addslashes(strip_tags(trim($arrRegister['login'])));
    $arrRegister['realname']   = addslashes(strip_tags(trim($arrRegister['realname'])));
    $arrRegister['country']    = addslashes(strip_tags(trim($arrRegister['country'])));
    $arrRegister['password']   = trim($arrRegister['password']);
    $arrRegister['verify']     = trim($arrRegister['verify']);
    $arrRegister['email']      = addslashes(strip_tags(trim($arrRegister['email'])));
    $arrRegister['tribe']      = addslashes(str_replace("'", "", strip_tags(trim($arrRegister['tribe']))));
    $arrRegister['alias']      = addslashes(strip_tags(trim($arrRegister['alias'])));
    $arrRegister['race']       = addslashes(strip_tags(trim($arrRegister['race'])));
    $arrRegister['bootcamp']   = addslashes(strip_tags(trim($arrRegister['bootcamp'])));
    $arrRegister['scriptstop'] = intval(str_replace(" ", "", $arrRegister['scriptstop']));

    echo '<div id="textMedium"><h2>Registration Report</h2><p>';
    $strDivReport = '';

    //==========================================================================
    // Verify that all fields are filled in
    //==========================================================================
    $cont = 'yes';

    if (!$arrRegister['login']) {
        $strDivReport .= "You need to fill in the login name.<br />";
        $cont = "no";
    }

    if (!$arrRegister['realname']){
        $strDivReport .= "You need to supply your real name.<br />";
        $cont = "no";
    }

    if (!$arrRegister['country']){
        $strDivReport .= "You need to supply your countries name.<br />";
        $cont = "no";
    }

    if (!$arrRegister['password']){
        $strDivReport .= "You need to fill in the password field.<br />";
        $cont = "no";
    }

    if (!$arrRegister['verify']){
        $strDivReport .= "You need to fill in the verify field.<br />";
        $cont = "no";
    }

    if ($arrRegister['password'] != $arrRegister['verify']) {
        $strDivReport .= "Your passwords do not match<br />";
        $cont = "no";
    }

    if (!$arrRegister['tribe']){
        $strDivReport .= "You need to name your tribe.<br />";
        $cont = "no";
    }

    if (!$arrRegister['alias']){
        $strDivReport .= "You need to give yourself a name.<br />";
        $cont = "no";
    }

    if (!$arrRegister['email']){
        $strDivReport .= "You need to enter a valid email address<br />";
        $cont = "no";
    }

    if (!$arrRegister['race']){
        $strDivReport .= "You must choose a race.<br />";
        $cont = "no";
    }

    if (!$arrRegister['CoC']){
        $strDivReport .= "You need to agree to the code of conduct to play ORKFiA.<br />";
        $cont = "no";
    }

    //==========================================================================
    // Something was missing
    //==========================================================================
    if ($cont == "no")
    {
        // Vay: changed "Complete" to "Completed"
        $strDivReport .= "<br /><br />";
        $strDivReport .= "Your registration could not be completed.<br /><br />";
        $strDivReport .= "Fill in all of the required fields and try again.<br /><br />";
        $strDivReport .= "<a href=\"javascript: history.go(-1)\">Back</a>";
        $strDivReport .= "</p></div>";

        echo $strDivReport;
        include_main_down();
        exit;
    }

    //==========================================================================
    // Captcha test (fill in numbers shown in a "picture")
    //==========================================================================
    $ip     = $_SERVER['REMOTE_ADDR'];
    $long   = ip2long($ip);
    $check  = mysql_query("SELECT * FROM reg_check WHERE id = {$arrRegister['reg_check']} AND ip = $long");
    $check  = @mysql_fetch_array($check);
    if ($arrRegister['scriptstop'] != $check['reg_value'])
    {
        $strDivReport .= "You need to fill in the solution to the formula into the box " .
             "below it.</p><p>If you repeatedly get this message you should go " .
             "back using your browser and try to hit 'refresh', that might " .
             "save your entered information.</p><p>If this do not work then please " .
             "close your browser and try sign up again. Thanks!</p><p>";
        $strDivReport .= "<a href=\"javascript: history.go(-1)\">Back</a>";
        $strDivReport .= "</p></div>";

        echo $strDivReport;
        include_main_down();
        exit;
    }
    mysql_query("DELETE FROM reg_check WHERE id = $arrRegister[reg_check]");

    //==========================================================================
    // Catch mass-registrations
    //==========================================================================
    if ($_SERVER['SERVER_NAME'] != 'development.orkfia.org')
    {
        $recent = date('YmdHis', strtotime('-4 hours'));
        $get    = mysql_query("SELECT * FROM gamestats WHERE signup_ip = '$arrRegister[ip]' AND signup_time > $recent");
        $tribe  = array();
        while ($arrtribe = mysql_fetch_array ($get, MYSQL_ASSOC))
        {
            $tribe[$arrtribe["id"]] = $arrtribe;
        }

        $counter = 0;
        $found = '';
        foreach($tribe as $strKey => $value)
        {
            $grab = mysql_query("select * from user where id = $value[id]");
            $grab = @mysql_fetch_array($grab);
            if($grab['username'])
            {
                $found = 'yes';
            }
            $counter++;
        }

        if($counter > 10)
        {
            $strDivReport .= "Too many people from this IP have signed up in the last 4 " .
            "hours, you will have to wait to join up.";
            $strDivReport .= "</p></div>";

            echo $strDivReport;
            include_main_down();
            exit;
        }
    } //devork
    else
    {
        $found = '';
    }

    //==========================================================================
    // Check email. (Same is used in preferences.inc.php)   Martel, May 31, 2006
    //==========================================================================
    if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
        '@'.
        '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
        '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $arrRegister['email']))
    {
        $strDivReport .= "You need to enter a valid email address!<br />";
        $strDivReport .= "</p></div>";

        echo $strDivReport;
        include_main_down();
        exit;
    }

    //==========================================================================
    // Set activation code
    //==========================================================================
    $arrRegister['code'] = "";
    if ($_SERVER['SERVER_NAME'] != 'development.orkfia.org')
    {
        for($i = 0; $i < 12; $i++)
        {
            $arrRegister['code'] .= substr("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", rand(0,61), 1);
        }
    }
    else
    {
        $arrRegister['code'] = 'verified';
    }

    //==========================================================================
    // Check race
    //==========================================================================
    $cont = check_race($arrRegister['race'], $cont);

    // Verifies alliance creation & join friends input and secures it
    $cont = fmat_kingdom($arrRegister, $cont);

    //==========================================================================
    // User ID (+1)
    // Updated to use gamestats table, which records all tribes ever created
    //                                                            - AI 07/10/06
    // (It only does for SUCCESSFUL signups, meaning errors may slip trough!)
    // New code - safe with blank DB and works correct  February 12, 2008 Martel
    //==========================================================================
    $strSql = "SELECT players FROM gamestats WHERE id = 1";
    if ($arrRes  = mysql_fetch_array(mysql_query($strSql)))
        $iNewUid = $arrRes['players'] + 1;
    elseif (mysql_num_rows(mysql_query("SELECT * FROM user")) > 0)
    {
        $iNewUid = mysql_query("SELECT id FROM user SORT BY id DESC LIMIT 1");
        $iNewUid = mysql_fetch_row($iNewUid);
        $iNewUid = $iNewUid[0];
    }
    else
        $iNewUid = 1; // The welcome-msg tribe

    //==========================================================================
    // Alliance ID
    //==========================================================================
    switch($arrRegister['alliance_type'])
    {
        case "new":

            // frost: changed below to 3 to enable free spot for APC alli
            // 5618: changed to 11, for various staff(-related) allis
            // Martel: added a more flexible upper limit that we can change easier
            $iNewAid = check_empty_alliances();

            if ($iNewAid == 0)
            {
                $strDivReport .= "There is no room for another alliance inside Orkfia, try to " .
                     "join a random instead.<br /><br />";

                $cont = 'no';
            }

        break;
        case "existing":

            $iNewAid = $arrRegister['ex_id'];

        break;
        case "random":

            $iNewAid = '';

        break;
        default:

            $strDivReport .= "Your selected alliance type is not supported. <br /><br />";
            $iNewAid = '';
            $cont = "no";
    }

    //==========================================================================
    // Avoid duplicate entries in the DB
    //==========================================================================
    $cont = check_taken($arrRegister, $cont);

    //==========================================================================
    // Something was missing (second check)
    //==========================================================================
    if ($cont == "no")
    {
        // Vay: changed "Complete" to "Completed"
        $strDivReport .= "<br /><br />";
        $strDivReport .= "Your registration could not be completed.<br /><br />";
        $strDivReport .= "Fill in all of the required fields and try again.<br /><br />";
        $strDivReport .= "<a href=\"javascript: history.go(-1)\">Back</a>";
        $strDivReport .= "</p></div>";

        echo $strDivReport;
        include_main_down();
        exit;
    }

    //==========================================================================
    // Proceed with registration
    //==========================================================================
    $timestamp = date('YmdHis');

    // Alliance placement (and/or alliance creation)
    $alliance_num = alliance_placement($iNewAid, $arrRegister);

    // Game stats: last registered userid
    update_registration($iNewUid);

    // Create user tables
    make_user_data($iNewUid, md5("t1r1p0d4"."$arrRegister[login]"), $arrRegister);
    make_army_data($iNewUid, $arrRegister['race']);
    if ($arrRegister['race'] == "Oleg Hai" || $arrRegister['race'] == "Mori Hai")
        make_army_mercs_data($iNewUid);
    make_build_data($iNewUid, $arrRegister['race']);
    make_goods_data($iNewUid, $arrRegister['race']);
    make_kills_data($iNewUid);
    make_milreturn_data($iNewUid);
    make_preferences_data($iNewUid, $arrRegister['code'], $arrRegister['email']);
    make_spell_data($iNewUid, $arrRegister['race']);
    make_pop_data($iNewUid, $arrRegister['race']);
    make_stats_data($iNewUid, $alliance_num, $arrRegister);
    make_thievery_data($iNewUid);
    make_online_data($iNewUid,$timestamp);
    make_news_data($iNewUid, $alliance_num, $arrRegister);
    make_ranking_data($iNewUid, $alliance_num, $arrRegister);
    make_mail_data($iNewUid, $arrRegister);
    make_design($iNewUid);

    // Activation email sent to all new players
    mail("$arrRegister[email]","ORKFiA Verification","Welcome to ORKFiA =) \n\nHere is your verification code: $arrRegister[code] \nUsername: $arrRegister[login] \nPassword: $arrRegister[password]\n" . HOST . "\n\nIt is recommended you tend to your tribe at least once per day. If you require help or this is your first time playing ORKFiA, you may find the forums and the guide useful. \n\nWe hope you enjoy your stay in ORKFiA =)" . SIGNED_ORKFIA, "From: ORKFiA <" . EMAIL_REGISTRATION . ">\r\nX-Mailer: PHP/" . phpversion() . "\r\nX-Priority: Normal");

    // Catch mass registrations notice
    if ($found == 'yes')
    {
        $strDivReport .= "<br /><br />";
        $strDivReport .=
            "The Law & Order Resort (found in alliance #2) has been notified " .
            "of this account's creation and will investigate it within the " .
            "near future." .
            "<br /><br />" .
            "An account was recorded as being created recently from this IP, " .
            "If you have more than one account you must delete all extras, " .
            "one account is the maxium each player can control. If you do " .
            "not you will lose all your accounts. If you have not created " .
            "more than one account it is likely you are on a proxy IP and " .
            "you should use the \"report sharing computers\" function found " .
            "in-game stating this." .
            "<br /><br />" .
            "If you have friends or family that play from your computer you " .
            "should also contact the staff in alliance #2 stating the situation.";
        $strDivReport .= "</p>";

        // Check if the report thread exists, otherwise create it
        $search = mysql_query("Select * from forum where poster_kd = 2 and parent_id  = 0 and title = 'To be investigated' and type = 0");
        $search = @mysql_fetch_array($search);
        if ($search['poster_kd'] != 2)
        {
            $insert = mysql_query("INSERT INTO forum VALUES ('', '0', '0','2', '0', 'To be investigated','Automated report thread', '$timestamp', '$timestamp','Reporter', 'Reporter','0', '0', '0', '0')") or die("insert:" . mysql_error());
        }

        // Find the report thread's id
        $search = mysql_query("Select * from forum where poster_kd = 2 and parent_id  = 0 and title = 'To be investigated' and type = 0");
        $search = @mysql_fetch_array($search);

        // Create post
        $strPost =  'Account creation warning, more than one user from ' . $arrRegister['ip'] . ' has created in the last 4 hours.' .
                    '<br /><br />' .
                    'Id: ' . $iNewUid .
                    '<br /><br />' .
                    'Username: ' . $arrRegister['login'] .
                    '<br /><br />' .
                    'Tribe name: ' . $arrRegister['tribe'] .
                    '<br /><br />' .
                    'Alliance: ' . $alliance_num .
                    '<br /><br />';

        // Post it
        mysql_query("INSERT INTO forum (poster_id,type,poster_kd,parent_id,post,date_time,updated,poster_name,poster_tribe,level) VALUES (0, 0, 2, $search[post_id], '$strPost', '$timestamp', '$timestamp', '<span class=\"player\">Reporter</span>', 'Reporter', 0)") or die('mysql error: ' . mysql_error());
        mysql_query("UPDATE forum SET updated = '$timestamp' WHERE post_id = $search[post_id]") or die('mysql error: ' . mysql_error());
        mysql_query("UPDATE user,stats SET allianceforum = allianceforum + 1 WHERE user.id = stats.id AND kingdom = 2") or die('mysql error: ' . mysql_error());
    }

    // Add ip number and signup date to the gamestats table
    mysql_query("INSERT INTO gamestats (id, signup_time, signup_ip) VALUES ($iNewUid, '$timestamp', '$arrRegister[ip]')");

    echo $strDivReport;

    include_once('inc/functions/forums.php');
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME && get_sponsor_badge($iNewUid) == '')
    {
?>

    <hr />
    <h2>Classic ORKFiA Sponsorship</h2>
    <hr />

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="center">
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="business" value="orkfia.paypal@phpsupport.se" />
        <input type="hidden" name="undefined_quantity" value="1" />
        <input type="hidden" name="item_name" value="Three Months Sponsorship" />
        <input type="hidden" name="item_number" value="Classic Dragon" />
        <input type="hidden" name="amount" value="42.00" />
        <input type="hidden" name="shipping" value="0.00" />
        <input type="hidden" name="no_shipping" value="1" />
        <input type="hidden" name="return" value="<?= HOST;?>main.php?cat=main&amp;page=sponsors&amp;thankyou" />
        <input type="hidden" name="cn" value="Message to admin" />
        <input type="hidden" name="currency_code" value="USD" />
        <input type="hidden" name="tax" value="0.00" />
        <input type="hidden" name="lc" value="SE" />
        <input type="hidden" name="bn" value="PP-BuyNowBF" />
        <table class="small" cellspacing="0" cellpadding="0">
            <tr class="header">
                <th colspan="3">Become a Classic Dragon</th>
            </tr>
            <tr class="data">
                <th>Rank 2:</th>
                <td><span class="elder">Classic Dragon</span></td>
                <td rowspan="3"><img src="<?=HOST_PICS;?>dragon_classic.gif" alt="Dragon" /></td>
            </tr>
            <tr class="data">
                <th>Donation:</th>
                <td>$42 / 3 months</td>
            </tr>
            <tr class="data">
                <th><input type="hidden" name="on0" value="Login nick" /><label for="i1">Login nick:</label></th>
                <td><input type="text" name="os0" id="i1" maxlength="60" value="<?php stripslashes($arrRegister[LOGIN]); ?>" /></td>
            </tr>
        </table>
        <input type="submit" name="submit" value="Sponsor ORKFiA for 3 months" />
    </form>
    <p class="center">This dragon will soar next to your name in the forums
    for 2 normal ages (1 age is 6 weeks). You could also consider
    <a href="main.php?cat=game&amp;page=sponsors">the other dragons</a>
    for a shorter period of time. The blue dragon is only <strong>$1 / week</strong> and you
    still help us out =) Thank you!</p>

<?php
    }
    elseif ($_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME && get_sponsor_badge($iNewUid) == '')
    {
        include_once('inc/pages/sponsors.inc.php');
        echo show_sponsor_options(stripslashes($arrRegister['login']));
    }
    elseif (($strBadge = get_sponsor_badge($iNewUid)) == '')
    {
        echo '<hr />' .
             '<h2>Thank you for supporting ORKFiA!</h2>' .
             '</hr />' .
             '<div class="center">' . $strBadge . '</div>';
    }

    echo "</p></div>";
}

?>
