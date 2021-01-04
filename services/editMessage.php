<?php
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['messageId']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(!message::EditMessage($_REQUEST['receiverHandle'], $_REQUEST['messageId'], $_REQUEST['message']))
    {
        $result["success"] = false;
        $result["errorMessage"] = "Couldn't edit message";
    }

    echo json_encode($result);
}

?>