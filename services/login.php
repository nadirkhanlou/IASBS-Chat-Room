<?php
require_once "model/user.php";

if(isset($_POST['login'])) {
    $result = array("success" => true, "errorMessage" => "");

    $user = new user(null, $_POST['handle'], null, $_POST['password']);
    if(!$user.CheckPassword())
    {
        $result["success"] = false;
        $result["errorMessage"] = "The handle or password is incorrect.";
    }

    echo $result;
}

?>