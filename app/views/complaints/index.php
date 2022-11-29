<?php
checkLogin();

$page_title = "Reklamasjoner";

require_once "../app/views/header.php";

$this->printSuccessFlash($d['confirm']['transfer']);
$this->printErrorFlash($d['error']['missing_data']);
$this->printErrorFlash($d['error']['not_owner']);
?>
    <h3>Reklamasjoner:</h3>

    Vis kun: 
    <table>
        <tr>
            <th></th>
            <th>Sak #</th>
            <th>Status</th>
            <th>Merke</th>
            <th>Modell</th>
            <th>Kunde</th>
            <th>Tlf</th>
            <th>Avdeling</th>
            <th>Ansatt</th>
            <th>Overfør</th>
            <th>E-post</th>
        </tr>

        <?php
        foreach ($d['all_department_complaints'] as $complaint) {
            ?>
            <tr>
                <td><a href="<?= DIR ?>complaints/edit/<?= $complaint->id ?>">Rediger</a></td>
                <td><?= $complaint->id ?></td>
                <td><?= $complaint->status->status ?></td>
                <td><?= $complaint->items->brands->id . " - " . $complaint->items->brands->name ?></td>
                <td><?= $complaint->items->id . " - " . $complaint->items->model ?></td>
                <td><?= $complaint->customers->id . " - " . $complaint->customers->name ?></td>
                <td><?= $complaint->customers->phone ?></td>
                <td><?= $complaint->departments->id . " - " . $complaint->departments->display_name ?></td>
                <td><?= $complaint->employees->id . " - " . $complaint->employees->name ?></td>
                <td>
                    <select onChange="transferOwnership(this)">
                        <option value="0">Velg...</option>
                    <?php
                        foreach($d['all_other_departments'] as $other_departments) {
                            echo "<option value=\"" . DIR . "complaints/transfer/" . $complaint->id . "/" . $other_departments->id . "\">" . $other_departments->display_name . "</option>" . PHP_EOL;
                        }
                    ?>
                    </select>


                
                </td>
                <td><a href="<?= DIR ?>complaints/mail/<?= $complaint->id ?>">Send</a></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <script type="text/javascript">
        function transferOwnership(a) {
            if(confirm('Vil du overføre reklamasjonen til valgt avdeling? Reklamasjonen kan kun overføres tilbake av valgt avdeling.')) {
                window.location.href=a.value;
            }
        }
    </script>


<?php

require_once "../app/views/footer.php";