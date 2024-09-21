<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $restaurants = getAllRestaurants();
        $restaurant_ids = [];
        $restaurant_ids = array_column($restaurants, 'id');

        if(count($restaurants) > 0){
            $name = $_POST["name"];
            $discount = $_POST["discount"];
            $restaurant_id = $_POST["rest_id"];
            
            if(in_array($restaurant_id, $restaurant_ids)){
                addNewCoupon($name, $discount, $restaurant_id);

                header("Location: ./manage-coupon.php");
                exit();
            }
            else{
                header("Location: ./add-coupon.php?mess=no_restaurant_id_err");
                exit();
            }    
        }
        else{
            header("Location: ./add-coupon.php?mess=no_restaurant_err");
            exit();
        }
    }
    elseif($_SERVER["REQUEST_METHOD"] == "GET"){
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <title>index.php</title>
        </head>
        <body>
            <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
            <div>
                <form action="./manage-coupon.php" method="GET">
                        <button type="submit" class="btn btn-primary mb-3">Go Back</button>
                </form>
        HTML;

        if(isset($_GET["mess"])){
            if($_GET["mess"] == "no_restaurant_err"){
                echo "<span style='color:red;'> No Restaurant exist in the app, So add a restaurant if you want to add a coupon </span>";
            }
            elseif($_GET["mess"] == "no_restaurant_id_err"){
                echo "<span style='color:red;'> No Restaurant with the provided id, please enter a valid restaurant id </span>";
            }
        }

        echo <<<HTML
                <form action="" method="POST" autocomplete="off">
                    <label for="name">Coupon Name: (This name will be used as coupon code!)</label>
                    <input type="text" name="name" placeholder="Name"><br><br>

                    <label for="desc">Coupon Discount Percentage: (From 1 to 100)</label>
                    <input type="text" name="discount" placeholder="Discount"><br><br>

                    <label for="logo">Restaurant Id:</label>
                    <input type="text" name="rest_id" placeholder="Restaurant Id"><br><br>

                    <button type="submit" class="btn btn-success mt-3">Save New Coupon</button>
                </form>
            </div>
        HTML;
        $restaurants = getAllRestaurants();
        echo "<br><h1>Restaurants</h1>";
        foreach($restaurants as $rest){
            $company = getCompanyById($rest["company_id"]);
            
            if(is_null($company["deleted_at"])){
                echo "<span><strong>Restaurant Name:</strong> ".$rest["name"]." | <strong>Restaurant Id = ".$rest["id"]."</strong></span>";
                echo "<br>-------------------------------------------------------------------<br>";
            }
        }

        echo <<<HTML
        </body>
        </html>
        HTML;
        }

}else{
    echo <<<HTML
    <html>
    <h1> 403 Forbidden </h1>
    <h2> This page is only for admin users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>