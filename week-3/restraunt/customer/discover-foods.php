<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
    if($max_price == 0){
        $max_price = null;
    }

    $restaurant_id = $_GET["restaurant_id"];

    $foods = getAllFoodsForRestaurant($min_price, $max_price, $restaurant_id);

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
        <form action="./view-restaurants.php" class="mb-3">
            <button type="submit" class="btn btn-secondary">Go Back</button>
        </form>
        <form action="./basket.php">
            <button type="submit" class="btn btn-success">Go To Your Basket</button>
        </form>
        <form method="GET" action="">
            <label for="min_price">Min Price:</label>
            <input type="number" name="min_price" id="min_price" step="0.01" placeholder="0.00">

            <label for="max_price">Max Price:</label>
            <input type="number" name="max_price" id="max_price" step="0.01" placeholder="100.00">

            <button type="submit">Filter</button>
        </form>
        <div class="search-container">
            <input type="text" id="search-bar" class="search-bar" placeholder="Search Food...">
        </div>
    HTML;

    echo '<div class="d-flex flex-wrap justify-content-start">';
    foreach($foods as $food) {
        if($food["deleted_at"] == null){
            echo "<div class='customer-container card m-2' style='width: 15rem;'>";
            echo "<img src='" . htmlspecialchars($food["image_path"], ENT_QUOTES, "UTF-8") . "' alt='Logo' style='width:%100;height:75px'>";
            echo '<div class="card-body">';
            echo '<h5 class="card-title"><strong>'. htmlspecialchars($food["name"], ENT_QUOTES, "UTF-8") .'</h5>';
            if($food["discount"] > 0){
                $new_price = $food["price"] * (100 - $food["discount"]) / 100;
                if(isset($rest_coupon)){
                    $new_new_price = $new_price * (100 - $rest_coupon["discount"]) /100;
                    echo '<h4 class="card-subtitle mb-2 text-body-secondary"><strong>Price:</strong><s> $'. $food["price"] .'</s> => <s>$'.$new_price.'</s> => $'.$new_new_price.'</h4>';
                }else{
                    echo '<h4 class="card-subtitle mb-2 text-body-secondary"><strong>Price:</strong><s> $'. $food["price"] .'</s> => $'.$new_price.'</h4>';
                }
            }else{
                echo '<h6 class="card-subtitle mb-2 text-body-secondary"><strong>Price:</strong> $'. $food["price"] .'</h6>';
            }
            echo '<p class="card-text"><strong>Food Description:</strong> '. htmlspecialchars($food['description'], ENT_QUOTES, "UTF-8") .'</p>';
            echo '<form method="POST" action="./add-to-basket.php" style="display:inline;" autocomplete="off">';
            echo '<input type="hidden" name="id" value="' . $food["id"] .'">';
            echo "<p class='mb-0'><strong>Add a Note:</strong></p>";
            echo "<input type='textarea' name='note' placeholder='no onion, ...'>";
            echo '<button type="submit" name="add_to_basket" style="display:inline;" class="btn btn-secondary mt-3">Add To Basket</button>';
            echo "</form>";
            echo '</div>';
            echo '</div>';
        }
    }
    echo '</div>';

    echo <<<HTML
    <script>
        function search() {
            document.getElementById('search-bar').addEventListener('input', function() {
            let query = this.value.toLowerCase();
            let customerItems = document.querySelectorAll('.customer-container');

            customerItems.forEach(function(item) {
                let customer = item.querySelector('h5').textContent.toLowerCase();
        
                if (customer.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            });
        }
    search();
    </script>
    </body>
    </html>
    HTML;
}else{
    echo <<<HTML
    <html>
    <h1> 401 Forbidden </h1>
    <h2> This page is only for customer users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>