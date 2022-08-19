<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

session_start();

// Composer autoloader
require_once "../vendor/autoload.php";

require_once "database.php";
require_once "core/app.php";
require_once "core/controller.php";
require_once "core/functions.php";




