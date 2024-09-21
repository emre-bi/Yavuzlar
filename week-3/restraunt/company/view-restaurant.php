<?php
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    $restaurant_id = $_GET["id"];

    echo <<<HTML
    <form action="./manage-food.php" method="GET">
        <input type="hidden" name="id" value="$restaurant_id">
        <button type="submit">Manage Food</button>
    </form>
    <form action="./manage-orders.php" method="GET">
        <input type="hidden" name="id" value="$restaurant_id">
        <button type="submit">Manage Orders</button>
    </form>
    HTML;
}else{
    echo <<<HTML
    <html>
    <h1> 403 Forbidden </h1>
    <h2> You can't Access to this page </h2>
    <form action="./company-panel.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>