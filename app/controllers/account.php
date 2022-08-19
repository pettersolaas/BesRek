<?php
// Controller for home page
class Account extends Controller {
    
    Public function __construct() {
        // Creates the User object with data from db
       $this->department = $this->model('Department');
     }

    // Show login form
    public function index() {
        $this->view('account/index');
    }


    private $given_department;
    private $given_password;

    private $requested_department;

    private $department_id;
    private $department_password;
    private $department_display_name;

    // Perform login
    public function login(){

        $this->given_department = $_POST['department'];
        $this->given_password = $_POST['password'];
        $this->department_password = "";

        // Check that both username and password is provided
        if(empty($this->given_department) || empty($this->given_password)) {
            $this->data['errors']['username_password_empty'] = "Du må oppgi både brukernavn og passord";
            $this->view('account/index', $this->data);
            exit;
        }

        // Try to get department info from db
        $this->requested_department = $this->department->where('login_name', '=', $this->given_department)->first();
        if(!empty($this->requested_department)){

            // Assign correct login info
            $this->department_id = $this->requested_department->id;
            $this->department_password = $this->requested_department->password;
            $this->department_display_name = $this->requested_department->display_name;
        }

        // Login success
        if (password_verify($this->given_password, $this->department_password)) {

            // Set session variables to identify a logged in user
            $_SESSION['department_id'] = $this->department_id;
            $_SESSION['department_login_name'] = $_POST['department'];
            $_SESSION['department_display_name'] = $this->department_display_name;
            header("Location: " . DIR . "home/index/");

        // Login failed
        } else {
            $this->data['errors']['login_fail'] = "Innlogging feilet.";
            $this->view('account/index', $this->data);
        }
    }

    // Log out
    public function logout(){
        session_destroy();
        header("Location: " . DIR . "account/index/");
    }

}