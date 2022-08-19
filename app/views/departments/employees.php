<?php
// Shows list of employees at a department
// Todo: Ability to change name
// Todo: ability to set user passive
checkLogin();

$page_title = "Ansatte ved avdeling";

require_once "../app/views/header.php";

// Array to keep track of employees already in this department
$current_employees = array();

// Show list of employees in a department
foreach($d['department_with_employees'] as $department) {
?>

    <h1><?= $department->display_name ?></h1>

    <h3>Current employees:</h3>
    <table>
    <?php
    foreach($department->employees as $employee){
        // Add current employee ID to array
        array_push($current_employees, $employee->id);
        ?>
        <tr>
            <td><?= $employee->id . " " . $employee->name ?></td>
            <?php
            // Show remove link if we are showing the department that is logged in
            if($_SESSION['department_id'] == $department->id) {
            echo "<td><a href=" . DIR . "departments/removeemployee/". $employee->id . ">Remove</a></td>";
            }
            ?>
        </tr>
        <?php
    }
    echo "</table>";
}

echo "Ansatte: " . count($current_employees);
?>

<h3>Add employee:</h3>
    <table>
    <?php
    foreach($d['all_employees'] as $employee) {

        // Skip employees who are already listed
        if(!in_array($employee->id, $current_employees)) {
            ?>

            <tr>
                <td><?= $employee->id . " " . $employee->name ?></td>
                <?php
                // Show add link if we are showing the department that is logged in
                if($_SESSION['department_id'] == $d['department_with_employees'][0]->id) {
                echo "<td><a href=" . DIR . "departments/addemployee/" . $employee->id . ">Add</a></td>";
                }
                ?>
            </tr>
            <?php
        }
    }
?>    

</table>




<?php
require_once "../app/views/footer.php";