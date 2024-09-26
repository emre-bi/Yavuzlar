<?php
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if(isset($_GET["mess"])){
        echo "<h3>". htmlspecialchars($_GET["mess"], ENT_QUOTES, "UTF-8") ."</h3>";
    }
    echo <<<HTML
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Basket</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <head>
            <body class="">
                <form action="./edit-profile.php" method="GET">
                    <button type="submit" class="btn btn-primary">Edit Profile</button>
                </form>
                <form action="./my-orders.php" method="GET">
                    <button type="submit" class="btn btn-primary">My Orders</button>
                </form>
                <form action="./manage-money.php" method="GET">
                    <button type="submit" class="btn btn-primary">Manage Money</button>
                </form>
                <form action="./view-restaurants.php" method="GET">
                    <button type="submit" class="btn btn-primary">Discover Foods</button>
                </form>
                <form action="./basket.php" method="GET">
                    <button type="submit" class="btn btn-primary">My Basket</button>
                </form>
            </body>
    </html>
    HTML;
}else{
    echo <<<HTML
    <html>
    <h1> 403 Forbidden </h1>
    <h2> This page is only for customer users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>