<?php

session_start();

//REMOVE SHARED FILE FROM ACCOUNT
if (isset($_POST['deletesharedID'])) {

  require 'dbh.inc.php';

     $userID = $_SESSION['userID'];

     $FiletoDelete = $_POST['deletesharedID'];

     //Delete any fileboards related to the file from the database
     $sql = "DELETE FROM fileboards WHERE fileID=?;";
     $stmt = mysqli_stmt_init($conn);
     if(!mysqli_stmt_prepare($stmt, $sql)){
       header("Location: ../index.php?error=sqlerror");
       exit();
     } else {

       mysqli_stmt_bind_param($stmt, "s", $FiletoDelete);
       mysqli_stmt_execute($stmt);


       //Delete any invitations related to the file from the database
       $sql = "DELETE FROM invitations WHERE fileID=?;";
       $stmt = mysqli_stmt_init($conn);
       if(!mysqli_stmt_prepare($stmt, $sql)){
         header("Location: ../index.php?error=sqlerror");
         exit();
       } else {

         mysqli_stmt_bind_param($stmt, "s", $FiletoDelete);
         mysqli_stmt_execute($stmt);

         //Delete any fileshares related to the file from the database
         $sql = "DELETE FROM fileshare WHERE fileID=? AND userID=?;";
         $stmt = mysqli_stmt_init($conn);
         if(!mysqli_stmt_prepare($stmt, $sql)){
           header("Location: ../index.php?error=sqlerror");
           exit();
         } else {

           mysqli_stmt_bind_param($stmt, "ss", $FiletoDelete, $userID);
           mysqli_stmt_execute($stmt);

           echo 'file removed';
           exit();

           header("Location: index.php?deletesuccess");
           exit();
         }

         header("Location: index.php?deletesuccess");
         exit();
       }

       header("Location: index.php?deletesuccess");
       exit();
     }

   } else {
     header("Location: index.php?error=sqlerror");
     exit();
   }
