<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = getUser($_SESSION["username"]);
        $balance = $user["balance"];

        $basket_items = getBasketItems($_SESSION["user_id"]);
        $basket_items = array_values(array_filter($basket_items, function($item) {
            return is_null($item['food_deleted_at']);
        }));
        $total_price = 0;

        
        foreach ($basket_items as $item) {
            $total_price += $item['food_price'] * $item['quantity'];
        }

        if(isset($_POST["coupon"])){
            $coupon = getCouponByName($_POST["coupon"], $basket_items[0]["restaurant_id"]);
    
            if($coupon){
                $total_price = $total_price * $coupon["discount"] / 100;
            }
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