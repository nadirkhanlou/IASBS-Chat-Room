<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['handle']))
{
    $result = array("success" => true, "errorMessage" => "", "user" => "");

    $user = new user(null, $_REQUEST['handle'], null, $_REQUEST['password'], false);
    if(!$user->CheckPassword())
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

    echo json_encode($result);
}

?>