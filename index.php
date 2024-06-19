<?php
    include 'protected/db.php.inc';
    try { 
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    } catch (PDOException $e){
        die( $e->getMessage() );
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">Car Rental Service</div>
            <div class="nav-links">
                <a href="#">About Us</a>
                <a href="#">Profile</a>
                <a href="#">Shopping Basket</a>
                <a href="register.php">Login/Register</a>
            </div>
        </header>
        <div class="main-container">
            <nav class="nav">
                <a href="#">Search a Car</a>
                <a href="#">View Rents</a>
            </nav>
            <main class="main">
                <h1>Main working area</h1>
                <p>Welcome to our car rental service.</p>
            </main>
        </div>
        <footer class="footer">
            <div>Small Logo</div>
            <div class="contact-info">
                Address: 123 Main St<br>
                Email: contact@carrental.com<br>
                Phone: (123) 456-7890<br>
                <a href="#">Contact Us</a>
            </div>
        </footer>
    </div>
</body>
</html>
