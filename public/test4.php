<?php


class User{
    public $name;

    public $fillable = ['username', 'email'];

    
}


class Controller {
    public function model($model){
        return new User();
    }
}


class Home extends Controller {

    public $user;

    Public function __construct() {
        // Creates the User object
        $this->user = $this->model('User');
        
        //echo "<pre>" . print_r($this->user);
        //echo $this->user->fillable[0];
     }

    // Method
    public function index($name = '') {

        // Set User object to property
        //$this->user = $name;

        //echo "<pre>" . print_r($this->user);
        //echo "<pre>" . print_r($this->user);
        




        // Call view method and specify output data
        $this->view('home/index', ['name' => $this->user2]);
    }

    public function test() {
        foreach (User->all() as $usr) {
            echo $usr->username;
        }
    }
}

$home = new Home;


$home->test();
//echo "<pre>" . print_r($home);



