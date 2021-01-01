<?php
require_once "model/user.php";

if(isset($_POST['create'])) {
    $result = array("success" => true, "error" => "");
    if(user::IsHandleExist())
    {
        $result["success"] = false;
        $result["error"] = "The Handle already exists.";
    }
    else if(user::IsPhoneNumberExist())
    {
        $result["success"] = false;
        $result["error"] = "The Phone Number already exists.";
    }
    else
    {
        $user = new user($_POST['fullName'], $_POST['handle'], $_POST['phoneNumber'], $_POST['password'], false);
        $user.StoreInDatebase();
    }

    echo $result;
}

?>