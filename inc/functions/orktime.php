<?php
//******************************************************************************
// functions orktime.php                              Martel, September 09, 2006
//
// Description: Functions related to the new orkfia calendar. (1 hour = 1 month)
//******************************************************************************

//==============================================================================
// Convertion functions (hours to orkfia years+month and vice versa)      Martel
//==============================================================================

// Input: int hours Returns: array with ['years'] and ['months']
function hoursToYears($iHours)
{
    $iYears  = floor($iHours / 12);
    $iMonths = floor($iHours % 12);

    $arrReturn['years']  = $iYears;
    $arrReturn['months'] = $iMonths;

    return $arrReturn;
}

// Input: int years OR years and months Returns: int hours
function yearsToHours($iYears, $iMonths = 0)
{
    $iHours = ($iYears * 12) + $iMonths;

    return $iHours;
}

//==============================================================================
// Calculator functions (or whatever to call them)                        Martel
//==============================================================================

// Get years+months between two hour integers
function yearsBetweenHours($iHours1, $iHours2)
{
    $iDiff = max(0, $iHours1 - $iHours2);

    return hoursToYears($iDiff);
}

// Get years+months between two hour integers
function yearsFromTo($iHoursLower, $iHoursHigher)
{
    $iDiff = abs($iHoursUpper - $iHoursLower);

    return hoursToYears($iDiff);
}

// Get time of teh future
function futureTimestamp($iEndHour, $iGameHours)
{
    $arrOrkDate = yearsBetweenHours($iEndHour, $iGameHours);

    if ($arrOrkDate['years'] == 0 && $arrOrkDate['months'] == 0)
    {
        return 0;
    }
    else
    {
        $temp       = yearsToHours($arrOrkDate['years'], $arrOrkDate['months']);
        $timestamp  = date(TIMESTAMP_FORMAT, strtotime("+$temp hours"));
    }

    return $timestamp;
}


function getAgeLength($iAge)
{
    $arrGameAge = mysql_fetch_array(mysql_query("SELECT * FROM admin_ages WHERE age = '$iAge' ORDER BY age DESC LIMIT 0, 1"));

    $iYearStart = $arrGameAge['year_start'];
    $iYearEnd   = $arrGameAge['year_end'];

    // Hard coded, shame on me but I rather do DB stuff later
    $AGE_START_MONTH  = 8;
    $AGE_LENGTH_HOURS = 996;

    // This should be re-usable
    $iAgeStartHour = yearsToHours($iYearStart, $AGE_START_MONTH);
    $iAgeEndHour   = $iAgeStartHour + $AGE_LENGTH_HOURS;

    return yearsBetweenHours($iAgeEndHour, $iGameHours);
}

//         // OrkDate since age start
//         $arrOrkDate = yearsBetweenHours($iAgeStartHour, 297);
//         // hours since age start !erueka
//         echo $temp = yearsToHours($arrOrkDate['years'], $arrOrkDate['months']);
//
//         // OrkDate from age start until age end
//         $arrOrkDate = yearsBetweenHours($iAgeStartHour, $iAgeEndHour);

// Returns: Years left to age end (faulty, needs to be based on game hours!)
function getYearsLeft2End()
{
    $strSQL  = "SELECT age_end FROM records WHERE id = 1";
    $arrRes  = mysql_fetch_array(mysql_query($strSQL));
    $iAgeEnd = $arrRes['age_end'];

    $timediff = $iAgeEnd - time();
    $hours    = $timediff / 3600;

    $iYears  = floor($hours / 12);
    $iMonths = floor($hours % 12);

    $arrReturn['years'] = $iYears;
    $arrReturn['months'] = $iMonths;

    return $arrReturn;
}

// Returns: Years left to age start (faulty, needs to be based on game hours!)
function getYearsLeft2Start()
{
    $strSQL    = "SELECT agestart FROM records WHERE id = 1";
    $arrRes    = mysql_fetch_array(mysql_query($strSQL));
    $iAgeStart = $arrRes['agestart'];

    $timediff = $iAgeStart - time();
    $hours    = $timediff / 3600;

    $iYears  = floor($hours / 12);
    $iMonths = floor($hours % 12);

    $arrReturn['years'] = $iYears;
    $arrReturn['months'] = $iMonths;

    return $arrReturn;
}

//==============================================================================
// Function that returns the Age information on top of layout ingame
//==============================================================================
function get_age_displays(&$objGame, &$objAge, $blnCheck)
{
    $iGameHours = $objGame->get_game_time(HOUR_COUNTER);
    $strAge = '';
    $strAgeExtra = '';

    if ($blnCheck == TRUE)
    {
        // Output for "Age X"
        $strAge      = 'Age ' . $objAge->getAgeNumber();
        $iOrkDate    = hoursToYears($iGameHours);
        if ($objAge->getFirstYear() <= ($iOrkDate['years'] + 14) && $objAge->getLastYear() > $iOrkDate['years'])
        {
            $more_than = '';

            // Calculate difference between "current hour" and "hour of age start"
            $iStartHours     = yearsToHours($objAge->getFirstYear());
            $arrODUntilStart = yearsBetweenHours($iStartHours, $iGameHours);

            // and "hour of age end"
            $iEndHours       = yearsToHours($objAge->getLastYear());
            $arrODUntilEnd   = yearsBetweenHours($iEndHours, $iGameHours);

            // if time left is more than 2 years we hide months and add "more than"
            if ($arrODUntilStart['years'] >= 2)
                $more_than = "more than ";
            elseif ($iStartHours == 0 && $arrODUntilEnd['years'] >= 2)
                $more_than = "more than ";

            // It will start counting down from 14 years
            if ($arrODUntilStart['years'] < 14 && ($arrODUntilStart['years'] > 0 || $arrODUntilStart['months'] > 0))
            {
                $strAgeExtra .= get_correct_language($arrODUntilStart, 'This age will start in ' . $more_than);
            }
            elseif ($arrODUntilEnd['years'] < 14 && ($arrODUntilEnd['years'] > 0 || $arrODUntilEnd['months'] > 0))
            {
                $strAgeExtra = get_correct_language($arrODUntilEnd, 'This age will end in ' . $more_than);
            }
        }
        else
        {
            $strAge = '"Between Ages Time"';
        }
    }
    else
    {
        $strAge = '"Between Ages Time"';
    }

    $arrReturn['str_age']       = $strAge;
    $arrReturn['str_age_extra'] = $strAgeExtra;

    return $arrReturn;
}

//==============================================================================
// When displaying "This age will start in" and
// + (X year( s ) ( and Y month( s ) )) + (Y month( s ))"
// then the later part IS what this function does, typically "X years and Y months"
//==============================================================================
function get_correct_language($arrOrkfiaDate, $strAgeExtra)
{
    // Year
    if ($arrOrkfiaDate['years'] > 1)
    {
        $strAgeExtra .= $arrOrkfiaDate['years'] . ' years';
    }
    elseif ($arrOrkfiaDate['years'] == 1)
    {
        $strAgeExtra .= $arrOrkfiaDate['years'] . ' year';
    }

    // Months (will only be shown the last 2 years)
    if ($arrOrkfiaDate['years'] < 2 && $arrOrkfiaDate['years'] > 0 && $arrOrkfiaDate['months'] >= 1)
    {
        $strAgeExtra .= ' and ';
        if ($arrOrkfiaDate['months'] > 1)
            $strAgeExtra .= $arrOrkfiaDate['months'] . ' months';
        elseif ($arrOrkfiaDate['months'] == 1)
            $strAgeExtra .= $arrOrkfiaDate['months'] . ' month';
    }
    elseif ($arrOrkfiaDate['years'] < 2 && $arrOrkfiaDate['years'] > 0 && $arrOrkfiaDate['months'] == 1)
    {
        $strAgeExtra .= ' and ' . $arrOrkfiaDate['months'] . ' month';
    }
    elseif ($arrOrkfiaDate['years'] == 0 && $arrOrkfiaDate['months'] > 1)
    {
        $strAgeExtra .= $arrOrkfiaDate['months'] . ' months';
    }
    elseif ($arrOrkfiaDate['years'] == 0 && $arrOrkfiaDate['months'] == 1)
    {
        $strAgeExtra .= $arrOrkfiaDate['months'] . ' month';
    }

    return $strAgeExtra;
}

?>