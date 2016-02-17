<?php
//******************************************************************************
// functions config.php                                 Martel November 02, 2007
//
// Details:
// Configuration file for orkfia. These have global scope.
//******************************************************************************
//==============================================================================
// SERVER CONSTANTS
//==============================================================================
define('DEV_SERVER_NAME', 'devork.phpsupport.se');
define('DINAH_SERVER_NAME', 'www.orkfiaclassic.com');

//==============================================================================
// GAME CONFIGURATION CONSTANTS
//==============================================================================
if (stristr($_SERVER['SERVER_NAME'], DEV_SERVER_NAME)) // fixes issue "www." etc
{
    define('HOST', 'http://' . DEV_SERVER_NAME . '/');
    // fixes issue "www." etc
    if ($_SERVER['SERVER_NAME'] != DEV_SERVER_NAME)
        header('Location: ' . HOST);

    define('HOST_GUIDE', 'http://orkfia.phpsupport.se/guide/');
    define("PROTECTION_HOURS", 8);
    define('STARTING_LAND', 400);
    define("MAX_ALLIANCE_SIZE", 20);
    define("MAX_ALLIANCES", 4);
    define('EMAIL_REPORTER', 'devork.reporter@phpsupport.se');
    define('EMAIL_REGISTRATION', 'devork.registration@phpsupport.se');
    define('SERVER_TAGLINE', 'Development Server');
}
elseif (stristr($_SERVER['SERVER_NAME'], DINAH_SERVER_NAME) || stristr($_SERVER['SERVER_NAME'], 'dinah.phpsupport.se'))
{
    define('HOST', 'http://' . DINAH_SERVER_NAME . '/');
    // fixes issue "www." etc
    if ($_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME)
        header('Location: ' . HOST);

    define('HOST_GUIDE', 'http://orkfia.phpsupport.se/guide/');
    define("PROTECTION_HOURS", 48);
    define('STARTING_LAND', 250);
    define("MAX_ALLIANCE_SIZE", 12);
    define("MAX_ALLIANCES", 60);
    define('EMAIL_REPORTER', 'reporter@orkfiaclassic.com');
    define('EMAIL_REGISTRATION', 'registration@orkfiaclassic.com');
    define('SERVER_TAGLINE', 'Classic Battlegrounds');
}
else
{
    define('HOST', 'http://orkfia.phpsupport.se/');
    // fixes issue "www." etc
    if ($_SERVER['SERVER_NAME'] != 'orkfia.phpsupport.se')
        header('Location: ' . HOST);

    define('HOST_GUIDE', 'http://orkfia.phpsupport.se/guide/');
    define("PROTECTION_HOURS", 8);
    define('STARTING_LAND', 400);
    define("MAX_ALLIANCE_SIZE", 12);
    define("MAX_ALLIANCES", 100);
    define('EMAIL_REPORTER', 'orkfia.reporter@phpsupport.se');
    define('EMAIL_REGISTRATION', 'orkfia.registration@phpsupport.se');
    define('SERVER_TAGLINE', 'Alliances At War');
}
define('HOST_PICS', HOST . 'pics/');
define('ORKFIA_COPYRIGHT', "© 2001-" . date('Y') . " ORKFiA");
define('SIGNED_ORKFIA', "\n\n~ The ORKFiA Staff Team\n\n___\nThis email is " .
                              "php generated, you cannot successfully reply.");

//==============================================================================
// Define some 'magic numbers', these can be changed at will
//  and will affect the game somewhere         - AI 30/10/06
//==============================================================================

define("TIMESTAMP_FORMAT", 'YmdHis');
define("MAX_SPELL_CASTS", 20);
define("MAX_THIEVE_OPS", 20);
define("FORUM_MAX_PAGES",5);
define("FORUM_POSTS_PER_PAGE",30);

?>