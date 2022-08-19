<?php

// Show list of all employees
// Form to add employees

checkLogin();

$page_title = "Ansattliste";

require_once "../app/views/header.php";


// dd($d);
// die;


// Check if any employees are returned
if(!$d['employees']->isEmpty()){
?>

    <table>
        <tr>
            <th colspan="2">Ansatte</th>
        </tr>
        <?php
        foreach ($d['employees'] as $employee) {

            if($employee->active) {
                $bg_color = "#8df184";
                $link_text = "Deaktiver";
                $link_url = "deactivate";
            } else{
                $bg_color = "#f18484";
                $link_text = "Aktiver";
                $link_url = "activate";
            }
            ?>

        <tr style="background-color: <?= $bg_color ?>;">
            <td><?= $employee->name ?></td>
            <td> <a href="<?= DIR ?>employees/<?= $link_url ?>/<?= $employee->id ?>"><?= $link_text ?></a>
        </tr>
        <?php
        }
        ?>
    </table>
    <br>
<?php

$this->printAllErrors($d);

} else {

    echo "<p>Ingen ansatte er opprettet.</p>";
}

?>

<br>

<h3>Opprett en ny ansatt:</h3>
<form action="<?= DIR ?>employees/create/" method="post">
    <label for="employee_name">Ansattnavn:</label><br>
    <input type="text" name="employee_name"><br>

    <input type="checkbox" name="add_to_department">
    <label for="add_to_department">Knytte ansatt til <?= $_SESSION['department_display_name'] ?>?</label><br>
    <button type="submit">Opprett</button>
</form>
<?php

//printError($d['errors']);

require_once "../app/views/footer.php";