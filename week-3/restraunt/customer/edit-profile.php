<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "customer"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["edit-info"])){
            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $id = $_POST["id"];
            
            editUserById($id, $name, $surname);

            header("Location: ./customer-panel.php?mess=Edit Process Successfully Completed!");
            exit();
        }
        elseif(isset($_POST["edit-cred"])){
            $username = $_SESSION["username"];
            $new_username = $_POST["username"];
            $old_password = $_POST["old-password"];
            $new_password = $_POST["new-password"];
            $id = $_POST["id"];
            
            $user = getUser($username);
    
            if(password_verify($old_password, $user["password"])){
                editUserCredById($id, $new_username, $new_password);
                header("Location: ./customer-panel.php?mess=Edit Process Successfully Completed!");
                exit();
            }
            
            header("Location: ./customer-panel.php?mess=Edit Process Failed!");
            exit();
        }
    }
    elseif($_SERVER["REQUEST_METHOD"] == "GET"){
        $username = $_SESSION["username"];
        
        $user = getUser($username);

        $name = $user["name"];
        $surname = $user["surname"];
        $id = $user["id"];

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <title>index.php</title>
        </head>
        <body>
            <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
            <div>
                <form action="./customer-panel.php" method="GET">
                        <button type="submit" class="btn btn-primary mb-3">Go Back</button>
                </form>
                <h1>Edit User Information</h1>
                <form action="" method="POST" autocomplete="off">
                    <input type="hidden" name="id" value="$id">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="$name"><br><br>

                    <label for="surname">Surname:</label>
                    <input type="text" name="surname" value="$surname"><br><br>

                    <button type="submit" name="edit-info" class="btn btn-success mt-3">Save</button>
                </form>
                <br>
                <h1>Edit User Credentials</h1>
                <form action="" method="POST" autocomplete="off">
                    <input type="hidden" name="id" value="$id">

                    <label for="username">Username:</label>
                    <input type="text" name="username" value="$username"><br><br>

                    <label for="password">Old Password:</label>
                    <input type="text" name="old-password" value=""><br><br>

                    <label for="password">New Password:</label>
                    <input type="text" name="new-password" value=""><br><br>

                    <button type="submit" name="edit-cred" class="btn btn-success mt-3">Save</button>
                </form>
            </div>
        </body>
        </html>
        HTML;
        }

}else{
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