<?php

if (isset($_POST['detailsID'])) {

  session_start();

    require 'dbh.inc.php';

    $fileID = $_POST['detailsID'];
    $userID = $_SESSION['userID'];

//Load file details from the file ID
$sql = "SELECT * FROM files WHERE fileID=$fileID";
$result = mysqli_query($conn, $sql);

if($row = mysqli_fetch_assoc($result)) {

  $filename = $row['filename'];
  $fileUser = $row['userID'];

  $sql = "SELECT * FROM users WHERE userID=$fileUser";
  $result = mysqli_query($conn, $sql);

  if($row = mysqli_fetch_assoc($result)) {

    $datas = array();


    $datas = array (
      array("owner",$row['firstName'],$row['surname']),
      array("filename",$filename),
    );

    echo $datas;
    exit();

  } else {
    echo 'no results found';
  }

} else {
  echo 'no results found';
}


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
