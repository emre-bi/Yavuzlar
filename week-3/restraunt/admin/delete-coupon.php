<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST["id"];
        
        deleteCouponById($id);

        header("Location: ./manage-coupon.php");
        exit();
    }
}

?>