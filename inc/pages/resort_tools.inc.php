<?php
//******************************************************************************
// Page resort_tools.inc.php                                  Created by: Martel
//                                                              January 14, 2006
// Modification history:
// January 14, 2006 - v.1 Created (Martel)
//
// ... be warned, this is no light-weight script (but fairly dynamic)
//
// Description: Add a file *.inc.php to the inc/staff/ dir and it will be found
//              and made available for anyone with lvl 6 access to add into the
//              DB (together with any restrictions to resort or level).
//
// Still to-do: * Detect removed tools
//              * more detailed activity logs
//******************************************************************************

function include_resort_tools_text()
{
    global $local_stats, $userid;
//     mysql_grab($userid, 'local', 'stats');

    // Two ways of pulling user stats; 1 is the above (bad) and 1 is with object
    // like below (good). Old resort tools require the above, but we want to
    // change that.

    $objUser     = &$GLOBALS['objSrcUser'];
    $arrSrcStats = $objUser->get_stats();
    $local_stats = $arrSrcStats;

    if (! $arrSrcStats['level'] > 0) //  && $arrSrcStats['kingdom'] < 11 ..commented out so I can use tools on devork (Martel)
    {
        echo
            '<div class="center">' .
                "Sorry, this page is restricted to ORKFiA Staff" .
            '</div>';
        include_game_down();
        exit;
    }
    $count = 0;
    if (isset($_POST['update_staff_tools']))
    {
        // Save new tools and access limits to DB
        foreach ( $_POST['new_tools'] as $details )
        {
            $toolname   = substr(stripslashes($details['toolname']),0,30);
            $new_name   = addslashes(trim(stripslashes($details['new_name'])));
            $req_level  = stripslashes($details['req_level']);
            $req_resort = stripslashes($details['req_resort']);

            $sql  = "INSERT INTO `staff_tools` SET
                    toolname    = '$toolname',
                    name_output = '$new_name',
                    req_level   = $req_level,
                    req_resort  = $req_resort";

            if ($new_name != "Tool Output Name")
            {
                if ( !$result = mysql_query($sql) )
                {
                    echo
                        '<div class="center">' .
                            "$count Resort tool(s) added" .
                        '</div>';
                    die ('<div class="center">' ."Could not save record!<br /><b>TOOL: $toolname</b>" . '</div>');
                }
                $count++;
            }
        }

        echo '<div class="center">' ."$count Resort tool(s) added<br /><br />";
        echo "<a href=\"main.php?cat=game&amp;page=resort_tools\">";
        echo "Back to Control Panel</a>" . '</div>';
    }
    elseif (isset($_POST['remove_staff_tool']))
    {
        $toolname = $_POST['remove_staff_tool'];
        $sql      = 'DELETE FROM staff_tools WHERE toolname = \'' . $toolname . '\'';

        if (! $result = mysql_query($sql))
        {
            die ("Could not remove tool!<br /><b>TOOL: $toolname</b>");
        }

        echo "$toolname removed from the list.<br /><br />";
        echo "<a href=\"main.php?cat=game&amp;page=resort_tools\">";
        echo "Back to Control Panel</a>";
    }
    elseif (isset($_GET['tool']))
    {
        // Log activity
        include_once('inc/functions/resort_tools.php');
        $tool = $_GET['tool'];
        log_staff_activity($tool);

        // Show tool
        echo '<div id="textBig">';
        echo "<a href=\"main.php?cat=game&amp;page=resort_tools\">";
        echo "<p>Back to Resort Tools</a></p>";
        include_once("inc/staff/$tool.inc.php");
        $function = "call_" . $tool . "_text";
        $function();
        echo "<p><a href=\"main.php?cat=game&amp;page=resort_tools\">";
        echo "Back to Resort Tools</a></p>";
        echo '</div>';
    }
    else
    {
        $staffmember = stripslashes($arrSrcStats['name']);
        echo '<div class="center">' . "<b>Welcome $staffmember =)</b></div><br />";

        // List all new pages found in /staff/
        if ($arrSrcStats['level'] == 6)
        {
            // Get all tool file names
            $arrfiles = scan_Dir('inc/staff');

            // Query DB for existing tools
            $query = "SELECT * FROM staff_tools";
            $result = mysql_query($query) or die("Error querying staff_tools!");
            while ($row = mysql_fetch_row($result))
            {
                $arrtools[] = $row[0];
            }

            // Add both arrays together and sort out the doubles
            $arrtotal = array_diff($arrfiles, $arrtools);
            $count = count($arrtotal);

            // Display remaining tools as 'new tools found'
            if ($count > 0)
            {
            ?>
            <form action="main.php?cat=game&amp;page=resort_tools" method="post">
            <input type="hidden" name="update_staff_tools" value="yes">

            <table class="medium" cellpadding="0" cellspacing="0">
            <tr class="header">
                <th colspan="4"><?=$count?> New Tools Found!</th>
            </tr>
            <tr class="subheader">
                <th>Tool</th>
                <td>Name</td>
                <td>Resort</td>
                <td>Level</td>
            </tr>

            <?php
                foreach($arrtotal as $id => $toolname)
                {
                    $link  = "<a href=\"main.php?cat=game&amp;page=resort_tools";
                    $link .= "&amp;tool=$toolname\">$toolname</a>";
                ?>

            <tr class="data">
            <th>
                <?=$link?>
            </th>

            <td>
                <input type="hidden" name="new_tools[<?=$id?>][toolname]"
                                                     value="<?=$toolname?>">
                <input size="30" name="new_tools[<?=$id?>][new_name]"
                                                   value="Tool Output Name">
            </td>

            <td>
                <Select size=1 name="new_tools[<?=$id?>][req_resort]">
                    <option value=0>All</option>
                    <option value=1>(#1)</option>
                    <option value=2>(#2)</option>
                    <option value=3>(#3)</option>
                    <option value=4>(#4)</option>
                    <option value=5>(#5)</option>
                    <option value=6>(#6)</option>
                    <option value=7>(#7)</option>
                    <option value=8>(#8)</option>
                    <option value=9>(#9)</option>
                    <option value=10>(#10)</option>
                </select>
            </td>

            <td>
                <Select size=1 name="new_tools[<?=$id?>][req_level]">
                    <option value=2>2</option>
                    <option value=3>3</option>
                    <option value=4>4</option>
                    <option value=5>5</option>
                    <option value=6>6</option>
                </select>
            </td>
            </tr>

                <?php
                }
            ?>
            <tr>
                <td class="center" colspan="4">
                <input type="submit" value="Add Tool(s)">
                </td>
            </tr>
            </table>
            </form>
            <br />
            <?php
            }

        }

        // List available tools
        echo build_table($arrSrcStats);
    }
}

function build_table($arrSrcStats)
{
    $level = $arrSrcStats['level'];
    $resort = $arrSrcStats['kingdom'];

    // Build QUERY
    $query  = "SELECT * FROM staff_tools WHERE ";
    $query .= "req_level <= $level";
    if ($level != '6')
    {
        $query  .= " AND (req_resort = $resort OR req_resort = 0)";
        $numcols = "3";
    }
    else
    {
        $numcols = "4";
    }
    $query .= " ORDER BY req_resort ASC, req_level DESC, name_output ASC";

    // Build TABLE
    $html = '<table class="medium" cellpadding="0" cellspacing="0">' .
            '<tr class="header">' .
                '<th colspan="' . $numcols . '">List of Available Tools</th>' .
            '</tr>' .
            '<tr class="subheader">' .
                '<th>Name</th>';

    if ($numcols != "4")
    {
        $html .= '<td>Resort</td>' .
                 '<td>Level</td>';
    }
    else
    {
        $html .= '<td>Resort</td>' .
                 '<td>Level</td>' .
                 '<td>Action</td>';
    }
    $html .= '</tr>';

    // Create table rows from resultset
    $result = mysql_query($query) or die("Error2 querying staff_tools!");
    while ($row = mysql_fetch_row($result))
    {
        $hex = '';
        switch($row[3])
        {
            case "0";
                $hex = "#ff4500"; // red (Eng)
                if($row[2] >= 5)
                    $hex = "#0F0F0F"; // all resort heads = black
            break;
            case "1";
                $hex = "#ff4500"; // red (Eng)
            break;
            case "2";
                $hex = "#4169e1"; // blue (L&O)
                if($row[2] >= 5)
                    $hex = "#2047c0"; // resort head = darker
            break;
            case "3";
                $hex = "forestgreen"; //green (Ops)
                if($row[2] >= 5)
                    $hex = "#206525"; // resort head = darker
            break;
            case "4";
                $hex = "#ffd700"; // yellow (M&PR)
                if($row[2] >= 5)
                    $hex = "#0F0F0F"; // resort head = darker
            break;
            case "6";
                $hex = "#8b4513"; // brown (R&D)
                if($row[2] >= 5)
                    $hex = "#0F0F0F"; // resort head = darker
            break;
        }

        $link  = "<a href=\"main.php?cat=game&amp;page=resort_tools";
        $link .= "&amp;tool=$row[0]\">$row[1]</a>";

        $html .= '<tr class="data">' .
                     '<th>' . $link . '</th>';

        if ($numcols != "4")
        {
            $html .= '<td bgcolor="' . $hex . '">(#' . $row[3] . ')</td>' .
                     '<td>Lvl ' . $row[2] . '</td>';
        }
        else
        {
            $html .= '<td bgcolor="' . $hex . '">(#' . $row[3] . ')</td>' .
                     '<td>Lvl ' . $row[2] . '</td>' .
                     '<td>' .
                        '<form action="main.php?cat=game&amp;page=resort_tools" method="post" style="margin: 0;">' .
                            '<input type="hidden" name="remove_staff_tool" value="'. $row[0] .'">' .
                            '<input type="submit" value="Remove">' .
                        '</form>' .
                     '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Return it
    return $html;
}

function scan_Dir($dir)
{
   $arrfiles = array();
   if (is_dir($dir))
   {
       if ($handle = opendir($dir))
       {
           while (false !== ($file = readdir($handle)))
           {
               if ($file != "archived" && $file != "." && $file != "..")
               {
                   $arrfiles[] = basename($file, ".inc.php");
               }
           }
       }
   }
   return $arrfiles;
}

?>
