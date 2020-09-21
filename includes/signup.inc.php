<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Checks if the signup submit button was pressed
if(isset($_POST['signup-submit'])){


require 'dbh.inc.php';

$firstName = $_POST['fName'];
$surname = $_POST['sName'];
$email = $_POST['uEmail'];
$pwd = $_POST['pwd'];
$pwdRepeat = $_POST['pwd-repeat'];


//Check if the any of the sign-up inputs are empty
if(empty($firstName) || empty($surname) || empty($email) || empty($pwd) || empty($pwdRepeat)){
  header("Location: ../signup.php?error=emptyfields&mail=".$email);
  exit();
}

//Check if the email AND username are invalid
else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && (!preg_match("/^[a-zA-Z]*$/", $firstName)) && (!preg_match("/^[a-zA-Z]*$/", $surname))){
    header("Location: ../signup.php?error=invalidmailname");
exit();
}

//Check if the email inputted is valid
else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  header("Location: ../signup.php?error=invalidmail&uname=".$email);
  exit();
}

//Check if the username inputted is valid
// else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
//   header("Location: ../signup.php?error=invalidname&mail=".$email);
//   exit();
// }

//Check if the passwords don't match
else if(!$pwd == $pwdRepeat){
  header("Location: ../signup.php?error=passwordcheck&mail=".$email);
    exit();
} else {

  $sql = "SELECT userEmail FROM users where userEmail=?";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ../signup.php?error=sqlerror");
    exit();
  }
  else {

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCheck = mysqli_stmt_num_rows($stmt);
    if($resultCheck > 0){
    header("Location: ../signup.php?error=emailtaken");
    exit();
    }
    else {

      $sql = "INSERT INTO `users` (firstName, surname, userEmail, userpwd) VALUES (?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){

        header("Location: ../signup.php?error=sqlerror");
        exit();
      } else {


        $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
        //print ($hashedpwd);
        mysqli_stmt_bind_param($stmt, "ssss", $firstName, $surname, $email, $hashedpwd);

        mysqli_stmt_execute($stmt);
        header("Location: ../index.php");


        //Get the user ID from the newly inserted user
        $sql = "SELECT userID FROM users WHERE userEmail=?;";
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

            $id = $row['userID'];

            mkdir('../uploads/'.$id);

            header("Location: ../index.php?signup=success");

            exit();

          } else{
            echo 'no results';
            header("Location: ../index.php?error=nouser");
              exit();
          }

        }

    }
  }
}
}

  mysqli_stmt_close($stmt);
  mysqli_close($conn);

} else {
  header("Location: ../index.php");
  exit();
}
