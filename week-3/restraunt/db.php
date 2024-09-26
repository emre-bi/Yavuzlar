<?php

$host = 'mysql';
$dbname = 'restaurant';
$dbusername = 'root';
$dbpassword = 'root';

function updateBasketNote($newNote, $id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $update_query = "UPDATE basket SET note = :note WHERE user_id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':note', $newNote);
    $stmt->execute();
}

function getBasketByUserId($user_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM basket WHERE user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $basket = $stmt->fetch(PDO::FETCH_ASSOC);

    return $basket;
}

function editCouponById($id, $name, $discount, $restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $update_query = "UPDATE cupon SET name = :name, discount = :discount, restaurant_id = :restaurant_id WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':discount', $discount);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->execute();
}

function getFoodsForCompany($company_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT f.* FROM food f
    JOIN restaurant r
    ON f.restaurant_id = r.id
    JOIN company c
    ON r.company_id = c.id
    WHERE c.id = :company_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $foods;
}

function shareComment($user_id, $restaurant_id, $title, $description,  $score){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO comments (user_id, restaurant_id, title, description, score, created_at)
                        VALUES (:user_id, :restaurant_id, :title, :description, :score, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':score', $score);
    $stmt->execute();
}
function getRestaurantScore($restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT AVG(score) AS avg_score FROM comments WHERE restaurant_id = :rest_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rest_id', $restaurant_id);
    $stmt->execute();
    $score = $stmt->fetch(PDO::FETCH_ASSOC);

    return $score['avg_score'] ?? null;
}
function getUserById($user_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM users WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function commentsForRestaurant($restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM comments WHERE restaurant_id = :rest_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rest_id', $restaurant_id);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $comments;
}

function getCouponById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM cupon WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    return $coupon;
}

function getCouponByRestaurantId($restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM cupon WHERE restaurant_id = :rest";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rest', $restaurant_id);
    $stmt->execute();
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    return $coupon;
}

function deleteUserById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $update_query = "UPDATE users SET deleted_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function createOrder($user_id, $order_status, $total_price, $basket_items){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO `order` (user_id, order_status, total_price, created_at)
                        VALUES (:user_id, :order_status, :total_price, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':order_status', $order_status);
    $stmt->bindParam(':total_price', $total_price);
    $stmt->execute();

    $order_id = $pdo->lastInsertId();
    $sql = "INSERT INTO order_items (food_id, order_id, quantity, price)
                        VALUES (:food_id, :order_id, :quantity, :price)";

    foreach($basket_items as $item){
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':food_id', $item["food_id"]);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':quantity', $item["quantity"]);
        $stmt->bindParam(':price', $item["food_price"]);
        $stmt->execute();
    }
}

function updateBasketQuantity($basket_id, $quantity){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $update_query = "UPDATE basket SET quantity = :quantity WHERE id = :basket_id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':basket_id', $basket_id);
    $stmt->execute();
}

function removeBasketById($basket_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $delete_query = "DELETE FROM basket WHERE id = :basket_id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindParam(':basket_id', $basket_id);
    $stmt->execute();
}


function addItemToBasket($user_id, $food_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM basket WHERE user_id = :user_id AND food_id = :food_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':food_id', $food_id);
    $stmt->execute();
    $basket_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$basket_item) {
        $insert_query = "INSERT INTO basket (user_id, food_id, note, quantity, created_at) VALUES (:user_id, :food_id, '', 1, NOW())";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->bindParam(':user_id', $user_id);
        $insert_stmt->bindParam(':food_id', $food_id);
        $insert_stmt->execute();
    } else {
        $update_query = "UPDATE basket SET quantity = quantity + 1 WHERE user_id = :user_id AND food_id = :food_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':user_id', $user_id);
        $update_stmt->bindParam(':food_id', $food_id);
        $update_stmt->execute();
    }
}

function getBasketItems($user_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "
        SELECT 
            b.id AS basket_id,
            b.quantity,
            f.id AS food_id,
            f.restaurant_id AS restaurant_id,
            f.name AS food_name,
            f.price AS food_price,
            f.deleted_at AS food_deleted_at,
            f.discount
        FROM basket b
        JOIN food f 
            ON b.food_id = f.id
        WHERE b.user_id = :user_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $basket_items;
}

function getAllFoods($min, $max){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM food WHERE 1=1";
    if ($min !== null) {
        $sql .= " AND price >= :min_price";
    }
    if ($max !== null) {
        $sql .= " AND price <= :max_price";
    }

    $stmt = $pdo->prepare($sql);
    if ($min !== null) {
        $stmt->bindParam(':min_price', $min, PDO::PARAM_STR);
    }
    if ($max !== null) {
        $stmt->bindParam(':max_price', $max, PDO::PARAM_STR);
    }

    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $foods;
}

function getAllFoodsForRestaurant($min, $max, $restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT f.* FROM food f
    JOIN restaurant r
    ON f.restaurant_id = r.id
    WHERE r.id = :rest_id";
    if ($min !== null) {
        $sql .= " AND price >= :min_price";
    }
    if ($max !== null) {
        $sql .= " AND price <= :max_price";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rest_id', $restaurant_id, PDO::PARAM_STR);
    if ($min !== null) {
        $stmt->bindParam(':min_price', $min, PDO::PARAM_STR);
    }
    if ($max !== null) {
        $stmt->bindParam(':max_price', $max, PDO::PARAM_STR);
    }

    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $foods;
}

function addBalance($user_id, $new_balance){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users
    SET balance = :balance
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':balance', $new_balance);
    $stmt->execute();
}

function getOrdersForCustomer($user_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT
    o.id AS order_id,
    o.total_price AS total_price,
    o.created_at AS order_created_at,
    o.order_status,
    oi.food_id,
    oi.quantity,
    u.id AS user_id,
    f.restaurant_id AS restaurant_id,
    f.id AS food_id,
    f.name AS food_name,
    f.description AS food_description,
    f.price AS food_price
    FROM `order` o
    JOIN order_items oi 
        ON o.id = oi.order_id
    JOIN food f
        ON oi.food_id = f.id
    JOIN users u
        ON u.id = o.user_id
    WHERE 
        u.id = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $orders;
}


function editUserCredById($id, $new_username, $new_password){
    global $host, $dbname, $dbusername, $dbpassword;
    $hashedPassword = password_hash($new_password, PASSWORD_ARGON2ID);
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users
    SET username = :username, password = :password
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':username', $new_username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
}

function editUserById($id, $name, $surname){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users
    SET name = :name, surname = :surname
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->execute();
}

function setOrderStatus($order_id, $status){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE `order`
    SET order_status = :order_status
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $order_id);
    $stmt->bindParam(':order_status', $status);
    $stmt->execute();
}

function getOrdersForRestaurant($restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT
    o.id AS order_id,
    o.total_price AS total_price,
    o.created_at AS order_created_at,
    o.order_status,
    oi.food_id,
    oi.quantity,
    u.id AS user_id,
    u.name AS user_name,
    f.name AS food_name,
    f.description AS food_description,
    f.price AS food_price
    FROM `order` o
    JOIN order_items oi 
        ON o.id = oi.order_id
    JOIN food f
        ON oi.food_id = f.id
    JOIN users u
        ON u.id = o.user_id
    WHERE 
        f.restaurant_id = :id
";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $restaurant_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $orders;
}

function editFoodById($name, $description, $logo, $food_id, $price, $discount){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE food
    SET name = :name, description = :description, image_path = :image_path, price = :price, discount = :discount
    WHERE id = :id;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_path', $logo);
    $stmt->bindParam(':id', $food_id);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':discount', $discount);
    $stmt->execute();
}

function getFoodById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM food WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    return $food;
}

function deleteFoodById($food_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE food SET deleted_at = NOW() WHERE id = :food_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':food_id', $food_id);
    $stmt->execute();
}

function getRestaurantIdForFood($food_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT r.id FROM restaurant r
    JOIN food f
    ON f.restaurant_id = r.id
    WHERE f.id = :food_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':food_id', $food_id);
    $stmt->execute();
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    return $restaurant["id"];
}

function addFoodToRestaurant($name, $description, $logo, $restaurantId, $price, $discount){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO food (restaurant_id, name, description, image_path, created_at, price, discount)
    VALUES(:restaurant_id, :name, :description, :image_path, NOW(), :price, :discount)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':restaurant_id', $restaurantId);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_path', $logo);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':discount', $discount);
    $stmt->execute();
}

function getFoodsForRestaurant($restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT f.* FROM food f
    JOIN restaurant r
    ON f.restaurant_id = r.id
    WHERE r.id = :rest_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rest_id', $restaurant_id);
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $foods;
}

function editRestaurantById($name, $description, $logo, $restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE restaurant
    SET name = :name, description = :description, image_path = :image_path
    WHERE id = :id;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_path', $logo);
    $stmt->bindParam(':id', $restaurant_id);
    $stmt->execute();
}

function getRestaurantById($restaurantId){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM restaurant WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $restaurantId);
    $stmt->execute();
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    return $restaurant;
}

function deleteRestaurantById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM restaurant WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function addNewRestaurant($name, $description, $logo, $company_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO restaurant (company_id, name, description, image_path, created_at)
    VALUES(:company_id, :name, :description, :image_path, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_path', $logo);
    $stmt->execute();
}

function addCompanyUser($username, $password, $name){
    $company = getCompanyByName($username);
    global $host, $dbname, $dbusername, $dbpassword;
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
    $role = "company";
    $company_id = $company["id"];
    $surname = "";
    $balance = 5000;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO users (company_id, role, name, surname, username, password, balance, created_at)
    VALUES(:company_id, :role, :name, :surname, :username, :password, :balance, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':balance', $balance);
    $stmt->execute();
}

function getCompanyByName($name){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM company WHERE name = :name";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    return $company;
}

function addNewCompany($name, $description, $logo){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO company(name, description, logo_path)
                        VALUES(:name, :description, :logo)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':logo', $logo);
    $stmt->execute();
}

function getAllRestaurantsForCompany($company_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT r.* FROM restaurant r
            JOIN company c
            on r.company_id = c.id
            WHERE c.id = :company_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->execute();
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $restaurants;
}

function addNewCoupon($name, $discount, $restaurant_id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO cupon(name, discount, restaurant_id, created_at)                        
    VALUES(:name, :disc, :rest_id, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':disc', $discount);
    $stmt->bindParam(':rest_id', $restaurant_id);
    $stmt->execute();
}

function deleteCouponById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM cupon WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function getAllRestaurants(){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM restaurant";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $restaurant = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $restaurant;
}


function getAllCoupons(){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM cupon";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $coupons;
}

function deleteCompanyById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE company SET deleted_at = NOW() WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function getCompanyById($id){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM company WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    return $company;
}

function editCompanyById($id, $name, $description, $logo){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE company
    SET name = :name, description = :description, logo_path = :logo_path
    WHERE id = :id;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':logo_path', $logo);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function getAllCustomers(){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM users";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $customers;
}

function getAllCompanies(){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM company";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $companies;
}

function getUser($username){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function addCustomer($username, $password, $name, $surname){
    global $host, $dbname, $dbusername, $dbpassword;
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
    $role = "customer";
    $balance = 5000;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO users (role, name, surname, username, password, balance, created_at)
    VALUES(:role, :name, :surname, :username, :password, :balance, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':balance', $balance);
    $stmt->execute();
}

function addAdminUser($username, $password, $name, $surname){
    global $host, $dbname, $dbusername, $dbpassword;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM users WHERE username='admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){}
    else{
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
    $role = "admin";
    $balance = 5000;

    $sql = "INSERT INTO users (role, name, surname, username, password, balance, created_at)
    VALUES(:role, :name, :surname, :username, :password, :balance, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':balance', $balance);
    $stmt->execute();
    }
}
?>