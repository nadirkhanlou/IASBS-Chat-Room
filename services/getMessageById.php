<?php
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['messageId']))
{
    $result = array("success" => true, "errorMessage" => "", "message" => null);

    $message = message::GetMessage($_REQUEST['messageId']);
    if($message)
    {
        $result["message"] = $message;
    }
    else
    {
        $result["success"] = false;
        $result["errorMessage"] = "Couldn't get message";
    }

    echo json_encode($result);
}

?>