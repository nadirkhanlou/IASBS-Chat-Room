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

?>