<?
function call_input_filter_text()
{
    global $tool;
    
    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }
    
    ini_set('error_reporting', E_NONE);
    
    ?>
    <div style="font-weight: bold; font-size: 12px; ">PHP Input Filter - 1.2.0</div>
    Copyright 2005 Daniel Morris. (<a href="http://www.opensource.org/licenses/gpl-license.php" alt="GNU General Public Licence">GPL Licence.</a>)<br /><br />
    <?php 

    // inject sample $_POST data etc..
    if ($_GET["use"] == "sample") {
        $sample_link = '<span class="small" style="color: #bbb; text-decoration: line-through;">(Inject sample form data)</span>';
        $_POST["input"] = 'I like <div good="blah" bad=blah>php</div> but not <br> XSS <img src=javascript:alert(\'bad!\')>';
        $_POST["tags"] = 'br';
        $_POST["attr"] = 'good, style';
        $_POST["tagmethod"] = 1;
        $_POST["attrmethod"] = 0;
        $_POST["xssauto"] = 'y';
    // sample_link as normal
    } else $sample_link = '<span class="small">(<a href="http://development.orkfia.org/main.php?cat=game&page=resort_tools&tool=input_filter&use=sample">Inject sample form data</a>)</span>';

    ?>
    <form class="white" action="http://development.orkfia.org/main.php?cat=game&page=resort_tools&tool=input_filter" method="post">
      <div class="title">Example Factory: <?php echo $sample_link; ?></div>
        <div style="margin-bottom: 5px;">String to be filtered:</div>
        <textarea name="input" style="width: 500px; height: 80px;"><?php if ($_POST["input"]) echo stripslashes($_POST["input"]); ?></textarea>
        <div style="margin-bottom: 5px; margin-top: 14px;">List Tags: <span class="small">(Comma-delimited. Eg: tag1, tag2, tag3)</span></div>
        <input name="tags" type="text" style="width: 500px;" value="<?php if ($_POST["tags"]) echo $_POST["tags"]; ?>">
        <div style="margin-bottom: 5px; margin-top: 14px;">List Attributes: <span class="small">(Comma-delimited. Eg: attr1, attr2, attr3)</span></div>
        <input name="attr" type="text" style="width: 500px;" value="<?php if ($_POST["attr"]) echo $_POST["attr"]; ?>">
        <div style="margin-bottom: 5px; margin-top: 14px;">Tag method to apply:</div>
        <select name="tagmethod">
            <option value="0" <?php if ((!$_POST["tagmethod"]) || ($_POST["tagmethod"] == 0)) echo "selected"; ?>>Remove all tags but specified</option>
            <option value="1" <?php if ($_POST["tagmethod"] == 1) echo "selected"; ?>>Remove only specified tags</option>
        </select>
        <div style="margin-bottom: 5px; margin-top: 14px;">Attibute method to apply:</div>
        <select name="attrmethod">
            <option value="0" <?php if ((!$_POST["attrmethod"]) || ($_POST["attrmethod"] == 0)) echo "selected"; ?>>Remove all attibutes but specified</option>
            <option value="1" <?php if ($_POST["attrmethod"] == 1) echo "selected"; ?>>Remove only specified attibutes</option>
        </select>
        <div style="margin-bottom: 5px; margin-top: 14px;">Strip <a href="http://cyberai.com/inputfilter/blacklist.php">identified</a> problem tags and attributes, regardless of user-defined arrays:</div>
        <select name="xssauto">
            <option value="y" <?php if ((!$_POST["xssauto"]) || ($_POST["xssauto"] == 'y')) echo "selected"; ?>>Auto-strip blacklisted: YES</option>
            <option value="n" <?php if ($_POST["xssauto"] == 'n') echo "selected"; ?>>Auto-strip blacklisted: NO</option>
        </select>
        <br><br><br>
        <input type="hidden" name="sent" value="yes">   
        <input name="submit" type="submit" value="Process Query">
    </form>
<?php

    // include class file
    require_once('inc/classes/clsInputFilter.php');

    // form has been sent empty
    if (($_POST["sent"]) && (!$_POST["input"])) {
        echo "<div class=\"white\"><div class=\"title\">View Results:</div>You have not entered any input data!!</div>\n";

    // form has been sent and input is not empty
    } else if (($_POST["sent"]) && ($_POST["input"])) {
        // input text
        $_POST["input"] = stripslashes($_POST["input"]);
        // tags array
        $tags = explode(',', $_POST["tags"]);
        for ($i = 0; $i < count($tags); $i++) $tags[$i] = trim($tags[$i]);
        // attr array
        $attr = explode(',', $_POST["attr"]);
        for ($i = 0; $i < count($attr); $i++) $attr[$i] = trim($attr[$i]);
        // select fields
        $tag_method = $_POST["tagmethod"];
        $attr_method = $_POST["attrmethod"];
        if ($_POST["xssauto"] == 'n') $xss_auto = 0;
        else $xss_auto = 1;
        // script-timer setup
        $sStart = microtime();  
        // more info on parameters in documentation.
        $myFilter = new InputFilter($tags, $attr, $tag_method, $attr_method, $xss_auto);
        // process input
        $result = $myFilter->process($_POST["input"]);
        // script timer stop
        $sStop = microtime(); 
        // script-timer display
        $time_elapsed = round(($sStop - $sStart), 4);
        // display output
        echo "<div class=\"white\"><div class=\"title\">View Results:</div>\n";
        echo '<div style="font-size: 11px; font-weight: normal; font-style: italic;"><span style="padding-right: 11px;">(Before)</span> ' . htmlentities($_POST["input"]) . "</div>\n";
        echo '<div style="font-size: 15px; font-weight: normal; padding-top: 10px;"><span style="padding-right: 12px;">(After)</span> ' .  htmlentities($result) . "</div>\n";
    //  echo '<div style="font-size: 15px; font-weight: normal; padding-top: 10px;"><span style="padding-right: 12px;">(Actual)</span> ' .  $result . "</div>\n";
        echo "</div>\n";
        echo "<div class=\"grey\" style=\"margin-top: 12px;\"><em>Script execution has taken $time_elapsed seconds.</em></div>\n";
    }
    ini_set('error_reporting', E_ALL);
}

?>