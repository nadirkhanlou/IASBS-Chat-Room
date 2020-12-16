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
    private $cellphone;

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

    function getCellphone() {
        return $this->cellphone;
    }

    function setCellphone($cellphone) {
        $this->cellphone = $cellphone;
    }
}

?>