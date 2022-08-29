<?php
checkLogin();

$page_title = "Endre reklamasjon";

require_once "../app/views/header.php";

if(!empty($d['complaint']->purchase_date)){
    $formatted_purchase_date = date('d.m.Y', strtotime($d['complaint']->purchase_date));
}

$to = $d['complaint']->brands->contact_mail;
$subject = "Reklamasjon på " . $d['complaint']->brands->name . " " . $d['complaint']->items->model . " (# " . $d['complaint']->id . ")";
$message_start = "Hei!\r\n\r\nVi har fått inn følgende reklamasjon:\r\n";
$message_details =
    "\r\nMerke: " . $d['complaint']->brands->name .
    "\r\nModell: " . $d['complaint']->items->model .
    "\r\nStørrelse: " . $d['complaint']->items->size .
    "\r\nFarge: " . $d['complaint']->items->color .
    "\r\nKjøpsdato: " . $formatted_purchase_date .
    "\r\n\r\nBeskrivelse: " . $d['complaint']->description;
$message_end = "\r\n\r\n\r\nMvh. \r\n\r\n" . $_SESSION['department_display_name'];
?>

<p>NB: Endringer som gjøres her gjelder kun for e-posten og blir ikke lagret i saken.</p>

<form action="<?= DIR ?>complaints/sendmail" method="post">
    <input type="hidden" name="complaint_id" value="<?= $d['complaint']->id ?>">
    Til: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="to" value="<?= $to ?>" size="70"><br>
    Emne: <input type="text" name="subject" value="<?= $subject ?>" size="70"><br><br>
    <textarea cols="100" rows="20" name="message"><?php
    echo $message_start . $message_details . $message_end;
    ?>
    </textarea>

    <div>
        <h3>Vedlegg:</h3>
        <?php
        // Print images if they exist
        if(!empty($d['images'])){
            foreach ($d['images'] as $image) {
                ?>
                <a href="<?= DIR ?>images/<?= $image->filename ?>" target="_blank">
                    <img src="<?= DIR ?>images/<?= $image->thumbnail ?>">
                </a>&nbsp;&nbsp;
                <?php
            }
        }
        ?>
    </div>
    
    <br>
    <br>


    <input type="submit" value="Send e-post" name="send_mail">

</form>



<?php



require_once "../app/views/footer.php";