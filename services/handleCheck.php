<?php
require_once "model/user.php";

if(isset($_POST['create'])) {
    $result = array("success" => true, "errorMessage" => "");
    if(user::IsHandleExist($_POST['handle']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "The Handle already exists.";
    }

    echo $result;
}

?>