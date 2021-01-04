<?php
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['messageId']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(!message::DeleteMessage($_REQUEST['receiverHandle'], $_REQUEST['messageId']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "Couldn't delete message";
    }

    echo json_encode($result);
}

?>