<?php

if (isset($_POST['loadFilesStart'])) {

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $hubID = $_POST['hubID'];

//Load all messages that are linked to that board ID
$sql = "SELECT * FROM files WHERE userID='$userID' AND parentHub='$hubID'";
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
