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
    
    <div class="input_container">
        <label for="to" class="email_label">Til:</label>
        <input type="text" name="to" value="<?= $to ?>" class="mail_text_fields">
    </div>
    
    <div class="input_container">
        <label for="subject" class="email_label">Emne:</label>
        <input type="text" name="subject" value="<?= $subject ?>" class="mail_text_fields">
    </div>
    
    <textarea name="message" class="email"><?php
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
                    <img src="<?= DIR ?>images/<?= $image->thumbnail ?>" class="complaint_image">
                </a>
                <?php
            }
        }
        ?>
    </div>

    <input type="button" value="Avbryt" onclick="window.location.href='<?= DIR ?>complaints/index'" class="form_buttons email_buttons">
    <input type="submit" value="Send e-post" name="send_mail" class="form_buttons email_buttons">

</form>



<?php



require_once "../app/views/footer.php";