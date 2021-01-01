<?php
require_once "model/message.php";
require_once "database.php";

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
		$this->password = isPasswordHashed ? $password : user::HashPassword($password);
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
	
	static function IsHandleExist($handle)
	{
		$query = "";
		$result = database::ExecuteQuery($query);
        return mysqli_num_rows($result) > 0;
	}
	
	static function IsPhoneNumberExist($phoneNumber)
	{
		$query = "";
		$result = database::ExecuteQuery($query);
        return mysqli_num_rows($result) > 0;
	}
	
	//Other methods
	
	function StoreInDatebase()
	{
		$query = "";
		$result = database::ExecuteQuery($query);
	}
	
	function CheckPassword()
	{
		$query = "";
		$result = database::ExecuteQuery($query);
        if(mysqli_num_rows($result)){
			$row = $result->fetch_array();
            $this->fullName = $row["fullName"];
            $this->handle = $row["handle"];
            $this->phoneNumber = $row["phoneNumber"];
            return true;
		}
		return false;
	}
	
	function GetContacts()
	{

	}

	function GetBlockedList()
	{
		
	}

	function GetMessages()
	{
		$query = "";
		$result = database::ExecuteQuery($query);
	}
	
	function SendMessage($receiverHandle, $messageText)
	{
		$msg = new message($this->handle, $receiverHandle, $messageText);
		return $msg.SendMessage();
	}
}

?>