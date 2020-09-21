<?php

if (isset($_POST['fileInv'])) {

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $email = $_SESSION['UserEmail'];
      $fileID = $_POST['fileInv'];


//Delete invitation from database
$sql = "DELETE FROM invitations WHERE fileID=? AND emailTo=?;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
  header("Location: ../index.php?error=sqlerror");
  exit();
} else {

  mysqli_stmt_bind_param($stmt, "ss", $fileID, $email);
  mysqli_stmt_execute($stmt);


  echo 'Deleted Invite';
  exit();

}


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
