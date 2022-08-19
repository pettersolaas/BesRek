<?php
checkLogin();

$page_title = "Rediger avdeling";

require_once "../app/views/header.php";

?>

    <form action="<?= DIR ?>departments/update" method="post">
        <label for="login_name">Innloggingsnavn:</label><br>
        <input type="text" name="login_name" value="<?= $this->printVar($d['login_name']) ?>">
        <?= $this->printError($d['errors']['login_name']) ?>
        <br><br>

        <label for="display_name">Synlig navn:</label><br>
        <input type="text" name="display_name" value="<?= $this->printVar($d['display_name']) ?>">
        <?= $this->printError($d['errors']['display_name']) ?> 
        <br><br>

        <label for="password">Nytt passord:</label><br>
        <input type="text" name="password"> (blir kun oppdatert dersom et nytt passord skrives inn)<br>
        <input type="text" name="password2">
        <?= $this->printError($d['errors']['password']) ?>
        <br><br>
        <?= $this->printAllErrors($d) ?>

        <input type="submit" name="form_submit" value="Lagre">
        <input type="button" value="Avbryt" onclick="location.href='<?= DIR ?>departments/index';"></input>
    </form>

<?php



require_once "../app/views/footer.php";
    
