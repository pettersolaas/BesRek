<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'register',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->bootEloquent();

// Secret word to add to password encryption
define("SLT", "Harpocrates");

// Configurable directory constant to make referencing and header redirects more reliable
// Example: 
// define("DIR", "/myprojects/project1/");
// http://example.com/myprojects/project1/controller/method/
// can be called with:
// <a href="<?= DIR =>controller/method/"></a>
define("DIR", "/mvcelo/public/");
