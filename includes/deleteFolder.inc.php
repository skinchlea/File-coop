<?php

session_start();

//DELETE FILE
if (isset($_POST['dFolderID'])) {


  require 'dbh.inc.php';

     $userID = $_SESSION['userID'];

     $FiletoDelete = $_POST['dFolderID'];

     $dir = '../uploads/'.$userID.'/'.$FiletoDelete;

     $directoryToDelete = scandir($dir);

     //Delete the folder from the directory
    for ($x = 0; $x < count($directoryToDelete); $x++){
      if($directoryToDelete[$x] != "." || $directoryToDelete[$x] != ".."){
        unlink('../uploads/'.$userID.'/'.$FiletoDelete.'/'.$directoryToDelete[$x]);
      }


        rmdir($dir);

        //Delete the folder from the database
        $sql = "DELETE FROM folders WHERE folderName=? AND userID=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
          header("Location: ../index.php?error=sqlerror");
          exit();
        } else {

          mysqli_stmt_bind_param($stmt, "ss", $FiletoDelete, $userID);
          mysqli_stmt_execute($stmt);

          echo "success";
          exit();
        }


    }


   }


 else {
   header("Location: index.php?error=deletefailed");
   exit();
}

?>
