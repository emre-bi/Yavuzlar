<?php
session_start();

include "../db.php";

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    $order_id = $_POST["id"];
    $rest_id = $_POST["rest_id"];

    if(isset($_POST["being_prepared"])){
        setOrderStatus($order_id, "being_prepared");
    }elseif(isset($_POST["on_the_way"])){
        setOrderStatus($order_id, "on_the_way");
    }elseif(isset($_POST["delivered"])){
        setOrderStatus($order_id, "delivered");
    }

    header("Location: ./manage-orders.php?id=".$rest_id);
    exit();

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