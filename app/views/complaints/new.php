<?php
checkLogin();

$page_title = "Opprett reklamasjon";

require_once "../app/views/header.php";
?>

<form action="<?= DIR ?>complaints/create" method="post" autocomplete="off">
    <label for="department_id">Avdelings ID</label><br>
    <input type="text" name="department_id" value="<?= $_SESSION['department_id'] ?>" class="read_only" readonly>
    <span></span>
    <br>
    <?php
    // Check if department have any employees
    // No employees
    if($d['active_employees']->isEmpty()){
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
            $selected = "";
            foreach ($d['active_employees'] as $employee) {
                if(isset($_POST['employee_id'])) {
                    if($_POST['employee_id'] == $employee->id) {
                        $selected = " selected";
                    } else {
                        $selected = "";
                    }
                }

                echo "<option value=\"" . $employee->id . "\"" . $selected . ">" . $employee->name . "</option>";
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
    <input type="text" name="customer_phone" id="customer_phone" value="<?= $this->printVar($_POST['customer_phone']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_phone']) ?></div>
    <br>

    <label for="customer_name">Kundenavn</label><br>
    <input type="text" name="customer_name" id="customer_name" value="<?= $this->printVar($_POST['customer_name']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_name']) ?></div>
    <br>

    <label for="customer_email">Kunde e-post</label><br>
    <input type="text" name="customer_email" id="customer_email" value="<?= $this->printVar($_POST['customer_email']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['customer_email']) ?></div>
    <br>

    <label for="customer_id">(Kunde ID)</label><br>
    <input type="text" name="customer_id" id="customer_id" value="<?= $this->printVar($_POST['customer_id']); ?>" class="read_only" readonly>
    <br>    <br>


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
    <input type="text" name="brand_name" id="brand_name" value="<?= $this->printVar($_POST['brand_name']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['brand_name']) ?></div>
    <br>   
    
    <label for="brand_id">(Merke ID)</label><br>
    <input type="text" name="brand_id" id="brand_id" value="<?= $this->printVar($_POST['brand_id']); ?>" class="read_only" readonly>
    <br><br>    

    <label for="item_model">Modell</label><br>
    <input type="text" name="item_model" value="<?= $this->printVar($_POST['item_model']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['item_model']) ?></div>
    <br>
    
    <label for="item_size">Størrelse</label><br>
    <input type="text" name="item_size" value="<?= $this->printVar($_POST['item_size']); ?>">
    <br><br>

    <label for="item_color">Farge</label><br>
    <input type="text" name="item_color" value="<?= $this->printVar($_POST['item_color']); ?>">
    <br><br>
    
    <label for="shown_receipt">Vist kvittering?</label><br>
    <input type="text" name="shown_receipt" value="<?= $this->printVar($_POST['shown_receipt']); ?>">
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

    <label for="purchase_date">Kjøpsdato</label><br>
    <input type="text" name="purchase_date" id="purchase_date" value="<?= $this->printVar($_POST['purchase_date']); ?>">
    <div class="errortext"><?= $this->printError($d['errors']['purchase_date']) ?></div>
    <br>
    
    <label for="purchase_sum">Kjøpssum</label><br>
    <input type="text" name="purchase_sum" value="<?= $this->printVar($_POST['purchase_sum']); ?>">
    <br><br>
    
    <label for="description">Beskrivelse av reklamasjon (sendes til leverandør)</label><br>
    <textarea cols="100" rows="10" name="description" value="<?= $this->printVar($_POST['description']); ?>"></textarea>
    <div class="errortext"><?= $this->printError($d['errors']['description']) ?></div>
    <br>
    
    <label for="internal_note">Internt notat (Vises ikke til kunde eller leverandør)</label><br>
    <textarea cols="100" rows="5" name="internal_note" value="<?= $this->printVar($_POST['internal_note']); ?>"></textarea>
    <br><br>

    <input type="submit" name="form_submit" value="Lagre">
</form>

<?php

require_once "../app/views/footer.php";
    
