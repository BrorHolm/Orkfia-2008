<?php
function include_faq_text()
{
    include_once('inc/classes/clsRace.php');
    $iAvailable = max(2, count(clsRace::getActiveRaces())-1);
?>
    <div id="textBig" style="margin: 15px auto; text-align: left;">

        <h2 style="margin: 15px auto; text-align: center;">
        <img src="<?=HOST_PICS;?>first_faq.gif" alt="FAQ - Frequently Asked
        Questions" /></h2>

        <a name="top"></a>
        <ol>
            <li><a href="#1"> I came here by mistake but I like the graphics,
            what is this about?</a></li>
            <li><a href="#2"> Does it cost anything to join?</a></li>
            <li><a href="#3"> Where do I start?</a></li>
            <li><a href="#4"> What should be my first actions?</a></li>
            <li><a href="#5"> Is there anything I must know?</a></li>
            <li><a href="#6"> How do I win?</a></li>
            <li><a href="#7"> What do I get if I win?</a></li>
            <li><a href="#8"> Where do all players hang out?</a></li>
            <li><a href="#9"> Where can I find more information?</a></li>
        </ol>

        <ol>
        <li><a name="1"></a><h3>I came here by mistake but I like the
        graphics, what is this about?</h3>
        <p>ORKFiA is an exciting online strategy game, developed and maintained
        by its players. It is text based and situated in a fantasy world called
        &#8220;Orkfia&#8221;, a world inspired by Tolkien's Middle Earth.
        Together with other players in your alliance you will be warring others
        as they step in your way. The combination of strategy and war is the
        ultimate challenge in ORKFiA, besides basic stuff like training military,
        constructing buildings and trying to stay alive.</p></li>

        <li><a name="2"></a><h3>Does it cost anything to join?</h3>
        <p>It is completely free to play, but we encourage our dedicated players to
        sponsor the game, with a small donation. Players who do so become <a
        href="main.php?cat=main&amp;page=sponsors">Dragon
        Sponsors</a>, getting a dragon next to their names in the forum and the
        appreciation of all players.</p></li>

        <li><a name="3"></a><h3>Where do I start?</h3>
        <p>Pick one of <a href="<?=HOST_GUIDE;?>races.php?chapter=4"
        target="_blank" class="newWindowLink">the <?=$iAvailable;?> races</a>,
        and then if you are ready to join, go here to <a
        href="main.php?cat=main&amp;page=register1">sign up!</a>
        </p></li>

        <li><a name="4"></a><h3>What should be my first actions?</h3>
        <p>Assuming you have already selected a race and created your account,
        your first actions should be to 1) build your barren land and 2) train
        about 17,000 basic military units, and then wait 4 hours for them to
        arrive. More can be found reading our guide&#8212;and especially its
        first article&#8212;<a href="<?=HOST_GUIDE;?>crash_course.php?chapter=1"
        target="_blank" class="newWindowLink">&#8220;Crash Course to
        ORKFiA&#8221;</a>.</p></li>

        <li><a name="5"></a><h3>Is there anything I must know?</h3>
        <p>Yes, you should read our <a href="main.php?cat=main&amp;page=CoC">Code
        of Conduct</a>, which contains <em>the rules</em> that are enforced
        within the game.</p></li>

        <li><a name="6"></a><h3>How do I win?</h3>
        <p>After 6 weeks (in orkfia time this measures 84 years) your tribe leader
        is 100 years old and dying. If you have played well and had a fun time
        with others in your alliance, you are a winner. But true victory
        can be measured many ways, is it killing others you put pride
        in, or maybe winning wars? There are many ways to prove your skill in
        ORKFiA, the most prestigious ones depending on good teamwork and
        the success of your alliance.</p></li>

        <li><a name="7"></a><h3>What do I get if I win?</h3>
        <p>If you survive the full period of 6 weeks that your leader has to live,
        you will start your next tribe with a heritage bonus to land, money
        and fame, based on the success of your previous tribe. </p></li>

        <li><a name="8"></a><h3>Where do all players hang out?</h3>
        <p>First and most important place to hang out will be inside your alliance,
        using the alliance forum. To interact with other players we have two
        official places: the <a
        href="<?=HOST;?>main.php?cat=main&amp;page=forums&amp;set=world">Forum
        </a>, and the <a href="irc://irc.netgamers.org/orkfia" target="_blank"
        class="newWindowLink">#orkfia IRC-channel</a> (located at the netgamers.org
        server).</p></li>

        <li><a name="9"></a><h3>Where can I find more information?</h3>
        <p>The best place for game information is the
        <a href="<?=HOST_GUIDE;?>" target="_blank" class="newWindowLink">
        ORKFiA Player Guide</a>.</p></li>
        </ol>

        <p><em>We wish you the best of luck getting to learn this exciting
        strategy game, and hope to see you inside the game soon!<br />
        ~ The ORKFiA Staff Team</em></p>

    </div>
<?php
}
?>
