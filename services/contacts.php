<?php
require_once "model/user.php";

if(isset($_POST['handle'])) {
    $result = array("success" => true, "errorMessage" => "", "users" => "");
    $user = new user("", $_POST['handle'], "", "");
    $contacts = $user.GetContacts();
    $blocked = $user.GetBlockedList();
    $users = array("contacts" => $contacts, "blocked" => $blocked);
    $result["users"] = $users;
    echo $result;
}

?>