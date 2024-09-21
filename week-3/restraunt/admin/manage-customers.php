<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    $customers = getAllCustomers();

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
        <br><br>
        <div class="mb-2">
            <input type="text" id="search-bar" class="search-bar" style="width:400px" placeholder="Search customers...">
        </div>
        
        <button id="show-active-users" class="btn btn-primary btn-sm">Search From Active Users</button>
        <button id="show-deleted-users" class="btn btn-secondary btn-sm">Search From Deleted Users</button>
        
    HTML;
    foreach($customers as $customer){
            $orders = getOrdersForCustomer($customer["id"]);
            $deleted_at = $customer["deleted_at"] ? $customer["deleted_at"] : "null";

            $active_orders = [];

            foreach($orders as $order){
                $order_id = $order["order_id"];
                if(!isset($active_orders[$order_id])){
                    $active_orders[$order_id] = [
                        'order_id' => $order_id,
                        'order_status' => $order['order_status'],
                        'total_price' => $order['total_price'],
                        'created_at' => $order['order_created_at'],
                        'user' => [
                            'user_id' => $order['user_id']
                        ]
                    ];
                }
            }
            echo "<br><div class='customer-entry' data-deleted-at='" . htmlspecialchars($deleted_at, ENT_QUOTES, "UTF-8") . "'>";
            echo "<h1>" . htmlspecialchars($customer["name"], ENT_QUOTES, "UTF-8") . " " . htmlspecialchars($customer["surname"], ENT_QUOTES, "UTF-8") . "</h1>";
            echo '<form method="POST" action="./delete-customer.php" style="display:inline;">';
            echo '<input type="hidden" name="id" value="' . $customer["id"] .'">';
            echo '<button type="submit" name="deleteCustomer" style="display:inline;" class="btn btn-danger btn-sm">Delete Customer</button>';
            echo "</form>";
            echo "<div class='d-flex flex-wrap justify-content-start'>";
            foreach($active_orders as $order){
                if($order["order_status"] != "delivered"){
                    echo "<div class='card m-2' style='width: 25rem;'>";
                    echo '<div class="card-body">';
                    echo "<h4>Order Status -> ". $order["order_status"] ."</h4>";
                    echo '<h5 class="card-title"><strong>Ordered At:</strong> '. $order["created_at"] .'</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-body-secondary"><strong>Total Price:</strong> $'. $order["total_price"] .'</h6>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo "</div><br>";
            echo "<h3>#############################</h3>";
            echo "</div>";
    }
    echo <<<HTML
    <script>
    let currentFilter = 'active'; 
    function filterUsers() {
        let customerItems = document.querySelectorAll('.customer-entry');

        customerItems.forEach(function(item) {
            let deletedAt = item.getAttribute('data-deleted-at'); 

            if (currentFilter === 'active' && deletedAt === "null") {
                item.style.display = 'block'; 
            } else if (currentFilter === 'deleted' && deletedAt !== "null") {
                item.style.display = 'block'; 
            } else {
                item.style.display = 'none'; 
            }
        });

        search(); 
    }

  
    function search() {
        let query = document.getElementById('search-bar').value.toLowerCase();
        let customerItems = document.querySelectorAll('.customer-entry');

        customerItems.forEach(function(item) {
            let customer = item.querySelector('h1').textContent.toLowerCase();

            if (item.style.display === 'block' && customer.includes(query)) {
                item.style.display = 'block'; 
            } else {
                item.style.display = 'none';
            }
        });
    }

   
    document.getElementById('search-bar').addEventListener('input', function() {
        search(); 
    });

    
    document.getElementById('show-active-users').addEventListener('click', function() {
        currentFilter = 'active'; 
        filterUsers(); 
    });

    document.getElementById('show-deleted-users').addEventListener('click', function() {
        currentFilter = 'deleted'; 
        filterUsers(); 
    });

    </script>
    </body>
    </html>
    HTML;
}else{
    header("Location: ./admin-panel");
    exit();
}

?>