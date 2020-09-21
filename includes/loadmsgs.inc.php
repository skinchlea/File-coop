<?php

if (isset($_POST['currentBoardID'])) {

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $tempboardID = $_POST['currentBoardID'];

//Load all messages that are linked to that board ID
$sql = "SELECT * FROM messages WHERE boardID=$tempboardID";
$result = mysqli_query($conn, $sql);
$datas = array();
if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)){
    $datas[] = $row;
  }
  echo json_encode($datas); 

  } else {
        echo 'no messages to load';
      }


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
