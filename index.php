<?php
session_start();
require "config.php";

$WelcomeMessage = "Welcome!";

include $SharedFolderPath."header.html";
if(isset($_SESSION['USER']))
{
    include $SharedFolderPath."content.html";
}
else
{
    include $ViewPath."login.html";
}

include $SharedFolderPath."footer.html";
?>
