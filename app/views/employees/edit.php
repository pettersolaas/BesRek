<?php
checkLogin();

$page_title = "Ansatte";

require_once "../app/views/header.php";
?>

    <table>
        <tr>
            <th>ID</td>
            <th>Innloggingsnavn</th>
            <th>Synlig navn</th>
            <th></th>
        </tr>
        <?php
        foreach($d as $department){
            ?>
            <tr>
                <td><?= $department->id ?></th>
                <td><?= $department->login_name ?></td>
                <td><?= $department->display_name ?></td>
                <td>
                    <?php
                    // Show edit button if this is the logged in department
                    if($department->id == $_SESSION['department_id']){
                        echo "<a href=\"" . DIR . "departments/edit/\">Rediger</a>";
                    }
                    ?>                    
            </tr>
            <?php
        }
        ?>
    </table>

<?php
require_once "../app/views/footer.php";