<?php
require_once "database/DB.php";
abstract class person {
    public $name;
    public $sur_name;

    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getFamily() {
        return $this->sur_name;
    }

    function setFamily($family) {
        $this->sur_name = $family;
    }
}

class user extends person
{
    private $username;
    private $password;
    private $telephone;
    private $bio;

    function getUsername() {
        return $this->username;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function getPassword() {
        return $this->password;
    }

    function setPassword($password) {
        $this->password = md5($password);
    }

    function getTelephone() {
        return $this->telephone;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    function getBio() {
        return $this->bio;
    }

    function setBio($bio) {
        $this->bio = $bio;
    }

    function checkUserPass()
    {
        $paramTypes = "ss";
        $Parameters = array($this->username, $this->password);
        $result = database::ExecuteQuery('CheckUserPass', $paramTypes, $Parameters);

        if(mysqli_num_rows($result) > 0)
        {
            $row = $result->fetch_array();
            $this->setName($row["name"]);
            $this->setFamily($row["family"]);
            return true;
        }
        return false;
    }

    private function getUserAsaText()
    {
        return $this->username.' '.$this->password.' '.$this->name.' '.$this->sur_name.PHP_EOL;
    }

    public function IsUsernameExist()
    {
        $paramTypes = "s";
        $Parameters = array($this->username);
        $result = database::ExecuteQuery('IsUsernameExist', $paramTypes, $Parameters);

        if(mysqli_num_rows($result) > 0)
              return true;
        return false;
    }

    function Save()
    {
        if(!$this->IsUsernameExist()) {
            $paramTypes = "ssssss";
            $Parameters = array($this->username, $this->password,
                $this->name, $this->sur_name, $this->telephone, $this->bio);
            database::ExecuteQuery('AddUser', $paramTypes, $Parameters);
            return true;
        }
        return false;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

    public static function GetAllUsers() {
        $result = database::ExecuteQuery('GetAllUsers');
        $usersList = array();
        $i = 0;
        while ($row = $result->fetch_array())
        {
            $tempUser = new user();
            $tempUser->setUsername($row['username']);
            $tempUser->setName($row['name']);
            $tempUser->setFamily($row['surname']);
            $usersList[$i++] = $tempUser->jsonSerialize();
        }
        return $usersList;
    }
    function Getprofile() {
        $paramTypes = "s";
        $Parameters = array($this->username);
        $profile = database::ExecuteQuery('GetProfile', $paramTypes, $Parameters);

        $tempUser = new user();
        $tempUser->setUsername($profile['username']);
        $tempUser->setName($profile['name']);
        $tempUser->setFamily($profile['surname']);
        $tempUser->setTelephone($profile['telephone']);
        $tempUser->setBio($profile['bio']);
        
        $result = $tempUser->jsonSerialize();
        return $result;
    }
}

class message {
    private $from;
    private $to;
    private $text;
    private $time;

    function setfrom($from) {
        $this->from = $from;
    }
    function setto($to) {
        $this->to = $to;
    }
    function settext($text) {
        $this->text = $text;
    }
    function settime($time) {
        if ($time == "") {
           $time = date("Y-m-d H:i:s");
        }
        $this->time = $time;
    }
    function getfrom() {
        return $this->from;
    }
    function getto() {
        return $this->to;
    }
    function gettext() {
        return $this->text;
    }
    function gettime() {
        return $this->time;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

    private function userexist() {
        $paramTypes = "s";
        $Parameters = array($this->to);
        $result = database::ExecuteQuery('IsUsernameExist', $paramTypes, $Parameters);

        if(mysqli_num_rows($result) > 0)
              return true;
        return false;
    }

    function send_messages() {
        if ($this->userexist()) {
            $this->settime();
            $paramTypes = "ssss";
            $Parameters = array($this->from, $this->to, $this->text, $this->time);
            database::ExecuteQuery('AddMessage', $paramTypes, $Parameters);
            return true;
        }
        return false;
    }

    function see_messages() {
        $paramTypes = "ss";
        $Parameters = array($this->from, $this->to);
        $messages = database::ExecuteQuery('GetMessages',$paramTypes, $Parameters);

        $MessageList = array();
        $i = 0;
        while ($r = $messages->fetch_array())
        {
            $tempMes = new message();
            $tempMes->setfrom($r['fromuser']);
            $tempMes->setto($r['destuser']);
            $tempMes->settext($r['message']);
            $tempMes->settime($r['time']);
            $MessageList[$i++] = $tempMes->jsonSerialize();
        }
        return $MessageList;
    }

    function delete_messages() {
        $paramTypes = "sss";
        $Parameters = array($this->from, $this->to, $this->time);
        database::ExecuteQuery('DeleteMessage',$paramTypes,$Parameters);
        $update = $this->see_messages();
        return $update;
    }

    function edit_messages($new_text) {
        $paramTypes = "ssss";
        $Parameters = array($this->from, $this->to, $new_text, $this->time);
        database::ExecuteQuery('UpdateMessage',$paramTypes,$Parameters);
        $update = $this->see_messages();
        return $update;
    }
}

?>