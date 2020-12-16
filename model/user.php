<?php

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
        $this->sur_name = $sur_name;
    }
}

class user extends person
{
    private $username;
    private $password;

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
        return $this->username.' '.$this->password.' '.$this->name.' '.$this->family.PHP_EOL;
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
            $paramTypes = "ssss";
            $Parameters = array($this->username, $this->password,
                $this->name, $this->family);
            database::ExecuteQuery('AddUser', $paramTypes, $Parameters);
            return true;
        }
        return false;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

    public static function GetAllUsers()
    {
        $result = database::ExecuteQuery('GetAllUsers');
        $usersList = array();
        $i = 0;
        while ($row = $result->fetch_array())
        {
            $tempUser = new user();
            $tempUser->setUsername($row['username']);
            $tempUser->setName($row['name']);
            $tempUser->setFamily($row['family']);
            $usersList[$i++] = $tempUser->jsonSerialize();
        }
        return $usersList;
    }
}

?>