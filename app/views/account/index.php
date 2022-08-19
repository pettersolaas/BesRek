<?php

// Login form


$page_title = "Innlogging";

require_once "../app/views/header.php";
?>


<form action="<?= DIR ?>account/login/" method="post">
    <label for="department">Avdeling:</label><br>
    <input type="text" name="department"><br><br>
    <label for="password">Passord:</label><br>
    <input type="text" name="password"><br><br>
    <button type="submit">Logg inn</button><br><br>
    <?= $this->printAllErrors($d); ?>
</form>


<?php
require_once "../app/views/footer.php";