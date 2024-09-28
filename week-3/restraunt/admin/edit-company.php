<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $logo = $_POST["logo"];
        
        editCompanyById($id, $name, $description, $logo);

        header("Location: ./manage-company.php");
        exit();
    }
    elseif($_SERVER["REQUEST_METHOD"] == "GET"){
        $id = $_GET["id"];
        $company = getCompanyById($id);
        $name = $company["name"];
        $description = $company["description"];
        $logo = $company["logo_path"];
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
                <form action="./manage-company.php" method="GET">
                        <button type="submit" class="btn btn-primary mb-3">Go Back</button>
                </form>

                <form action="" method="POST" autocomplete="off">
                    <input type="hidden" name="id" value=$id>
                    <label for="name">Company Name:</label>
                    <input type="text" name="name" value="$name"><br><br>

                    <label for="desc">Company Description:</label>
                    <input type="text" name="description" value="$description"><br><br>

                    <label for="logo">Company Logo Path:</label>
                    <input type="text" name="logo" value="$logo"><br><br>

                    <button type="submit" class="btn btn-success mt-3">Save</button>
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
    <h2> This page is only for admin users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>
