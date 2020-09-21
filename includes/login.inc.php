<?php

//Did the user get to this page by pressing the login button
if(isset($_POST['login-submit'])){

require 'dbh.inc.php';

$email = $_POST['uname'];
$password = $_POST['pwd'];

if(empty($email) || empty($password)){
  header("Location: ../index.php?error=emptyfields");
  exit();
} else {
  $sql = "SELECT * FROM users WHERE userEmail=?";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ../index.php?error=sqlerror");
    exit();
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //Check if the query returned a result
    if($row = mysqli_fetch_assoc($result)) {
      $pwdCheck = password_verify($password, $row['userpwd']);

      //The password entered does not match
      if($pwdCheck == false) {
        header("Location: ../index.php?error=wrongpwd");
        exit();
      }
      //The password entered is correct
      else if($pwdCheck == true) {
          session_start();
          $_SESSION['userID'] = $row['userID'];
          $_SESSION['UserEmail'] = $row['userEmail'];
          $_SESSION['firstName'] = $row['firstName'];
          $_SESSION['lastName'] = $row['surname'];
          header("Location: ../index.php?login=success");
          exit();
      } else {
        header("Location: ../index.php?error=wrongpwd");
        exit();
      }
    }
     else {
       header("Location: ../index.php?error=nouser");
       exit();
     }

  }
}



}
else {
    header("Location: ../index.php");
    exit();
}

?>
