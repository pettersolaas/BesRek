<?php

//require_once "../app/init.php";

//$app = new App();

//$url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

//echo "<pre>" . print_r($url);

$text = "http://localhost/mvc/public/index.php/";

//echo rtrim("http://localhost/mvc/public/index.php///", '-');
//echo filter_var($text, FILTER_SANITIZE_URL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{ padding: 10px;}
    </style>
</head>
<body>
    <?php

//$url = explode('/', $text);
$url = explode('/', filter_var(rtrim($text, '/'), FILTER_SANITIZE_URL));

// echo "<pre>" . print_r($url);

echo $url[0];

// foreach($url as $item) {
//     echo $item . "<br>";
// }


?>
</body>
</html>