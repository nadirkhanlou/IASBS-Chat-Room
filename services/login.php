<?php
session_start();
require_once "model/user.php";

if(isset($_POST['handle']))
{
    $result = array("success" => true, "errorMessage" => "", "user" => "");

    $user = new user(null, $_POST['handle'], null, $_POST['password']);
    if(!$user.CheckPassword())
    {
        $result["success"] = false;
        $result["errorMessage"] = "The handle or password is incorrect.";
    }
    else
    {
        $accessibleUser = new accessibleUser($user);
        $_SESSION["USER"] = serialize($accessibleUser);
        $result["user"] = $accessibleUser;
    }

    echo serialize($result);
}

?>