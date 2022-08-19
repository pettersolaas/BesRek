<?php

function printError(&$var, $else = '') {
    echo isset($var) && $var ? $var : $else;
}

$data = array();


$employees = array(
    '0' => 'Petter',
    '1' => 'Lise'
);

$departments = array(
    '0' => 'Bergen',
    '1' => 'Molde'
);

$data['departments'] = $departments;
$data['employees'] = $employees;

// Add error message
$data['errors']['test'] = 'Error 1';
$data['errors']['error_name'] = 'Error 2';

// Print all errors
function printAllErrors() {
    global $data;
    if(isset($data['errors'])){
        foreach ($data['errors'] as $error) {
            echo "<div class=\"errortext\">" . $error . "</div>";
        }
    }
}

printAllErrors();


// Parameters
$data['url_parameter'] = 5;


//print_r($data);

$data['url_parameter'];



// echo $data['errors']['username_exists'];