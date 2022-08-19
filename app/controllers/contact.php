<?php

class Contact extends Controller {

    public function index() {
        echo "contact index";
    }

    public function phone(){
        echo "contact phone";
    }

    public function name($name = '', $name2 = null) {

        echo $name;

        echo "<br>";

        echo $name2;
        // $user = $this->model('User');
        // $user->name = $name;
        
        // $this->view('home/index', ['name' => $user->name]);
    }
}