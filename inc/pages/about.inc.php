<?php
function include_about_text(){
    global $Host;

    include_once('inc/pages/credits.inc.php');
    include_credits_text();
/*?>
    <div id="textBig" style="margin: 15px auto; text-align: center;">

        <h2><img src="<?=$Host;?>first_about.gif" alt="About ORKFiA Online Strategy Game" /></h2>

        <p><a href="main.php?cat=main&amp;page=faq">FAQ</a></p>

        <p><a href="main.php?cat=main&amp;page=credits">Credits</a></p>

        <p><a href="main.php?cat=main&amp;page=CoC">Code of Conduct</a></p>

        <p><a href="main.php?cat=main&amp;page=privacy">Privacy Policy</a></p>

    </div>
<?php*/
}
?>