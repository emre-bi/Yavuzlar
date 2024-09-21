<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
    $company_id = $_GET["id"];

    $foods = getFoodsForCompany($company_id);

        echo <<<HTML
        <html>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <title></title>
        </head>
        <body>
            <img src="https://docs.yavuzlar.org/~gitbook/image?url=https%3A%2F%2F10693534-files.gitbook.io%2F%7E%2Ffiles%2Fv0%2Fb%2Fgitbook-x-prod.appspot.com%2Fo%2Fspaces%252FpHJ8OuTO6xpfwqkn7vmg%252Fuploads%252FNmiPz5vqo93pdwm3FjZC%252Fyavuzlar-yatay-logo-text-border.png%3Falt%3Dmedia%26token%3Dba52fcbc-2c9f-4f22-9b67-d3ff56918fb1&width=768&dpr=1&quality=100&sign=8fbd23e7&sv=1">
        <br>   
        <br>
        HTML;


        foreach($foods as $food){
            if($food["deleted_at"] == null){
                echo "<div class='customer-container border p-3 mb-3'>";
                echo "<img src='" . htmlspecialchars($food["image_path"], ENT_QUOTES, "UTF-8") . "' alt='Logo' style='width:30px;height:30px'>";
                echo "<span>" . htmlspecialchars($food["name"], ENT_QUOTES, "UTF-8") . "</span>";
                echo "</div>";
            }
        }
        echo <<<HTML
        
        </body>
        </html>
        HTML;
}
else{
    header("Location: ./admin-panel");
    exit();
}
?>