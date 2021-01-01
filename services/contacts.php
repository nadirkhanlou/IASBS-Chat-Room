<?php
require_once "model/user.php";

if(isset($_POST['contacts']))
{
    $result = array("success" => true, "errorMessage" => "", "users" => "");

    $handle = ""; //handle should be read from cookie

    $user = new user(null, $handle, null, null);
    $contacts = $user.GetContacts();
    $blocked = $user.GetBlockedList();
    $users = array("contacts" => $contacts, "blocked" => $blocked);
    $result["users"] = $users;

    echo $result;
}

?>