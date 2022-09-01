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
       $this->images = $this->model('Image');
     }


    // Show list of complaints
    public function index() {
    
        // Fetch complaints data
        $this->data['all_complaints'] = $this->complaint->with(['departments', 'employees', 'customers', 'items', 'brands'])->get();

        $this->view('complaints/index', $this->data);
    }

    // Show complaints form
    public function new() {

        // Get active employees for drop down list
        $this->data['all_employees'] = $this->getAllEmployeesFromDepartment();

        // Show/return to form
        $this->view('complaints/form', $this->data);
    }

    // Process new or edited complaint
    public function process(){
        
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
                    $this->data['errors']['purchase_date'] = "Kjøpsdato må fylles ut. Bruk formatet dd.mm.åååå";
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

                // Decide on using new or existing customer
                if(empty($customer_match_id)) {

                    // Create new customer and return its ID
                    $new_customer = $this->customer->create([
                        'name' => $_POST['customer_name'],
                        'phone' => $_POST['customer_phone'],
                        'email' => $_POST['customer_email']
                    ]);
                    $customer_id = $new_customer->id;
                
                } else {
                    // Use existing customer and ID
                    $customer_id = $customer_match_id;
                }

                // Decide on using new or existing brand
                if($this->brandExists($_POST['brand_id'], $_POST['brand_name'])){
                    // Use existing brand
                    $brand_id = $_POST['brand_id'];
                } else {
                    // Create new brand
                    $new_brand = $this->brand->create(['name' => $_POST['brand_name']]);
                    $brand_id = $new_brand->id;
                }

                // Create new complaint or edit existing?
                if(empty($_POST['complaint_id'])){

                    // Create new complaint
                    $new_item = $this->item->create([
                        'brand_id' => $brand_id,
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

                } else {

                    // TODO make sure complaint exists and belongs to department

                    // Edit complaint
                    $new_item = $this->item->where('id', '=', $_POST['item_id'])->update([
                        'brand_id' => $brand_id,
                        'model' => $_POST['item_model'],
                        'size' => $_POST['item_size'],
                        'color' => $_POST['item_color']
                    ]);

                    // Format date or set to NULL
                    if(!empty($_POST['purchase_date'])){
                        $purchase_date = date('Y-m-d', strtotime($_POST['purchase_date']));
                    } else {
                        $purchase_date = NULL;
                    }

                    $updated_complaint = $this->complaint->where('id', '=', $_POST['complaint_id'])->update([
                        'employee_id' => $_POST['employee_id'],
                        'customer_id' => $customer_id,           
                        'shown_receipt' => $_POST['shown_receipt'],
                        'purchase_date' => $purchase_date,
                        'purchase_sum' => $_POST['purchase_sum'],
                        'description' => $_POST['description'],
                        'internal_note' => $_POST['internal_note']
                    ]); 

                }

                // Done
                // Todo: show created complaint -> print complaint? Send mail to brand rep
                header("Location: " . DIR . "complaints/index");
            } else {
                // Errors are present - show form again
                $this->new();
            }


        }
    }

    // Prepare data for edit
    private $requested_complaint;
    private $complaint_images;

    public function edit($complaint_id, $d = null) {

        // Check to see if complaint exists and belong to current department
        if(!empty($complaint_id)){
            $this->requested_complaint = $this->complaint->where('department_id', '=', $_SESSION['department_id'])->find($complaint_id);
            
            if(!empty($this->requested_complaint)) {

                // Get associated images if they exist
                $this->complaint_images = $this->images->where('complaint_id', '=', $complaint_id)->get();
                if(!$this->complaint_images->isEmpty()){
                    $this->data['images'] = $this->complaint_images;
                }

                // Get all active employees
                $this->data['all_employees'] = $this->getAllEmployeesFromDepartment();

                // Get complaint
                $this->data['complaint'] = $this->requested_complaint;

                // Add errors from calling function (add/remove employee)
                if(isset($d['errors'])){
                    $this->data = array_merge($this->data, $d);
                }

                $this->view('complaints/form', $this->data);
            } else {
                echo "Reklamasjonssaken eksisterer ikke eller tilhører en annen avdeling.";
            }
        }
    }

        // Save image to complaint
        public function uploadImage() {

            // Check if form is submitted
            if(isset($_POST['image_submit'])){


                foreach ($_FILES['image']['tmp_name'] as $key => $val ) {
                    if (!preg_match("/^.*\.(jpg|jpeg|png)$/i", $_FILES['image']['name'][$key])) {
                        $this->data['errors']['image_extension' . $key] = "Filen \"" . $_FILES['image']['name'][$key] . "\" er av ugyldig filtype. Du kan kun laste opp filer med filtype jpg, jpeg og png";
                    }
                }
    
                // Make sure requested complaint exists
                if(!$this->complaintExists($_POST['complaint_id'])){
                    $this->data['errors']['complaint_does_not_exist'] = "Angitt reklamasjon ble ikke funnet";
                }
                
                foreach ($_FILES['image']['tmp_name'] as $key => $val ) {
                    $filesize_in_mb = (($_FILES['image']['size'][$key]/1024)/1024);
                    // if($_FILES['documents']['size'][$key] > 7340032){
                    if($filesize_in_mb > 5){
                        $this->data['errors']['file_size' . $key] = "Filen \"" . $_FILES['image']['name'][$key] . "\" er " . round($filesize_in_mb, 2) . "MB. Filstørrelsen kan ikke overstige 5MB";
                    }
                }

                foreach ($_FILES['image']['tmp_name'] as $key => $val ) {
                    if($_FILES['image']['error'][$key] !== UPLOAD_ERR_OK){
                        $this->data['errors']['upload_error' . $key] = "Det oppsto en feil under opplasting av bildet. Kode: " . $_FILES['image']['error'][$key];
                    }
                }
                // $this->data['image_errors2']['test'] = "yes";

                // if(!isset($this->data['image_errors'])){
                //     echo "no errors";
                // } else {
                //     echo "errors";
                // }


                
                // die;

                    // Process image upload if no errors are set
                    if(!$this->errors()){
            
                        foreach ($_FILES['image']['tmp_name'] as $key => $val ) {
                            // Get file extension
                            $file_ext = pathinfo($_FILES['image']['name'][$key], PATHINFO_EXTENSION);

                            $resized_image = $this->thumbnail($_FILES['image']['tmp_name'][$key], 1600);
                            $resized_image_thumb = $this->thumbnail($_FILES['image']['tmp_name'][$key], 200);
        
                            // Set new filename: complaint_id + unique id + extension
                            $new_filename = $_POST['complaint_id'] . "_" . uniqid() . "." . $file_ext;
                            $new_filename_thumb = $_POST['complaint_id'] . "_" . uniqid() . "_thumb." . $file_ext;
                            $new_path = "images/";
                            
                            $this->imageToFile($resized_image, $new_path . $new_filename);
                            $this->imageToFile($resized_image_thumb, $new_path . $new_filename_thumb);
                            // die;

                            // Save to db
                            $new_db_image = $this->images->create([
                                'complaint_id' => $_POST['complaint_id'],
                                'filename' => $new_filename,
                                'thumbnail' => $new_filename_thumb
                            ]);
                        }




    
                        // Move file
                        // move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $new_filename);
    
                        // Image uploaded
                        header("Location: " . DIR . "complaints/edit/" . $_POST['complaint_id']);
                        exit;
                    } else {
                        // Errors are present - show form again
                        $this->edit($_POST['complaint_id'], $this->data);
                    }
    
    
            
            }
        }

        public function thumbnail($inputFileName, $maxSize) {
            $info = getimagesize($inputFileName);
        
            $width = isset($info['width']) ? $info['width'] : $info[0];
            $height = isset($info['height']) ? $info['height'] : $info[1];
        
            // Calculate aspect ratio
            $wRatio = $maxSize / $width;
            $hRatio = $maxSize / $height;
        
            // Using imagecreatefromstring will automatically detect the file type
            $sourceImage = imagecreatefromstring(file_get_contents($inputFileName));
        
            // Calculate a proportional width and height no larger than the max size.
            if (($width <= $maxSize) && ($height <= $maxSize)) {
                // Input is smaller than thumbnail, do nothing
                return $sourceImage;
            } elseif (($wRatio * $height) < $maxSize) {
                // Image is horizontal
                $tHeight = ceil($wRatio * $height);
                $tWidth = $maxSize;
            } else {
                // Image is vertical
                $tWidth = ceil($hRatio * $width);
                $tHeight = $maxSize;
            }
        
            $thumb = imagecreatetruecolor($tWidth, $tHeight);
        
            // Copy resampled makes a smooth thumbnail
            imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
            imagedestroy($sourceImage);
        
            return $thumb;
        }

        function imageToFile($im, $fileName, $quality = 90) {
            if (!$im || file_exists($fileName)) {
                return false;
            }
        
            $ext = strtolower(substr($fileName, strrpos($fileName, '.')));
        
            switch ($ext) {
                case '.jpg':
                case '.jpeg':
                    imagejpeg($im, $fileName, $quality);
                    break;
                case '.png':
                    imagepng($im, $fileName);
                    break;
                default:
                    return false;
            }
            return true;
        }


    // Remove image from database and server
    public function removeImage($complaint_id, $image_filename){

        //Fetch requested image if it belongs to current department
        $image_exists = $this->images->
        where('images.filename', '=', $image_filename)->
        whereHas('complaints', function($q) {
            $q->where('department_id', '=', $_SESSION['department_id']);
        })->first();

        if(empty($image_exists)) {
            // Image does not exist or belongs to a different department
            $this->data['errors']['image_not_found'] = "Bildet eksisterer ikke eller tilhører en annen avdeling";
            echo "image does not exist";
        } else {
            // Image exists and belongs to current department
            
            // Delete image
            if (file_exists("images/" . $image_exists->filename)) {
                unlink("images/" . $image_exists->filename);
            }
            
            //Delete thumbnail
            if (file_exists("images/" . $image_exists->thumbnail)) {
                unlink("images/" . $image_exists->thumbnail);
            }
            
            // Delete from db
            $deleted_image = $this->images->where('filename', '=', $image_filename)->delete();

            // Go back to edit
            header("Location: " . DIR . "complaints/edit/" . $complaint_id);
            exit;
        }
        
        // Errors are present - show form again
        $this->edit($complaint_id, $this->data);
    }

    // Prepare sending of email
    public function mail($complaint_id){
        if(!empty($complaint_id)){
            $this->requested_complaint = $this->complaint->where('department_id', '=', $_SESSION['department_id'])->find($complaint_id);
            
            if(!empty($this->requested_complaint)) {
                // Get complaint
                $this->data['complaint'] = $this->requested_complaint;

                // Get associated images if they exist
                $this->complaint_images = $this->images->where('complaint_id', '=', $complaint_id)->get();
                if(!$this->complaint_images->isEmpty()){
                    $this->data['images'] = $this->complaint_images;
                }

                    $this->view('complaints/mail', $this->data);
            } else {
                echo "Reklamasjonssaken eksisterer ikke eller tilhører en annen avdeling.";
                exit;
            }
        }
    }
    // Send e-mail with complaint to brand contact
    public function sendMail(){
        // Check if form is submitted
        if(isset($_POST['send_mail'])){

            $separator = md5(time());
            $eol = "\r\n";

            // main header (multipart mandatory)
            $headers = "From: name <petter.solaas@gmail.com>" . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
            $headers .= "This is a MIME encoded message." . $eol;
        
            // message
            $body = "--" . $separator . $eol;
            $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
            $body .= "Content-Transfer-Encoding: 8bit" . $eol;
            $body .= $_POST['message'] . $eol;

            // Get images
            $complaint_images = $this->images->
            where('complaint_id', '=', $_POST['complaint_id'])->
            whereHas('complaints', function($q) {
                $q->where('department_id', '=', $_SESSION['department_id']);
            })->get();

            // Add images to mail
            foreach ($complaint_images as $image) {

                $file = "images/" . $image->filename;

                $content = file_get_contents($file);
                $content = chunk_split(base64_encode($content));

                // attachment
                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $image->filename . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol;
                $body .= $content . $eol;
            }

            $body .= "--" . $separator . "--";
       
            // Send mail
            // $send_mail = mail($_POST['to'], $_POST['subject'], $body, $headers);
            $send_mail = "faux";

            if($send_mail){
                $this->data['confirm']['email'] = "E-posten ble sendt";
            } else {
                $this->data['flasherrors']['email'] = "Det oppsto en feil under sending av e-post";
            }
                        
            // Go back to edit
            $this->edit($_POST['complaint_id'], $this->data);

        }
    }




    // Check if brand exists
    public function brandExists($brand_id, $brand_name){
        if($this->brand->where('id', '=', $brand_id)->where('name', '=', $brand_name)->exists()){
            return true;
        } else {
            return false;
        }
    }

    // Checks if a complaint exists
    public function complaintExists($complaint_id){
        if($this->complaint->where('id', '=', $complaint_id)->exists()){
            return true;
        } else {
            return false;
        }
    }

    // Checks if a complaint belongs to current department
    public function isComplaintOwner($complaint_id){
        if($this->complaint->where('id', '=', $complaint_id)->where('department_id', '=', $_SESSION['department_id'])->exists()){
            return true;
        } else {
            return false;
        }
    }

    // Get all active employees from department currently logged in
    public function getActiveEmployees(){
        return $this->department->find($_SESSION['department_id'])->employees->unique()->sortBy('name')->where('active', '=', '1');
    }

    // Get all employees from department currently logged in
    public function getAllEmployeesFromDepartment(){
        return $this->department->find($_SESSION['department_id'])->employees->unique()->sortBy('name');
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