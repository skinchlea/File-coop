<?php

if (isset($_POST['sendInv'])) {

  session_start();

    require 'dbh.inc.php';

  $userID = $_SESSION['userID'];

  $emailTo = $_POST['emailTo'];
  $fileIDnow = $_POST['sendInv'];
  $false = 'false';

    //Send invitation and add to invitation table

    //Add to invitation table
    $sql = "INSERT INTO invitations (emailTo, userFrom, invAccepted, fileID) VALUES (?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    } else {


      mysqli_stmt_bind_param($stmt, "ssss", $emailTo, $userID, $false, $fileIDnow);
      mysqli_stmt_execute($stmt);

      echo $userID;
      exit();

    }

} else {
     header("Location: ../index.php");
}
