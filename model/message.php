<?php
require_once dirname(__FILE__)."/../database.php";
require_once dirname(__FILE__)."/user.php";

class accessibleMessage
{
	public $SenderHandle;
	public $ReceiverHandle;
	public $Message;
	public $MessageType;
	public $DateTime;
	public $MessageId;

	function __construct($senderHandle, $receiverHandle, $message, $messageType, $dateTime, $messageId)
	{
		$this->senderHandle = $senderHandle;
		$this->ReceiverHandle = $receiverHandle;
		$this->Message = $message;
		$this->MessageType = $messageType;
		$this->DateTime = $dateTime;
		$this->MessageId = $messageId;
	}

}

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
		return $this->dateTime;
	}
	
	function SendMessage()
	{
		if(user::IsHandleExist($this->receiverHandle))
		{

			$query = "CALL SENDMESSAGE('{$this->senderHandle}', '{$this->receiverHandle}', '{$this->message}', 'text')";
			$result = database::ExecuteQuery($query);
	
			if(mysqli_num_rows($result) > 0)
			{
				$this->dateTime = $result->fetch_array()['created_at'];
				return true;
			}
			else
			{
				return false;
			}
		}
		return false;
	}
	
	function IsDelivered()
	{
		return !is_null($dataTime);
	}

	//static methods

	static function GetNewMessages($handle)
	{
		if(user::IsHandleExist($handle))
		{

			$query = "CALL FETCH_NEW_MESSAGES('{$handle}')";
			$result = database::ExecuteQuery($query);
	
			if(!$result)
				return false;

			$rows = $result->fetch_all(MYSQLI_ASSOC);
			$retVal = [];
			for ($i = 0; $i < count($rows); ++$i) {
				$contact = new accessibleMessage($rows[$i]['senderHandle'], $handle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $contact);
			}
				
			return $retVal;
		}
		return false;
	}

	static function GetOldMessages($handle, $senderHandle)
	{
		if(user::IsHandleExist($handle))
		{

			$query = "CALL FETCH_OLD_MESSAGES('{$handle}', '{$senderHandle}')";
			$result = database::ExecuteQuery($query);
	
			if(!$result)
				return false;

			$rows = $result->fetch_all(MYSQLI_ASSOC);
			$retVal = [];
			for ($i = 0; $i < count($rows); ++$i) {
				$contact = new accessibleMessage($senderHandle, $handle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $contact);
			}
				
			return $retVal;
		}
		return false;
	}

	static function GetUnDeliveredMessages($handle, $contactHandle)
	{
		if(user::IsHandleExist($handle))
		{

			$query = "CALL FETCH_NOT_DELIVERED_MESSAGES('{$handle}', '{$contactHandle}')";
			$result = database::ExecuteQuery($query);
	
			if(!$result)
				return false;

			$rows = $result->fetch_all(MYSQLI_ASSOC);
			$retVal = [];
			for ($i = 0; $i < count($rows); ++$i) {
				$contact = new accessibleMessage($handle, $contactHandle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $contact);
			}
				
			return $retVal;
		}
		return false;
	}
}

?>