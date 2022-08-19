<?php
checkLogin();

$page_title = "Meny";

require_once "../app/views/header.php";


?>


<h1>Meny:</h1>

<h2>Administrering</h2>
<a href="<?= DIR ?>departments/index/">Avdelinger</a><br>
<a href="<?= DIR ?>employees/index">Ansatte</a><br>

<h2>Reklamasjoner</h2>
<a href="<?= DIR ?>complaints/index">Oversikt</a><br>
<a href="<?= DIR ?>complaints/new">Registrere ny sak</a><br>
<a href="">Ubehandlede</a><br>
<a href="">Oversendt importør</a><br>
<a href="">Venter på ny vare</a><br>
<a href="">Søk</a><br>


<?php
require_once "../app/views/footer.php";