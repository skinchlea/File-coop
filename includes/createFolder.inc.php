<?php

session_start();

//CREATE FOLDER
if (isset($_POST['folderCheck'])) {


    function searchFolders()
    {

        $found = false;
        $searched = 1;
        $newFolder = 'New Folder ' . $searched;

        require 'dbh.inc.php';
        $userID = $_SESSION['userID'];
        $hubID = $_POST['hubID'];

        //Directory to search
        $dir = '../uploads/' . $userID;

        //Searched directory
        $directory = scandir($dir, 1);

        //This prints all files in directory
        // foreach($directory as $result) {
        // echo $result, '<br>';
        // }
        // exit();

        //Check for folder in directory
        for ($x = 0; $x <= count($directory) - 1; $x++) {

            //If it doesn't exist
            if (!in_array($newFolder, $directory)) {

                //Create It
                mkdir('../uploads/' . $userID . '/' . $newFolder);

                //Add to database here

                $sql = "INSERT INTO folders (userID, folderName, parentHub) VALUES (?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../index.php?error=sqlerror");
                    exit();
                } else {

                    mysqli_stmt_bind_param($stmt, "ssi", $userID, $newFolder, $hubID);
                    mysqli_stmt_execute($stmt);

                    //Select Details from newly created folder and send to Front End
                    $sql = "SELECT * FROM folders WHERE folderName='$newFolder' AND userID='$userID'";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../index.php?error=sqlerror");
                        exit();
                    } else {

                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        

                        //Check if the query returned a result
                        if ($row = mysqli_fetch_assoc($result)) {

                          echo json_encode($row);
                            exit();

                        } else {
                            echo 'no results found';
                        }

                    }

                    //header("Location: ../index.php");
                    exit();

                }

            } else {

                //If it does exist, add onto counter and try again

                $searched++;
                $newFolder = 'New Folder ' . $searched;
            }

        }
    }

    searchFolders();

} else {
    header("Location: index.php?error=deletefailed");
    exit();
}
