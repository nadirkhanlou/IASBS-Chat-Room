<?php
session_start();
require_once "config.php";
require_once "model/user.php";

$WelcomeMessage = "Welcome!";

include $SharedFolderPath."header.html";

if(isset($_SESSION['USER']))
{
    $user = unserialize($_SESSION['USER']);
    $fullName = $user->FullName;
    $handle = $user->Handle;
    $phoneNumber = $user->PhoneNumber;

    include $SharedFolderPath."content.html";
}
else
{
    include $ViewPath."login.html";
}

include $SharedFolderPath."footer.html";
?>
