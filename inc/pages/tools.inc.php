<?php
function include_tools_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];

    include_once('inc/pages/advisors.inc.php');
    $toolsText = get_guide_link($objSrcUser, 'tools');

    echo $toolsText;
}
?>
