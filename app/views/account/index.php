<?php

// Login form


$page_title = "Innlogging";

require_once "../app/views/header.php";
?>

<div class="login_form">
    <form action="<?= DIR ?>account/login/" method="post">

        <div class="input_container">
            <label for="department">Avdeling:</label>
            <input type="text" name="department" class="text_fields">
        </div>

        <div class="input_container">
            <label for="password">Passord:</label>
            <input type="text" name="password"  class="text_fields">
        </div>
        
        <div class="center_container">
            <div class="input_container">
                <input type="submit" class="form_buttons" value="Logg inn">
            </div>
        </div>

        <div class="center_container">
            <?= $this->printAllErrors($d); ?>
        </div>
    </form>
</div>

<?php
require_once "../app/views/footer.php";