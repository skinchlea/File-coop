<?php

if (isset($_POST['previewFileID'])) {
  

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $previewID = $_POST['previewFileID'];

//Load all messages that are linked to that board ID
$sql = "SELECT * FROM files WHERE fileID=$previewID";
$result = mysqli_query($conn, $sql);

//Check if the query returned a result
if($row = mysqli_fetch_assoc($result)) {

  $filename = $row['filename'];
  $fileOwner = $row['userID'];
  echo 'uploads/'.$fileOwner.'/'.$filename;
  exit();

} else {
  echo 'no results found';
}

} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
