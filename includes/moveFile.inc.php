<?php

if (isset($_POST['moveFile'])) {

  session_start();

require 'dbh.inc.php';

$userID = $_SESSION['userID'];

$fileID = $_POST['moveFile'];
$folderID = $_POST['moveFolder'];

$sql = "UPDATE files SET parentFolder='$folderID' WHERE fileID='$fileID' AND userID='$userID';";
   
if (mysqli_query($conn, $sql)) {
   echo "file moved";
} else {
   echo "Error updating record: " . mysqli_error($conn);
}

} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}