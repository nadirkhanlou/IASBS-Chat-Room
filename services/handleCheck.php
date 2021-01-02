<?php
session_start();
require_once "../model/user.php";

if(isset($_REQUEST['handle']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(!(isset($_SESSION["USER"]) && unserialize($_SESSION["USER"])['handle'] == $_REQUEST['handle']))
    {
        if(user::IsHandleExist($_REQUEST['handle']))
        {
            $_REQUEST["success"] = false;
            $_REQUEST["errorMessage"] = "The Handle already exists.";
        }
    }

    echo json_encode($result);
}

?>