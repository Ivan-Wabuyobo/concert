<?php
session_start();
include "dbconnect.php";

$event = $_GET['event'];
$user = $_SESSION['user']['id'];
$sql = "INSERT INTO `likes`(`event_id`, `user_id`, `liked`) VALUES ('$event', '$user', '1')";
$results = $conn->query($sql);
if($results){

    $transaction_id = "#".date('Ym').time();
    $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Liked an event', '$user')";
    $conn->query($sql);

    // redirect the user to the home page
    header("location:home.php");

}
