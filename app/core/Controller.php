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
    protected $images;

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

    // Prints an error variable only if it exists
    // Example: $this->printError($d['errors']['error'name'])
    public function printError(&$error) {
        if(isset($error)) {
            echo "<div class=\"errortext\">" . $error . "</div>";
        }
    }

    // Print all errors that may exist
    // Example: $this->printAllErrors($d);
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

    // Print first variable if it exists, or second variable if it exists
    // Example: $this->printFormVar($d->customers->mail, $_POST['customer_phone'])
    // function printFormVar($db_data, $post_data) {
    //     if(!empty($db_data)){
    //         echo $db_data;
    //     } elseif(!empty($post_data)) {
    //         echo $post_data;
    //     }
    // }

}