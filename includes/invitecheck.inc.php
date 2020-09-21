<?php

if (isset($_POST['invcheck'])) {


session_start();
require 'dbh.inc.php';
$userID = $_SESSION['userID'];

//Check for user email from userID
$myEmail = $_SESSION['UserEmail'];

//Check for invitations linked to email


$sql = "SELECT * FROM invitations WHERE emailTo=?";
$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)){
  header("Location: ../index.php?error=sqlerror");
  exit();
}

else {
  mysqli_stmt_bind_param($stmt, "s", $myEmail);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  //Check if the query returned a result
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      $filesLinked[] = $row;
    }

    //Check for file names linked to invitations
for ($i = 0; $i < count($filesLinked); $i++)
{

  $fileID = $filesLinked[$i]['fileID'];

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
        $fileNames[] = $row;

    } else {
      echo 'No results found';
    }
}

}

    echo json_encode($fileNames);

    } else {
          echo 'No results found';
        }

}

} else {
      header("Location: ../index.php");
}
