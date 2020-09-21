<?php

if (isset($_POST['loadID'])) {

  session_start();

    require 'dbh.inc.php';

       $userID = $_SESSION['userID'];

       $fileToLoad = $_POST['loadID'];

       //Get the file ID that relates to the file selected
       $sql = "SELECT fileID FROM files WHERE userID=? AND filename=?";
       $stmt = mysqli_stmt_init($conn);
       if(!mysqli_stmt_prepare($stmt, $sql)){
         header("Location: ../index.php?error=sqlerror");
         exit();
       }
       else {
         mysqli_stmt_bind_param($stmt, "ss", $userID ,$fileToLoad);
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);

         //Check if the query returned a result
         if($row = mysqli_fetch_assoc($result)) {

           $tempfileID = $row['fileID'];

         //Get the board ID that connects to that file
         $sql = "SELECT boardID FROM fileboards WHERE fileID=$tempfileID";
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

             $tempboardID = $row['boardID'];
             echo $tempboardID;

           } else {
             echo 'no results found';
           }

           }

       } else {
              echo 'no results found';
       }
     }
}else{
    header("Location: ../index.php?error=error");
     echo 'no variable received';
    }
