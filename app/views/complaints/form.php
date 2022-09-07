<?php
checkLogin();

$page_title = "Endre reklamasjon";

require_once "../app/views/header.php";

$this->printSuccessFlash($d['confirm']['email']);
$this->printErrorFlash($d['flasherrors']['email']);
?>


<div class="form-container">
    <div class="complaint_form">

        <form action="<?= DIR ?>complaints/process" method="post" autocomplete="off">
            

            <div class="input_container">
                <label for="brand_id">Merke</label>
                <input class="text_fields" type="text" name="brand_name" id="brand_name" value="<?= $_POST['brand_name'] ?? $d['complaint']->brands->name ?? ''; ?>">
                <?= $this->printError($d['errors']['brand_name']) ?>
            </div>

            <div class="input_container">
                <label for="item_model">Modell</label>
                <input class="text_fields" type="text" name="item_model" value="<?= $_POST['item_model'] ?? $d['complaint']->items->model ?? ''; ?>">
                <?= $this->printError($d['errors']['item_model']) ?>
            </div>
            
            <div class="input_container">
                <label for="item_size">Størrelse</label>
                <input class="text_fields" type="text" name="item_size" value="<?= $_POST['item_size'] ?? $d['complaint']->items->size ?? ''; ?>">
            </div>
            
            <div class="input_container">
                <label for="item_color">Farge</label>
                <input class="text_fields" type="text" name="item_color" value="<?= $_POST['item_color'] ?? $d['complaint']->items->color ?? ''; ?>">
            </div>
            
            <div class="input_container">
                <label for="shown_receipt">Vist kvittering?</label>
                <input class="text_fields" type="text" name="shown_receipt" value="<?= $_POST['shown_receipt'] ?? $d['complaint']->shown_receipt ?? ''; ?>">
            </div>

            <script>
            // Show calender
            $( function() {
            $( "#purchase_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd.mm.yy"
            });
            } );
            </script>

            <?php
            if(!empty($d['complaint']->purchase_date)){
                // var_dump($d['complaint']->purchase_date);
                // die;
                $formatted_purchase_date = date('d.m.Y', strtotime($d['complaint']->purchase_date));
            }
            ?>    

            <div class="input_container">
                <label for="purchase_date">Kjøpsdato</label>
                <input class="text_fields" type="text" name="purchase_date" id="purchase_date" value="<?= $_POST['purchase_date'] ?? $formatted_purchase_date ?? ''; ?>">
                <?= $this->printError($d['errors']['purchase_date']) ?>
            </div>
            
            <div class="input_container">
                <label for="purchase_sum">Kjøpssum</label>
                <input class="text_fields" type="text" name="purchase_sum" value="<?= $_POST['purchase_sum'] ?? $d['complaint']->purchase_sum ?? ''; ?>">
            </div>
            

            <div class="textarea_container">
                <label for="description">Beskrivelse av reklamasjon (sendes til leverandør)</label>
                <textarea class="text_fields" cols="100" rows="10" name="description"><?= $_POST['description'] ?? $d['complaint']->description ?? ''; ?></textarea>
                <?= $this->printError($d['errors']['description']) ?>
            </div>
            
            <div class="textarea_container">
                <label for="internal_note">Internt notat (Vises ikke til kunde eller leverandør)</label>
                <textarea class="text_fields" cols="100" rows="5" name="internal_note"><?= $_POST['internal_note'] ?? $d['complaint']->internal_note ?? ''; ?></textarea>
            </div>






            <div class="customer">

                <?php
                // Check if department have any employees
                // No employees
                if($d['all_employees']->isEmpty()){
                    ?>
                    <p>Det er ingen aktive ansatte registrert på din avdeling.</p><br>
                    <a href="<?= DIR ?>employees/index">Opprett en ny ansatt</a><br>
                    <a href="<?= DIR ?>departments/employees">Legg til ansatt til avdeling</a><br>
                    <?php
                    // Employees found
                } else {
                    ?>
                    
                    <div class="input_container">
                        <label for="employee_id">Ansatt:</label>
                        <select name="employee_id" class="employee_selector">
                            <option value="">Velg...</option>
                            <?php
                            // Set already registered employee as selected
                            $selected = "";
                            foreach ($d['all_employees'] as $employee) {
                                if(isset($_POST['employee_id'])) {
                                    if($_POST['employee_id'] == $employee->id) {
                                        $selected = " selected";
                                    } 
                                } elseif ($d['complaint']->employees->id == $employee->id) {
                                    $selected = " selected";
                                }

                                // Print only active employees or an inactive if it was originally the complaints employee
                                if(($employee->active == 1) || !empty($selected)){
                                echo "<option value=\"" . $employee->id . "\"" . $selected . ">" . $employee->name . "</option>";
                                }
                                $selected = "";
                            }
                            ?>
                        </select>
                    <?php 
                }
                ?>

                <?= $this->printError($d['errors']['employee_id']) ?>
                
                </div>
                    <script>
                    // Customer autocomplete search
                    $(document).ready(function() {
                        $('#customer_phone').autocomplete({
                            source: function(request,response){
                                // Fetch data
                                $.ajax({
                                    url: '<?= DIR ?>complaints/getcustomer',
                                    data: 'GET',
                                    dataType: 'json',
                                    data: {
                                        search: request.term
                                    },
                                    success: function(data){
                                        response(data);
                                    }
                                });
                            },
                            select: function(event,ui){
                                $('#customer_phone').val(ui.item.label);
                                $('#customer_name').val(ui.item.name);
                                $('#customer_email').val(ui.item.email);
                                $('#customer_id').val(ui.item.id);
                                return false;
                            }
                        });
                    });
                    </script>

                    <div class="input_container">
                        <label for="customer_phone">Kunde tlf</label>
                        <input class="text_fields" type="text" name="customer_phone" id="customer_phone" value="<?= $_POST['customer_phone'] ?? $d['complaint']->customers->phone ?? ''; ?>">
                        <?= $this->printError($d['errors']['customer_phone']) ?>
                    </div>

                    <div class="input_container">
                        <label for="customer_name">Kundenavn</label>
                        <input class="text_fields" type="text" name="customer_name" id="customer_name" value="<?= $_POST['customer_name'] ?? $d['complaint']->customers->name ?? ''; ?>">
                        <?= $this->printError($d['errors']['customer_name']) ?>
                    </div>

                    <div class="input_container">
                    <label for="customer_email">Kunde e-post</label>
                    <input class="text_fields" type="text" name="customer_email" id="customer_email" value="<?= $_POST['customer_email'] ?? $d['complaint']->customers->email ?? ''; ?>">
                    <?= $this->printError($d['errors']['customer_email']) ?>
                    </div>


                    <script>
                    // Customer autocomplete search
                    $(document).ready(function() {
                        $('#brand_name').autocomplete({
                            source: function(request,response){
                                // Fetch data
                                $.ajax({
                                    url: '<?= DIR ?>brands/getbrands',
                                    data: 'GET',
                                    dataType: 'json',
                                    data: {
                                        search: request.term
                                    },
                                    success: function(data){
                                        response(data);
                                    }
                                });
                            },
                            select: function(event,ui){
                                $('#brand_name').val(ui.item.label);
                                $('#brand_id').val(ui.item.id);
                                return false;
                            }
                        });
                    });
                </script>
                <?php
                // Show extra fields if case is already saved
                if(!empty($d['complaint']->id)) {
                    ?>
                    <div class="input_container">
                        <label for="complaint_id2">Saksnr</label>
                        <input class="text_fields" type="text" name="complaint_id2" value="<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>" readonly>
                    </div>
                    
                    <div class="input_container">
                        <label for="complaint_status">Status</label>
                        <input class="text_fields" type="text" name="complaint_status" value="<?= $_POST['complaint_status'] ?? $d['complaint']->status ?? ''; ?>" readonly>
                    </div>                <?php } ?>
            </div>

            <input class="form_buttons" type="button" value="Avbryt" onclick="window.location.href='<?= DIR ?>complaints/index'">
            <input class="form_buttons" type="submit" name="form_submit" value="Lagre">
            <?php 
            if(!empty($_POST['complaint_id']) || !empty($d['complaint']->id)) {
                    ?>
            <input class="form_buttons" type="button" value="Send e-post" onclick="window.location.href='<?= DIR ?>complaints/mail/<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>'">

            <?php } ?>

            <input type="text" name="customer_id" id="customer_id" value="<?= $_POST['customer_id'] ?? $d['complaint']->customers->id ?? ''; ?>" class="read_only" hidden>
            <input type="text" name="complaint_id" value="<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>" class="read_only" hidden>
            <input type="text" name="department_id" value="<?= $_SESSION['department_id'] ?>" class="read_only" hidden>
            <input type="text" name="brand_id" id="brand_id" value="<?= $_POST['brand_id'] ?? $d['complaint']->brands->id ?? ''; ?>" class="read_only" hidden>
            <input type="text" name="item_id" id="item_id" value="<?= $_POST['item_id'] ?? $d['complaint']->items->id ?? ''; ?>" class="read_only" hidden>
        </form>
    </div>







    <div class="images_form">
        <?php
        // Print images if they exist
        if(!empty($d['images'])){
            foreach ($d['images'] as $image) {
                ?>
                <div class="complaint-image">
                    <a href="<?= DIR ?>complaints/removeImage/<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>/<?= $image->filename ?>" onclick="return confirm('Slette bildet?')">
                        <img src="<?= DIR ?>icons/delete.png">
                    </a>
                    <a href="<?= DIR ?>images/<?= $image->filename ?>" target="_blank">
                        <img src="<?= DIR ?>images/<?= $image->thumbnail ?>" class="complaint_image">
                    </a>
                </div>
                <?php
            }
        }

        // Show upload form if case is already saved
        if(!empty($_POST['complaint_id']) || !empty($d['complaint']->id)) {
        ?>
            <h3>Last opp bilder:</h3>
            <form action="<?= DIR ?>complaints/uploadImage" method="post" enctype="multipart/form-data">

                <input type="text" name="complaint_id" value="<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>" class="read_only" hidden>
                <input class="form_file_button" type="file" name="image[]" value="Velg bilde" multiple="multiple">
                <input class="form_buttons" type="submit" name="image_submit" value="Last opp">

            </form>
            <?php
            // Print all errors generated by image upload
            $this->printRequestedErrors($d['errors']['images']);
        }
        ?>
    </div>
</div>


<?php
require_once "../app/views/footer.php";
    
