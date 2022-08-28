<?php

$d = array();
// $d['errors'] = array();
// $d['errors']['error1'] = "This is error one";
// $d['errors']['error2'] = "This is error two";


// $d['image_errors']['image_size'][0] = "This is image error one";
// $d['image_errors']['image_size'][1] = "This is image error two";


$a = array(
    0 => "verdi0",
    1 => "verdi1",
    2 => "verdi2",
    3 => "verdi3"
);

$b = array();
foreach ($a as $key => $value) {
    $b['errors']['error' . $key] = $value;
}

print_r($b);
die;



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


function printAllErrors(&$d) {
    if(isset($d)) {
        foreach($d as $key => $value)
            if(is_array($d[$key])){
                foreach($value as $k => $v){
                    echo "<div class=\"errortext\">" . $value[$k] . "</div>";
                }
            } else {
                echo "<div class=\"errortext\">" . $d[$key] . "</div>";
            }
    }
}

// function printAllImageErrors(&$d) {
//     if(isset($d['image_errors'])) {
//         foreach($d['image_errors'] as $error)
//         echo "<div class=\"errortext\">" . $error . "</div>";
//     }
// }

printAllErrors($d['errors']);
// print_r($data);

// $test = "abc";

// echo @$test;