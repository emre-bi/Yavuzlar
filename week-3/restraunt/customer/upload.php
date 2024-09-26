<?php

session_start();

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    
    $targetDir = "./uploads/";
    
    $imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION));
    
    $targetFilePath = $targetDir . $username . "." . $imageFileType;
    
    $uploadOk = 1;

    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    if ($_FILES["profileImage"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }


    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        header("Location: ./edit-profile.php");
        exit();
    } else {

        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFilePath)) {
            echo "The file " . htmlspecialchars($username) . " has been uploaded as your profile image.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
