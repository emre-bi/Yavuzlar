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

foreach ($basket_items as $item) {
    $new_total_price = 0;
    $new_total_price += ($item['food_price'] * (100 - $item["discount"]) / 100) * $item['quantity'];
}

if($basket_items){
    $restaurant_id = $basket_items[0]["restaurant_id"];
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
    $new_new_total_price = -1;
    if(isset($rest_coupon)){
        $new_new_total_price = 0;
        if(isset($new_total_price)){
            $new_new_total_price = $new_total_price * (100 - $rest_coupon["discount"]) / 100;
        }else{
            $new_new_total_price = $total_price * (100 - $rest_coupon["discount"]) / 100;
        }
    }
}

$basket = getBasketByUserId($_SESSION["user_id"]);

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
                            
                            <td><?php if ($new_total_price != -1): ?>
                <?php if ($new_new_total_price != -1): ?>
                    <h4>Total: <s>$<?= number_format($total_price, 2) ?></s> => <s>$<?= number_format($new_total_price, 2) ?></s> => $<?= number_format($new_new_total_price, 2) ?></h4>
                <?php else: ?>
                    <h4>Total: <s>$<?= number_format($total_price, 2) ?></s> => $<?= number_format($new_total_price, 2) ?></h4>
                <?php endif; ?>
            <?php else: ?>
                <h4>Total: $<?= number_format($total_price, 2) ?></h4>
            <?php endif; ?></td>

                            

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
                <?php if ($new_new_total_price != -1): ?>
                    <h4>Total: <s>$<?= number_format($total_price, 2) ?></s> => <s>$<?= number_format($new_total_price, 2) ?></s> => $<?= number_format($new_new_total_price, 2) ?></h4>
                <?php else: ?>
                    <h4>Total: <s>$<?= number_format($total_price, 2) ?></s> => $<?= number_format($new_total_price, 2) ?></h4>
                <?php endif; ?>
            <?php else: ?>
                <h4>Total: $<?= number_format($total_price, 2) ?></h4>
            <?php endif; ?>
        
        <?php else: ?>
            <p>Your basket is empty.</p>
        <?php endif; ?>
        <?php if (isset($item)): ?>
        <form action="./checkout.php" method="POST" autocomplete="off">
            <label for="note">Add a Note:</label>
            <input type="textarea" style='width:20rem; height:2.5rem' name="note" value="<?= $basket["note"] ?>"><br><br>
            
            <button class="btn btn-success">Checkout</button>
        </form>
        <?php endif; ?>
        <?php if (isset($item)): ?>
        <form action="./discover-foods.php" class="mt-3 mb-3" method="GET">
        <?php else: ?>
        <form action="./view-restaurants.php" class="mt-3 mb-3" method="GET">
        <?php endif; ?>
            <input type="hidden" name="restaurant_id" value="<?= $item['restaurant_id'] ?>">
            <button class="btn btn-secondary">Continue To Discover Foods</button>
        </form>
    </div>
</body>
</html>