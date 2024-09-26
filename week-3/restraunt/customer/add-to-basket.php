<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["note"])){
            $basket = getBasketByUserId($_SESSION["user_id"]);
            $newNote = $basket["note"] . " " . $_POST["note"];
            updateBasketNote($newNote, $_SESSION["user_id"]);
        }
        $food_id = $_POST["id"];
        $food = getFoodById($food_id);
        $old_basket_items = getBasketItems($_SESSION["user_id"]);
        $basket_items = array_values(array_filter($old_basket_items, function($item) {
            return is_null($item['food_deleted_at']);
        }));

        if($basket_items){
            $restaurant_id = $basket_items[0]["restaurant_id"];
            if($restaurant_id == $food["restaurant_id"]){
                addItemToBasket($_SESSION["user_id"], $food_id);
                header("Location: ./basket.php?mess=The Food added to basket Successfully!");
                exit();
            }else{
                header("Location: ./basket.php?mess=The Foods in the basket must be belongs to same restaurant!");
                exit();
            }
        }else{
            addItemToBasket($_SESSION["user_id"], $food_id);
        }

        header("Location: ./basket.php");
        exit();
    }
}

?>