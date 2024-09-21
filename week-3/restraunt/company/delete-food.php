<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $food_id = $_POST["id"];
        $restaurantId = getRestaurantIdForFood($food_id);
        $company_restaurants = getAllRestaurantsForCompany($_SESSION["company_id"]);
        $restaurantIds = array_column($company_restaurants, 'id');

        if (in_array($restaurantId, $restaurantIds)) {
            deleteFoodById($food_id);
        }

        header("Location: ./manage-food.php?id=".$restaurantId);
        exit();
    }
}

?>