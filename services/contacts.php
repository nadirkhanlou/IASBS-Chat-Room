<?php
require_once "model/user.php";

if(isset($_POST['handle'])) {
    $user = new user("", $_POST['handle'], "", "");
    $contacts = $user.GetContacts();
    $blocked = $user.GetBlockedList();
    $users = array("contacts" => $contacts, "blocked" => $blocked);
    echo $users;
}

?>