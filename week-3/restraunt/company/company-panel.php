<?php
include "../db.php";
session_start();

if(isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
    $company_id = $_SESSION["company_id"];
    $restaurants = getAllRestaurantsForCompany($company_id);
    
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
        
        <form  action="./add-restaurant.php" method="GET">
            <input type="hidden" name="company_id" value="$company_id">
            <button type="submit" class="btn btn-primary">Add a New Restaurant</button>
        </form>
    HTML;

    foreach($restaurants as $restaurant){
        echo "<div class='border p-3 mb-3'>";
        echo "<img src='". htmlspecialchars($restaurant["image_path"], ENT_QUOTES, "UTF-8") ."' alt='Logo' style='width:30px;height:30px'";
        echo "<span>" . htmlspecialchars($restaurant["name"], ENT_QUOTES, "UTF-8") . "</span>";
        echo '<form method="GET" action="./view-restaurant.php" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $restaurant["id"] .'">';
        echo '<button type="submit" name="view-restaurant" style="display:inline;" class="btn btn-secondary">View</button>';
        echo "</form>";
        echo '<form method="GET" action="./edit-restaurant.php" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $restaurant["id"] .'">';
        echo '<button type="submit" name="edit-restaurant" style="display:inline;" class="btn btn-success">Edit</button>';
        echo "</form>";
        echo '<form method="POST" action="./delete-restaurant.php" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $restaurant["id"] .'">';
        echo '<button type="submit" name="delete-restaurant" style="display:inline;" class="btn btn-danger">Delete</button>';
        echo "</form>";
        echo "</div>";
    }

    echo <<<HTML
    </body>
    </html>
    HTML;
}else{
    echo <<<HTML
    <html>
    <h1> 401 Forbidden </h1>
    <h2> This page is only for company users </h2>
    <form action="../login.php" method="GET">
        <button type="submit">Return Back</button>
    </form>
    </html>
    HTML;  
}

?>