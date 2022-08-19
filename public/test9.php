

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="test9.php" method="post">
<input type="text" name="password">
<button type="submit">Submit</button>

</form>


<?php


//$password = " ";

echo "<br><br>";

echo "Password length: " . strlen($_POST['password']);

echo "<br><br>";

if(strlen($_POST['password']) > 0) {
    echo "Over 0";
} else {
    echo "Under 0";
}

echo "<br><br>";

if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $_POST['password'])) {
    echo "Does not match";
} else {
    echo "Matches";
}



?>
</body>
</html>