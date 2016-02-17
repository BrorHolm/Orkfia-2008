<?php
//============================================================================
// allow user to change _everything_ for testing purposes
//============================================================================
function include_uberdev_text(){
    global $building_count, $building_output, $building_variables;
    if ($_SERVER['SERVER_NAME'] != DEV_SERVER_NAME)
    {
        echo "EVIL!!!! HOW DID YOU GET HERE?!";
        include_game_down();
        exit;
    }
    $objSrcUser = &$GLOBALS['objSrcUser'];
    $userid = $objSrcUser->get_userid();
    // if stuff is posted, do that first
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(!array_empty($_POST['building'])){
            var_dump($_POST['building']); //debug
            foreach ($_POST['building'] as $strToBuild => $strNumber){
                if($strNumber == '') continue;
                if(get_magic_quotes_gpc())
                {
                    $strToBuild = stripslashes($strToBuild);
                    $strNumber = stripslashes($strNumber);
                }
                $strToBuild = mysql_real_escape_string($strToBuild);
                $strNumber = mysql_real_escape_string($strNumber);
                //print("<br />" . "UPDATE build SET $strToBuild = $strNumber WHERE id = $userid" . "<br />"); //debug
                var_dump(mysql_query("UPDATE build SET $strToBuild = $strNumber WHERE id = $userid"));
                echo '<br />'.mysql_error();
            }

        }
        if(!array_empty($_POST['population'])){
            var_dump($_POST['population']); //debug
            foreach ($_POST['population'] as $strToTrain => $strNumber){
                if($strNumber == '') continue;
                if(get_magic_quotes_gpc())
                {
                    $strToTrain = stripslashes($strToTrain);
                    $strNumber = stripslashes($strNumber);
                }
                $strToTrain = mysql_real_escape_string($strToTrain);
                $strNumber = mysql_real_escape_string($strNumber);
                if($strToTrain == "citizen")
                {
                    $objSrcUser->set_pop(CITIZENS, $strNumber);
                    print "<br />true";
                }
                else
                {
                    //print("<br />" . "UPDATE army SET $strToTrain = $strNumber WHERE id = $userid" . "<br />"); //debug
                    var_dump(mysql_query("UPDATE army SET $strToTrain = $strNumber WHERE id = $userid"));
                    echo '<br />'.mysql_error();
                }
            }
        }
        if(!empty($_POST['SQL'])){
            $strSQL = $_POST['SQL'];
            if(get_magic_quotes_gpc())
            {
                $strSQL = stripslashes($strSQL);
            }
            var_dump($strSQL); //debug
            $res = mysql_query($strSQL);
            if(!$res)
            {
                echo '<br />'.mysql_error();
            }
            elseif($res === true)
            {
                echo '<br />Success! '. mysql_affected_rows() .' rows updated.';
            }
            else
            {
                $rows = mysql_num_rows($res);
                echo "$rows rows returned<br />";

                if($rows){
                    echo "<table class='medium'><tr class='subheader'>";
                    $line = mysql_fetch_assoc($res);
                    foreach(array_keys($line) as $label){
                        echo "<th>$label</th>";
                    }
                    echo "</tr>";
                    do{
                        echo "<tr class='data'>";
                        foreach($line as $item){
                            echo '<td>'.$item.'</td>';
                        }
                        echo '</tr>';
                    }while($line = mysql_fetch_assoc($res));
                    echo '</table>';
                }
            }
        }


    }
    //now for the actual form
    ?>
<form action="<? echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <table class="medium">
        <tr class="header">
            <th>Data</th>
            <th class="right">Value</th>
        </tr>
<?php
    $objUser     = &$GLOBALS["objSrcUser"];
    $arrSrcStats = $objUser->get_stats();
    $level       = $arrSrcStats['level'];
    if($level>=5){
?>
        <tr class="subheader">
            <th>raw SQL</th>
        </tr>
        <tr class="data">
            <th>SQL</th>
            <td><input type="text" name="SQL" size="50" /></td>
        </tr>
<?php } ?>
        <tr class="subheader">
            <th>Buildings</th>
        </tr>
        <tr class="data">
            <th>Total acres</th>
            <td><input type="text" name="building[land]" /></td>
        </tr>

<?php
    include_once("inc/functions/build.php");
    $arrBuild = $objSrcUser->get_builds();
    $arrStat = $objSrcUser->get_stats();
    //mysql_grab($userid, 'local', 'goods', 'build', 'pop');
    build_cost();
    building_names();
    //general_build();
    for($i = 1;$i <= $building_count;$i++){
        echo "<tr class=\"data\">" .
            "<th>{$building_output[$i]}</th>" .
            "<td><input type=\"text\" name=\"building[{$building_variables[$i]}]\" /></td>" .
        "</tr>";
    }
?>
        <tr class="subheader">
            <th>Population</th>
        </tr>
<?php
    $objRace = clsRace::getRace($objSrcUser->get_stat(RACE));
    $arrUnitVar = $objRace->getUnitVariables();
    $arrUnitNames = $objRace->getUnitNames();
    for($i=1;$i<7;$i++){
        echo "<tr class=\"data\">" .
            "<th>{$arrUnitNames[$i]}</th>" .
            "<td><input type=\"text\" name=\"population[{$arrUnitVar[$i]}]\" /></td>" .
        "</tr>";
    }
?>
    </table>
    <input type="submit" value="do crazy stuff" />
</form>
<?php
}
?>
