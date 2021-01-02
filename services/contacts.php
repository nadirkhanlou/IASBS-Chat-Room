<?php
session_start();
require_once "../model/user.php";

if(isset($_REQUEST['contacts']))
{
    $result = array("success" => true, "errorMessage" => "", "users" => "");

    if(isset($_SESSION["USER"]))
    {
        $handle = unserialize($_SESSION["USER"])->Handle;
        $user = new user(null, $handle, null, null);
        $contacts = $user->GetContacts();
        $blocked = $user->GetBlockedList();
        $users = array("contacts" => $contacts, "blocked" => $blocked);
        $result["users"] = $users;
    }
    else
    {
        $result["success"] = false;
        $result["errorMessage"] = "You are not logged in";
    }

    echo json_encode($result);
}

?>