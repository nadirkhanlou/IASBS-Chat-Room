<?php
session_start();

if(isset($_REQUEST['logout']))
{
    $result = array("success" => true, "errorMessage" => "");

    if(isset($_SESSION['USER']))
    {
        unset($_SESSION['USER']);
    }
    else
    {
        $result['success'] = false;
        $result['errorMessage'] = 'You are not logged in';
    }

    echo json_encode($result);
}

?>