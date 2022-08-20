<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="<?= DIR ?>css/index.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="<?= DIR ?>js/jquery-3.6.0.min.js"></script>
    <script src="<?= DIR ?>js/jquery-ui.js"></script>
</head>
<body>
<?php
if(isLoggedIn()) {
?>
Innlogget som: <?= $_SESSION['department_display_name'] ?>

&nbsp;&nbsp;&nbsp;[<a href="<?= DIR ?>account/logout/">Logg ut</a>]

&nbsp;&nbsp;&nbsp;[<a href="<?= DIR ?>home/index">Hjem</a>]

<br>
<br>
<?php
}

?>