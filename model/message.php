<?php
require_once "user.php";
require_once "../database.php";

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
	
	function SendMessage()
	{
		if(user::IsHandleExist($this->receiverHandle))
		{
			
			//store in database
			$query = "";
			$result = database::ExecuteQuery($query);
			
			return true;
		}
		return false;
	}
	
	function IsDelivered()
	{
		return !is_null($dataTime);
	}
}

?>