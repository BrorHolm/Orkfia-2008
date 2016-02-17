<?php
//==============================================================================
// Martel: May 13, 2005. Big makeover.
// Reason for all functions is to easily change order of fields and remove/add
// stuff
// History:
// May 12, 2007: Recoded to remove globals-dependancy and add safer code. Martel
//==============================================================================
include_once('inc/functions/register.php');

function include_register1_text()
{
    $strHost      = $GLOBALS['Host'];
    $strCopyright = $GLOBALS['OrkfiaCopyright'];

    include_once('inc/classes/clsGame.php');
    $objGame           = new clsGame();
    $strTribeSwitch    = $objGame->get_game_switch(TRIBE_CREATION);
    $strAllianceSwitch = $objGame->get_game_switch(ALLIANCE_CREATION);
    $strBootcampSwitch = $objGame->get_game_switch(BOOTCAMP_SIGNUPS);

    $link = "main.php?cat=main&amp;page=register1&amp;alliance_type=";

    if (! isset($_GET['alliance_type']))
    {
        //======================================================================
        // Signup selection (first part) of registration page
        //======================================================================
?>
    <div id="text" style="margin: 15px auto; width: 600px;">

        <h2 style="margin: 15px auto; text-align: center;"><img src="<?= $strHost; ?>first_sign_up.gif" alt="ORKFiA Sign Up!" /></h2>

        <h3>Join an Alliance</h3>

        <p>If you have the password of an existing alliance you may join in by
        selecting <em>Join Friends</em>, otherwise <em>Join Random</em> is the
        recommended choice. You can merge any time later if you wish to move
        into another alliance.</p>

<?php
        if ($strTribeSwitch == ON)
        {
            echo "<p>| <a href=\"".$link."random\">Join Random</a> ";
            echo "| <a href=\"".$link."existing\">Join Friends</a> |</p>";
        }
        else
        {
            echo "<p><em class=\"positive\">The option to join an alliance has temporarily been disabled.</em></p>";
        }
?>

        <h3>Join a Bootcamp</h3>

        <p>Bootcamp leaders will assist you here, answering questions and
        teaching you how to master the game. If you wish to lead or instruct a
        boot camp, contact staff in alliance (#3).</p>
<?php

        // M: For some reason this stopped working with the new MySQL server
//         $strSQL = "SELECT kingdom, COUNT(*) as num_players FROM stats GROUP BY kingdom HAVING kingdom IN(SELECT id as kingdom FROM kingdom WHERE bootcamp = 'yes') ORDER BY kingdom DESC";
//         $resSQL = mysql_query($strSQL);
        $iOpenBootcamps = 0;
        $iSpotsOpen = 0;
        $strSQL = "SELECT id FROM " . TBL_ALLIANCE . " WHERE bootcamp = 'yes' ORDER BY id DESC";
        $resSQL = mysql_query($strSQL);
        while ($arrRES = mysql_fetch_array($resSQL))
        {
            $strSQL = "SELECT COUNT(*), " . ALLIANCE . " FROM stats WHERE " .
                      ALLIANCE . " = {$arrRES[ID]} GROUP BY " . ALLIANCE .
                      " ORDER BY " . ALLIANCE . " DESC";
            $resSQL2 = mysql_query($strSQL);
            $arrRES2 = mysql_fetch_row($resSQL2);

            if ($arrRES2[0] < MAX_ALLIANCE_SIZE)
            {
                $iOpenBootcamps++;
                $iLowestAlliNr = $arrRES2[1];
                $iSpotsOpen = MAX_ALLIANCE_SIZE - $arrRES2[0];
            }
        }


//         $iOpenBootcamps = 0;
//         while ($arrRES = mysql_fetch_array($resSQL))
//         {
//             if ($arrRES['num_players'] < MAX_ALLIANCE_SIZE)
//             {
//                 $iOpenBootcamps++;
//                 $iLowestAlliNr = $arrRES['kingdom'];
//                 $iSpotsOpen = MAX_ALLIANCE_SIZE - $arrRES['num_players'];
//             }
//         }

        if ($strBootcampSwitch == OFF)
        {
?>
        <p><em class="positive">The option to join a bootcamp has temporarily been disabled.</em></p>
<?php
        }
        elseif ($iOpenBootcamps == 0)
        {
?>
        <p><em class="positive">Sorry, currently there are no bootcamps available.</em></p>
<?php
        }
        else
        {
?>
        <p>| <a href="<?=$link;?>random&amp;bootcamp=yes">Join Bootcamp</a> | <em>Bootcamp (#<?=$iLowestAlliNr; ?>) accepts <?=$iSpotsOpen; ?> more tribes</em></p>
<?php
        }
?>
        <h3>Create an Alliance</h3>

        <p>Recommended if you plan on joining with more than 5 other players as
        the world of Orkfia is a very competitive place. Small alliances tend
        not to lead to greatness.</p>

        <p>

<?php

        $iEmptyAllianceId = check_empty_alliances();

        if ($strAllianceSwitch == ON && $strTribeSwitch == ON && $iEmptyAllianceId > 0)
        {
            echo "| <a href=\"".$link."new\">Create an Alliance</a> |" .
                 " <em>Alliance (#$iEmptyAllianceId) is available</em>";
        }
        elseif ($iEmptyAllianceId == 0)
        {
            echo '<em class="positive">Sorry, the option to create an alliance is currently unavailable. (Maximum of ' . MAX_ALLIANCES . ' alliances reached)</em>';
        }
        else
        {
            echo '<em class="positive">Alliance creation has temporarily been disabled.</em>';
        }
?>

        </p>
    </div>

    <div class="center" style="font-size: 0.8em"><?= $strCopyright; ?></div>

<?php
    }
    else
    {
        //======================================================================
        // Form fields of registration page
        //======================================================================
?>

    <div id="text" style="margin: 15px auto; width: 600px;">
        <h2 style="margin: 15px auto; text-align: center;"><img src="<? echo $strHost; ?>first_sign_up.gif" alt="ORKFiA Sign Up!" /></h2>

<?php
        if (isset($_GET['bootcamp']) && $_GET['bootcamp'] == 'yes')
            $bootcamp = $_GET['bootcamp'];
        else
            $bootcamp = 'no';

        // Validate alliance type input on creation
        $iEmptyAllianceId = check_empty_alliances();
        $strAllianceType = strval($_GET['alliance_type']);
        if ($strAllianceType == 'new' && ($strAllianceSwitch == OFF || $iEmptyAllianceId == 0 || $bootcamp == 'yes'))
            $strAllianceType = 'random';
        form_head($strAllianceType);
        switch ($strAllianceType)
        {
            case "random":

                echo "<input type=\"hidden\" value=\"$bootcamp\" name=\"register[bootcamp]\" />";

            break;
            case "existing":

                form_join_friends(); // join existing

            break;
            case "new":

                form_create_alliance(); // create alliance

            break;
        }

        form_login_info();
        form_tribe_info();
        form_agreement();
        form_random();
        form_post();
?>

        </div>

<?php
    }
}

// Martel: Top of form
function form_head($strAllianceType)
{
    echo "<form method=\"post\" action=\"main.php?cat=main&amp;page=register2&amp;alliance_type=$strAllianceType\">";
    echo "<input type=\"hidden\" name=\"register[alliance_type]\" value=\"$strAllianceType\" />";
}

// Martel: Join existing alliance
function form_join_friends()
{
    echo "<div class=\"row\">";
    echo "<strong>Join Friends</strong>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Alliance Number:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[ex_id]\"></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Alliance Password:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[ex_pass]\"></span>";
    echo "</div>";
}

// Martel: Create Alliance
function form_create_alliance()
{
    echo "<div class=\"row\">";
    echo "<strong>Create New Alliance</strong>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Alliance Name:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[kingdom_name]\"></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Alliance Password:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[kingdom_pass]\"></span>";
    echo "</div>";
}
// Martel: Username, Password, E-mail
function form_login_info()
{
    echo "<div class=\"row\">";
    echo "<strong>Account Information</strong>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Full Name:</span>";
    echo "<span class=\"formw\"><input maxlength=\"30\" name=\"register[realname]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">E-mail:</span>";
    echo "<span class=\"formw\"><input maxlength=\"40\" name=\"register[email]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"formw\"><font size=\"-2\">A valid e-mail is required.</font></span>";
    echo "</div>";

    // Martel: Never ask for country again
    echo "<input name=\"register[country]\" type=\"hidden\" value=\"" . get_country() . "\" />";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Login Name:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[login]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Password:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" type=\"password\" name=\"register[password]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Confirm Password:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" type=\"password\" name=\"register[verify]\" /></span>";
    echo "</div>";
}

// Martel: Leader Name, Tribe Name, Race
function form_tribe_info()
{
    echo "<div class=\"row\">";
    echo "<strong>Tribe Information</strong>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Tribe Name:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[tribe]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Leader Name:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[alias]\" /></span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Choose Race:</span>";
    echo "<span class=\"formw\">";
    echo "<select size=\"1\" name=\"register[race]\">";

    // AI: do this dynamically using getActiveRaces()
    require_once('inc/races/clsRace.php');
    foreach(clsRace::getActiveRaces() as $raceName)
    {
        echo "<option value='$raceName'>$raceName</option>";
    }

    echo "</select> ";
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
        echo "<a href=\"" . HOST_GUIDE . "races2.php?chapter=4\" class=\"newWindowLink\" target =\"_blank\" style=\"cursor: help\">Guide</a></span>";
    else
        echo "<a href=\"" . HOST_GUIDE . "races.php?chapter=4\" class=\"newWindowLink\" target =\"_blank\" style=\"cursor: help\">Guide</a></span>";
    echo "</div>";
}

function form_agreement()
{
    echo "<div class=\"row\">";
    echo "<strong>Agreement</strong>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "Before you create this account you must agree to the <a href=\"main.php?cat=main&amp;page=CoC\" class=\"newWindowLink\" target =\"_blank\">Code of Conduct</a>.<br /> Make sure you have read through it and are aware of the policies which <br />are enforced in the game.<br />";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "You also agree that by creating your account, all details you have<br /> supplied are correct, persons who create accounts with false <br />information may be removed from ORKFiA.<br />";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<label>I agree to both: <input type=\"checkbox\" name=\"register[CoC]\" /></label>";
    echo "</div>";
}

// Martel: Random Number
function form_random()
{
    echo "<div class=\"row\">";
    echo "<strong>Security Check</strong>";
    echo "</div>";

    // this should be alot simpler - AI
    // seed it, incase we have an old version which doesn't automatically do that
    $time = explode(' ',microtime());
    mt_srand($time[1]);
    $num1 = mt_rand(100,999);
    $num2 = mt_rand(10,100);
    $numbers = $num1 * $num2;
    $infixes = array('*', 'times', 'multiplied by', 'x');
    $infix = $infixes[mt_rand(0, count($infixes)-1)];
    $question = "$num1 $infix $num2";
    $randomness = mt_rand(1,99999); //to prevent caching the image
    $ip = $_SERVER["REMOTE_ADDR"];
    $long = ip2long($ip);
    mysql_query("INSERT INTO reg_check (reg_value, ip) VALUES ($numbers, $long)") or die(mysql_error());

    $signup_check = mysql_query("Select id from reg_check where reg_value = $numbers AND ip = $long");
    $signup_check = mysql_fetch_array($signup_check);

    echo "<input type=\"hidden\" name=\"register[ip]\" value=\"$ip\" />";
    echo "<input type=\"hidden\" name=\"register[reg_check]\" value=\"$signup_check[id]\" />";

    echo "<div class=\"row\">";
    //echo "<span class=\"formw indicator\">",$update,"</span><br />";
    // since we don't have GD, images aren't going to work...
    //echo "<img src='captcha.php?id={$signup_check['id']}&amp;info=$randomness'/>";
    echo "<span class='formw indicator'>Solve this equation: $question</span><br />";
    echo "<span class='formw indicator'>(<a href='http://www.google.com/search?q=$question' target='_blank'>google it</a> if you can't figure it out)</span>";
    echo "</div>";

    echo "<div class=\"row\">";
    echo "<span class=\"label\">Enter the solution:</span>";
    echo "<span class=\"formw\"><input maxlength=\"20\" name=\"register[scriptstop]\" /></span>";
    echo "</div>";
}

// Martel: Post Button
function form_post()
{
    echo "<div class=\"row\">";
    echo "<input type=\"submit\" value=\"Sign Up Now!\" />";
    echo "</div>";

    echo "</form><br />";
}

//==============================================================================
// Taken this function from the count_visitor class      Martel, August 21, 2007
//==============================================================================
function get_country()
{
    if (!defined('IP_TABLE'))
        define('IP_TABLE', 'ip2nation');

    if (!defined('IP_COUNTRY_TABLE'))
        define('IP_COUNTRY_TABLE', 'ip2nationCountries');

    $country_sql = sprintf("SELECT country FROM %s WHERE ip < INET_ATON('%s') ORDER BY ip DESC LIMIT 0,1", IP_TABLE, $_SERVER['REMOTE_ADDR']);
    $country_res = mysql_query($country_sql);
    $country = mysql_result($country_res, 0, "country");

    if (empty($country))
        return "xxx"; // anything will do.. will check this again somewhere else
    else
        return $country;
}
?>




















