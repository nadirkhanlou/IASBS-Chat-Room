<?php
session_start();
require_once dirname(__FILE__)."/../model/message.php";
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['receiverHandle']))
{
    $result = array("success" => true, "errorMessage" => "", "dateTime" => null);

    if(isset($_SESSION['USER']))
    {
        $senderHandle = unserialize($_SESSION['USER'])->Handle;
        $message = new message($senderHandle, $_REQUEST['receiverHandle'], $_REQUEST['messageText']); 
        if($message->SendMessage())
        {
            $result['dateTime'] = $message->GetDateTime();
        }
        else
        {
            $result['success'] = false;
            $result['errorMessage'] = "Couldn't send message";
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