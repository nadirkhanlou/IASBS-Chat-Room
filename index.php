<?php
session_start();
require_once "config.php";

$WelcomeMessage = "Welcome!";

include $SharedFolderPath."header.html";

//echo "<div id = 'variable-content-container'>";

if(isset($_SESSION['USER']))
{
    include $SharedFolderPath."content.html";
}
else
{
    include $ViewPath."login.html";
}

//echo "<div/>";

include $SharedFolderPath."footer.html";
?>
