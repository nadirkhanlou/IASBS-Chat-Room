<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

$result = array("success" => true, "errorMessage" => "", "user" => "");

if(isset($_SESSION["USER"]))
{
    $user = unserialize($_SESSION["USER"]);
    $result["user"] = $user;
}
else
{
    $result["success"] = false;
    $result['errorMessage'] = 'You are not logged in';
}

echo json_encode($result);


?>