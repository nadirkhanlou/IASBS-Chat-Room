<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['contactHandle']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(isset($_SESSION['USER']))
    {
        $userHandle = unserialize($_SESSION['USER'])->Handle;
        $contactHandle = $_REQUEST['contactHandle'];
        $user = new user("", $userHandle, "", "");
        if(!$user->BlockUser($contactHandle))
        {
            $result["success"] = false;
            $result["errorMessage"] = "Couldn't block user";
        }
    }
    else
    {
        $result["success"] = false;
        $result["errorMessage"] = "You are not logged in";
    }

    echo json_encode($result);
}

?>