<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

$result = array("success" => true, "errorMessage" => "", "users" => "");

if(isset($_SESSION["USER"]))
{
    $handle = unserialize($_SESSION["USER"])->Handle;
    $user = new user(null, $handle, null, null);
    $contacts = $user->GetContacts();
    $blocked = $user->GetBlockedList();
    $blockedBy = $user->GetBlockedByList();
    $users = array("contacts" => $contacts, "blocked" => $blocked, "blockedBy" => $blockedBy);
    $result["users"] = $users;
}
else
{
    $result["success"] = false;
    $result["errorMessage"] = "You are not logged in";
}

echo json_encode($result);

?>