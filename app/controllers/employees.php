<?php
checkLogin();

class Employees extends Controller {

    Public function __construct() {
        $this->employees = $this->model('Employee');
        $this->departments = $this->model('Department');        
     }

    // Show list of all emloyees
    public function index($data = null) {

        // Retrieve all employees
        $this->data['employees'] = $this->employees->get();

        
        // Error if there are no users in DB
        // if($this->data['employees']->isEmpty()){
        //     $this->data['errors'] = "No users exist yet";
        // }

        $this->view('employees/index', $this->data);
    }

    // Show only active employees

    // Show only inactive employees

    // Create new employee
    public function create() {

        // Check if requested employee name is taken
        $employee_exists = $this->employees->where('name', '=', $_POST['employee_name'])->exists();

        if ($employee_exists) {
            $this->data['errors']['employee_exist'] = "Feil: En ansatt med det navnet eksisterer allerede.";
            // print_r($this->errors);
            // die();
        }

        // Sanitize requested employee name
        if(!preg_match('/^[a-zA-Z0-9_-æøåÆØÅ]{3,30}$/', $_POST['employee_name'])) {
            $this->data['errors']['username'] = "Brukernavn må være av lengde 3-30 og kan kun inneholde følgende tegn: a-å 0-9 _ -";
        }

        // Create employee
        if(!$this->errors()) {
            $new_employee = $this->employees->create(['name' => $_POST['employee_name']]);

            // Add employee to logged in department
            if(!empty($_POST['add_to_department'])) {
                $this->departments->find($_SESSION['department_id'])->employees()->attach($new_employee->id);
            }
        }

        $this->index($this->data);
    }


    // Activate employee
    public function activate($employee_id = null) {

        // Make sure user exists
        $requested_employee = $this->employees->find($employee_id);
// // echo "<pre>";
//         if($requested_employee){
//             echo "not empty";
//         } else {
//             echo "empty";
//         }

//         echo "<br>";
//         var_dump($requested_employee->active);
        
//         die;

        // Update user
        if(!empty($requested_employee) && !$requested_employee->active) {
            $requested_employee->active = 1;
            $requested_employee->save();
        } else {
            $this->data['errors']['employee_doesnt_exist_or_active'] = "Feil: Ansatt ble ikke funnet eller er allerede aktivert.";
        }

        if($this->errors()){
            // Return to main list with errors
            $this->index($this->data);
        } else {
            // Return to main list with cleaner URL
            header("Location: " . DIR . "employees/index/");
            exit;
        }
    }


    // Dectivate employee
    public function deactivate($employee_id = null) {

        // Make sure user exists
        $requested_employee = $this->employees->find($employee_id);

        // Update user
        if(!empty($requested_employee) && $requested_employee->active) {
            $requested_employee->active = 0;
            $requested_employee->save();
        } else {
            $this->data['errors']['employee_doesnt_exist_or_inactive'] = "Feil: Ansatt ble ikke funnet eller er allerede deaktivert.";
        }
        
        if($this->errors()){
            // Return to main list with errors
            $this->index($this->data);
        } else {
            // Return to main list with cleaner URL
            header("Location: " . DIR . "employees/index/");
            exit;
        }
    }



}
