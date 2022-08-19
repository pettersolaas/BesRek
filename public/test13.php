<?php



// if(isset($_GET['mail']) && strlen($_GET['mail']) > 0){
//     if (!filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL)) {
//         echo "E-postadressen må formateres slik: navn@domene.com";
//         } else {
//             echo "E-mail er ok";
//         }
// } else {

//     echo "Not specified";
// }

if(!empty($_GET['mail'])){
    echo "Not empty";
} else {

    echo "empty";
}