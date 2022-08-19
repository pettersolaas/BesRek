<?php


// Check if user has submitted login form, then validate login
if(isset($_POST['username'])) {
    if ($admin_username == $_POST['username'] && $admin_password == $_POST['password']) {

        // Login success
        session_start(); 
        $_SESSION['user'] = $_POST['username'];
        header("Location: ../index.php");
    } else {

        // Login failed, prepare error message
        $login_error ="<p>Feil brukernavn eller passord</p>";
        header("Location: ../index.php?loginerror=1");
    }
} 