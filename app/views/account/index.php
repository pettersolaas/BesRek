<?php

// Login form


$page_title = "Innlogging";

require_once "../app/views/header.php";
?>

<div class="login_form">
    <form action="<?= DIR ?>account/login/" method="post">
        <label for="department">Avdeling:</label>
        <input type="text" name="department" class="text_fields">
        <label for="password">Passord:</label>
        <input type="text" name="password"  class="text_fields">
        <input type="submit" class="form_buttons" value="Logg inn">
        <?= $this->printAllErrors($d); ?>
    </form>
</div>

<?php
require_once "../app/views/footer.php";