<?php

//==============================================================================
//  This tool exists to let people with level 4 (the tour heads or something)
// make the tribes of the tourers as small as possible - AI
//==============================================================================
function call_tourers_text(){
    require_once('inc/functions/resort_tools.php');
    check_access($_GET['tool']);

    require_once('inc/functions/mail.php');

    // first the "actually do something if asked" thing
    if (isset($_POST['id']) && isset($_POST['submit']) && isset($_POST['sure']) && $_POST['sure'] == 'yes')
    {
        //get the old values
        $objTrgUser = new clsUser($_POST['id']);
        $arrBuilds  = $objTrgUser->get_builds();
        $arrArmys   = $objTrgUser->get_armys();
        $intFame    = $objTrgUser->get_stat(FAME);

        //make the new values
        $keysBuilds = array_keys($arrBuilds);
        $valsBuilds = array_fill(0, count($arrBuilds), 0);
        $arrBuilds  = array_combine($keysBuilds, $valsBuilds);
        $arrBuilds[ID] = $_POST['id']; //DO NOT FORGET
        $arrBuilds[LAND] = 1; //prevent div0s

        $keysArmys  = array_keys($arrArmys);
        $valsArmys  = array_fill(0, count($arrArmys), 0);
        $arrArmys   = array_combine($keysArmys, $valsArmys);
        $arrArmys[ID] = $_POST['id']; //DO NOT FORGET

        $intFame    = 0;

        $arrUserInfo = array
        (
            NEXT_ATTACK => 1,
            PAUSE_ACCOUNT => 48
        );

        //set the new values
        $objTrgUser->set_builds($arrBuilds);
        $objTrgUser->set_armys($arrArmys);
        $objTrgUser->set_stat(FAME, $intFame);

        //update the tribe too
        require_once('inc/functions/update.php');
        require_once('inc/functions/update_ranking.php');
        doUpdateRankings($objTrgUser, true);
        check_to_update($objTrgUser->get_userid());
        //do this afterwards, otherwise the updaterankings doesn't work too well
        $objTrgUser->set_user_infos($arrUserInfo);

        //tell them about it
        send_mail($GLOBALS['objSrcUser']->get_userid(), $_POST['id'], "You have been tourified", "This is an automated message indicating that I have used the 'Tourify' tool to turn your tribe into a tourer tribe.");
        echo "User {$_POST['id']} has been tourified. ORKFiA mail has been sent to let him know.<br /><br /><br />";

    }
    echo "'Tourifying' a tribe means setting it to 1 land, 0 fame, removing all military and suspending it. " .
        "Please make sure you're tourifying the right tribe, because the only way to undo this is resetting." .
        "<br /><br /><form method=\"post\" action=\"{$_SERVER['REQUEST_URI']}\">" .
        "<label for=\"id\">User id of tribe to tourify </label>" .
        "<input type=\"text\" id=\"id\" name=\"id\" size=\"5\" maxlength=\"5\" /><br />" .
        "<input type=\"checkbox\" id=\"sure\" name=\"sure\" value=\"yes\" />" .
        "<label for=\"sure\"> I'm sure I want to tourify this tribe</label><br />" .
        "<input type=\"submit\" name=\"submit\" value=\"tourify\" />" .
        "</form>";

}

function array_combine($keys, $values) {
    $result = array() ;
    while( ($k=each($keys)) && ($v=each($values)) ) $result[$k[1]] = $v[1] ;
    return $result ;
}

?>
