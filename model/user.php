<?php
require_once "model/message.php";

class user
{
	private $fullName;
	private $handle;
	private $phoneNumber;
	private $password;
	
	/*
	The constructor consider the password is hashed by default, otherwise it can be noticed by
	isPasswordHashed parameter so the constructor will hash it before storing it  
	*/
	
	function __construct($fullName, $handle, $phoneNumber, $password, $isPasswordHashed = true)
	{
		$this->fullName = $fullName;
		$this->handle = $handle;
		$this->phoneNumber = $phoneNumber;
		$this->password = isPasswordHashed ? $password : HashPassword();
	}
	
	
	//Getters
	
	public function GetFullName ()
	{
		return $this->fullName;
	}
	
	public function GetHandle ()
	{
		return $this->handle;
	}
	
	public function GetPhoneNumber ()
	{
		return $this->phoneNumber;
	}
	
	public function GetPassword ()
	{
		return $this->password;
	}
	
	//Static methods
	
	static function HashPassword($password)
    {
        return md5($password);
    }
	
	static function IsUsernameExist()
	{
		
	}
	
	//Other methods
	
	function StoreInDatebase()
	{
		
	}
	
	function CheckPassword()
	{
		
	}
	
	function GetMessages()
	{
		
	}
	
	function SendMessage($receiverHandle, $messageText)
	{
		msg = new message($this->handle, $receiverHandle, $messageText);
		return msg.SendMessage();
	}
}

>