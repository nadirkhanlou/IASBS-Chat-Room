<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['handle']))
{
    $result = array("success" => true, "errorMessage" => "");

    
    if(!(isset($_SESSION["USER"]) && unserialize($_SESSION["USER"])->Handle == $_REQUEST['handle']))
    {
        
        if(user::IsHandleExist($_REQUEST['handle']))
        {
            $result["success"] = false;
            $result["errorMessage"] = "The Handle already exists.";
        }
    }

    echo json_encode($result);
}

?>