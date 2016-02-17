<?
function call_move_text()
{
    global $local_stats,$id,$confirm,$target,$tool;

    include_once('inc/functions/resort_tools.php');
    check_access($tool);

    echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">" .
         "Input user id: <input name=id size=5><br>" .
         "Move to alliance: <input name=target size=5><br>" .
         '<label>Check if touring:</label>' .
         '<input type="checkbox" name="tourer">' .
         "<input type=submit value=Move name=confirm>" .
         "</form>" .
         "<br><br>";

    if ($confirm && $id && $target)
    {
        //if ($target == 1 && $local_stats[LEVEL] < 5)
        //    $target = 2;

        echo "User $id has been move to alliance $target";

        $orkTime = date(TIMESTAMP_FORMAT);
        $search = mysql_query("SELECT * FROM stats WHERE id = $id");
        $search =  mysql_fetch_array($search);
        //mysql_query("UPDATE stats SET kingdom = $target, type ='player', invested = 0 where id = $id");
        //mysql_query("UPDATE rankings_personal SET alli_id = $target where id = $id");
        //mysql_query("UPDATE stats SET vote = 0, invested = 0 WHERE vote = $id");
        //mysql_query("UPDATE goods SET credits = 0, market_money = 0, market_food = 0, market_soldiers = 0, market_wood = 0 WHERE id = $id");
        move_tribe($id, $target);
        mysql_query("INSERT INTO news (id, time, ip, type, duser, ouser, result, text, kingdom_text, kingdoma, kingdomb) VALUES ('', '$orkTime', '---', 'Mod Move', '0', '0', '1', '', '<font class=\"indicator\">$search[tribe] was moved from #$search[kingdom] to #$target!</font>', '$search[kingdom]', '$target')");
    }

}
//move a tribe, abstracted out
function move_tribe($id, $target){
    $objSrcUser = new clsUser($id);
    $local_stats = $objSrcUser->get_stats();
    if ($target == 1 && $local_stats[LEVEL] < 5)
        $target = 2;

    mysql_query("UPDATE stats SET kingdom = $target, type ='player', invested = 0, vote = 0 where id = $id");
    mysql_query("UPDATE rankings_personal SET alli_id = $target where id = $id");
    mysql_query("UPDATE goods SET credits = 0, market_money = 0, market_food = 0, market_soldiers = 0, market_wood = 0 WHERE id = $id");

}
?>
