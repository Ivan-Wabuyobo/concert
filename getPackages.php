<?php
include "dbconnect.php";
$event = $_GET['event'];
$sql = "SELECT * FROM `package` WHERE package.event = '$event'";
$results = $conn->query($sql);
$data = array();
while ($row = $results->fetch_assoc()) {
    $data[] = $row;
}
// convert the data array to a JSON string
$json = json_encode($data);

echo $json;

?>