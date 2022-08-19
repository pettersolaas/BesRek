<?php
$str = "<?php echo 555; ?>";
$newstr = htmlspecialchars($str, ENT_QUOTES);
// echo $newstr;

if(!preg_match('/^[a-zA-Z0-9_-æøåÆØÅ]{5,20}$/', htmlspecialchars("BERGEN"))) {
    echo "error";
} else {
    echo "noerror";
}