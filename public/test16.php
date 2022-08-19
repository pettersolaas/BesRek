<?php

$personer = array(
    1 => "petter",
    2 => "john",
    3 => "morten"
);



foreach ($personer as &$person) {
    echo $person . "<br>";

    if ($person == "petter") {
        $person = "nye petter";
    }
}

echo "<br>";

foreach ($personer as $person) {
    echo $person . "<br>";
}