<?php
//******************************************************************************
// pages main.inc.php                                                     ORKFiA
// Information: (Security Update) Martel, July 17, 2006
// Password now encrypted using sha1 with improved routines of checking this.
//******************************************************************************

function include_main_text()
{
    global  $Host;

    // Show / hide content from registered users vs non-registered ones (Martel)
    $strNotForRegistered = ' style=""';
    $strNotForGuests = ' style=""';
    if (isset($_COOKIE['check']))
        $strNotForRegistered = ' style="visibility: hidden; display: none;"';
    else
        $strNotForGuests = ' style="visibility: hidden; display: none;"';

    include_once('inc/classes/clsGame.php');
    $objGame        = new clsGame();
    $strLoginSwitch = $objGame->get_game_switch(LOGIN_STOPPER);

    include_once('inc/functions/races.php');
    //changed to use clsRace - AI
    require_once('inc/races/clsRace.php');
    $arrRaces    = clsRace::getActiveRaces();
    $strRace     = $arrRaces[$iRand = rand(1, count($arrRaces)-1)];
    $arrRandRace = getUnitVariables($strRace);

?>
            <div id="textBig" style="height: 110px;">
                <div id="login">
                    <h2><img src="<?echo $Host;?>first_login.gif" alt="Login" height="26" /></h2>

<?php
    if ($strLoginSwitch == 'on' && !isset($_GET['stagepass']))
    {
?>
                    <p>
                        <em class="positive">Logins are temporarily disabled.</em>
                    </p>
                    <p><em>(The gods are updating Orkfia, so be back soon.)</em></p>
<?php
    }
    else
    {
?>
                        <form action="main.php?cat=main&amp;page=login2" method="post">
                            <label for="i1" class="hidden">Username</label>
                            <input maxlength="20" name="login[username]" size="9" class="login" id="i1"/>
                            <label for="i2" class="hidden">Password</label>
                            <input maxlength="20" name="login[password]" size="9" type="password" class="password" id="i2"/>
                            <input type="submit" name="LoginButton" value="Login" class="submit" />
                            or <a href="main.php?cat=main&amp;page=register1">Sign Up!</a>
                        </form>
<?php
    }

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
// <p>ORKFiA is an <strong>excellent strategy <abbr title="Persistent Browser Based Game"><a href="http://www.pbbg.org/" title="Persistent Browser Based Game" class="gloss">PBBG</a></abbr></strong>
?>
                </div>
                <div id="teaser">
                    <br />
                    <h3><?=SERVER_TAGLINE;?></h3>
                    <p>ORKFiA is an <strong>excellent strategy <abbr title="Persistent Browser Based Game">PBBG</abbr></strong>
                    in a fantasy setting.</p>
                </div>
            </div>

    <div id="columns">
        <div id="leftcolumn">

            <div class="text"<?echo $strNotForRegistered;?>>
                <h2><img src="<?echo $Host;?>first_intro.gif" alt="Introduction" height="26" /></h2>
<?php
    if ($_SERVER['SERVER_NAME'] == DINAH_SERVER_NAME)
    {
?>

                <p>Before ORKFiA Infinity there was a time of furious battles
                and desperate struggles,
                but also a time of innocence. The inhabitants of the world
                Orkfia, who we know as Orkfians, were ruled over by the
                Empyreons. The Empyreons, who are an unknown species summoned
                to lead and organize tribes of all known races, were
                battling against time and against
                each others, in a futile war that would always end with the
                Comet's impact. Their rescue, and also their curse, was the time
                spell which would restore the lands of Orkfia to its untouched
                state a hundred years back in time. This Era has since been
                called the Eternal Occurrence,
                and was ended when the spell's effect wore off and the Comet
                finally hit Orkfia. Year 93 OE Orkfia was freed from the
                effects of the time spell, or was it?</p>

                <h3>Classic ORKFiA: Return of the Comet</h3>
                <p>The classic version of ORKFiA will take you, as a player,
                back to where it all started, as one of the Empyreons during
                the age of the Eternal Recurrence (<strong>10 - 93 OE</strong>).
                Beginning in a world that have just learnt the tactics of war,
                the deceitfulness of thieves, the devastation of magic and the
                fine arts of politics. Once more the world will continually be
                recreated by the time spell, and the Orkfians will battle
                against time, and each other, to take control over the world
                before it disintegrates. </p>

                <p>We welcome you as a member, and perhaps even as a sponsoring
                dragon to fund the ongoing of this game, to sign up for the next
                age in this classic renewal of ORKFiA.</p>

                <ul>
                    <li><a href="main.php?cat=main&amp;page=register1">Sign Up!</a>
                </li>

            </div>

<?php
    }
    else
    {
?>

                <div class="center"><img src="<?=HOST_PICS;?>fighter.gif"
                title="Invade other tribes to conquer land and resources"
                alt="" /><img
                src="<?=HOST_PICS;?>wizard.gif" title="The mystics can cause
                devastating damage to your enemies" alt="" /><img
                src="<?=HOST_PICS;?>thief.gif" title="The thieves are usually
                considered scum, but in war times you need every asset
                available" alt="" /></div>

                <p>Grow your tribe in a <span class="highlight" title="Each hour
                the game is updated 1 time" style="border-bottom:
                1px dotted;">real time</span>
                environment, teamed together <span class="highlight">with
                friends</span> in an effort to become the largest, strongest and
                most famous of all alliances!</p>

                <p>You have to execute the right strategy to grow large, strong
                and famous. There are many strategies to choose. Your tribe can
                <span class="highlight">grow</span> by the valuables your
                citizens find in the mines, or they can <span class="highlight">
                do research</span> to improve your efficiency,
                defense, power and production.</p>

                <p>The other possibility is to
                <span class="highlight">kill those who oppose you</span>.</p>

                <p>Once
                all your enemies have perished, you will be the greatest leader
                the world has ever seen.</p>

                <ul>
                    <li><a href="main.php?cat=main&amp;page=register1">Sign Up!</a>
                </li>

            </div>

<?php
    }

    include_once('inc/pages/global_news.inc.php');

    // Show Global News
    echo "<br />";
    echo showGlobalNews('tiny');

?>
        </div>

        <div id="rightcolumn">
            <div class="text"<?echo $strNotForRegistered;?>>

                <h2><img src="<?echo $Host;?>first_faq.gif" alt="FAQ" height="26" /></h2>
                <p>Is this your first visit to ORKFiA? If you are a novice&#8212;or
                just curious&#8212;<a href="main.php?cat=main&amp;page=faq">we
                recommend our FAQ</a> with answers to some of the most common
                questions about this game.</p>

            </div>
            <div class="text"<?echo $strNotForRegistered;?>>

                <h2><img src="<?echo $Host;?>first_join.gif" alt="Join" height="26" /></h2>
                <p>ORKFiA comes in two versions, both with hundreds of players
                and both for free. So it's really up to you, do you want to
                <span class="highlight">play with or without resets</span>
                between rounds?</p>
                <ul>
                    <li><a href="http://orkfia.phpsupport.se/main.php?cat=main&amp;page=register1">Infinity Sign Up!</a> (Play until you die)</li>
                    <li><a href="http://dinah.phpsupport.se/main.php?cat=main&amp;page=register1">Classic Sign Up!</a> (Resets between each round)</li>
                </ul>

            </div>
            <div class="text"<?echo $strNotForGuests;?>>

                <h2><img src="<?echo $Host;?>first_sponsors.gif" alt="Sponsor" height="26" /></h2>
                <p>ORKFiA owes its success to a dedicated player base. It is very easy to help:</p>
                <ul>
                    <li>Donate and <a href="main.php?cat=main&amp;page=sponsors">Become a <em>Dragon</em></a></li>
                    <li>Link to <a href="<?=HOST;?>">ORKFiA <?=SERVER_TAGLINE;?></a></li>
                </ul>
                <div class="center">
                    <a href="http://orkfia.phpsupport.se/">
                    <img src="<?=HOST_PICS;?>promotional/ork-button.png"
                    alt="" border="0" /></a>
                    <a href="http://dinah.phpsupport.se/">
                    <img src="<?=HOST_PICS;?>promotional/ork-button-classic.png"
                    alt="" border="0" /></a><br /><br />
                </div>

            </div>
            <div class="text"<?echo $strNotForRegistered;?>>

                <h2><img src="<?echo $Host;?>first_forum.gif" alt="Forum" height="26" /></h2>
                <p>If you are curious about what happens inside Orkfia, you are
                most welcome to have a sneak peek. No need to sign up, just
                go ahead and lurk!</p>
                <ul>
                    <li><a href="main.php?cat=main&amp;page=forums&amp;set=news&amp;mode=threads">Announcements</a></li>
                    <li><a href="main.php?cat=main&amp;page=forums&amp;set=world&amp;mode=threads">World Forum</a></li>
                    <li><a href="main.php?cat=main&amp;page=forums&amp;set=game&amp;mode=threads">Game Talk</a></li>
                </ul>

            </div>
            <div class="text"<?echo $strNotForGuests;?>>

                <h2><img src="<?echo $Host;?>first_chat.gif" alt="Chat" height="26" /></h2>
                <p>Talk to staff and active players in our IRC-channels:</p>
                <ul>
                    <li><a href="irc://irc.netgamers.org/orkfia" target="_blank" class="newWindowLink">#orkfia</a> (official)</li>
                    <li><a href="irc://irc.netgamers.org/orkfia-classic" target="_blank" class="newWindowLink">#orkfia-classic</a> (official)</li>
                    <li><a href="irc://irc.netgamers.org/orkfiafunroom" target="_blank" class="newWindowLink">#orkfiafunroom</a></li>
                </ul>

            </div>
            <div class="text" <?echo substr($strNotForRegistered, 0, -1) . ' text-align: center;"';?>>

                <h2><img src="<?echo $Host;?>first_guide.gif" alt="Player's Strategy Guide" height="26" /></h2>

                <table class="small" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr class="header">
                        <th colspan="5">Featured: Race <?=$iRand . ' of ' . (count($arrRaces)-1) .  ' - <i>' . $strRace;?></i></th>
                    </tr>
                    <tr class="subheader">
                        <th>Class</th>
                        <th>Unit Name</th>
                        <th><span class="militstats" style="color: #000;">Offence</span></th>
                        <th><span class="militstats" style="color: #000;">Defence</span></th>

                        <th class="right">Gold</th>
                    </tr>
<?php

    $arrClass = array(2 => 'Basic', 'Specialist', 'Specialist', 'Elite', 'Thief');
    foreach($arrClass as $i => $strClass)
    {
?>
                    <tr class="data">

                        <th><?=$strClass;?></th>

                        <td class="left"><?=$arrRandRace['output'][$i];?></td>

                        <td class="center"><span class="militstats"><?=$arrRandRace['offence'][$i];?></span></td>

                        <td class="center"><span class="militstats"><?=$arrRandRace['defence'][$i];?></span></td>

                        <td><?=number_format($arrRandRace['gold'][$i]);?></td>
                    </tr>
<?php
    }
?>
                </table>

                <p>Let your journey start at <a href="<?=HOST_GUIDE;?>" target="_blank" class="newWindowLink">the Player Guide</a></p>

            </div>
        </div>


<?php

    //==========================================================================
    //                                                 Martel, December 07, 2006
    // Age display, identical except for "month" to what is in layout.php
    //==========================================================================
//     include_once('inc/classes/clsGame.php');
//     $objGame    = new clsGame();
//     $iGameHours = $objGame->get_game_time(HOUR_COUNTER);
//     $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

//     // age stuff
//     include_once('inc/classes/clsAge.php');
//     $objAge   = new clsAge();
//     $blnCheck = $objAge->loadAge($iAgeNumber); // either FALSE or TRUE

//     // display stuff
//     include_once('inc/functions/orktime.php');
//     $arrAgeDisplays = get_age_displays($objGame, $objAge, $blnCheck);
//     $arrOrkDate     = hoursToYears($iGameHours);

//     // Months
//     $strMonths      = "";
//     if($arrOrkDate['months'] > 0)
//         $strMonths  = "Month " . $arrOrkDate['months'] . ", ";
//     $arrAgeDisplays = get_age_displays($objGame, $objAge, $blnCheck);

//     // Alliance
//     $arrGameHistorys = $objGame->get_historys($arrOrkDate['years']);
//     $strTopAlliance  = 'Top Alliance: ' . $arrGameHistorys[ALLI_NAME] . ' (#' . $arrGameHistorys[ALLI_ID] . ')';

    // queries for stats
    $strSQL1 = 'SELECT (COUNT(id) / 2) as wars FROM war WHERE target > 0 LIMIT 1';
    $strSQL2 = 'SELECT COUNT(id) as alliances FROM ' . ALLIANCE . ' WHERE id > 10 LIMIT 1';
    $strSQL3 = 'SELECT COUNT(id) as players FROM user LIMIT 1';
    $strSQL4 = 'SELECT COUNT(id) as oldies FROM user WHERE hours > 948 LIMIT 1';
//     $strSQL5 = 'SELECT AVG(land) as avg_land FROM build,user WHERE user.hours > 948 LIMIT 1';
    $iWars = intval(mysql_result(mysql_query($strSQL1), 0));
    $iAlliances = mysql_result(mysql_query($strSQL2), 0);
    $iPlayers = mysql_result(mysql_query($strSQL3), 0);
    $iOldies = mysql_result(mysql_query($strSQL4), 0);
//     $iAvgLand = mysql_result(mysql_query($strSQL5), 0);


    echo
        '<div class="clear"><hr /></div>' .
        '<div class="text">' .
            '<h3>Game Overview</h3>' .
//             '<p>' .
//                 '<strong>' . $arrAgeDisplays['str_age'] . $arrAgeDisplays['str_age_extra'] . '</strong><br />' .
//                 '<strong>' . $strMonths . 'Year ' . $arrOrkDate['years'] . ' OE</b>&nbsp;</strong>' .
//             '</p>' .
            '<p>' .
                "<strong>$iWars</strong> ongoing wars, <strong>$iAlliances</strong> alliances and <strong>$iPlayers</strong> tribes. <strong>$iOldies</strong> are dying, <strong>more</strong> will be killed." .
            '</p>' .
//             '<p>' .
//                 "<strong>$strTopAlliance</strong>" .
//             '</p>' .
            '<p>' .
                'ORKFiA is hosted by <a href="http://phpsupport.se/" target="_blank" title="Swedish PHP &amp; MySQL Support" class="newWindowLink">PHP Support .SE</a>' .
            '</p>' .
//             '<h3>Game Configuration</h3>' .
//             '<p>' .
//                 "Max alliances: " . MAX_ALLIANCES . " <br />" .
//                 "Tribes per alliance: " . MAX_ALLIANCE_SIZE . " <br />" .
//                 "Average tribes per alliance: " . round($iPlayers / $iAlliances) . " <br />" .
//                 "Average size of tribes with 5 years to live: " . number_format($iAvgLand) . " acres <br />" .
//             '</p>' .
        '</div>';
    //==========================================================================
    // end nonsense ;)
    //==========================================================================

    echo '</div>';
}

?>

