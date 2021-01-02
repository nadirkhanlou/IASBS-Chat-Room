<?php
require_once "../model/user.php";

if(isset($_REQUEST['handle']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(user::IsHandleExist($_REQUEST['handle']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "The Handle already exists.";
    }
    else if(user::IsPhoneNumberExist($_REQUEST['phoneNumber']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "The Phone Number already exists.";
    }
    else
    {
        $user = new user($_REQUEST['fullName'], $_REQUEST['handle'], $_REQUEST['phoneNumber'], $_REQUEST['password'], false);
        if(!$user->StoreInDatebase())
        {
            $result["success"] = false;
            $result["errorMessage"] = "Couldn't create account.";
        }
    }

    echo json_encode($result);
}

?>