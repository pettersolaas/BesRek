<?php

$d = array();
// $d['errors'] = array();
$d['errors']['error1'] = "This is error one";
$d['errors']['error2'] = "This is error two";


$employees_in_dep = array ('peter', 'john', 'morten');
$employees_not_in_dep = array ('toby', 'jack', 'tiril');

// Put datasets into array
$data = [
    'employees_in_dep' => $employees_in_dep,
    'employees_not_in_dep' => $employees_not_in_dep
];

if(!empty($d['errors'])){
    $data = array_merge($data, $d);
}





// print_r($data);

// $test = "abc";

echo @$test;