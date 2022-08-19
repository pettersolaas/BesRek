<?php
checkLogin();

$page_title = "Reklamasjoner";

require_once "../app/views/header.php";

?>
    <h3>Reklamasjoner:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Merke</th>
            <th>Modell</th>
            <th>Kunde</th>
            <th>Tlf</th>
            <th>Avdeling</th>
            <th>Ansatt</th>
        </tr>

        <?php
        foreach ($d['all_complaints'] as $complaint) {
            ?>
            <tr>
                <td><?= $complaint->id ?></td>
                <td><?= $complaint->items->brands->id . " - " . $complaint->items->brands->name ?></td>
                <td><?= $complaint->items->id . " - " . $complaint->items->model ?></td>
                <td><?= $complaint->customers->id . " - " . $complaint->customers->name ?></td>
                <td><?= $complaint->customers->phone ?></td>
                <td><?= $complaint->departments->id . " - " . $complaint->departments->display_name ?></td>
                <td><?= $complaint->employees->id . " - " . $complaint->employees->name ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php




        // dd($data['all_complaints'][0]->items->id);


        require_once "../app/views/footer.php";