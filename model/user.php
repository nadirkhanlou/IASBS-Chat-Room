<?php
require_once dirname(__FILE__)."/../database.php";
require_once dirname(__FILE__)."/message.php";

class accessibleUser
{
	public $FullName;
	public $Handle;
	public $PhoneNumber;

	function __construct($user)
	{
		$this->FullName = $user->GetFullName();
		$this->Handle = $user->GetHandle();
		$this->PhoneNumber = $user->GetPhoneNumber();
	}

}

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
		$this->password = $isPasswordHashed ? $password : user::HashPassword($password);
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

	//Static methods
	
	static function HashPassword($password)
    {
        return md5($password);
    }
	
	static function IsHandleExist($handle)
	{
		$query = "CALL GET_USER_BY_HANDLE('{$handle}')";
		$result = database::ExecuteQuery($query);
        return mysqli_num_rows($result) > 0;
	}
	
	static function IsPhoneNumberExist($phoneNumber)
	{
		$query = "CALL GET_USER_BY_PHONE({$handle})";
		$result = database::ExecuteQuery($query);
        return mysqli_num_rows($result) > 0;
	}
	
	//Other methods
	
	function StoreInDatebase()
	{
		$query = "CALL ADD_USER('{$this->phoneNumber}', '{$this->handle}', '{$this->password}', '{$this->fullName}')";
		$result = database::ExecuteQuery($query);
		return !$result ? false : true;
	}
	
	function CheckPassword()
	{
		$query = "CALL CHECK_PASSWORD('{$this->handle}', '{$this->password}')";
		$result = database::ExecuteQuery($query);
        if(mysqli_num_rows($result) > 0){
			$row = $result->fetch_array();
            $this->fullName = $row["full_name"];
            $this->handle = $row["handle"];
            $this->phoneNumber = $row["phone"];
            return true;
		}
		return false;
	}

	function EditInfo($NewHandle, $newPassword, $newFullName)
	{
		$query = "CALL EDITPROFILE('{$this->handle}', '{$NewHandle}', '{$newPassword}', '{$newFullName}')";
		$result = database::ExecuteQuery($query);
		if(!$result)
		{
			return false;
		}
		else
		{
			$this->fullName = $newFullName;
            $this->handle = $NewHandle;
            $this->password = $newPassword;
			return true;
		}
	}

	function GetContacts()
	{
		$query = "CALL GET_CONTACTS('{$this->handle}')";
		$result = database::ExecuteQuery($query);
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		$retVal = [];
		for ($i = 0; $i < count($rows); ++$i) {
			$user = new user($rows[$i]['full_name'], $rows[$i]['handle'], $rows[$i]['phone'], "");
			$contact = new accessibleUser($user);
			array_push($retVal, $contact);
		}
		return $retVal;
	}

	function GetBlockedList()
	{
		$query = "CALL GET_BLOCKED('{$this->handle}')";
		$result = database::ExecuteQuery($query);
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		$retVal = [];
		for ($i = 0; $i < count($rows); ++$i) {
			$user = new user($rows[$i]['full_name'], $rows[$i]['handle'], $rows[$i]['phone'], "");
			$contact = new accessibleUser($user);
			array_push($retVal, $contact);
		}
		return $retVal;
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

	function Serialize()
	{
		
	}
}

?>