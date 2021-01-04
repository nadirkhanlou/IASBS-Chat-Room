<?php
session_start();
require_once dirname(__FILE__)."/../model/user.php";
require_once dirname(__FILE__)."/../model/message.php";

$result = array("success" => true, "errorMessage" => "", "messages" => null);

if(isset($_REQUEST['contactHandle']))
{
    if(isset($_SESSION['USER']))
    {
        $handle = unserialize($_SESSION['USER'])->Handle;
        $contactHandle = $_REQUEST['contactHandle'];
    
        $receivedMessages = message::GetOldMessages($handle, $contactHandle);
        $sentMessages = message::GetOldMessages($contactHandle, $handle);
        $undeliveredMessages = message::GetUnDeliveredMessages($handle, $contactHandle);
        if($receivedMessages || $sentMessages || $undeliveredMessages)
        {
            $messages = array("receivedMessages" => $receivedMessages, "sentMessages" => $sentMessages, "undeliveredMessages" => $undeliveredMessages);
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
}


?>