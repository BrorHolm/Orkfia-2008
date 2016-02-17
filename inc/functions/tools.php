<?

function call_target_find($landMin,$landMax,$strMin,$strMax,$fameMin,$fameMax,$races,$sort,$sortType)
{
    // ---One hell of an sql query start---

    $sql="SELECT tribe_name,alli_id,land,nw,fame,race,id FROM rankings_personal WHERE hours >= " . PROTECTION_HOURS . " AND alli_id > 10 AND ";
    $criteria=0; //this var is needed to check whether to put ADD in the query (the user may only want to search by 1 criteria)
    if(is_numeric($landMin))
    {
        $sql.="land>=$landMin ";
        $criteria=1;
    }
    if(is_numeric($landMax))
    {
        if($criteria>0)
        {
            $sql.="AND ";
        }

        $sql.="land<=$landMax ";
        $criteria=1;
    }
    if(is_numeric($strMin))
    {
        if($criteria>0)
        {
            $sql.="AND ";
        }

        $sql.="nw>=$strMin ";
        $criteria=1;
    }
    if(is_numeric($strMax))
    {
        if($criteria>0)
        {
            $sql.="AND ";
        }

        $sql.="nw<=$strMax ";
        $criteria=1;
    }
    if(is_numeric($fameMin))
    {
        if($criteria>0)
        {
            $sql.="AND ";
        }

        $sql.="fame>=$fameMin ";
        $criteria=1;
    }
    if(is_numeric($fameMax))
    {
        if($criteria>0)
        {
            $sql.="AND ";
        }

        $sql.="fame<=$fameMax ";
        $criteria=1;
    }
    $sql.="AND race IN (";

    $racesAmount=count($races);
    for($i=0;$i<$racesAmount;$i++)
    {
        $sql.="'$races[$i]'";

        if($i!=$racesAmount-1)
        {
            $sql.=",";
        }
    }

    $sql.=") ORDER BY $sort $sortType LIMIT 100";

    // ---One hell of an sql query end---

    $results=mysql_query($sql) or die(mysql_error());

if ($races[0] != "") {
?>
<table cellspacing="0" cellpadding="0" class="big" id="results">
    <tr class="header">
        <th colspan="7">Your Search Results</th>
    </tr>
    <tr class="subheader">
        <th>Tribe name</th>
        <th class="right">#</th>
        <th class="center">War</th>
        <th class="center">Race</th>
        <th class="right">Land</th>
        <th class="right">Strength</th>
        <th class="right">Fame</th>
    </tr>
<?
    include_once("inc/functions/war.php");

    while($arrTrgFind = mysql_fetch_array($results))
    {
        $warTarget = war_target($arrTrgFind['alli_id']);

        echo "<tr class=\"data\">";
        echo "<th><a href=\"main.php?cat=game&amp;page=external_affairs&amp;tribe=$arrTrgFind[id]&amp;aid=$arrTrgFind[alli_id]\">$arrTrgFind[tribe_name]</th>";
        echo "<td><a href=\"main.php?cat=game&amp;page=alliance&amp;aid=$arrTrgFind[alli_id]\">$arrTrgFind[alli_id]</td>";
        if ($warTarget == 0)
            echo "<td>&nbsp;</td>";
        else
            echo "<td class=\"negative center\">*WAR*</td>";
        echo "<td class=\"center\">$arrTrgFind[race]</td>";
        echo "<td>$arrTrgFind[land]</td>";
        echo "<td>$arrTrgFind[nw]</td>";
        echo "<td>$arrTrgFind[fame]</td>";
        echo "</tr>";
   }
?>
    </tbody>
</table>
<div class="center"><? echo mysql_num_rows($results); ?> Targets Found.</div>
<?
}
}
?>