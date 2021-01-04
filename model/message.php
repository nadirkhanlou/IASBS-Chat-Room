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
		$this->SenderHandle = $senderHandle;
		$this->ReceiverHandle = $receiverHandle;
		$this->Message = $message;
		$this->MessageType = $messageType;
		$this->DateTime = $dateTime;
		$this->MessageId = $messageId;
	}

}

class editedMessages
{
	public $MessageId;
    public $ReceiverHandle;
    public $EditType;
	public $NewMessages;
	
	function __construct($messageId, $receiverHandle, $editType, $newMessages)
	{
		$this->MessageId = $messageId;
		$this->ReceiverHandle = $receiverHandle;
		$this->EditType = $editType;
		$this->NewMessages = $newMessages;
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
				$row = $result->fetch_array();
				$this->dateTime = $row['createdAt'];
				return $row['createdMessageId'];
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
				$messages = new accessibleMessage($rows[$i]['senderHandle'], $handle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $messages);
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
				$messages = new accessibleMessage($senderHandle, $handle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $messages);
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
				$messages = new accessibleMessage($handle, $contactHandle, $rows[$i]['message'], $rows[$i]['messageType'], $rows[$i]['messageDateTime'], $rows[$i]['messageId']);
				array_push($retVal, $messages);
			}
				
			return $retVal;
		}
		return false;
	}


	static function EditMessage($receiverHandle, $messageId, $message)
	{
		if(user::IsHandleExist($receiverHandle) && message::IsMessageExist($messageId))
		{

			$query = "CALL EDITMESSAGE('{$messageId}', '{$message}', '{$receiverHandle}'";
			$result = database::ExecuteQuery($query);

			return $result;
		}
		return false;
	}

	static function DeleteMessage($receiverHandle, $messageId)
	{
		if(user::IsHandleExist($receiverHandle) && message::IsMessageExist($messageId))
		{

			$query = "CALL DELETEMESSAGE('{$messageId}', '{$receiverHandle}')";
			$result = database::ExecuteQuery($query);

			return $result;
		}
		return false;
	}

	static function IsMessageExist($messageId)
	{
		$query = "CALL GET_MESSAGE('{$messageId}')";
		$result = database::ExecuteQuery($query);

		if(!$result)
			return;

		return mysqli_num_rows($result) > 0;
	}

	static function GetMessage($messageId)
	{
		$query = "CALL GET_MESSAGE('{$messageId}')";
		$result = database::ExecuteQuery($query);

		if(!$result || mysqli_num_rows($result) <= 0)
			return;

		$row = $result->fetch_array();
		$message = new accessibleMessage(null, null, $row["message"], $row["message_type"], $row["created_at"], $row["id"]);

		return  $message;
	}

	static function GetRecentEditedMessages($handle)
	{
		if(user::IsHandleExist($handle))
		{

			$query = "CALL GET_EDITED_LIST('{$handle}')";
			$result = database::ExecuteQuery($query);
	
			if(!$result)
				return false;

			$rows = $result->fetch_all(MYSQLI_ASSOC);
			$retVal = [];
			for ($i = 0; $i < count($rows); ++$i) {
				$messages = new editedMessages($rows[$i]['messageId'], $rows[$i]['receiverHandle'], $rows[$i]['edit_type'], $rows[$i]['new_messages']);
				array_push($retVal, $messages);
			}
				
			return $retVal;
		}
		return false;
	}
}

?>