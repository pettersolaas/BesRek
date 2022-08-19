<?php

$host = "localhost"; /* Host name */$user = "root"; /* User */$password = ""; /* Password */$dbname = "register"; /* Database name */
$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}

if(isset($_GET['search'])){
 $search = mysqli_real_escape_string($con,$_GET['search']);

 $query = "SELECT * FROM customers WHERE name like'%".$search."%'";
 $result = mysqli_query($con,$query);

 $response = array();
 while($row = mysqli_fetch_array($result) ){
   $response[] = array("value"=>$row['id'],"label"=>$row['name']);
 }

 echo json_encode($response);
}

exit;