<?php
require "config.php";
require "../model/user.php";
include "../view/login.html";


$Message = '';
$uiFname_cv = "";
$uiLname_cv = "";
$uiPhone_cv = "";
$uiUsername_cv = "";

if(isset($_POST['uiSubmit']))
{
    $uiName_cv = $_POST['uiFname'];
    $uiFamily_cv = $_POST['uiLname'];
    $uiUsername_cv = $_POST['uiUsername'];
    $uiPhone_cv = $_POST['uiPhone'];

    $validationMessage = validation();
    if($validationMessage == "") {
        $u = new user();
        $u->setName($_POST['uiFname']);
        $u->setFamily($_POST['uiLname']);
        $u->setUsername($_POST['uiUsername']);
        $u->setPassword($_POST['uiPassword']);
        $u->setTelephone($_POST['uiPhone']);
        if($u->Save())
            $Message = 'You have successfully registed.';
        else
            $Message = 'The username already exists. Please use a different username.';
    }
    else
        $Message = $validationMessage;
}






function validation()
{
    $Message = "";
    if($_POST["uiFname"] == "")
        $Message = 'Enter your name'."<br/>";
    if($_POST["uiLname"] == "")
        $Message .= 'Enter your family'."<br/>";
    if($_POST["uiUsername"] == "")
        $Message .= 'Enter your username.'."<br/>";
    if($_POST["uiPassword"] == "")
        $Message .= 'Enter your password'."<br/>";
    if($_POST["uiPhone"] == "")
        $Message .= 'Enter your Phone number'."<br/>";

    if($_POST["uiPassword"] != $_POST["uiConfirmPassword"])
        $Message .= 'Password and confirmation password do not match.'."<br/>";


    return $Message;
}

?>