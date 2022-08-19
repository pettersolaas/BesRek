<?php
checkLogin();

// Controller for home page
class Complaints extends Controller {


    Public function __construct() {
        // Creates the User object with data from db
       $this->department = $this->model('Department');
       $this->employee = $this->model('Employee');
       $this->complaint = $this->model('Complaint');
       $this->item = $this->model('Item');
       $this->customer = $this->model('Customer');
       $this->brand = $this->model('Brand');
     }


    // Show list of complaints
    public function index() {

        // Get active employees for drop down list
        //$this->data['active_employees'] = $this->getActiveEmployees();

        // $this->data['all_complaints'] = $this->complaint->with('departments:display_name')->get();
        
        // $department_with_employees = $this->department->with(['employees'])->where('id', '=', $department_id)->get();

        //$this->data['all_complaints'] = $this->complaint->with(['employees'])->where('id', '=', '1')->get();

        
        $this->data['all_complaints'] = $this->complaint->with(['departments', 'employees', 'customers', 'items', 'brands'])->get();

        // Show/return to form
        $this->view('complaints/index', $this->data);
    }


    // Show complaints form
    public function new() {

        // Get active employees for drop down list
        $this->data['active_employees'] = $this->getActiveEmployees();

        // Show/return to form
        $this->view('complaints/new', $this->data);
    }

    // Store a new complaint
    public function create(){
        
        // Check if form is submitted
        if(isset($_POST['form_submit'])){

            // Validate employee name
            if(empty($_POST['employee_id'])){
                $this->data['errors']['employee_id'] = "Velg en ansatt";
            }

            // Validate customer phone
            if(empty($_POST['customer_phone'])){
                $this->data['errors']['customer_phone'] = "Kundens telefonnummer må skrives inn";
            }

            // Validate customer name
            if(empty($_POST['customer_name'])){
                $this->data['errors']['customer_name'] = "Kundens navn må skrives inn";
            }
            
            // Validate e-mail (if set)
            if(!empty($_POST['customer_email'])){
                if (!filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
                    $this->data['errors']['customer_email'] = "E-postadressen må formateres slik: navn@domene.com";
                  }
            }

            // Validate brand
            if(empty($_POST['brand_name'])) {
                $this->data['errors']['brand_name'] = "Produsent (merke) må oppgis";
            }

            // Validate item name
            if(empty($_POST['item_model'])) {
                $this->data['errors']['item_model'] = "Varenavn (modell) må oppgis";
            }
            
            // Validate purchase_date
            if(!empty($_POST['purchase_date'])) {
                if (!preg_match("/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/", $_POST['purchase_date'])) {
                    $this->data['errors']['purchase_date'] = "Kjøpsdato har feil format. Bruk dd.mm.ååå";
                }
            }

            // Validate description
            if(empty($_POST['description'])) {
                $this->data['errors']['description'] = "Beskrivelse av reklamasjonen må skrives inn";
            }


            // Check if any errors has been set
            if(!$this->errors()){

            // Customer data validation
            $customer_match_id = "";

            // Fetch existing customer with same phone
            $customers_on_file = $this->customer->where('phone', '=', $_POST['customer_phone'])->get();

                // If existing customer was found, start comparison
                if(!$customers_on_file->isEmpty()){
                        
                foreach($customers_on_file as $customer_on_file) {

                    // See if data matches existing customer or if we create a new
                    if($_POST['customer_id'] == $customer_on_file->id && $_POST['customer_name'] == $customer_on_file->name && $_POST['customer_phone'] == $customer_on_file->phone) {
                        
                        // Was optional email set?
                        if(!empty($_POST['customer_email'])) {

                            // Does email match?
                            if($_POST['customer_email'] == $customer_on_file->email){
                                $customer_match_id = $customer_on_file->id;
                            }
                        } else {
                            // Email was not set, but everything else matched
                            $customer_match_id = $customer_on_file->id;
                        }
                    }
                }
            }

            // Decide on using new/existing
            if(empty($customer_match_id)) {

                // Insert new customer and return its ID
                $new_customer = $this->customer->create([
                    'name' => $_POST['customer_name'],
                    'phone' => $_POST['customer_phone'],
                    'email' => $_POST['customer_email']
                ]);
            // echo "created customer: " . $new_customer->id;
            $customer_id = $new_customer->id;
            
            } else {
                // Use existing profile and ID
                $customer_id = $customer_match_id;
                // echo "<br>Using existing customer<br>";
            }

            $new_item = $this->item->create([
                'brand_id' => $_POST['brand_id'],
                'model' => $_POST['item_model'],
                'size' => $_POST['item_size'],
                'color' => $_POST['item_color']
            ]);
            // echo "<br>Created item<br>";

            $this->complaint->create([
                'department_id' => $_SESSION['department_id'],
                'employee_id' => $_POST['employee_id'],
                'customer_id' => $customer_id,
                'item_id' => $new_item->id,                
                'shown_receipt' => $_POST['shown_receipt'],
                'purchase_date' => date('Y-m-d', strtotime($_POST['purchase_date'])),
                'purchase_sum' => $_POST['purchase_sum'],
                'description' => $_POST['description'],
                'internal_note' => $_POST['internal_note']
            ]);
            // echo "<br>Created complaint<br>";

                // Done -> print complaint? Send mail to 
                // $this->view('complaints/list', $data);
            } else {
                // Errors are present - back to form with errror messages
                $this->new($this->data);
            }


        }
        
    }

    // Get all active employees from department currently logged in
    public function getActiveEmployees(){
        return $this->department->find($_SESSION['department_id'])->employees->unique()->sortBy('name')->where('active', '=', '1');
    }

    // Get an employee name
    public function getEmployee(){
        $query = $_REQUEST["term"] . '%';
        $customers = $this->customer->where('phone', 'LIKE', $query)->get();
        // dd($employees);
        foreach($customers as $customer){
            echo "<p>" . $customer->phone . "</p>";
        }
    }

    // Get JSON list of customers
    public function getCustomer(){

        if(!empty($_GET['search'])) {
            $search = $_GET['search'] . "%";

            $customers = $this->customer->where('phone', 'LIKE', $search)->get();

            $response = array();
            foreach ($customers as $customer) {
                $response[] = array(
                    "label" => strval($customer->phone),
                    "id" => strval($customer->id),
                    "name" => $customer->name,
                    "email" => $customer->email
                );
           }
        }
        echo json_encode($response);
        exit;
    }

}