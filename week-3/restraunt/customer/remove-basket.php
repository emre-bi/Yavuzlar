<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $basket_id = $_POST["basket_id"];
    
        removeBasketById($basket_id);

        header("Location: ./basket.php");
        exit();
    }
}

?>