<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = htmlspecialchars($_POST['name']);
        $cardNumber = htmlspecialchars($_POST['card_number']);
        $expiryDate = htmlspecialchars($_POST['expiry_date']);
        $cvv = htmlspecialchars($_POST['cvv']);
        $amount = htmlspecialchars($_POST['amount']);

        $isCardValid = strlen($cardNumber) === 16 && strlen($cvv) === 3;
        if ($isCardValid && $amount > 0) {
            $user = getUser($_SESSION["username"]);
            $new_balance = $amount + $user["balance"];
            addBalance($user["id"], $new_balance);

            header("Location: ./manage-money.php?mess=Payment Successfull");
            exit();
        }
        else{
            header("Location: ./manage-money.php?mess=Payment Failed");
            exit();
        }

    }
    elseif($_SERVER["REQUEST_METHOD"] == "GET"){
        $user = getUser($_SESSION["username"]);
        $balance = $user["balance"];
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <title>payment.php</title>
        </head>
        <body>
            <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
            <form action="./customer-panel.php" method="GET">
                    <button type="submit" class="btn btn-primary mb-3">Go Back</button>
            </form>
        HTML;


        if(isset($_GET["mess"])){
            echo "<span style='background-color:red; color:white;'>". htmlspecialchars($_GET["mess"], ENT_QUOTES, 'UTF-8') ."</span>";
        }
            
        echo <<<HTML
                <span>All Cards that have 16 digit at card number and 3 digit at cvv is accepted</span>
                <h1>Account Balance: $$balance</h1>
                <div>
                    <h2 class="mt-5">Payment Info</h2>
                    <form action="manage-money.php" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="name" class="form-label">Cardholder Name</label>
                            <input type="text" id="name" name="name" placeholder="demo" required>
                        </div>
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
                            <input type="text" id="expiry_date" name="expiry_date" placeholder="00/00" required>
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" id="cvv" name="cvv" pattern="[0-9]{3}" placeholder="000" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="text" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-success">Submit Payment</button>
                    </form>
                </div>
        </body>
        </html>
        HTML;
        }

}
else{
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