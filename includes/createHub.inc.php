<?php

session_start();

//CREATE FOLDER
if (isset($_POST['hubName'])) {

    error_reporting(E_ALL);
ini_set('display_errors', 1);

    require 'dbh.inc.php';
    $userID = $_SESSION['userID'];
    $hubName = $_POST['hubName'];

    $sql = "INSERT INTO hubs (hubName, userID) VALUES (?,?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    } else {

        mysqli_stmt_bind_param($stmt, "ss", $hubName, $userID);
        mysqli_stmt_execute($stmt);

        //LOAD DATA FROM NEWLY CREATED HUB AND SEND BACK TO JS
          //Get the the details from the file
          $sql = "SELECT * FROM hubs WHERE userID='$userID' AND hubName='$hubName'";
          $stmt = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../index.php?error=sqlerror");
            exit();
          } else {

            // mysqli_stmt_bind_param($stmt, "s", $tempfileID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            //Check if the query returned a result
            if($row = mysqli_fetch_assoc($result)) {

              echo json_encode($row);
              exit();

            } else {
              echo 'no results found';
            }

        }

        }

        //header("Location: ../index.php");
        exit();

    

} else {
    header("Location: index.php?error=deletefailed");
    exit();
}
