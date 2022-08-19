<?php

function printVar2(&$var) {
    if(isset($var)) {
        echo $var;
    }
}

function printVar(&$var, $else = '') {
    echo isset($var) && $var ? $var : $else;
}

$d['employee'] = "Pete";

printVar2($d['employee']);