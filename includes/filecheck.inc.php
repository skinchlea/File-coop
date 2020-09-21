<?php

if (isset($_POST['fCheck'])) {

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $files = array();

//Load all messages that are linked to that board ID
$sql = "SELECT * FROM fileshare WHERE userID=$userID";
$result = mysqli_query($conn, $sql);
$filenumbers = array();
if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)){
    $filenumbers[] = $row['fileID'];
  }


  //For every file ID shared with this user, get the file details.

  for ($i = 0; $i < count($filenumbers); $i++) {

    $fileID = $filenumbers[$i];

    $sql = "SELECT * FROM files WHERE fileID=$fileID";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)) {

      array_push($files,$row);
        //$files[] = $row;

      }

}

        if(count($files) != 0){
          echo json_encode($files);
        }

  } else {
        echo 'No results found';
      }


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
