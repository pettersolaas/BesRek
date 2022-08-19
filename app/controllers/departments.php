<?php
checkLogin();

// Controller for departments
class Departments extends Controller {

    Public function __construct() {
        // Creates the User object
        $this->department = $this->model('Department');
        $this->employee = $this->model('Employee');      
    }

    // Show department list and menu
    public function index() {
        
        // Get all department data
        $this->department = $this->department->all();

        $this->view('departments/index', $this->department);
    }

    // Edit a specified department
    public function edit() {

        // Get data for the department being edited
        $this->data['login_name'] = $this->department->find($_SESSION['department_id'])->login_name;
        $this->data['display_name'] = $this->department->find($_SESSION['department_id'])->display_name;

        // SHow edit form with original data
        $this->view('departments/edit', $this->data);
    }


    // Process request to update department
    private $new_login_name;
    private $new_display_name;
    private $new_pw1;
    private $new_pw2;
    
    public function update() {
        // Sanitize input
        $this->new_login_name = htmlspecialchars($_POST['login_name']);
        $this->new_display_name = htmlspecialchars($_POST['display_name']);
        $this->new_pw1 = htmlspecialchars($_POST['password']);
        $this->new_pw2 = htmlspecialchars($_POST['password2']);

        // Check input and define error messages
        if(!preg_match('/^[a-zA-Z0-9_-æøåÆØÅ]{5,20}$/', $this->new_login_name)) {
            $this->data['errors']['login_name'] = "Brukernavn må være av lengde 5-20 og kan kun inneholde følgende tegn: a-å 0-9 _ -";
        }

        if(!preg_match('/^.{2,20}$/', $this->new_display_name)) {
            $this->data['errors']['display_name'] = "Synlig navn må være av lengde 2-20.";
        }

        // Map new data temporarily to department object
        $this->department = Department::find($_SESSION['department_id']);
        $this->department->login_name = $this->new_login_name;
        $this->department->display_name = $this->new_display_name;
        
        // Map new password only if it is entered and don't generate an error
        if(strlen($this->new_pw1) > 0 || strlen($this->new_pw2) > 0) {
            if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $this->new_pw1)) {
                $this->data['errors']['password'] = "Passord må være av lengde 8-20 og kun inneholde følgende tegn: a-å 0-9 _ -";
            } elseif($this->new_pw1 != $this->new_pw2) {
                $this->data['errors']['password'] = "Passordene var ikke identiske";
            } else {
                $this->department->password = crypt($this->new_pw1, SLT);
            }
        }

        // Go back to form and display errors and values submitted by user
        if($this->errors()) {
            // Set fields to submitted data or original data?
            if (isset($this->new_login_name)) {
                $this->data['login_name'] = $this->new_login_name;
            } else {
                $this->data['login_name'] = $_SESSION['login_name'];
            }

            if(isset($this->new_display_name)) {
                $this->data['display_name'] = $this->new_display_name;
            } else {
                $this->data['display_name'] = $_SESSION['display_name'];
            }

            $this->view('departments/edit', $this->data);

        // Update db with new values
        } else {            
            // Save to database and update users session
            $this->department->save();
            $_SESSION['department_login_name'] = $this->department->login_name;
            $_SESSION['department_display_name'] = $this->department->display_name;

            header("Location: " . DIR . "departments/index/");
            exit;
        }
    }

    // Show the employees for a specified department
    private $employees_in_dep;
    private $employees_not_in_dep;    
    public function employees($d = null){

        // var_dump($d);
        // die();
        // Get employees who are already in department
        $this->employees_in_dep = $this->employee->withWhereHas('departments', fn($query) => 
        $query->where('departments.id', '=', $_SESSION['department_id'])
        )->orderBy('name', 'asc')->get();

        // Get employees who arenot in department
        $this->employees_not_in_dep  = $this->employee->WhereDoesntHave('departments', fn($query) => 
        $query->where('departments.id', '=', $_SESSION['department_id'])
        )->orderBy('name', 'asc')->get();

        // Put datasets into array
        $this->data = [
            'employees_in_dep' => $this->employees_in_dep,
            'employees_not_in_dep' => $this->employees_not_in_dep
        ];

        // Add errors from calling function (add/remove employee)
        if(isset($d['errors'])){
            $this->data = array_merge($this->data, $d);
        }
        
        $this->view('departments/employees', $this->data);
    }


    // Add an employee to department
    private $employee_exists;
    private $employee_already_in_department;

    public function addemployee($employee_id){

        // Check if employee exists
        $this->employee_exists = $this->employee->find($employee_id);
        
        if (!$this->employee_exists) {
            $this->data['errors']['employee_doesnt_exist'] = "Feil: Ansatt # " . $employee_id . " eksisterer ikke.";
        }
        
        // Check if employee is already added to requested department
        $this->employee_already_in_department = $this->department->find($_SESSION['department_id'])->employees()->find($employee_id);

        if ($this->employee_already_in_department) {
            $this->data['errors']['employee_already_in_department'] = "Feil: Ansatt #" . $employee_id . " er allerede tilkoblet avdeling #" . $_SESSION['department_id'];
        }

        // Add employee
        if (!$this->employee_already_in_department && $this->employee_exists) {
            $add_employee = $this->department->find($_SESSION['department_id'])->employees()->attach($employee_id);
        }
        $this->employees($this->data);  
    }


    // Remove an employee from a department
    private $remove;
    
    public function removeemployee($employee_id){

        // Remove employee-department attachment
        $this->remove = $this->department->find($_SESSION['department_id'])->employees()->detach($employee_id);

        // Add error message if query returned 0
        if(!$this->remove) {
            $this->data['errors']['unknown_error'] = "En feil oppsto. Den ansatte kunne ikke fjernes";
        }

        // Go back to list and pass on errors
        $this->employees($this->data);
    }
    
 }

         /* Easier queries:
        
        // Find department
        $current_department = $this->department->find(1);

        // Addemployee
        $current_department->employees()->attach(10);

        // All in one - add employee
        $current_department = $this->department->find(1)->employees()->attach(9);

        // Find an employee in a department
        $remove = $this->department->find(1)->employees()->find(1);
        */