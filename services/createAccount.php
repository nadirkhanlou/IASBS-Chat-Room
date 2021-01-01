<?php
require_once "model/user.php";

if(isset($_POST['create'])) {
    $result = array("success" => true, "errorMessage" => "");
    if(user::IsHandleExist($_POST['handle']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "The Handle already exists.";
    }
    else if(user::IsPhoneNumberExist($_POST['phoneNumber']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "The Phone Number already exists.";
    }
    else
    {
        $user = new user($_POST['fullName'], $_POST['handle'], $_POST['phoneNumber'], $_POST['password'], false);
        $user.StoreInDatebase();
    }

    echo $result;
}

?>