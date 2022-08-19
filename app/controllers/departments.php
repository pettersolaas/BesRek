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
    public function update() {
        
        // Map new data temporarily to department object
        $this->department = Department::find($_SESSION['department_id']);
        $this->department->login_name = $_POST['login_name'];
        $this->department->display_name = $_POST['display_name'];

        // Check input and define error messages
        if(!preg_match('/^[a-zA-Z0-9_-æøåÆØÅ]{5,20}$/', $_POST['login_name'])) {
            $this->data['errors']['login_name'] = "Brukernavn må være av lengde 5-20 og kan kun inneholde følgende tegn: a-å 0-9 _ -";
        }

        if(!preg_match('/^.{2,20}$/', $_POST['display_name'])) {
            $this->data['errors']['display_name'] = "Synlig navn må være av lengde 2-20.";
        }

        // Map new password only if it is entered and don't generate an error
        if(strlen($_POST['password']) > 0 || strlen($_POST['password2']) > 0) {
            if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $_POST['password'])) {
                $this->data['errors']['password'] = "Passord må være av lengde 8-20 og kun inneholde følgende tegn: a-å 0-9 _ -";
            } elseif($_POST['password'] != $_POST['password2']) {
                $this->data['errors']['password'] = "Passordene var ikke identiske";
            } else {
                $this->department->password = crypt($_POST['password'], SLT);
            }
        }

        // Go back to form and display errors and values submitted by user
        if($this->errors()) {
            // Set fields to submitted data or original data?
            if (isset($_POST['login_name'])) {
                $this->data['login_name'] = $_POST['login_name'];
            } else {
                $this->data['login_name'] = $_SESSION['login_name'];
            }

            if(isset($_POST['display_name'])) {
                $this->data['display_name'] = $_POST['display_name'];
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
    public function employees($department_id = null){

        if(!$department_id) {
            $department_id = $_SESSION['department_id'];
        }

        // Get department and its connected employees
        $department_with_employees = $this->department->with(['employees'])->where('id', '=', $department_id)->get();
        
        // Check if results are returned
        if($department_with_employees->isEmpty()){
            $this->data['errors']['invalid_department'] = "Avdeling " . $department_id . " eksisterer ikke";
        }

        // Get list of employees
        $this->employee = $this->employee->all();

        // Check if employees-list is populated
        if($this->employee->isEmpty()){
            $this->data['errors']['no_employees'] = "Ingen ansatte eksisterer. <a href=\"" . DIR . "employees/new/\">Opprett ny ansatt</a>";
        }

        // Put datasets into array
        $this->data =[
            'department_with_employees' => $department_with_employees,
            'all_employees' => $this->employee
        ];
        
        $this->view('departments/employees', $this->data);
    }


    // Add an employee to department
    public function addemployee($employee_id){

        // Check if employee exists
        $employee_exists = $this->employee->find($employee_id);
        
        if (!$employee_exists) {
            $this->data['errors']['employee_doesnt_exist'] = "Feil: Ansatt # " . $employee_id . " eksisterer ikke.";
        }
        
        // Check if employee is already added to requested department
        $employee_already_in_department = $this->department->find($_SESSION['department_id'])->employees()->find($employee_id);

        if ($employee_already_in_department) {
            $this->data['errors']['employee_already_in_department'] = "Feil: Ansatt #" . $employee_id . " er allerede tilkoblet avdeling #" . $_SESSION['department_id'];
        }

        // Add employee
        if (!$employee_already_in_department && $employee_exists) {
            $add_employee = $this->department->find($_SESSION['department_id'])->employees()->attach($employee_id);
        }
        $this->employees($_SESSION['department_id'], $this->data);  
    }


    // Remove an employee from a department
    public function removeemployee($employee_id){

        // Remove employee-department attachment
        $remove = $this->department->find($_SESSION['department_id'])->employees()->detach($employee_id);
        
        // Add error message if query returned 0
        if(!$remove) {
            $this->data['errors']['unknown_error'] = "En feil oppsto. Den ansatte kunne ikke fjernes";
        }

        // Go back to list and pass on errors
        $this->employees($_SESSION['department_id'], $this->data);
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