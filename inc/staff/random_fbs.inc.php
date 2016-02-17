<?php
//******************************************************************************
// staff tool age_handler.inc.php                     Martel, September 29, 2006
//******************************************************************************
include_once('inc/classes/clsAge.php');
include_once('inc/classes/clsGame.php');

function call_age_handler_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $objGame    = new clsGame();

    //==========================================================================
    // Take care of sent form data
    //==========================================================================
    if (isset($_POST['i_age_start']) && isset($_POST['i_age_end']))
    {
        // Get post variables
        $iAgeStart = $_POST['i_age_start'];
        $iAgeEnd   = $_POST['i_age_end'];

        // Get last age's id or 0
        $iLastAge  = 0;
        $iLastAge  = mysql_result(mysql_query("SELECT age_number FROM ages ORDER BY age_number DESC LIMIT 0, 1"), 0);
        $iLastAge++;

        // Instantiate object and save to db
        $objNewAge = new clsAge($iLastAge, $iAgeStart, $iAgeEnd);
        $objNewAge->saveAge();
    }
    elseif (isset($_POST['i_age_to_make_now']))
    {
        $iAgeToMakeNow = $_POST['i_age_to_make_now'];
        $objGame->set_game_time(AGE_NUMBER, $iAgeToMakeNow);
    }
    elseif (isset($_POST['i_age_to_delete']))
    {
        $iAgeToDelete = $_POST['i_age_to_delete'];
        $objNewAge = new clsAge($iAgeToDelete);
        $objNewAge->deleteAge();
    }

    //==========================================================================
    // Show Task Links
    //==========================================================================
    echo 'Select Task:' .
         '<br /><br />' .
         ' | ' .
         '<a href="main.php?cat=game&amp;page=resort_tools' .
         '&amp;tool=' . $tool . '&amp;show=create">Create Age</a>' .
         ' | ' .
         '<a href="main.php?cat=game&amp;page=resort_tools' .
         '&amp;tool=' . $tool . '&amp;show=remove">Remove Age</a>' .
         ' | ' .
         '<a href="main.php?cat=game&amp;page=resort_tools' .
         '&amp;tool=' . $tool . '&amp;show=current">Current Age</a>' .
         ' | ' .
         '<br /><br />';

    //==========================================================================
    // Show table with existing ages
    //==========================================================================
    echo $strAgesTable =
        '<table class="small" cellspacing="0" cellpadding="0">' .
            '<tr class="header">' .
                '<th colspan="3">' . 'Ages' . '</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th>' . "Age #" . '</th>' .
                '<th class="right">' . "Start" . '</th>' .
                '<th class="right">' . "End" . '</th>' .
            '</tr>';

    $resSQL = mysql_query("SELECT * FROM ages ORDER BY age_number ASC");
    while($arrRES = mysql_fetch_array($resSQL))
    {
        echo $strAgesTable =
            '<tr class="data">' .
                '<th>' . $arrRES['age_number'] . '</th>' .
                '<td>' . $arrRES['year_start'] . ' OE</td>' .
                '<td>' . $arrRES['year_end'] . ' OE</td>' .
            '</tr>';
    }

    echo $strAgesTable =
        '</table>' .
        '<br />';

    //==========================================================================
    // Which option page to show
    //==========================================================================
    $strShow = '';
    if (isset($_GET['show']))
        $strShow = strval($_GET['show']);

    switch ($strShow)
    {
        case "create":

            echo $strForm =
                '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=' . $tool . '&amp;show=create">' .
                    '<label>First Year of new age: ' .
                    '<input type="text" size="6" maxlength="4" name="i_age_start" />' .
                    '</label>' .
                    '<br /><br />' .
                    '<label>Last Year of new age: ' .
                    '<input type="text" size="6" maxlength="4" name="i_age_end" />' .
                    '</label>' .
                    '<br /><br />' .
                    '<input type="submit" value="Create" />' .
                    '<br /><br />' .
                '</form>';

        break;
        case "remove":

            $iLastAge = @mysql_result(mysql_query("SELECT age_number FROM ages ORDER BY age_number DESC LIMIT 0, 1"), 0);

            echo $strForm =
                '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=' . $tool . '&amp;show=remove">' .
                    '<label>Age to Remove: ' .
                    '<input type="text" size="6" maxlength="4" name="i_age_to_delete" value="' . $iLastAge . '" />' .
                    '</label>' .
                    '<br /><br />' .
                    '<input type="submit" value="Remove" />' .
                    '<br /><br />' .
                '</form>';

        break;
        case "current":

            $iAgeNumber = $objGame->get_game_time(AGE_NUMBER);

            echo $strForm =
                '<form method="post" action="main.php?cat=game&amp;page=resort_tools&amp;tool=' . $tool . '&amp;show=current">' .
                    '<label>Current Age: ' .
                    '<input type="text" size="6" maxlength="4" name="i_age_to_make_now" value="' . $iAgeNumber . '" />' .
                    '</label>' .
                    '<br /><br />' .
                    '<input type="submit" value="Save New" />' .
                    '<br /><br />' .
                '</form>';

        break;
    }
}
?>
