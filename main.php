<?php
//******************************************************************************
// Heart of orkfia: main.php                              Martel, March 03, 2006
//
// Description: Global container for all orkfia pages, functions, everything.
//******************************************************************************
include_once('inc/functions/config.php');

//==============================================================================
// Error reporting on development vs. live game                           Martel
//==============================================================================
if ($_SERVER['SERVER_NAME'] == DEV_SERVER_NAME)
{
    // To have all errors reported
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', TRUE);
}
else
{
    // To have none of the errors reported
    ini_set('error_reporting', E_ERROR);
    ini_set('display_errors', FALSE);
}

//==============================================================================
// Force Register globals = off (overrides the php.ini setting)           Martel
//==============================================================================
/*
if (ini_get('register_globals'))
{
    $unset = array();
    $unset = array_merge(array_keys($_GET), array_keys($_POST),
                                                          array_keys($_COOKIE));
    $unset = array_unique($unset);
    foreach($unset as $rg_var)
    {
       unset($$rg_var);
    }
}
*/

//==============================================================================
// Check that $cat and $page are declared                  Martel, July 17, 2006
//==============================================================================
$cat  = 'main';
$page = 'main';

if (isset($_GET['cat']) && !empty($_GET['cat']))
{
    $cat  = strval($_GET['cat']);

    if (isset($_GET['page']) && !empty($_GET['page']))
    {
        $page = strval($_GET['page']);
    }
}

//==============================================================================
// Disable Caching In-game                                 Martel, July 18, 2006
//==============================================================================
if ($cat == 'game')
{
    // ignore 'STOP' button
    ignore_user_abort(TRUE);

    header("Cache-Control: must-revalidate");
    $offset = 60 * 60 * 24 * -1;
    $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
    header($ExpStr);
}

//==============================================================================
// 4/11/2003 6:09PM - Natix
// Compression test, might save up some latency on the server.
// ----------------------------------------------------------------------------
// since CPU is the bottleneck, gzipping stuff on the way out seems like a bad
//  idea, removed the output handler, but the buffer itself we want to keep for
//  sessions                                                       - AI 30/09/06
// Adding gzip for the cat=main pages to minimize files there.. saves 2kb / hit
//                                                      Martel, October 14, 2007
//==============================================================================
if ($cat == 'main')
    ob_start('ob_gzhandler');
else
    ob_start();
ob_implicit_flush(0);

//==============================================================================
// Check for malicious input
//==============================================================================

// IIS doesnt support REQUEST_URI
$_SERVER['REQUEST_URI'] = (isset($_SERVER['REQUEST_URI']) ?
                             $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);

// Append the query string if it exists and isn't null
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
{
   $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
}

// IIS end

$query = $_SERVER['REQUEST_URI'];
if (strstr($query, 'register[') || strstr($query, 'userid') ||
    strstr($query, 'username')  || strstr($query, 'password=') )
{
    ECHO 'DO NOT TRY TO FEED VARIABLES TO THE GAME, YOUR USERNAME HAS BEEN ' .
         'LOGGED AND YOU WILL LIKELY BE BANNED IF IT OCCURS MORE THAN ONCE!';

    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    if ($page == 'build2' || $page =='invade2'    || $page == 'army2' ||
        $page == 'login2' || $page == 'thievery2' || $page == 'register2' ||
        $page == 'explore2')
    {
        include_once('inc/pages/logout.inc.php');
        include_logout_text();
    }
}
else
{
    //==========================================================================
    // March 09, 2006 - Martel
    // TEST. Should filter all user-input sent by (any, incl xss) forms.
    //==========================================================================
    require('inc/classes/clsInputFilter.php');
    $strictFilter = new InputFilter();
    $clean_POST = &$_POST;
    $clean_POST = $strictFilter->process($clean_POST);
}

//==============================================================================
// Connect to the Database
//==============================================================================
include_once('inc/on_load.php');
connectdb();

//==============================================================================
// Load up things that should be available on a global scope everywhere
//==============================================================================
include_once('inc/functions/constants.php');
include_once('inc/functions/generic.php');
include_once('inc/classes/clsUser.php');
include_once('inc/classes/clsAlliance.php');

//==============================================================================
// Time stamps (Used here and/or globally elsewhere)
//==============================================================================
$timestamp = date(TIMESTAMP_FORMAT);
$orkTime   = $timestamp;
$timeNow   = $timestamp;
$arrDate   = getdate();

//==============================================================================
// Frost: Global time counter for the game.
// Functionality: Game hours only update if someone logs on once each hour.
//==============================================================================
include_once('inc/classes/clsGame.php');
$objGame     = new clsGame();
$arrGameTime = $objGame->get_game_times();
if ($arrGameTime[CURRENT_HOUR] != $arrDate['hours'])
{
    if ($objGame->get_game_switch(GLOBAL_PAUSE) != ON)
    {
        $arrGameTime[HOUR_COUNTER]++;
        mysql_query('UPDATE admin_global_time SET hour_counter = ' .
                    $arrGameTime[HOUR_COUNTER]);
    }
    mysql_query("UPDATE admin_global_time SET current_hour = $arrDate[hours]");

    //==========================================================================
    // Frost: auto event handler
    //==========================================================================
    $arrEvents = mysql_query('SELECT * FROM auto_event WHERE execution_hour ' .
                             '<= ' . $arrGameTime[HOUR_COUNTER]);

    $iEvents   = count($arrEvents) + 1;
    for ($i = 0; $i < $iEvents; $i++)
    {
        $arrEvent = mysql_fetch_array($arrEvents);

        // Run Event
        mysql_query($arrEvent['query']);

        // Delete Event
        mysql_query('DELETE FROM auto_event WHERE event_id = ' .
                    $arrEvent['event_id']);
    }

    //==========================================================================
    // Martel: Military expedition handler (atm only used for monitoring).
    // This will decrease every expedition with one hour, eventually deleting
    // them.
    // Intended functionality: Through this you can move an army outside the
    // tribe borders, so for example you could use it to defend a tribe for x
    // hours and after that have it moved back with X hour return time.
    // ATM it will just act as a way to allow multiple people to monitor one
    // tribe.
    //==========================================================================
    mysql_query('UPDATE military_expeditions SET duration_hours = ' .
                'duration_hours - 1');
    mysql_query('DELETE FROM military_expeditions WHERE duration_hours = 0');
}

//==============================================================================
// Layout output variable declarations
//==============================================================================
include_once('inc/layout/layout.php');

$page_up   = 'include_' . $cat . '_up';
$page_down = 'include_' . $cat . '_down';
$page_text = 'include_' . $page . '_text';
include_page_layout();

//==============================================================================
// Martel: In-game things
//==============================================================================
if ($cat == 'game')
{
    unset($userid);

    // Verify That the UserId Cookie Exists, otherwise the user has to log in
    if (isset($_COOKIE['userid']) && isset($_COOKIE['check']))
    {
        $userid   = $_COOKIE['userid'];
        $checkmd5 = $_COOKIE['check'];
    }
    else
    {
        $page = 'session_expired';
        $page_up();

        echo $strDiv =
        '<div id="textMedium"><p>' .
            'You are not logged in, please either ' .
            '<a href="main.php?cat=main&amp;page=main">login to an existing ' .
            'account</a> or <a href="main.php?cat=main&amp;page=register1">' .
            'sign up for a new account</a>.' .
        '</p></div>';

        include_game_down();
        exit;
    }

    //==========================================================================
    // Instantiate Objects
    //==========================================================================
    // User
    $GLOBALS['objSrcUser'] = new clsUser ($userid);
    $objSrcUser  = &$GLOBALS['objSrcUser'];
    $arrSrcUsers = $objSrcUser->get_user_infos();
    $arrSrcStats = $objSrcUser->get_stats();

    // Game
    include_once('inc/classes/clsGame.php');
    $objGame = new clsGame();

    //==========================================================================
    // frost: For stable switching, change number to 6 for admin-only access
    //==========================================================================
    $strLoginStopper = $objGame->get_game_switch(LOGIN_STOPPER);
    if ($strLoginStopper == 'on' && (isset($arrSrcStats[LEVEL]) && $arrSrcStats[LEVEL] < 5))
    {
        $page = 'session_expired';
        $page_up();

        echo $strDiv =
        '<div id="textMedium"><p>' .
            'Currently, you can not login. This may be due to a game change ' .
            'or a technical problem.' .
            '</p><p>' .
            'Please visit the game forum for information.' .
            '</p><p>' .
            '~ The ORKFiA Staff Team' .
        '</p></div>';

        include_game_down();
        exit;
    }

    //==========================================================================
    // Martel: Safety check, I would assume this prevents false cookie values
    //==========================================================================
    $md5me  = 't1r1p0d4' . $arrSrcUsers[USERNAME];
    $md5sum = md5($md5me);
    if ($md5sum != $checkmd5)
    {
        $page = 'session_expired';
        $page_up();

        echo $strDiv =
        '<div id="textMedium"><p>' .
            'You are not logged in, please either ' .
            '<a href="main.php?cat=main&amp;page=main">login</a> or ' .
            '<a href="main.php?cat=main&amp;page=register1">create an account' .
            '</a>.' .
        '</p></div>';

        include_game_down();
        exit;
    }

    //==========================================================================
    // Martel: Accounts in alliance #0 have been (will be) deleted
    //==========================================================================
    if ($arrSrcStats[ALLIANCE] == 0)
    {
        $page = 'session_expired';
        $page_up();

        echo $strDiv =
            '<div id="textMedium"><p>' .
                'Your account is deleted.' .
                '</p><p>' .
                'Possible reasons for deletion could be because you owned ' .
                'multiple tribes, you were being abusive or you broke the ' .
                'rules in some other manner. If you can correct your ways you '.
                'are invited back, else we wish you well and say goodbye.' .
            '</p></div>';

        include_game_down();
        exit;
    }

    //==========================================================================
    // Save now (for each page hit) as the last time being online
    //==========================================================================
    $objSrcUser->set_online(TIME, $timestamp);

    //==========================================================================
    // Forced Page Redirections
    // Information: Pages here will show even if the user try visit other places
    //==========================================================================
    $strVerificationCode = $objSrcUser->get_preference(EMAIL_ACTIVATION);
    // 4 hour Sessions
    $fourHoursAgo = date('Y-m-d H:i:s', strtotime('-4 hours'));

    // Let's see if we should force a visit someplace...
    if ($page == 'logout')
    {
    }
    elseif ($arrSrcUsers[LAST_LOGIN] < $fourHoursAgo && $REQUEST_METHOD != 'POST')
    {
        // Session Expired (Added request method check so noone is logged out
        // while doing an attack or similiar)
        $page = 'session_expired';
        $page_text = 'include_' . $page . '_text';
    }
    elseif ($strVerificationCode != 'verified' && $arrSrcUsers[LOGINS] >= 3)
    {
        // Authentication of email
        $page = 'verify';
        $page_text = 'include_' . $page . '_text';
    }
    elseif (($arrSrcStats[RESET_OPTION] == 'yes' || $arrSrcStats[KILLED] == 1)
            && $page != 'forums')
    {
        // Account is to be reset
        $page = 'reset_account';
        $page_text = 'include_' . $page . '_text';
    }
    elseif ($arrSrcUsers[STATUS] == 2)
    {
        // Force users to visit the tribe page at login
        $page = 'motd';
        $page_text = 'include_' . $page . '_text';
    }

    // Seed The Random Value That will be used Throughout the pages
    mt_srand((double)microtime() * 1000000);

    // Check if tribe has updates
    include_once('inc/functions/update.php');
    check_to_update($userid);
}
elseif ($cat == 'main' && $page == 'main')
{
    //==========================================================================
    // Visitor logging on the front page                Martel, October 05, 2006
    //==========================================================================
    include("inc/classes/count_visitors_class.php");

    // create a new instance of the count_visitors class.
    $my_visitors = new Count_visitors;

    $my_visitors->delay = 720; // how often (in hours) a visitor is registered
    $my_visitors->insert_new_visit(); // That's all
}

//==============================================================================
// Initiate the upper part of main page / ingame layout
//==============================================================================
$page_up();

//==============================================================================
// Check if the requested file exist
//==============================================================================
if (! file_exists('inc/pages/' . $page . '.inc.php'))
{
    include_once('inc/pages/logout.inc.php');
    include_logout_text();
}
else
{
    include_once('inc/pages/' . $page . '.inc.php');
}

//==============================================================================
// Show content and bottom of page
//==============================================================================
$page_text();
$page_down();

//==============================================================================
// stop execution of script on 'STOP'
// Do we really care? it's not like anything else will be executed - AI 30/09/06
//==============================================================================
/*
if ($cat == 'game')
{
    ignore_user_abort(FALSE);
}
*/

?>
