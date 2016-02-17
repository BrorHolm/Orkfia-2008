<?php
function include_logout_text()
{
    setcookie("userid", "");
    header("Location: main.php?cat=main&page=main");
}

?>
