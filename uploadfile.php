<?php

require 'includes/dbh.inc.php';

session_start();

$userID = $_SESSION['userID'];

if (0 < $_FILES['file']['error']) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    exit();
} else {

    $hubID = $_POST['hubID'];

    //Get all the files they selected from the file input
    $file = $_FILES['file'];

    //Get the file name
    $fileName = $file['name'];

    //Get the temp file location
    $fileTmpName = $file['tmp_name'];

    //Get the file size
    $fileSize = $file['size'];

    //Check for errors
    $fileError = $file['error'];

    //Get the file type
    $fileType = $file['type'];

    //Get the file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    //Which files are allowed to be uploaded
    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'txt');

    //Check if the file extension of uploaded file matches an allowed type
    if (in_array($fileActualExt, $allowed)) {
        //Check for errors
        if ($fileError === 0) {
            //Check for size
            if ($fileSize < 10000000) {

                //Give the file a unique name and put the original extension back
                //$fileNameNew = uniqid('', true).".".$fileActualExt;

                //Set a destination for the file
                $fileDestination = 'uploads/' . $userID . '/' . $fileName;

                //Move the file into the folder in directory
                move_uploaded_file($fileTmpName, $fileDestination);

                //Insert that file into the database
                $sql = "INSERT INTO files (userID, filename, fileSize, parentHub) VALUES (?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../index.php?error=sqlerror");
                    exit();
                } else {

                    mysqli_stmt_bind_param($stmt, "ssss", $userID, $fileName, $fileSize, $hubID);
                    mysqli_stmt_execute($stmt);

                    //Get the file ID from the newly inserted row
                    $sql = "SELECT * FROM files WHERE userID=? AND filename=?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../index.php?error=sqlerror");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $userID, $fileName);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        //Check if the query returned a result
                        if ($row = mysqli_fetch_assoc($result)) {

                            //Get the file details returned - file name, file ID etc..
                            $fileDetails = $row;

                            //CREATE a board for the newly uploaded file
                            $sql = "INSERT INTO fileboards (fileID) VALUES (?)";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../signup.php?error=sqlerror");
                                exit();
                            } else {

                                mysqli_stmt_bind_param($stmt, "s", $row['fileID']);
                                mysqli_stmt_execute($stmt);

                                //encode the results and push it through AJAX
                                echo json_encode($fileDetails);
                                exit();

                            }
                        } else {
                            echo 'no results';
                            header("Location: index.php?error=boardfail");
                        }

                        //move_uploaded_file($_FILES['file']['name'], 'uploads/' . $_FILES['file']['name']);
                        echo "Uploaded";

                    }
                }
            } else {
                echo "File Too Big!";
                exit();
            }
        } else {
            echo "Error";
            exit();
        }
    }
}
