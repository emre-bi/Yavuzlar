<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST["id"];
        
        deleteCompanyById($id);

        header("Location: ./manage-company.php");
        exit();
    }
}

?>