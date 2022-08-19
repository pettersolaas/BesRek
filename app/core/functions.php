<?php

// Site-wide function to check if user is logged in
function isLoggedIn(){
    if(isset($_SESSION['department_id'])){
        return true;
    }
}

// Site-wide function to make sure user is logged in, or redirect to login page
function checkLogin(){
    if(!isLOggedIn()){
        header("Location: " . DIR . "account/index/");
        exit;
    }
}

