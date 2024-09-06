<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index.php</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="username"><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="password"><br><br>

            <button type="submit">Login</button>
            
            <span>admin login -> admin:admin</span><br>
            <span>student login -> student:student</span><br>
            <span>second student login -> student2:student2</span>
        </form>
    </div>
</body>
</html>