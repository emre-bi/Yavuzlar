<?php
include 'db.php';
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = getUser($username);

    if($user && $password == $user["password"]){
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        if($user["isAdmin"] == 1){
            $_SESSION["role"] = "admin";
        }
        else{
            $_SESSION["role"] = "non-admin";
        }
        header("Location: /app.php");
        exit();
    }
    else{
        header("Location: /index.php");
        exit();
    }
}
?>