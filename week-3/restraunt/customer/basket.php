<?php
include "../db.php";
session_start();

$old_basket_items = getBasketItems($_SESSION["user_id"]);

$basket_items = array_values(array_filter($old_basket_items, function($item) {
    return is_null($item['food_deleted_at']);
}));

$total_price = 0;
foreach ($basket_items as $item) {
    $total_price += $item['food_price'] * $item['quantity'];
}
$user = getUser($_SESSION["username"]);
$balance = $user["balance"];
$new_total_price = -1;

if(isset($_GET["coupon"])){
    $restaurant_id = $basket_items[0]["restaurant_id"];
    $coupon = getCouponByName($_GET["coupon"], $restaurant_id);
    
    if($coupon){
        echo "<h3>Coupon is used and %". $coupon["discount"] ." discount is applied</h3>";
        $new_total_price = $total_price * $coupon["discount"] / 100;
    }else{
        echo "<h3>Coupon is not Valid</h3>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form action="./customer-panel.php" class="mb-3">
            <button type="submit" class="btn btn-secondary">Go Back</button>
        </form>
        <?php if(isset($_GET['mess'])){ ?>
            <h1><?= htmlspecialchars($_GET["mess"], ENT_QUOTES, "UTF-8"); ?></h1>
        <?php } ?>
        <h1>Your Balance -> $<?=$balance?></h1>
        <h2>Your Basket</h2>

        <?php if (count($basket_items) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($basket_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['food_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>$<?= number_format($item['food_price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['food_price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <form method="POST" action="update-basket.php">
                                    <input type="hidden" name="basket_id" value="<?= $item['basket_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 50px;">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                                <form method="POST" action="remove-basket.php" style="display:inline;">
                                    <input type="hidden" name="basket_id" value="<?= $item['basket_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($new_total_price != -1): ?>
                <h4>Total: <s>$<?= number_format($total_price, 2) ?></s> => $<?= number_format($new_total_price, 2) ?></h4>
            <?php else: ?>
                <h4>Total: $<?= number_format($total_price, 2) ?></h4>
            <?php endif; ?>
        
        <?php else: ?>
            <p>Your basket is empty.</p>
        <?php endif; ?>
        <form action="./checkout.php" method="POST">
            <?php if($new_total_price != -1): ?>
                <input type="hidden" name="coupon" value="<?= $_GET["coupon"] ?>">
            <?php endif; ?>
            <button class="btn btn-success">Checkout</button>
        </form>
        <form action="./discover-foods.php" class="mt-3 mb-3" method="GET">
            <button class="btn btn-secondary">Continue To Discover Foods</button>
        </form>
        <form action="" autocomplete="off">
            <input type="text" name="coupon" placeholder="Coupon Code">
            <button type="submit" class="btn btn-primary">Apply Coupon</button>
        </form>
    </div>
</body>
</html>