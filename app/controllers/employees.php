<?php
checkLogin();

class Employees extends Controller {

    Public function __construct() {
        $this->employees = $this->model('Employee');
        $this->departments = $this->model('Department');        
     }

    // Show list of all emloyees
    public function index($d = null) {

        // Retrieve all employees
        $this->data['employees'] = $this->employees->orderBy('name', 'asc')->get();

        $this->view('employees/index', $this->data);
    }

    // Create new employee
    private $employee_exists;
    private $new_employee_name;
    private $new_employee;

    public function create() {
        
        // Sanitize requested employee name
        $this->new_employee_name = htmlspecialchars($_POST['employee_name']);
        if(!preg_match('/^[a-zA-Z0-9_-æøåÆØÅ]{3,30}$/', $this->new_employee_name)) {
            $this->data['errors']['username'] = "Brukernavn må være av lengde 3-30 og kan kun inneholde følgende tegn: a-å 0-9 _ -";
        }

        // Check if requested employee name is taken
        $this->employee_exists = $this->employees->where('name', '=', $this->new_employee_name)->exists();

        if ($this->employee_exists) {
            $this->data['errors']['employee_exist'] = "Feil: En ansatt med det navnet eksisterer allerede.";
        }

        // Create employee
        if(!$this->errors()) {
            $this->new_employee = $this->employees->create(['name' => $this->new_employee_name]);

            // Add employee to logged in department
            if(!empty($_POST['add_to_department'])) {
                $this->departments->find($_SESSION['department_id'])->employees()->attach($this->new_employee->id);
            }
        }

        $this->index($this->data);
    }

    // Activate employee
    private $requested_employee;
    
    public function activate($employee_id = null) {

        // Make sure user exists
        $this->requested_employee = $this->employees->find($employee_id);

        // Update user
        if(!empty($this->requested_employee) && !$this->requested_employee->active) {
            $this->requested_employee->active = 1;
            $this->requested_employee->save();
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
        $this->requested_employee = $this->employees->find($employee_id);

        // Update user
        if(!empty($this->requested_employee) && $this->requested_employee->active) {
            $this->requested_employee->active = 0;
            $this->requested_employee->save();
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
