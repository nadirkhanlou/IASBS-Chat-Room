<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";

$result = array("success" => true, "errorMessage" => "", "editedList" => "");

if(isset($_SESSION["USER"]))
{
    $editedMessages = message::GetRecentEditedMessages(unserialize($_SESSION["USER"])->Handle);
    if($editedMessages)
    {
        $result["editedList"] = $editedMessages;
    }
    else
    {
        $result["success"] = false;
        $result['errorMessage'] = 'Nothing has been edited';
    }
}
else
{
    $result["success"] = false;
    $result['errorMessage'] = 'You are not logged in';
}

echo json_encode($result);


?>