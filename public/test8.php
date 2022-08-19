<?php

class Myerror {

    protected $errors;

    function setErrors()
    {
    //$this->errors['username'] = "Brukernavn må være av lengde 5-20 og kun inneholde følgende tegn: a-å 0-9 _";
    }

    function test() {
        if(isset($this->errors)) {
            echo "is set:
            ";
            var_dump($this->errors);
        } else{
            
            echo "not set:
            ";
            var_dump($this->errors);
        }
    }
}


$error_object = new Myerror;

$error_object->setErrors();
$error_object->test();
?>