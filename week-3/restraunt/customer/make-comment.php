<?php
include "../db.php";
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $restaurant_id = $_GET["restaurant_id"];
    $orders = getOrdersForCustomer($_SESSION["user_id"]);
    $user_id = $_SESSION["user_id"];


    $past_orders = array_values(array_filter($orders, function($order) {
        return $order['order_status'] == "delivered";
    }));


    $comments = commentsForRestaurant($restaurant_id);
    $user_ids = array_column($comments, 'user_id');

    if(in_array($user_id, $user_ids)){
        header("Location: ./make-comment.php?restaurant_id=".$restaurant_id."&mess=You can Share only one comment for each Restaurant!");
        exit();
    }
    $restaurantIds = [];
    $restaurantIds = array_column($past_orders, 'restaurant_id');

    if(in_array($restaurant_id, $restaurantIds)){
        $score = $_POST["score"];
        if($score > 0 || $score < 11){
            $title = $_POST["title"];
            $description = $_POST["description"];

            shareComment($user_id, $restaurant_id, $title, $description,  $score);
            header("Location: ./make-comment.php?mess=Comment Shared&restaurant_id=".$restaurant_id);
            exit();
        }else{
            header("Location: ./make-comment.php?mess=Score must be between 1 and 10&restaurant_id=".$restaurant_id);
            exit();
        }
    }
    else{
        header("Location: ./make-comment.php?mess=You need to have delivered order from this restraunt to share a comment&restaurant_id=".$restaurant_id);
        exit();
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    $restaurant_id = $_GET["restaurant_id"];
    $comments = commentsForRestaurant($restaurant_id);

    $avg_score = number_format(getRestaurantScore($restaurant_id), 2);
    $restaurant = getRestaurantById($restaurant_id);


    echo <<<HTML
    
    <html>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title></title>
    </head>
    <body>
        <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
        <br><br>
    HTML;
        if(isset($_GET["mess"])){
            echo "<h2 style='color:red'>". htmlspecialchars($_GET["mess"], ENT_QUOTES, "UTF-8") ."</h2>";
        }
    echo <<<HTML
        <br><br>
        <h1>Restaurant Name: {$restaurant["name"]}</h1>
        <h2>Restaurant Score: $avg_score</h2>
        <br><br>
        <form action="" method="POST">
            <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
            <input type="text" name="title" placeholder="Title">
            <input type="text" name="description" placeholder="Description">
            <input type="text" name="score" placeholder="Score: 1-10" pattern=[1-10] title="Should be between 1 and 10">
            <button class="btn btn-success btn-sm">Share Comment</button>
        </form>
    HTML;
    foreach($comments as $comment){
        $user = getUserById($comment["user_id"]);
        echo "<br><div class='customer-entry'>";
        echo "<h2>" . htmlspecialchars($user["name"], ENT_QUOTES, "UTF-8") . " " . htmlspecialchars($user["surname"], ENT_QUOTES, "UTF-8") . "</h1>";
        echo "<h3>Rating Score: ". $comment["score"] ."</h3>";
        echo "<h4>".htmlspecialchars($comment["title"], ENT_QUOTES, "UTF-8")."</h4>";
        echo "<span>".htmlspecialchars($comment["description"], ENT_QUOTES, "UTF-8")."</span>";
        echo "</div><br>";
        echo "<h3>#############################</h3>";
        echo "</div>";
    }
    echo <<<HTML
    </body>
    </html>
    HTML;
}