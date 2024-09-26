<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    $coupons = getAllCoupons();

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
        <form action="./admin-panel.php" method="GET">
                        <button type="submit" class="btn btn-secondary mb-3">Go Back</button>
        </form>
        <form  action="./add-coupon.php" method="GET">
            <button type="submit" class="btn btn-primary">Add a New Coupon</button>
        </form>
    HTML;


    foreach($coupons as $coupon){
        echo "<div class='coupon-container border p-3 mb-3'>";
        echo "<span>Restaurant Id = <strong>".$coupon["restaurant_id"]."</strong></span>";
        echo "<span> | Coupon Name(Code) => <strong>" . htmlspecialchars($coupon["name"], ENT_QUOTES, "UTF-8") . "</strong></span>";
        echo "<span> | Discount Percentage = <strong>%" . $coupon["discount"] . "</strong></span>";
        echo '<form method="GET" action="./edit-coupon.php" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $coupon["id"] .'">';
        echo '<button type="submit" name="editCoupon" style="display:inline;" class="btn btn-success">Edit</button>';
        echo "</form>";
        echo '<form method="POST" action="./delete-coupon.php" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $coupon["id"] .'">';
        echo '<button type="submit" name="deleteCoupon" style="display:inline;" class="btn btn-danger">Delete</button>';
        echo "</form>";
        echo "</div>";
    }
    
    echo <<<HTML
    </body>
    </html>
    HTML;
}else{
    header("Location: ./admin-panel.php");
    exit();
}
?>