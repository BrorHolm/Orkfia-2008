<?
function include_targetfinder_text()
{
    global $Host;
    include_once('inc/functions/tools.php');
    include_once("inc/classes/clsUser.php");
    require_once('inc/races/clsRace.php');


    // value initializations for the echos in the form

    if(!empty($_POST))
    {
        $landMin=stripslashes($_POST["landMin"]);
        $landMax=stripslashes($_POST["landMax"]);
        $strMin=stripslashes($_POST["strMin"]);
        $strMax=stripslashes($_POST["strMax"]);
        $fameMin=stripslashes($_POST["fameMin"]);
        $fameMax=stripslashes($_POST["fameMax"]);
        if(empty($_POST["races"])) {
            echo "<div class=\"center negative\">You need to select at least 1 race</div>";
            $races[0] = "";
        } else {
        $races=$_POST["races"];
        }
        $sort=$_POST["sort"];
        $sortType=$_POST["sortType"];
        $error=false;
        // adding a lil check or max was higher then min
        if(!empty($landMax)&&$landMin>$landMax)
        {
            echo "<div class=\"center negative\">Your land minimum is higher than land maximum!</div>";
            $error=true;
        }
        if(!empty($strMax)&&$strMin>$strMax)
        {
            echo "<div class=\"center negative\">Your strength minimum is higher than strength maximum!</div>";
            $error=true;
        }
        if(!empty($fameMax)&&$fameMin>$fameMax)
        {
            echo "<div class=\"center negative\">Your fame minimum is higher than fame maximum!</div>";
            $error=true;
        }
        if(!is_numeric($landMin)&&!is_numeric($landMax)&&!is_numeric($strMin)&&!is_numeric($strMax)&&!is_numeric($fameMin)&&!is_numeric($fameMax))
        {
            echo "<div class=\"center negative\">You must fill at least one search criterium!</div>";
            $error=true;
        }

    }
    else
    {
        $landMin="";
        $landMax="";
        $strMin="";
        $strMax="";
        $fameMin="";
        $fameMax="";
        /*$races[0]="Uruk Hai";
        $races[1]="Oleg Hai";
        $races[2]="Mori Hai";
        $races[3]="Dark Elf";
        $races[4]="Wood Elf";
        $races[5]="High Elf";
        $races[6]="Dwarf";
        $races[7]="Brittonian";
        $races[8]="Viking";
        $races[9]="Raven";
        $races[10]="Dragon";
        $races[11]="Eagle";
        $races[12]="Nazgul";
        $races[13]="Undead";
        $races[14]="Spirit";*/
        $races = clsRace::getRaces();
        $sort="land";
        $sortType="DESC";
    }
?>

<form id="tFinder" method="post" action="main.php?cat=game&amp;page=targetfinder&amp;z=do">
    <table cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="header">
                <th colspan="3">Target Finder</th>
            </tr>
            <tr class="subheader">
                <th>Type</th>
                <th colspan="2">Option</th>
            </tr>
            <tr class="data">
                <th>Land Size: </th>
                <td>From <input type="text" name="landMin" size="8" maxlength="6" value="<? echo $landMin; ?>" /></td>
                <td>To <input type="text" name="landMax" size="8" maxlength="6" value="<? echo $landMax; ?>" /></td>
            </tr>
            <tr class="data">
                <th>Strength: </th>
                <td>From <input type="text" name="strMin" size="8" maxlength="8" value="<? echo $strMin; ?>" /></td>
                <td>To <input type="text" name="strMax" size="8" maxlength="8" value="<? echo $strMax; ?>" /></td>
            </tr>
            <tr class="data">
                <th>Fame: </th>
                <td>From <input type="text" name="fameMin" size="8" maxlength="6" value="<? echo $fameMin; ?>" /></td>
                <td>To <input type="text" name="fameMax" size="8" maxlength="6" value="<? echo $fameMax; ?>" /></td>
            </tr>
            <tr class="data">
                <th>
                    Races:
                </th>
                <td colspan="2" class="center">
                    <table>
                        <tbody>
<?php /*                            <tr class="data">
                                <td class="left"><input name="races[]" type="checkbox" value="Uruk Hai" <? if(in_array("Uruk Hai",$races)) echo "checked=\"checked\""; ?> /> Uruk Hai</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Oleg Hai" <? if(in_array("Oleg Hai",$races)) echo "checked=\"checked\""; ?> /> Oleg Hai</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Mori Hai" <? if(in_array("Mori Hai",$races)) echo "checked=\"checked\""; ?> /> Mori Hai</td>
                            </tr>
                            <tr class="data">
                                <td class="left"><input name="races[]" type="checkbox" value="Dark Elf" <? if(in_array("Dark Elf",$races)) echo "checked=\"checked\""; ?> /> Dark Elf</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Wood Elf" <? if(in_array("Wood Elf",$races)) echo "checked=\"checked\""; ?> /> Wood Elf</td>
                                <td class="left"><input name="races[]" type="checkbox" value="High Elf" <? if(in_array("High Elf",$races)) echo "checked=\"checked\""; ?> /> High Elf</td>
                            </tr>
                            <tr class="data">
                                <td class="left"><input name="races[]" type="checkbox" value="Dwarf" <? if(in_array("Dwarf",$races)) echo "checked=\"checked\""; ?> /> Dwarf</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Brittonian" <? if(in_array("Brittonian",$races)) echo "checked=\"checked\""; ?> /> Brittonian</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Viking" <? if(in_array("Viking",$races)) echo "checked=\"checked\""; ?> /> Viking</td>
                            </tr>
                            <tr class="data">
                                <td class="left"><input name="races[]" type="checkbox" value="Raven" <? if(in_array("Raven",$races)) echo "checked=\"checked\""; ?> /> Raven</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Dragon" <? if(in_array("Dragon",$races)) echo "checked=\"checked\""; ?> /> Dragon</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Eagle" <? if(in_array("Eagle",$races)) echo "checked=\"checked\""; ?> /> Eagle</td>
                            </tr>
                            <tr class="data">
                                <td class="left"><input name="races[]" type="checkbox" value="Nazgul" <? if(in_array("Nazgul",$races)) echo "checked=\"checked\""; ?> /> Nazgul</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Undead" <? if(in_array("Undead",$races)) echo "checked=\"checked\""; ?> /> Undead</td>
                                <td class="left"><input name="races[]" type="checkbox" value="Spirit" <? if(in_array("Spirit",$races)) echo "checked=\"checked\""; ?> /> Spirit</td>
                            </tr> */
                        foreach(clsRace::getRaces() as $number => $race){
                            if(($number % 3) == 0)
                                echo '<tr class="data">';
                            echo "<td class=\"left\"><input name=\"races[]\" type=\"checkbox\" value=\"$race\" ";
                            if(in_array($race,$races))
                                echo 'checked="checked"';
                            echo " /> $race</td>";
                            if(($number % 3) == 2)
                                echo '</tr>';
                        }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                    <a href="#" onclick="var races=document.getElementById('tFinder')['races[]']; for(var i=0,len=races.length;i<len;i++) races[i].checked=true;">Check All</a>
                    |
                    <a href="#" onclick="var races=document.getElementById('tFinder')['races[]']; for(var i=0,len=races.length;i<len;i++) races[i].checked=false;">Uncheck All</a>
                </td>
            </tr>
            <tr class="data">
                <th>Sort By: </th>
                <td colspan="2">
                    <select name="sort">
                        <option value="land" <? if($sort=="land") echo "selected=\"selected\""; ?>>Land</option>
                        <option value="nw" <? if($sort=="nw") echo "selected=\"selected\""; ?>>Strength</option>
                        <option value="fame" <? if($sort=="fame") echo "selected=\"selected\""; ?>>Fame</option>
                    </select>
                </td>
            </tr>
            <tr class="data">
                <th>Sort Type: </th>
                <td colspan="2">
                    <select name="sortType">
                        <option value="DESC" <? if($sortType=="DESC") echo "selected=\"selected\""; ?>>Descending</option>
                        <option value="ASC" <? if($sortType=="ASC") echo "selected=\"selected\""; ?>>Ascending</option>
                    </select>
                </td>
            </tr>
            <tr class="data">
                <td colspan="3"><input type="submit" name="submit" value="Find Targets" /></td>
            </tr>
        </tbody>
    </table>
</form>
<br />
<?
    if(!empty($_POST))
    {
        if(!$error) // call the actual search & print function if something is filled
        {
            call_target_find($landMin,$landMax,$strMin,$strMax,$fameMin,$fameMax,$races,$sort,$sortType);
        }
    }
}
?>
