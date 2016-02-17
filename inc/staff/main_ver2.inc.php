<?php
//******************************************************************************
// pages main.inc.php                                                     ORKFiA
// Information: (Security Update) Martel, July 17, 2006
// Password now encrypted using sha1 with improved routines of checking this.
//******************************************************************************

function include_main_text()
{
    global  $Host;

    include_once('inc/classes/clsGame.php');
    $objGame        = new clsGame();
    $strLoginSwitch = $objGame->get_game_switch(LOGIN_STOPPER);

    include_once('inc/functions/races.php');
    //$arrRaces = getRaces();
    //$strRace     = $arrRaces[$iRand = rand(1,15)];
    //$arrRandRace = getUnitVariables($strRace);
    //changed to use clsRace - AI
    require_once('inc/races/clsRace.php');
    $arrRaces    = clsRace::getActiveRaces();
    $strRace     = $arrRaces[$iRand = rand(1,count($arrRaces)-1)];
    $arrRandRace = getUnitVariables($strRace);
?>
            <div id="text" style="height: 85px;">
                <div id="login">
                    <h2><img src="<?echo $Host;?>first_login.gif" alt="Login" height="26" /></h2>
                    <p>

<?php
    if ($strLoginSwitch == 'on' && !isset($_GET['stagepass']))
    {
?>
                        <em style="color: lightgreen;">Logins are temporarily disabled.</em>
                    </h2>
                    <p>(We're probably just updating the game so be back soon!)</p>
<?php
    }
    else
    {
?>
                        <form action="main.php?cat=main&amp;page=login2" method="post">
                            <label for="1" class="hidden">Username</label>
                            <input maxlength="20" name="login[username]" size="9" class="login" id="1"/>
                            <label for="2" class="hidden">Password</label>
                            <input maxlength="20" name="login[password]" size="9" type="password" class="password" id="2"/>
                            <input type="submit" name="LoginButton" value="Login" align="middle" class="submit" />
                            or <a href="main.php?cat=main&amp;page=register1">Sign Up!</a>
                        </form>
                    </p>
<?php
    }

    /*
    $age1     = mktime(0,0,0,9,3,2006); //hours,mins,secs,month,day,year
    $timediff = $age1 - time();
    $days     = intval($timediff / 86400);
    $remain   = $timediff % 86400;
    $hours    = intval($remain / 3600);
    $remain   = $remain % 3600;
    $mins     = intval($remain / 60);

    if ($timediff > 0)
        echo ' <i style="color: lightgreen;">New Age Start: <br/>';
    if ($days > 1)
        echo $days . ' days left';
    elseif ($days == 1)
        echo $days . ' day left';
    elseif ($hours > 1)
        echo $hours . ' hours left';
    elseif ($hours == 1)
        echo $hours . ' hour left';
    elseif ($hours == 0 && $mins > 0)
        echo $mins . ' minutes left!';

    if ($timediff > 0)
        echo "</i>";
    */

    // Martel: Safety caution - Do not help kiddies find usernames or guess pws
    if (isset($_GET['error']))
    {
        switch ($_GET['error'])
        {
            case 'error':

                echo '<p>' .
                     'Wrong name or password, please try again.' .
                     '</p>';

            break;
            case 'empty':

                echo '<p>' .
                     'Empty form, please fill in and try again.' .
                     '</p>';

            break;
        }
    }

?>
                </div>
                <div id="teaser">
                    <p>ORKFiA is an excellent <strong>online strategy game</strong>
                    in a fantasy setting. Play in alliances together with your friends, at work, in school or at home, and become the greatest leader of all.</p>
                </div>
            </div>

            <div id="columns">
                <div id="intro">

                    <div class="center">
                        <h2>Global Game News</h2>
                    </div>

<?php

    include_once('inc/pages/global_news.inc.php');

    // Show Global News
    echo showGlobalNews('tiny');

?>


                    <div id="text">

                        <h2><img src="<?echo $Host;?>first_join.gif" alt="Join" height="26" /></h2
                        <p>Create and lead your very own tribe inside Orkfia, for absolutely no cost. Go here to <a href="main.php?cat=main&amp;page=sponsors">Sign Up!</a></p>

                        <h2><img src="<?echo $Host;?>first_chat.gif" alt="Chat" height="26" /></h2>
                        <p>Talk to staff and active players in our IRC-channel: <a href="irc://irc.netgamers.org/orkfia" target="_blank" class="newWindowLink">#orkfia</a></p>

                    </div
                    <div class="center" style="text-align: left;">
                        <h2 style="margin: 0px auto; margin-top: 10px; text-align: center;">Game Stats</h2>

<?php
    //==========================================================================
    //                                                 Martel, December 07, 2006
    // Age display, identical except for "month" to what is in layout.php
    //==========================================================================
    include_once('inc/classes/clsGame.php');
    $objGame    = new clsGame();
    $iGameHours = $objGame->get_game_time(HOUR_COUNTER);
    $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

    // age stuff
    include_once('inc/classes/clsAge.php');
    $objAge   = new clsAge();
    $blnCheck = $objAge->loadAge($iAgeNumber); // either FALSE or TRUE

    // display stuff
    include_once('inc/functions/orktime.php');
    $arrAgeDisplays = get_age_displays($objGame, $objAge, $blnCheck);
    $arrOrkDate     = hoursToYears($iGameHours);

    // Months
    $strMonths      = "";
    if($arrOrkDate['months'] > 0)
        $strMonths  = "Month " . $arrOrkDate['months'] . ", ";
    $arrAgeDisplays = get_age_displays($objGame, $objAge, $blnCheck);

    // Alliance
    $arrGameHistorys = $objGame->get_historys($arrOrkDate['years']);
    $strTopAlliance  = 'Top Alliance: ' . $arrGameHistorys[ALLI_NAME] . ' (#' . $arrGameHistorys[ALLI_ID] . ')';

    // queries for stats
    $strSQL1 = 'SELECT (COUNT(id) / 2) as wars FROM war WHERE target > 0 LIMIT 1';
    $strSQL2 = 'SELECT COUNT(id) as alliances FROM ' . ALLIANCE . ' WHERE id > 10 LIMIT 1';
    $strSQL3 = 'SELECT COUNT(id) as players FROM user LIMIT 1';
    $strSQL4 = 'SELECT COUNT(id) as oldies FROM user WHERE hours > 948 LIMIT 1';
    $strSQL5 = 'SELECT AVG(land) as avg_land FROM build,user WHERE user.hours > 948 LIMIT 1';
    $iWars = intval(mysql_result(mysql_query($strSQL1), 0));
    $iAlliances = mysql_result(mysql_query($strSQL2), 0);
    $iPlayers = mysql_result(mysql_query($strSQL3), 0);
    $iOldies = mysql_result(mysql_query($strSQL4), 0);
    $iAvgLand = mysql_result(mysql_query($strSQL5), 0);


    echo
//         '<h2>Game Overview</h2>' .
//         '<p>' .
//             '<i>' . $arrAgeDisplays['str_age'] . $arrAgeDisplays['str_age_extra'] . '</i><br />' .
//             '<strong>' . $strMonths . 'Year ' . $arrOrkDate['years'] . ' OE</strong>&nbsp;</strong>' .
//         '</p>' .
        '<p>' .
            "Currently in ORKFiA there are <strong style=\"font-size: 1.5em;\">$iWars</strong> ongoing wars, <strong style=\"font-size: 1.5em;\">$iAlliances</strong> alliances and <strong style=\"font-size: 1.5em;\">$iPlayers</strong> tribes." .
//             " <strong>$iOldies</strong> of these tribes' rulers will die of age within 5 years. Inactivity scripts are run weekly and will affect these numbers." .
        '</p>';
//         '<h2>Game Configuration</h2>' .
//         '<p>' .
//             "Max alliances: " . MAX_ALLIANCES . " <br />" .
//             "Tribes per alliance: " . MAX_ALLIANCE_SIZE . " <br />" .
//             "Average tribes per alliance: " . round($iPlayers / $iAlliances) . " <br />" .
//             "Average size of tribes with 5 years to live: " . number_format($iAvgLand) . " acres <br />" .
//         '</p>';
    //==========================================================================
    // end nonsense ;)
    //==========================================================================
?>


                    </div>

                </div>
                <div id="donate">

                    <div id="text" style="text-align: left;">

                        <h2><img src="<?echo $Host;?>first_intro.gif" alt="Strategy Game Introduction" height="26" /></h2>

                        <p><span class="highlight">Grow your tribe in a real time environment</span>,
                        teamed together with friends in an effort to become the largest,
                        strongest and most famous of all alliances!</p>

                        <p>You have to execute the right strategy to grow large, strong
                        and famous. There are <span class="highlight">many strategies to
                        choose</span>. Your tribe can grow by the valuables your citizens
                        find in the mines, or they can do research in many fields to
                        improve your efficiency, defense, power and production.</p>

                        <p>The other possibility is to <span class="highlight">kill
                        those who oppose you</span>. Once all your enemies have perished, you
                        will <span class="highlight">be the greatest leader the world has ever seen</span>.</p>

                    </div>
                </div>
            </div>

<?php

}

function random_greeting()
{
    $arrMessage =
    array
    (
        "Welcome to ORKFiA, traveller.",
        "ORKFiA is an excellent strategy game in a fantasy setting.",
        "Play in alliances together with your friends.",
        "Play ORKFiA from school.",
        "Play ORKFiA from work.",
        "Play ORKFiA anywhere.",
        "Play ORKFiA at home.",
        "Become the greatest leader of all."
    );
}

?>

