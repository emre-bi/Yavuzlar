<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = getUser($_SESSION["username"]);
        $balance = $user["balance"];
        $note = $_POST["note"];

        $basket_items = getBasketItems($_SESSION["user_id"]);
        $basket_items = array_values(array_filter($basket_items, function($item) {
            return is_null($item['food_deleted_at']);
        }));
        $total_price = 0;

        
        foreach ($basket_items as $item) {
            $total_price += $item['food_price'] * $item['quantity'];
        }
        $new_total_price = -1;
        foreach ($basket_items as $item) {
            $new_total_price = 0;
            $new_total_price += ($item['food_price'] * (100 - $item["discount"]) / 100) * $item['quantity'];
        }
        
        $restaurant_id = $basket_items[0]["restaurant_id"];
        $coupons = getAllCoupons();
        $coupon_rest_ids = [];
            $coupon_rest_ids = array_column($coupons, "restaurant_id");
            if(in_array($restaurant_id, $coupon_rest_ids)){
                foreach($coupons as $coupon){
                    if($coupon["restaurant_id"] == $restaurant_id){
                        $rest_coupon = $coupon;
                    }
                }
            }
        $new_new_total_price = -1;
        if(isset($rest_coupon)){
            $new_new_total_price = 0;
            if(isset($new_total_price)){
                $new_new_total_price = $new_total_price * (100 - $rest_coupon["discount"]) / 100;
            }else{
                $new_new_total_price = $total_price * (100 - $rest_coupon["discount"]) / 100;
            }
        }
        
        if($new_new_total_price != -1){
            $total_price = $new_new_total_price;
        }elseif($new_total_price != -1){
            $total_price = $new_total_price;
        }

        if($total_price < 0){
            header("Location: ./basket.php?mess=Sorry, Order Process is Failed. Price can't be negative number");
            exit();
        }elseif($balance < $total_price){
            header("Location: ./basket.php?mess=Sorry, Order Process is Failed. Your balance can't afford the price");
            exit();
        }elseif($balance > $total_price){
            $new_balance = $balance - $total_price;            
            $user_id = $user["id"];
            $order_status = "pending";

            createOrder($user_id, $order_status, $total_price, $basket_items);

            addBalance($_SESSION["user_id"], $new_balance);

            foreach($basket_items as $item){
                removeBasketById($item["basket_id"]);
            }

            header("Location: ./basket.php?mess=Order Process is Successfully Done!");
            exit();
        }
    }
}

?>