<?php
session_start();
$user =  $_SESSION['user']['id'];
$transaction_id = "#".date('Ym').time();
include "dbconnect.php";
$sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Logged out successfully', '$user')";
$conn->query($sql);

// remove all session variables
session_unset();
session_destroy();

// redirect the user to the index page
header("location:login.php");

?>