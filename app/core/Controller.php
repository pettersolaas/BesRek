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
    // Example 1 (nested array):
    // $d['errors']['brand_name'] = "Error message";          ->            $this->printAllErrors($d['errors']);
    // Example 2 (double nested array):
    // $d['image_errors']['file_size'][0] = "File xxx is too big";          ->            $this->printAllErrors($d['image_errors']);
    // function printAllErrors(&$d) {
    //     if(isset($d)) {
    //         foreach($d as $key => $value)
    //             if(is_array($d[$key])){
    //                 foreach($value as $k => $v){
    //                     echo "<div class=\"errortext\">" . $value[$k] . "</div>";
    //                 }
    //             } else {
    //                 echo "<div class=\"errortext\">" . $d[$key] . "</div>";
    //             }
    //     }
    // }
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