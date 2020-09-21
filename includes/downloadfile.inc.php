<?php

if (isset($_POST['downloadID'])) {

  session_start();

    require 'dbh.inc.php';

    $fileID = $_POST['downloadID'];
    $userID = $_SESSION['userID'];

//Load file details from the file ID
$sql = "SELECT * FROM files WHERE fileID=$fileID";
$result = mysqli_query($conn, $sql);
$datas = array();
if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)){
    $datas[] = $row;
  }
  echo json_encode($datas);

  } else {
        echo 'No results found';
      }


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}