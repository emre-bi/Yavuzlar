<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    $restaurant_id = $_GET["id"];
    $orders = getOrdersForRestaurant($restaurant_id);

    $formatted_orders = [];
    $past_orders = [];

    foreach($orders as $order){
        $order_id = $order["order_id"];
        if(!isset($formatted_orders[$order_id])){
            $formatted_orders[$order_id] = [
                'order_id' => $order_id,
                'order_status' => $order['order_status'],
                'total_price' => $order['total_price'],
                'created_at' => $order['order_created_at'],
                'user' => [
                    'user_id' => $order['user_id'],
                    'user_name' => $order['user_name']
                ],
                'foods' => []
            ];
        }
        $formatted_orders[$order_id]['foods'][] = [
            'food_name' => $order['food_name'],
            'food_description' => $order['food_description'],
            'quantity' => $order['quantity'],
            'food_price' => $order['food_price']
        ];
    }
    
    echo <<<HTML
    <html>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>manage-customers.php</title>
    </head>
    <body>
        <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
        <h1>Orders</h1>
    HTML;

    echo '<div class="d-flex flex-wrap justify-content-start">';
    foreach($formatted_orders as $order) {
        if($order["order_status"] != "delivered"){
            echo "<div class='card m-2' style='width: 25rem;'>";
            echo '<div class="card-body">';
            echo "<h4>Order Status -> ". $order["order_status"] ."</h4>";
            echo '<h5 class="card-title"><strong>Customer:</strong> '. htmlspecialchars($order["user"]["user_name"], ENT_QUOTES, "UTF-8") .'</h5>';
            echo '<h6 class="card-subtitle mb-2 text-body-secondary"><strong>Total Price:</strong> $'. $order["total_price"] .'</h6>';
            foreach ($order['foods'] as $food) {
                echo '<p class="card-text">'. htmlspecialchars($food['food_name'], ENT_QUOTES, "UTF-8") .'<strong style="color:red">(x'.$food['quantity'].')</strong> - $' . $food['food_price'] .'</p>';
            }
            echo '<form method="POST" action="./order-status.php" style="display:inline;">';
            echo '<input type="hidden" name="id" value="' . $order["order_id"] .'">';
            echo '<input type="hidden" name="rest_id" value="'.$restaurant_id.'">';
            echo '<button type="submit" name="being_prepared" style="display:inline;" class="btn btn-primary">Being Prepared</button>';
            echo '<button type="submit" name="on_the_way" style="display:inline;" class="btn btn-success m-1">On the Way</button>';
            echo '<button type="submit" name="delivered" style="display:inline;" class="btn btn-danger">Delivered</button>';
            echo "</form>";

            echo '</div>';
            echo '</div>';
        }else{
            $past_orders[$order["order_id"]] = $order;
        }
    }
    echo '</div>';

    echo "<h1>Past Orders</h1>";
    echo '<div class="d-flex flex-wrap justify-content-start">';
    foreach($past_orders as $order){
        echo "<div class='card m-2' style='width: 25rem;'>";
        echo '<div class="card-body">';
        echo "<h4>Order Status -> ". $order["order_status"] ."</h4>";
        echo '<h5 class="card-title"><strong>Ordered At:</strong> '. $order["created_at"] .'</h5>';
        echo '<h6 class="card-subtitle mb-2 text-body-secondary"><strong>Total Price:</strong> $'. $order["total_price"] .'</h6>';
        foreach ($order['foods'] as $food) {
            echo '<p class="card-text">'. htmlspecialchars($food['food_name'], ENT_QUOTES, "UTF-8") .'<strong style="color:red">(x'.$food['quantity'].')</strong> - $' . $food['food_price'] .'</p>';
        }
        echo '</div>';
        echo '</div>';
    }
    echo "</div>";

    echo <<<HTML
    </body>
    </html>
    HTML;
}else{
    echo <<<HTML
    <html>
    <h1> 401 Forbidden </h1>
    <h2> This page is only for company users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>