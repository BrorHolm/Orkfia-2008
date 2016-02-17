<?php
//******************************************************************************
// pages verify.inc.php                                    Martel, July 18, 2006
//******************************************************************************

function include_verify_text()
{
    $objSrcUser  = &$GLOBALS['objSrcUser'];
    $arrSrcUsers = $objSrcUser->get_user_infos();
    $arrSrcStats = $objSrcUser->get_stats();
    $arrSrcPrefs = $objSrcUser->get_preferences();
    $strMail     = "Welcome to ORKFiA =) \n\nHere is your verification code: " .
                   $arrSrcPrefs[EMAIL_ACTIVATION] . "\n\n\nIt is recommended " .
                   "you tend to your tribe at least once per day. If you " .
                   "require help or this is your first time playing ORKFiA, " .
                   "you may find the forums and player guide useful.\n\nWe " .
                   "hope you enjoy your stay in ORKFiA =)" . SIGNED_ORKFIA;

    if ($objSrcUser->get_preference(EMAIL_ACTIVATION) == 'verified')
    {
        header("Location: main.php?cat=game&page=tribe");
    }

    $show = 'verify';
    if (isset($_GET['show'])) { $show = $_GET['show']; }

    echo get_verify_links($show);

    //==========================================================================
    // Option #1: Enter Verification Code
    //==========================================================================
    if (isset($_POST['code']))
    {
        $code = trim($_POST['code']);
        if ($code == $arrSrcPrefs[EMAIL_ACTIVATION])
        {
            $objSrcUser->set_preference(EMAIL_ACTIVATION, "'verified'");
            // Vay: changed "Thankyou" to "Thank you", "address, you" to
            // "address. You"
            echo $strDiv =
            '<div id="textMedium"><p>' .
                "Thank you for authenticating your email address. You may " .
                "now continue playing." .
                '</p><p>' .
                '<a href="main.php?cat=game&amp;page=tribe">Continue</a>' .
            '</p></div>';
        }
        else
        {
            echo $strDiv =
            '<div id="textMedium"><p>' .
                "Code was incorrect, please try again." .
                '</p><p>' .
                '<a href="main.php?cat=game&amp;page=verify">Return</a>' .
            '</p></div>';
        }
    }

    //==========================================================================
    // Option #2: Enter a new valid Email Address
    //==========================================================================
    // Changed to check for existing email address and to use
    //  quote_smart, also changed mail() to use escaped form because
    //  of how it works on some systems (a system call  - AI 24/10/06
    //==========================================================================
    elseif (isset($_POST['do2']) && $_POST['do2'] == "yes")
    {
        include_once('inc/functions/generic.php');
        $strEmailSafe = quote_smart($_POST['email']);
        if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
            '@'.
            '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
            '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $strEmailSafe))
        {
            echo $strDiv =
            '<div id="textMedium"><p>' .
                "Sorry! Invalid email address!" .
            '</p></div>';
        }
        else
        {
           $pref_search = mysql_query ("SELECT * FROM preferences WHERE email = $strEmailSafe");
           $pref_search = mysql_fetch_array($pref_search);
           if ($pref_search)
           {
               echo "That email is in use";
           }
           else
           {
                $objSrcUser->set_preference(EMAIL, $strEmailSafe);

                mail($_POST['email'], "ORKFiA Verification", $strMail, "From: ORKFiA <" . EMAIL_REGISTRATION . ">\r\nX-Mailer: PHP/" . phpversion() . "\r\nX-Priority: Normal");

                echo $strDiv =
                '<div id="textMedium"><p>' .
                    "Your email has been updated and a new verification code " .
                    "was sent to you. It may take a few minutes for it to " .
                    "arrive, thank you for your patience." .
                '</p></div>';
            }
        }
    }

    //==========================================================================
    // Option #3: Try Send the Mail Again to the Same Address
    //==========================================================================
    elseif (isset($_POST['confirm3']))
    {
        // Vay: changed email address from otakus server to sourceforge server

        mail($arrSrcPrefs[EMAIL], "ORKFiA Verification", $strMail,
                                                    "From: " . EMAIL_REGISTRATION);

        echo $strDiv =
            '<div id="textMedium"><p>' .
                "A new verification code was sent to you. It may take a few " .
                "minutes for it to arrive, thank you for your patience." .
            '</p></div>';

    }
    else
    {

        // Vay: changed "accessable" to "accessible"
        echo $strDiv =
        '<div id="textMedium"><p>' .
            '<b>It is now time to verify your email address</b>' .
        '</p></div>';

        switch ($show)
        {
            case "verify":

        //======================================================================
        // Option #1: Enter Verification Code
        //======================================================================

        echo $strForm =
            '<div class="center">' .
            '<h2>Enter your verification code:</h2>' .
            '<form method="post" action="main.php?cat=game&amp;page=verify">' .
                '<input name="code" size="13">' .
                '<br /><br />' .
                '<input type="submit" value="Submit" name="confirm">' .
            '</form>' .
            '</div>';

            break;
            case "change":

        //======================================================================
        // Option #2: Enter a new valid Email Address
        //======================================================================
        $strEmail = $objSrcUser->get_preference(EMAIL);

        echo $strForm =
            '<div class="center">' .
            '<h2>...or change email address:</h2>' .
            'Current email:' .
            '<br /><br />' .
            '<b>' . $strEmail . '</b>' .
            '<br /><br />' .
            '<form method="post" action="main.php?cat=game&amp;page=verify&amp;show=change">' .
                'New email: ' .
                '<input type="text" name="email">' .
                '<br /><br />' .
                '<input type="hidden" name="do2" value="yes">' .
                '<input type="submit" value="Send Code">' .
            '</form>' .
            '</div>';

             break;
             case "resend":

        //======================================================================
        // Option #3: Try Send it Again
        //======================================================================
        $strEmail = $objSrcUser->get_preference(EMAIL);

        // Vay: changed "Re-Send" to "Resend"
        echo $strForm =
            '<div class="center">' .
            '<h2>... or resend the code:</h2>' .
            'Current email:' .
            '<br /><br />' .
            '<b>' . $strEmail . '</b>' .
            '<br /><br />' .
            '<form method="post" action="main.php?cat=game&amp;page=verify&amp;show=resend">' .
                '<input type="submit" value="Send Code" name="confirm3">' .
            '</form>' .
            '</div>';

            break;
        }

        echo $strDiv =
        '<div id="textMedium">' .
            '<p>' .
            '<b>If you have not receieved your code, follow these steps: </b>' .
            '</p><p>' .
            '1. Check your junk folder. <br />' .
            '2. Allow mails sent from "' . EMAIL_REGISTRATION . '" <br />' .
            '3. Request a new code. <br />' .
            'or <br />' .
            '4. Change your email address.' .
            '</p><p>' .
            '<em>Delivery takes less than 30 seconds to most email hosts. If none ' .
            'of these will work, consult the World Forum.</em>' .
        '</p></div>';
    }
}

//==============================================================================
// Links at top of page                                     Martel, May 24, 2006
//==============================================================================
function get_verify_links($show = '')
{
    $arrPage = array(1 => 'verify', 'resend', 'change');
    $arrName = array(1 => 'Enter Verification', 'Request New Code', 'Change Address');

    $str = '<div class="center">| ';
    foreach ($arrPage as $key => $page )
    {
        if ($show != $page)
        {
            $str .= '<a href="main.php?cat=game&amp;page=verify&amp;show=' .
                   $page . '">' . $arrName[$key] . '</a>';
        }
        else
        {
            $str .= '<b>' . $arrName[$key] . '</b>';
        }
        $str .= ' | ';
    }

    $str .= '</div>';

    return $str;
}
?>
