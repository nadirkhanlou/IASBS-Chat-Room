<?php
require_once "model/user.php";

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
		if(user.IsUsernameExist())
		{
			
			//store in database
			
			return true;
		}
		return false;
	}
	
	function IsDelivered()
	{
		return !is_null($dataTime)
	}
}

>