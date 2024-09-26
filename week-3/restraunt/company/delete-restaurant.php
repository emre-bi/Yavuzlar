<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $restaurantId = $_POST["id"];
        $company_restaurants = getAllRestaurantsForCompany($_SESSION["company_id"]);
        $restaurantIds = [];
        $restaurantIds = array_column($company_restaurants, 'id');

        if (in_array($restaurantId, $restaurantIds)) {
            deleteRestaurantById($restaurantId);
        }

        header("Location: ./company-panel.php");
        exit();
    }
}

?>