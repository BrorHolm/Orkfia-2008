<?php
//******************************************************************************
// pages preview.inc.php                                    Martel June 10, 2007
//
// Preview of the game, for people who don't want to sign up to try the game.
//******************************************************************************

function include_preview_text()
{
    global  $cat;
?>
<style type="text/css">
<!--
.ike-slideshow {
/*border-top: 1px #dddddd solid;
border-bottom: 1px #dddddd solid;*/
padding: 0px 0px;
margin: 0px 0px;
}

.ike-slideshow ul {
margin: 0px;
padding: 0px;
list-style: none;
}

.ike-slideshow ul li {
display: none;
margin: 0px;
padding: 0px;
list-style: none;
height: inherit;
line-height: inherit;
}

.ike-slideshow ul li.first {
display: block;
}

.ike-slideshow-image {
background-color: #222222;
text-align: center;
padding: 0px;
}

.ike-slideshow-info {
padding: 4px;
text-align: center;
margin: 4px;
padding: 4px;
}

.ike-slideshow-caption {
height: 30px;
}

.ike-slideshow-caption p {
line-height: 14px;
}

.ike-slideshow-pagination {
float: left;
margin-right: 10px;
}

.ike-slideshow img {
border: 0px !important;
padding: 0px !important;
display: inline !important;
margin: 0px !important;
vertical-align: middle !important;
max-width: 100%;
}

.ike-slideshow-controls {
text-align: center;
}

.ike-slideshow-controls a {
border: 0px;
padding: 2px 4px;
margin: 4px;
color: #aaaaaa;
}
-->
</style>

<?php
    if ($cat == 'main') {
        ECHO "<h2 style=\"margin: 15px auto; text-align: center;\"><img src=\"",HOST_PICS,"first_preview.gif\" alt=\"Game Preview / Quick Tour\" /></h2>";
    }
?>


    <div class="ike-slideshow">
        <div class="ike-slideshow-controls">
            <a class="ike-slideshow-back" href="javascript:ike_slideshow('ike-slideshow-2',-1);void(0);">< Back</a>
            <a class="ike-slideshow-forward" href="javascript:ike_slideshow('ike-slideshow-2',1);void(0);">Forward ></a>
        </div>
        <div class="ike-slideshow-loading" style="height: 401px; line-height: 399px; font-size: 1px;">Loading images.</div>
        <ul id="ike-slideshow-2">
        <li><div class="ike-slideshow-image first" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>tribe.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>tribe.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption"> <p>"The Tribe Page" summarizes the current condition of your own account. Size in acreage, strength, and fame all indicators of changes are listed along with some basic stats on your military/mage/thief positioning.</p><p>"The Elder Message" displayed here is generally the first place to look for information of an urgent nature intended for all members of your alliance.</p></div></div></li>

        <li><div class="ike-slideshow-image" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>population.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>population.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption">   <p>"Internal Affairs" are a series of advisor pages, each detailing the factors affecting your tribe. There are five advisors; population, resources, military, mage/thief, and infrastructure.</p></div></div></li>
        <li><div class="ike-slideshow-image" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>magic.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>magic.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption">   <p>"Action Pages" are the beginnings of all your work. Your mage casts all offensive and defensive spells from the 'Magic' page.</p></div></div></li>
        <li><div class="ike-slideshow-image" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>attacking.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>attacking.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption">   <p>Your general deploys all of the five types of attacks from the 'Invasions' page.</p></div></div></li>
        <li><div class="ike-slideshow-image" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>thievery.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>thievery.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption">   <p>Thieves are sent to gather intel about and perform devious operations against your enemies from the 'Thievery' page.</p></div></div></li>
        <li><div class="ike-slideshow-image" style="height: 401px; line-height: 399px; font-size: 1px;"><a href="<?echo HOST_PICS . 'promotional/'; ?>military.jpg"><img src="<?echo HOST_PICS . 'promotional/'; ?>military.jpg" alt="" width="400" height="400" /></a></div><div class="ike-slideshow-info"><div class="ike-slideshow-caption">   <p>Sign up now and join the ORKFiA community. Stay informed with ingame News, stay in touch with forums and messaging, stay aware with community involvement. Care to join us? <a href="main.php?cat=main&amp;page=register1">Sign Up!</a></p></div></div></li>
        </ul>
    </div>

<?php
}

?>