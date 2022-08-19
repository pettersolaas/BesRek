<?php
checkLogin();

$page_title = "Ansatte ved avdeling";

require_once "../app/views/header.php";

// Check if there are any employees created
if($d['employees_in_dep']->isEmpty() && $d['employees_not_in_dep']->isEmpty()){
    ?>
    <br>
    <p>Ingen ansatte eksisterer. Vil du opprette en ny?</p>
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

} else {
    
    // Employees already in department
    if($d['employees_in_dep']->isEmpty()) {
        // No employees in dep
        echo "<p>Det er ingen ansatte i denne avdelingen</p>";
    } else {
        // Show list of employees currently in department
        ?>
        <h3>Ansatte i avdeling:</h3>
        <table>
        <?php
        foreach($d['employees_in_dep'] as $employee){
            ?>
            <tr>
                <td><?= $employee->id . " " . $employee->name ?></td>
                <?php
                echo "<td><a href=" . DIR . "departments/removeemployee/". $employee->id . ">Fjern</a></td>";
                ?>
            </tr>
            <?php
        }
        echo "</table>";
        echo "Ansatte: " . $d['employees_in_dep']->count();
    }

    $this->printAllErrors($d);

    // Available employees
    if($d['employees_not_in_dep']->isEmpty()){
        // No employees available to add
        echo "<p>Det er ingen flere ansatte som kan legges til</p>";
    } else{
        // Show list of employeees not in department (who can be added)
        ?>
        <h3>Legg til ansatt:</h3>
        <table>
            <?php
            foreach($d['employees_not_in_dep'] as $employee) {
                ?>
                <tr>
                    <td><?= $employee->id . " " . $employee->name ?></td>
                    <?php
                    echo "<td><a href=" . DIR . "departments/addemployee/" . $employee->id . ">Legg til</a></td>";
                    ?>
                </tr>
                <?php
            }
        ?>
        </table>
        <?php
    }
}

// var_dump($d);

require_once "../app/views/footer.php";