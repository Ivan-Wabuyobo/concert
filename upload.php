<?php
if (!empty($_FILES)) {
  $file = $_FILES['file'];
  $targetDir = "fliers/";
  $targetFile = $targetDir . basename($file["name"]);
  move_uploaded_file($file["tmp_name"], $targetFile);
}
?>