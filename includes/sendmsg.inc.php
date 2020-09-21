<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['loadedBoard'])) {

  session_start();

    require 'dbh.inc.php';

    $userID = $_SESSION['userID'];
    $fName = $_SESSION['firstName'];
    $lName = $_SESSION['lastName'];

    $boardID = $_POST['loadedBoard'];
    $newMessage = $_POST['newMsg'];


    $sql = "INSERT INTO messages (msgText, boardID, userID, firstName, lastName) VALUES (?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    } else {


      mysqli_stmt_bind_param($stmt, "sssss", $newMessage, $boardID, $userID, $fName, $lName);
      mysqli_stmt_execute($stmt);

      echo $fName." ".$lName;
      //header("Location: ../index.php");
      exit();

    }


} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
