<?php

$date = $_GET['date'];

if(checkdate($date)) {
    "OK!";
} else {
    echo "NOT OK!";
}