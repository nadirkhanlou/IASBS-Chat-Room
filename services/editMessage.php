<?php
require_once dirname(__FILE__)."/../model/user.php";

if(isset($_REQUEST['messageId']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(true)
    {
        
    }
    else
    {
        $result["success"] = false;
        $result["errorMessage"] = "";
    }

    echo json_encode($result);
}

?>