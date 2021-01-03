<?php
require_once dirname(__FILE__)."/../database.php";
require_once dirname(__FILE__)."/user.php";

class message
{
	private $senderHandle;
	private $receiverHandle;
	private $message;
	private $dateTime;
	
	function __construct($senderHandle, $receiverHandle, $message, $dateTime = null)
	{
		$this->senderHandle = $senderHandle;
		$this->receiverHandle = $receiverHandle;
		$this->message = $message;
		$this->dateTime = $dateTime;
	}

	function GetDateTime()
	{
		return strtotime($dateTime);
	}
	
	function SendMessage()
	{
		if(user::IsHandleExist($this->receiverHandle))
		{

			$query = "CALL SENDMESSAGE('{$this->senderHandle}', '{$this->receiverHandle}', '{$this->message}', 'text')";
			$result = database::ExecuteQuery($query);
			
			return !$result ? false : true;
		}
		return false;
	}
	
	function IsDelivered()
	{
		return !is_null($dataTime);
	}
}

?>