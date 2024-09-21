<?php
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    echo <<<HTML
    <form action="./manage-customers.php" method="GET">
        <button type="submit">Manage Customers</button>
    </form>
    <form action="./manage-company.php" method="GET">
        <button type="submit">Manage Company</button>
    </form>
    <form action="./manage-coupon.php" method="GET">
        <button type="submit">Manage Coupons</button>
    </form>
    HTML;
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