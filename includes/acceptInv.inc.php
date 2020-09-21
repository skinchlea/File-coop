<?php

if (isset($_POST['acceptInv'])) {

  session_start();

    require 'dbh.inc.php';

      $userID = $_SESSION['userID'];
      $email = $_SESSION['UserEmail'];
      $fileID = $_POST['acceptInv'];


      $sql = "INSERT INTO fileshare (fileID, userID) VALUES (?,?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../index.php?error=sqlerror");
        exit();
      } else {


        mysqli_stmt_bind_param($stmt, "ss", $fileID, $userID);
        mysqli_stmt_execute($stmt);

        //Delete invitation from database
        $sql = "DELETE FROM invitations WHERE fileID=? AND emailTo=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
          header("Location: ../index.php?error=sqlerror");
          exit();
        } else {

          mysqli_stmt_bind_param($stmt, "ss", $fileID, $email);
          mysqli_stmt_execute($stmt);

          //Get the the details from the file
          $sql = "SELECT * FROM files WHERE fileID=$fileID";
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
        //header("Location: ../index.php");
        exit();

      }


}
} else {
    header("Location: ../index.php?error=sqlerror");
    exit();
}
