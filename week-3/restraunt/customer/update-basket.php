<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $basket_id = $_POST["basket_id"];
        $quantity = $_POST["quantity"];

        updateBasketQuantity($basket_id, $quantity);
        

        header("Location: ./basket.php");
        exit();
    }
}

?>