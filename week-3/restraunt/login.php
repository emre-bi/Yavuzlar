<?php
include './db.php';
session_start();
$username = "admin";
$password = "admin";
$name = "admin";
$surname = "";
addAdminUser($username, $password, $name, $surname);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = getUser($username);
    
    if($user && password_verify($password, $user["password"])){
        $_SESSION["company_id"] = $user["company_id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["user_id"] = $user["id"];
        
        header("Location: ./login.php");
        exit();
    }
    else{
        header("Location: ./login.php?mess=Wrong username or password");
        exit();
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_SESSION["role"])){
        if($_SESSION["role"] == "customer"){
            header("Location: ./customer/customer-panel.php");
            exit();
        }elseif($_SESSION["role"] == "company"){
            header("Location: ./company/company-panel.php");
            exit();
        }elseif($_SESSION["role"] == "admin"){
            header("Location: ./admin/admin-panel.php");
            exit();
        }
    }
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>index.php</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
        <div>
    HTML;


    if(isset($_GET["mess"])){
        echo "<span style='background-color:red; color:white;'>". htmlspecialchars($_GET["mess"], ENT_QUOTES, 'UTF-8') ."</span>";
    }

    echo <<<HTML
            <form action="./signup.php" method="GET">
                <button type="submit" class="btn btn-secondary mb-3">Go To Signup Page</button>
            </form>
            <form action="./login.php" method="POST" autocomplete="off">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="username"><br><br>

                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="password"><br><br>

                <button type="submit" class="btn btn-success">Login</button>
            </form>
        </div>
    </body>
    </html>
    HTML;
    
}
?>