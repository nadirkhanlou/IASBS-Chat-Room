<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['newHandle']))
{
    $result = array("success" => true, "errorMessage" => "", "user" => "");

    if(isset($_SESSION["USER"]))
    {
        $handle = unserialize($_SESSION["USER"])->Handle;
        $user = new user(null, $handle, null, $_REQUEST['currentPassword'], false);
        if(!$user->CheckPassword())
        {
            $result["success"] = false;
            $result["errorMessage"] = "The current password is incorrect.";
        }
        else
        {
            $hashedNewPass = user::HashPassword($_REQUEST['newPassword']);
            if($user->EditInfo($_REQUEST['newHandle'], $hashedNewPass, $_REQUEST['newFullName']))
            {
                $accessibleUser = new accessibleUser($user);
                $_SESSION["USER"] = serialize($accessibleUser);
                $result["user"] = $accessibleUser;
            }
            else
            {
                $result["success"] = false;
                $result["errorMessage"] = "Couldn't edit information";
            }
        }
    }
    else
    {
        $result['success'] = false;
        $result['errorMessage'] = 'You are not logged in';
    }


    echo json_encode($result);
}

?>