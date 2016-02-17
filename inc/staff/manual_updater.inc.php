<?php

function call_manual_updater_text()
{
    global $upper, $lower, $submit1, $submit2, $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    // 30 minutes rather than 30 seconds until script times out
    set_time_limit(1800);

    if ($upper && $lower && $submit1)
    {
        $checker = 0;
        for ($i=$lower; $i<$upper; $i++)
        {
//          echo "<br>" . $i . " :: ";
            $check = mysql_query("Select * from user where id = $i");
            $check = mysql_fetch_array($check);
            $check3 = mysql_query("Select * from stats where id = $i");
            $check3 = mysql_fetch_array($check3);

            if ($check[USERNAME] && $check3[TRIBE])
            {
//              echo $i;
                include_once('inc/functions/update.php');
                check_to_update($check[ID]);

                include_once('inc/classes/clsUser.php');
                $objTmpUser = new clsUser($check[ID]);

                include_once('inc/functions/update_ranking.php');
                doUpdateRankings($objTmpUser, 'yes');

                $checker++;
            }
        }
        ECHO "<br><br>$checker successfully updated =)<br><br>";
    }
    elseif ($upper && $lower && $submit2)
    {
        $checker = 0;
        for ($i=$lower; $i<$upper; $i++)
        {
            echo "<br>" . $i . " :: ";
            $check = mysql_query("Select * from user where id = $i");
            $check = mysql_fetch_array($check);
            $check3 = mysql_query("Select * from stats where id = $i");
            $check3 = mysql_fetch_array($check3);

            if ($check[USERNAME] && $check3[TRIBE])
            {
                echo $i;
                include_once('inc/classes/clsUser.php');
                $objTmpUser = new clsUser($check[ID]);

                include_once('inc/functions/update_ranking.php');
                doUpdateRankings($objTmpUser, 'yes');

                $checker++;
            }
        }
        ECHO "<br><br>$checker successfully updated =)<br><br>";
    }

    IF($upper) {$bleh = $upper + 100;} ELSE {$bleh = 100; $upper = 2;}

    echo $strForm =
        '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">' .
             'Input lower: <input name="lower" size="5" value="' . $upper . '">' .
             '<br />' .
             'Input upper: <input name="upper" size="5" value="' . $bleh . '">' .
             '<br /><br />' .
             '<input type="submit" value="Update" name="submit1">&nbsp;' .
             '<input type="submit" value="Rankings Only" name="submit2">' .
         '</form>';
}

?>