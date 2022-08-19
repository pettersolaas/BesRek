<?php
checkLogin();

// Controller for home page and login form
class Home extends Controller {


    Public function __construct() {
     }

    // Home page when logged in
    public function index() {
        $this->view('home/index');
    }

}
