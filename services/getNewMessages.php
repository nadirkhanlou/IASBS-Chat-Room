<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";
require_once dirname(__FILE__)."/../model/message.php";

$result = array("success" => true, "errorMessage" => "", "messages" => null);

if(isset($_SESSION['USER']))
{
    $handle = unserialize($_SESSION['USER'])->Handle;
    
    $messages = message::GetNewMessages($handle);
    if($messages)
    {
        $result['messages'] = $messages;
    }
    else
    {
        $result['success'] = false;
        $result['errorMessage'] = "Couldn't fecth messages";
    }
}
else
{
    $result['success'] = false;
    $result['errorMessage'] = 'You are not logged in';
}

echo json_encode($result);


?>