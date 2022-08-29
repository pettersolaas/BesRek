<?php
checkLogin();

$page_title = "Endre reklamasjon";

require_once "../app/views/header.php";

// Print images if they exist
if(!empty($d['images'])){
    foreach ($d['images'] as $image) {
        ?>
        <a href="<?= DIR ?>images/<?= $image->filename ?>" target="_blank">
            <img src="<?= DIR ?>images/<?= $image->thumbnail ?>">
        </a>
        <a href="<?= DIR ?>complaints/removeImage/<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>/<?= $image->filename ?>">REMOVE</a>
        <br>
        <?php
    }
}
?>
<form action="<?= DIR ?>complaints/uploadImage" method="post" enctype="multipart/form-data">
    <input type="file" name="image[]" value="Velg bilde" multiple="multiple">
    <input type="submit" name="image_submit" value="Last opp">
    <br>
    <input type="text" name="complaint_id" value="<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>" class="read_only" hidden>

</form>


<?php
// Print all errors generated by image upload
$this->printAllErrors($d);
?>


<form action="<?= DIR ?>complaints/process" method="post" autocomplete="off">
    <input type="text" name="complaint_id" value="<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>" class="read_only" hidden>
    <br>

    <input type="text" name="department_id" value="<?= $_SESSION['department_id'] ?>" class="read_only" hidden>
    <span></span>
    <br>
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
        <label for="employee_id">Ansatt:</label><br>
        <select name="employee_id">
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

    <div class="errortext"><?= $this->printError($d['errors']['employee_id']) ?></div>
    <br>

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

    <label for="customer_phone">Kunde tlf</label><br>
    <input type="text" name="customer_phone" id="customer_phone" value="<?= $_POST['customer_phone'] ?? $d['complaint']->customers->phone ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_phone']) ?></div>
    <br>

    <label for="customer_name">Kundenavn</label><br>
    <input type="text" name="customer_name" id="customer_name" value="<?= $_POST['customer_name'] ?? $d['complaint']->customers->name ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_name']) ?></div>
    <br>

    <label for="customer_email">Kunde e-post</label><br>
    <input type="text" name="customer_email" id="customer_email" value="<?= $_POST['customer_email'] ?? $d['complaint']->customers->email ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_email']) ?></div>

    <input type="text" name="customer_id" id="customer_id" value="<?= $_POST['customer_id'] ?? $d['complaint']->customers->id ?? ''; ?>" class="read_only" hidden>
    <br>


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

    <label for="brand_id">Merke</label><br>
    <input type="text" name="brand_name" id="brand_name" value="<?= $_POST['brand_name'] ?? $d['complaint']->brands->name ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['brand_name']) ?></div>
    
    <input type="text" name="brand_id" id="brand_id" value="<?= $_POST['brand_id'] ?? $d['complaint']->brands->id ?? ''; ?>" class="read_only" hidden>
    <input type="text" name="item_id" id="brand_id" value="<?= $_POST['item_id'] ?? $d['complaint']->items->id ?? ''; ?>" class="read_only" hidden>
    <br>

    <label for="item_model">Modell</label><br>
    <input type="text" name="item_model" value="<?= $_POST['item_model'] ?? $d['complaint']->items->model ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['item_model']) ?></div>
    <br>
    
    <label for="item_size">Størrelse</label><br>
    <input type="text" name="item_size" value="<?= $_POST['item_size'] ?? $d['complaint']->items->size ?? ''; ?>">
    <br><br>

    <label for="item_color">Farge</label><br>
    <input type="text" name="item_color" value="<?= $_POST['item_color'] ?? $d['complaint']->items->color ?? ''; ?>">
    <br><br>
    
    <label for="shown_receipt">Vist kvittering?</label><br>
    <input type="text" name="shown_receipt" value="<?= $_POST['shown_receipt'] ?? $d['complaint']->shown_receipt ?? ''; ?>">
    <br><br>
    
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

    <label for="purchase_date">Kjøpsdato</label><br>
    <input type="text" name="purchase_date" id="purchase_date" value="<?= $_POST['purchase_date'] ?? $formatted_purchase_date ?? ''; ?>">
    <div class="errortext"><?= $this->printError($d['errors']['purchase_date']) ?></div>
    <br>
    
    <label for="purchase_sum">Kjøpssum</label><br>
    <input type="text" name="purchase_sum" value="<?= $_POST['purchase_sum'] ?? $d['complaint']->purchase_sum ?? ''; ?>">
    <br><br>
    
    <label for="description">Beskrivelse av reklamasjon (sendes til leverandør)</label><br>
    <textarea cols="100" rows="10" name="description"><?= $_POST['description'] ?? $d['complaint']->description ?? ''; ?></textarea>
    <div class="errortext"><?= $this->printError($d['errors']['description']) ?></div>
    <br>
    
    <label for="internal_note">Internt notat (Vises ikke til kunde eller leverandør)</label><br>
    <textarea cols="100" rows="5" name="internal_note"><?= $_POST['internal_note'] ?? $d['complaint']->internal_note ?? ''; ?></textarea>
    <br><br>

    <input type="button" value="Send e-post" onclick="window.location.href='<?= DIR ?>complaints/mail/<?= $_POST['complaint_id'] ?? $d['complaint']->id ?? ''; ?>'">
    <input type="submit" name="form_submit" value="Lagre">
</form>


<?php




require_once "../app/views/footer.php";
    
