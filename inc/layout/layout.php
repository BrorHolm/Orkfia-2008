<?php
//******************************************************************************
// layout functions layout.php                                            ORKFiA
//
// History:
// July 18, 2006 - Martel cleaned it up -again- but extensively this time
//******************************************************************************

//==============================================================================
// frost: declaration of some layout vars used in this file ... to make
// calibration easier
//==============================================================================
$OrkfiaCopyright = ORKFIA_COPYRIGHT;
$Host = HOST_PICS; // Use HOST_PICS for images and HOST in front of "main.php?"

//==============================================================================
//
//==============================================================================
function include_page_layout()
{
    global  $cat, $page, $strTitle;

    $strTitle = ucwords(eregi_replace("[1-2]", "", str_replace("_", " ", $page)));
    if ($cat == 'main')
        $strTitle = SERVER_TAGLINE;

?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="ORKFiA - <?=SERVER_TAGLINE;?> - is an excellent strategy PBBG in a fantasy setting. Play in alliances together with your friends, at work, in school or at home, and become the greatest leader of all." />
    <meta name="keywords" content="orkfia, online, strategy, game, multiplayer, browser, web, fantasy, MMORPG" />

    <link rel="shortcut icon" href="favicon.ico" />
    <title>ORKFiA | <?=$strTitle;?></title>
<?php

}

//==============================================================================
//
//==============================================================================
function include_main_up()
{
    global  $page, $strTitle;

    $strSkin = 'black_v2';
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
        $strSkin = 'ork_v2';

    $arrSlogans[] = "&#8220;No resets&#8212;Only death&#8221;";
    $arrSlogans[] = "&#8220;No resets&#8212;Only death&#8221;";
    $arrSlogans[] = "&#8220;No resets&#8212;Only death&#8221;";
    $arrSlogans[] = "&#8220;No resets&#8212;Only death&#8221;";
    $arrSlogans[] = "8 hours to prepare... 1000 hours to survive";
    $arrSlogans[] = "Explore new strategies... Defeat new alliances";
    $arrSlogans[] = "Magic, Thievery &amp; Military... Every path leads to death";
    $arrSlogans[] = "Team up with your enemies... and kill your friends";

?>
    <script language="JavaScript" type="text/javascript" src="orkfiaJS.php"></script>

    <link rel="stylesheet" type="text/css" media="all" href="css/<?=$strSkin;?>.css?version=1" />
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css?version=1" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/ie.css" /><![endif]-->

</head>
<body>
    <div id="container750center">

        <div id="header">
            <h1 style="margin: 0; padding: 0;"><img src="<?=HOST_PICS . 'orkfia_head_l.jpg'; ?>" alt="ORKFiA | <?=$strTitle;?>" /></h1>

            <ul id="gameStats" style="left: 232px;">
                <li style="color: #000;">Time: <span style="color: #000;" id="ClockTime"><?= date('H:i:s', time()); ?></span><br /><br /></li>
                <?php if ($_SERVER['SERVER_NAME'] != DINAH_SERVER_NAME) { ?><li><em style="color: #AAA; line-height: 3em; font-weight: normal;"><?php echo $arrSlogans[rand(0, count($arrSlogans)-1)]; ?></em></li><?php } ?>
            </ul>
        </div>

        <div id="startbar_l_shadow"></div>

        <div id="menu">
          <ul id="nav">
<?php
    if ($page != 'main')
    {
?>
            <li class="menu"><a href="main.php?cat=main&amp;page=main" class="header">Home</a></li>
<?php
    }
    elseif ($page == 'main')
    {
?>
            <li class="menu"><strong class="header">Home</strong></li>
<?php
    }

    if ($page != 'register1')
    {
?>
            <li class="menu"><a href="main.php?cat=main&amp;page=register1" class="header">Sign Up!</a></li>
<?php
    }
    elseif ($page == 'register1')
    {
?>
            <li class="menu"><strong class="header">Sign Up!</strong></li>
<?php
    }

    if ($page != 'faq')
    {
?>
            <li class="menu"> <a href="main.php?cat=main&amp;page=faq" class="header">FAQ</a></li>
<?php
    }
    elseif ($page == 'faq')
    {
?>
            <li class="menu"> <strong class="header">FAQ</strong></li>
<?php
    }

    if ($page != 'preview')
    {
?>
            <li class="menu"><a href="main.php?cat=main&amp;page=preview" class="header">Quick Tour</a></li>
<?php
    }
    elseif ($page == 'preview')
    {
?>
            <li class="menu"><strong class="header">Quick Tour</strong></li>
<?php
    }

    if ($page != 'forums')
    {
?>
            <li class="menu"><a href="main.php?cat=main&amp;page=forums" class="header">Forum</a></li>
<?php
    }
    elseif ($page == 'forums')
    {
?>
            <li class="menu"><strong class="header">Forum</strong></li>
<?php
    }
?>

            <li class="menu"><a href="<?=HOST_GUIDE;?>" target="_blank" class="header newWindowLink">Guide</a></li>
            <li class="options"><a href="main.php?cat=main&amp;page=sponsors" class="header">Sponsors</a></li>
          </ul>
        </div>

        <div id="upper_shadow"></div>

        <div id="portal">
<?php

} // end of include main up

//==============================================================================
//
//==============================================================================
function include_main_down()
{
    global $OrkfiaCopyright;
?>
        </div>

    <div id="lower_shadow"></div>

    <div id="footer">
         <a href="main.php?cat=main&amp;page=privacy" class="header">Privacy Policy</a>
    </div>

    <div id="copyright"><?=$OrkfiaCopyright;?></div>
    <div id="logout"><a href="main.php?cat=main&amp;page=about" class="header">About</a></div>

</div>
</body>
</html>
<?php

    //==========================================================================
    // 4/11/2003 6:09PM - Natix
    // Compression test, might save up some latency on the server.
    //==========================================================================
//     $lenght = ob_get_length();
    // $_fp = fopen('bandwidth_log', "a+");
    // fwrite($_fp, "Test: $page - $lenght - $userid\n");
    // fflush($_fp);
    // fclose($_fp);
    ob_end_flush();
} // end of include_main_down

//==============================================================================
//
//==============================================================================
function include_game_up()
{
    global  $page, $strTitle;

    $objSrcUser  = &$GLOBALS['objSrcUser'];

    // clsGame for gametime
    include_once('inc/classes/clsGame.php');
    $objGame    = new clsGame();
    $iGameHours = $objGame->get_game_time(HOUR_COUNTER);
    $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);
    $strPaused = $objGame->get_game_switch(GLOBAL_PAUSE);

    // clsAge for age counters
    include_once('inc/classes/clsAge.php');
    $objAge   = new clsAge();
    $blnCheck = $objAge->loadAge($iAgeNumber); // either FALSE or TRUE

    // orkTime for some time functions
    include_once('inc/functions/orktime.php');
    $arrOrkDate     = hoursToYears($iGameHours);

    // Months
    $strMonths      = "";
    if($arrOrkDate['months'] > 0)
        $strMonths  = "Month " . $arrOrkDate['months'] . ", ";
    $arrAgeDisplays = get_age_displays($objGame, $objAge, $blnCheck);

    if ($strPaused == ON)
        $arrAgeDisplays['str_age_extra'] .= ' <span class="positive">PAUSED</span>';


    // Alliance
    $arrGameHistorys = $objGame->get_historys($arrOrkDate['years']);
    if ($arrGameHistorys[ALLI_NAME] == NULL) // Martel: Prevent year-change lag
        $arrGameHistorys = $objGame->get_historys($arrOrkDate['years']-1);
    $strTopAlliance  = 'Top Alliance: ' . $arrGameHistorys[ALLI_NAME] . ' (#<a href="main.php?cat=game&amp;page=alliance&amp;aid=' . $arrGameHistorys[ALLI_ID] . '">' . $arrGameHistorys[ALLI_ID] . '</a>)';

    // Design stuff
    if (isset($objSrcUser))
        $arrDesign = $objSrcUser->get_designs();

    if (! isset($arrDesign[COLOR]))
        $arrDesign[COLOR] = 'forest_v1';

    if (! isset($arrDesign[WIDTH]) || $arrDesign[WIDTH] == "")
        $arrDesign[WIDTH] = '750';

    if (! isset($arrDesign[ALIGNMENT]))
        $arrDesign[ALIGNMENT] = 'center';

?>
    <script language="JavaScript" type="text/javascript">
        <!---
        var serverDate=new Date('<?=date("F d, Y H:i:s", time()); ?>')
        //-->
    </script>
    <script language="JavaScript" type="text/javascript" src="orkfiaJS.php"></script>
    <link rel="stylesheet" type="text/css" media="all" href="css/<?=$arrDesign[COLOR]; ?>.css?version=1" />
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css?version=1" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/ie.css" /><![endif]-->

<?php
    //If my new menu works I will put it here. I will also remove the ID part when a first good part is done.
    if ($objSrcUser->get_userid() == 7160 && $_SERVER['SERVER_NAME'] == DEV_SERVER_NAME) { ?>
        <link rel="stylesheet" type="text/css" media="all" href="css/harrytesting.css" />
        <!--[if lte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/harryie.css" /><![endif]-->
<?php
    }
?>
</head>

<body onload="clockStart();">

<div id="<?='container' . $arrDesign[WIDTH] . $arrDesign[ALIGNMENT]; ?>">

  <div id="header">
    <h1 style="margin: 0; padding: 0;"><img src="<?=HOST_PICS . 'head' . $arrDesign[WIDTH] . '.jpg'; ?>" alt="ORKFiA" /></h1>

    <ul id="gameStats">
        <li style="color: #000;">Time: <span style="color: #000;" id="ClockTime"><?= date('H:i:s', time()); ?></span></li>
        <li><br /></li>
        <li><a href="main.php?cat=game&amp;page=rankings&amp;show=annual&amp;type=currentage"><?=$arrAgeDisplays['str_age']; ?></a> &nbsp; <em><?=$arrAgeDisplays['str_age_extra']; ?></em></li>
        <li><strong><?=$strMonths; ?>Year <?= $arrOrkDate['years']; ?> OE</strong> &nbsp; <em><?=$strTopAlliance; ?></em></li>
    </ul>
  </div>

  <div id="menu">

    <?php include_game_menu(); ?>

  </div>

  <div id="upper_shadow"></div>

  <div id="portal">
    <div class="center">
      <h2 style="margin: 0;"><img src="<?=HOST_PICS; ?>gryph_l.gif" alt=""/><img src="<?=HOST_PICS . $page; ?>.gif" alt="<?=$strTitle; ?>" /><img src="<?=HOST_PICS; ?>gryph_r.gif" alt="" /></h2>
    </div>
<?php

} // end of include_game_up

//==============================================================================
//
//==============================================================================
function include_game_down()
{
    global  $objSrcUser, $OrkfiaCopyright;

    if (isset($objSrcUser))
    {
        //======================================================================
        // Xmas Special                                 November 27, 2007 Martel
        $arrDayPage = array(
                   1=>'tribe', 'motd', 'vote', 'war_room',
                      'racestats', 'targetfinder', 'build', 'alliance',
                      'army', 'alliance_news', 'CoC', 'explore',
                      'orkfiastats', 'raze_build', 'global_news', 'invade',
                      'resourcefarms', 'market', 'mystic', 'news',
                      'mystic2', 'raze_army', 'research', 'forums');

        if (date('n') == 12 && date('j') < 25 && $_GET['page'] == $arrDayPage[date('j')])
        {
            include_once('inc/pages/xmashidden.inc.php');
            include_xmashidden_text(); // include Xmas special page
        }
        //======================================================================

        $citizens = $objSrcUser->get_pop(CITIZENS);
        $arrGoods = $objSrcUser->get_goods();

        // Martel 2004-12-28: New tribe stats - display them in the head of page.
        // Martel March 05, 2006: Updated methods of acquiring these to object ones.

?>
    </div>

    <div id="lower_shadow"></div>

    <div id="footer">

<?php

    $arrSrcPrefs = $objSrcUser->get_preferences();
    $iSrcLevel   = $objSrcUser->get_stat(LEVEL);
    $strSQL      = "SELECT * FROM messages WHERE new = 'new' " .
                   "AND for_user = $arrSrcPrefs[id] AND action='received'";
    $resSQL      = mysql_query($strSQL) or die(mysql_error());
    $iNewPMs     = mysql_num_rows($resSQL);

    // ORKFIA MAIL =============================================================
    if ($iNewPMs > 0)
    {
        echo $strOrkfiaMailLink =
            '<img src="../../pics/pm_new.gif" border="0" width="15" height="9">' .
            '<a href="main.php?cat=game&amp;page=mail" ' .
            'class="header check_new">' . 'Orkfia Mail' .
            ' (' . $iNewPMs . ')</a>';
    }
    else
    {
        echo $strOrkfiaMailLink =
            '<a href="main.php?cat=game&amp;page=mail" class="header">' .
            'Orkfia Mail' . '</a>';
    }

    $iSrcAlliance = $objSrcUser->get_stat(ALLIANCE);
    if ($iSrcAlliance > 10)
    {
        // ALLIANCE FORUM ======================================================
        if ($arrSrcPrefs[NEW_A] > 0)
        {
            echo $strAllianceForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=alliance' .
                '&amp;mode=threads" class="header check_new">' . 'Alliance Forum' .
                '</a>';
        }
        else
        {
            echo $strAllianceForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=alliance' .
                '&amp;mode=threads" class="header">' . 'Alliance Forum' . '</a>';
        }

        // STAFF FORUMS (for moderators) =======================================
        if ($iSrcLevel > 1 && $arrSrcPrefs[NEW_S] > 0)
        {
            echo $strStaffForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=staff101' .
                '&amp;mode=threads" class="header check_new">' . 'Staff Forum' .
                '</a>';
        }
        elseif ($iSrcLevel > 1)
        {
            echo $strStaffForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=staff101' .
                '&amp;mode=threads" class="header">' . 'Staff Forum' . '</a>';
        }
    }
    elseif ($iSrcLevel > 1)
    {
        // STAFF FORUMS ========================================================
        if ($arrSrcPrefs[NEW_S] > 0)
        {
            echo $strStaffForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=staff101' .
                '&amp;mode=threads" class="header check_new">' . 'Staff Forum' .
                '</a>';
        }
        else
        {
            echo $strStaffForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=staff101' .
                '&amp;mode=threads" class="header">' . 'Staff Forum' . '</a>';
        }

        // L&O FORUMS ==========================================================
        if ($arrSrcPrefs[NEW_L] > 0 && ($iSrcAlliance == 2 || $iSrcAlliance == 1))
        {
            echo $strLnoForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=lno' .
                '&amp;mode=threads" class="header check_new">' . 'Law & Order' .
                '</a>';
        }
        elseif ($iSrcAlliance == 2 || $iSrcAlliance == 1)
        {
            echo $strLnoForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=lno' .
                '&amp;mode=threads" class="header">' . 'Law & Order' . '</a>';
        }

        // OPS FORUMS ==========================================================
        if ($arrSrcPrefs[NEW_O] > 0 && ($iSrcAlliance == 3 || $iSrcAlliance == 1))
        {
            echo $strOpsForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=ops' .
                '&amp;mode=threads" class="header check_new">' . 'Operations' .
                '</a>';
        }
        elseif ($iSrcAlliance == 3 || $iSrcAlliance == 1)
        {
            echo $strOpsForumLink =
                '<a href="main.php?cat=game&amp;page=forums&amp;set=ops' .
                '&amp;mode=threads" class="header">' . 'Operations' . '</a>';
        }
    }

    // WORLD FORUM =============================================================
    if ($arrSrcPrefs[NEW_W] > 0)
    {
        echo $strWorldForumLink =
            '<a href="main.php?cat=game&amp;page=forums&amp;set=world' .
            '&amp;mode=threads" class="header check_new">' . 'World Forum' .
            '</a>';
    }
    else
    {
        echo $strWorldForumLink =
            '<a href="main.php?cat=game&amp;page=forums&amp;set=world' .
            '&amp;mode=threads" class="header">' . 'World Forum' . '</a>';
    }

?>
    </div>

    <span id="copyright"><?=$OrkfiaCopyright ?></span>
    <span id="logout"><a href="main.php?cat=game&amp;page=logout" class="header">Logout</a></span>

    <div id="tribeStats">
        <table cellspacing="0" cellpadding="0">
        <tr>
        <th>Money:</th><td><?=number_format($arrGoods[MONEY]) ?></td>
        </tr>

        <tr>
        <th>Food:</th><td><?=number_format($arrGoods[FOOD]) ?></td>
        </tr>

        <tr>
        <th>Wood:</th><td><?=number_format($arrGoods[WOOD]) ?></td>
        </tr>

        <tr>
        <th>Citizens:</th><td><?=number_format($citizens) ?></td>
        </tr>
        </table>
    </div>
</div>
</body>
</html>
<?php

    } // end if isset objsrcuser
    else
    {

?>

  </div>

  <div id="lower_shadow"></div>

    <div id="footer">
    </div>

    <span id="copyright"><?=$OrkfiaCopyright ?></span>
    <span id="logout"><a href="main.php?cat=main&amp;page=main" class="header">Not Logged In</a></span>
</div>
</body>
</html>
<?php

    }
    //==========================================================================
    // 4/11/2003 6:09PM - Natix
    // Compression test, might save up some latency on the server.
    //==========================================================================
//     $lenght = ob_get_length();
    //$_fp = fopen('bandwidth_log', "a+");
    //fwrite($_fp, "Test: $page - $lenght - $userid\n");
    //fflush($_fp);
    //fclose($_fp);
    ob_end_flush();
} // end of include_game_down

function include_game_menu()
{
    if (isset($GLOBALS['objSrcUser']))
    {
        $objSrcUser  = &$GLOBALS['objSrcUser'];
        $objSrcAlli  = $objSrcUser->get_alliance();
        $arrSrcPrefs = $objSrcUser->get_preferences();

        $dtLastNews  = $objSrcUser->get_user_info(LAST_NEWS);
        $iBarren = $objSrcUser->get_barren();
        $iBasics = $objSrcUser->get_army(UNIT1);
        include_once('inc/functions/explore.php');
        $iExplore = getMaxExplore($objSrcUser);
    }
    else
    {
        $dtLastNews  = 0;
        $arrSrcPrefs[NEW_A] = 0;
        $iBarren = 0;
        $iBasics = 0;
        $iExplore = 0;
    }

    //==========================================================================
    // Extra stuff, for fun :p keep developing              Martel July 25, 2007
    //==========================================================================

    // TRIBE ===================================================================

    $iTribeCounter = 0;
    $arrClassStr[0][0] = '';

    if ($dtLastNews > 0)
    {
        $arrClassStr[0][0] .= ' class = "check_new"';
        $iTribeCounter++;
    }

    if ($iTribeCounter > 0)
        $iTribeCounter = ' (<span class="indicator">' . $iTribeCounter . '</span>)';
    else
        $iTribeCounter = '';

    // ALLIANCE ================================================================

    $iAllianceCounter = 0;
    $arrClassStr[1][0] = '';

    if ($arrSrcPrefs[NEW_A] > 0)
    {
        $arrClassStr[1][0] = ' class = "check_new"';
        $iAllianceCounter++;
    }

    if ($iAllianceCounter > 0)
        $iAllianceCounter = ' (<span class="indicator">' . $iAllianceCounter . '</span>)';
    else
        $iAllianceCounter = '';

    // ACTIONS =================================================================

    $iActionsCounter = 0;
    $arrClassStr[2][0] = '';
    $arrClassStr[2][1] = '';
    $arrClassStr[2][2] = '';

    if ($iBarren > 0)
    {
        $arrClassStr[2][0] = ' class = "check_new"';
        $iActionsCounter++;
    }

    if ($iBasics > 0)
    {
        $arrClassStr[2][1] = ' class = "check_new"';
        $iActionsCounter++;
    }

    if ($iExplore > 0)
    {
        $arrClassStr[2][2] = ' class = "check_new"';
        $iActionsCounter++;
    }

    if ($iActionsCounter > 0)
        $iActionsCounter = ' (<span class="indicator">' . $iActionsCounter . '</span>)';
    else
        $iActionsCounter = '';

    //==========================================================================
?>

    <ul id="nav">
      <li class="menu"><a href="main.php?cat=game&amp;page=tribe" class="header">Tribe<?php echo $iTribeCounter; ?></a>
        <ul>
          <li><a href="main.php?cat=game&amp;page=tribe">The Tribe</a></li>
          <li><a href="main.php?cat=game&amp;page=news"<?php echo $arrClassStr[0][0]; ?>>Tribe News</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=advisors">Internal Affairs</a></li>
          <li><a href="main.php?cat=game&amp;page=mail">Orkfia Mail</a></li>
          <li><a href="main.php?cat=game&amp;page=motd">Admin Message</a><hr class="menuLine" /></li>
          <?php if (isset($objSrcUser) && ($objSrcUser->get_stat(TYPE) == 'elder' || $objSrcUser->get_stat(TYPE) == 'coelder')) { ?>
          <li><a href="main.php?cat=game&amp;page=elder">Elder Options</a></li>
          <?php } ?>
          <?php if (isset($objSrcUser) && $objSrcUser->get_stat(LEVEL) > 2) { ?>
          <li><a href="main.php?cat=game&amp;page=resort_tools">Resort Tools</a></li>
          <?php } ?>
        </ul>
      </li>

      <li class="menu"><a href="main.php?cat=game&amp;page=alliance" class="header">Alliance<?php echo $iAllianceCounter; ?></a>
        <ul>
          <li><a href="main.php?cat=game&amp;page=alliance">The Alliance</a></li>
          <li><a href="main.php?cat=game&amp;page=alliance_news">Alliance News</a><hr class="menuLine" /></li>
          <li><a style="width: 55%; float: left;" href="main.php?cat=game&amp;page=market">Market</a> <a style="width: 20%; float: left;" href="main.php?cat=game&amp;page=market&amp;action=sell">Sell</a></li>
          <li><a href="main.php?cat=game&amp;page=research">Research</a></li>
          <li><a href="main.php?cat=game&amp;page=war_room">War Room</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=vote">Elect Elder</a></li>
          <li><a href="main.php?cat=game&amp;page=forums&amp;set=alliance&amp;mode=threads"<?php echo $arrClassStr[1][0]; ?>>Alliance Forum</a></li>
        </ul>
      </li>

      <li class="menu"><a href="main.php?cat=game&amp;page=build" class="header">Actions<?php echo $iActionsCounter; ?></a>
        <ul>
          <li><a href="main.php?cat=game&amp;page=build"<?php echo $arrClassStr[2][0]; ?>>Construction</a></li>
          <li><a href="main.php?cat=game&amp;page=army"<?php echo $arrClassStr[2][1]; ?>>Military Training</a></li>
          <li><a href="main.php?cat=game&amp;page=explore"<?php echo $arrClassStr[2][2]; ?>>Exploration</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=mystic">Mystics</a></li>
          <li><a href="main.php?cat=game&amp;page=thievery">Thievery</a></li>
          <li><a href="main.php?cat=game&amp;page=invade">Invasion</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=global_news">Global News</a></li>
        </ul>
      </li>

      <li class="menu"><a href="main.php?cat=game&amp;page=orkfiastats" class="header">Tools</a>
        <ul>
          <li><a href="main.php?cat=game&amp;page=orkfiastats">Orkfia Stats</a></li>
          <li><a href="main.php?cat=game&amp;page=rankings">Rankings</a></li>
          <li><a href="main.php?cat=game&amp;page=racestats">Racial Stats</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=resourcefarms">Resource Farms</a></li>
          <li><a href="main.php?cat=game&amp;page=targetfinder">Target Finder</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=preferences">Options</a></li>
        </ul>
      </li>

      <li class="menu"><a href="<?=HOST_GUIDE;?>" target="_blank" class="header newWindowLink">Help</a>
        <ul>
          <li><a href="<?=HOST_GUIDE;?>" target="_blank" class="newWindowLink">Player Guide</a></li>
          <li><a href="main.php?cat=game&amp;page=forums&amp;set=game&amp;mode=threads">Game Talk</a><hr class="menuLine" /></li>
          <li><a href="main.php?cat=game&amp;page=CoC">Code of Conduct</a></li>
        </ul>
      </li>
<?php
    $iUserLevel = 0;
    if (isset($objSrcUser))
        $iUserLevel = $objSrcUser->get_stat(LEVEL);

    if($iUserLevel > 1) { ?>
        <li class="modTools"><a href="main.php?cat=game&amp;page=resort_tools" class="header">Resort Tools</a></li>
    <?php } else { ?>
        <li class="modTools"><a href="main.php?cat=game&amp;page=sponsors" class="header">Sponsors</a></li>
    <?php } ?>
      <li class="options"><a href="main.php?cat=game&amp;page=preferences" class="header">Options</a></li>
    </ul>
<?php
    //If my new menu works I will put it here. I will also remove the ID part when a first good part is done.
    if ($objSrcUser->get_userid() == 7160 && $_SERVER['SERVER_NAME'] == DEV_SERVER_NAME) {
       include("inc/harrytesting.php");
    }
?>
<?php
}

?>


