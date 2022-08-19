<?php

// Controller superclass. Methods for calling in controllers and views

class Controller {
    
    // Containers
    protected $brand;
    protected $complaint;
    protected $customer;
    protected $departments;
    protected $employees;
    protected $item;
    protected $data;

    public function model($model){
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $d = []){
        require_once '../app/views/' . $view . '.php';
    }

    // Check if errors are present in $data['errors] array
    public function errors() {
        if(isset($this->data['errors'])) {
            return true;
        } else {
            return false;
        }
    }

    // Print all errors that may exist
    // Example: $this->printAllErrors2($d);
    public function printAllErrors(&$d) {
        if(isset($d['errors'])) {
            foreach($d['errors'] as $error)
            echo "<div class=\"errortext\">" . $error . "</div>";
        }
    }

    // Print variable if set (re-autofill form fields)
    // Example: $this->printVar($d['key_name'])
    function printVar(&$var, $default = false) {
        echo isset($var) ? $var : $default;
    }




    // Prints an error variable only if it exists
    // public function printError(&$var, $else = '') {
    //     echo isset($var) && $var ? $var : $else;
    // }

    // Prints an error variable only if it exists
    public function printError(&$error) {
        if(isset($error)) {
            echo "<div class=\"errortext\">" . $error . "</div>";
        }
    }
    


    // Print variable if set (re-autofill form fields)
    // Example: 
    // public function printVar2(&$var) {
    //     if(isset($var)) {
    //         echo $var;
    //     } else { echo "føk"; }
    // }
    
    // public function printVar(&$var, $else = '') {
    //     echo isset($var) && $var ? $var : $else;
    // }


    
}