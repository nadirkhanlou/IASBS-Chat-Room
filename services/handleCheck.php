<?php
session_start();
require_once "model/user.php";

if(isset($_POST['handle']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(!(isset($_SESSION["USER"]) && unserialize($_SESSION["USER"])['handle'] == $_POST['handle']))
    {
        if(user::IsHandleExist($_POST['handle']))
        {
            $result["success"] = false;
            $result["errorMessage"] = "The Handle already exists.";
        }
    }

    echo serialize($result);
}

?>