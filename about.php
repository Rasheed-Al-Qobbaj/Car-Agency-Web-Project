<?php

include 'protected/db.php.inc';

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

session_start();

if (isset($_SESSION['ManagerID'])) {
    $isManager = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <a href="index.php"> 
                    LuxaCar
                    <img src="protected/logo.png" alt="Logo">
                </a>
            </div>
            <div class="nav-links">
                <a href="about.php">About Us</a>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <a href="profile.php"><?php echo $_SESSION['username']; ?></a>
                    <?php if (!isset($isManager)): ?>
                    <a href="viewRent.php">Shopping Basket</a>
                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </header>
        <div class="main-container">
            <nav class="nav">
                <?php if (isset($_SESSION['isManager'])): ?>
                    <a href="addCar.php">Add Car</a>
                    <a href="addLocation.php">Add Location</a>
                    <a href="returnCarMan.php">Return Car</a>
                    <a href="inquireCar.php">Inquire about Cars</a>
                <?php elseif (isset($_SESSION['username'])): ?>
                    <a href="returnCar.php">Return car</a>
                    <a href="viewRent.php">View Rented Cars</a>
                <?php endif; ?>
                <a href="searchCar.php">Search a Car</a>
            </nav>
            <main class="main">
                <h1>About Us</h1>
                <p>Welcome to LuxaRent</p>
                <p>At LuxaRent, we believe that every journey should be a luxurious experience. Founded with the vision of redefining car rentals, LuxaRent offers a fleet of high-end vehicles tailored to meet the needs of discerning customers. Whether you need a car for a special occasion, business travel, or simply to indulge in a luxurious driving experience, LuxaRent is here to make it happen.</p>
                <h2>Our Mission</h2>
                <p>To provide exceptional car rental services that blend luxury, comfort, and convenience, ensuring our customers enjoy an unparalleled driving experience.</p>
                <h2>Our Vision</h2>
                <p>To be the leading luxury car rental service globally, recognized for our commitment to quality, customer satisfaction, and innovative solutions.</p>
                <h2>Our Values</h2>
                <ul>
                    <li>Excellence: We strive for perfection in every aspect of our service.</li>
                    <li>Integrity: We operate with transparency and uphold the highest ethical standards.</li>
                    <li>Customer-Centricity: Our customers are at the heart of everything we do.</li>
                    <li>Innovation: We continuously seek to innovate and improve our services.</li>
                </ul>
                <h2>Why Choose LuxaRent?</h2>
                <ul>
                    <li>Premium Fleet: Choose from a wide range of luxurious cars from top brands like BMW, Mercedes-Benz, Audi, and more.</li>
                    <li>Unmatched Service: Our team is dedicated to providing you with personalized and professional service.</li>
                    <li>Convenience: Easy booking process, flexible rental periods, and convenient pick-up and drop-off locations.</li>
                    <li>Exclusive Benefits: Enjoy special offers, loyalty rewards, and tailored packages to suit your needs.</li>
                </ul>
                <h2>Our Fleet</h2>
                <p>LuxaRent offers a diverse selection of luxury vehicles, including:</p>
                <ul>
                    <li>Sedans: Perfect for business travel and city driving.</li>
                    <li>SUVs: Ideal for family trips and off-road adventures.</li>
                    <li>Sports Cars: For those who seek thrill and performance.</li>
                    <li>Electric Vehicles: Combining luxury with sustainability.</li>
                </ul>
                <h2>Customer Testimonials</h2>
                <p><em>"Renting from LuxaRent was a seamless and luxurious experience. The car was immaculate, and the service was top-notch."</em> - <strong>Iyad J.</strong></p>
                <p><em>"I highly recommend LuxaRent for anyone looking to travel in style. Their fleet is outstanding, and the customer service is exceptional."</em> - <strong>Yousef H.</strong></p>
                <h2>Join the LuxaRent Experience</h2>
                <p>Experience the ultimate in luxury and comfort with LuxaRent. Book your car today and embark on a journey like no other.</p>
            </main>
            </div>
        <footer class="footer">
            <div>
                <img src="protected/logo.png" alt="Small Logo">
            </div>
            <div class="contact-info">
                Address: Alwakalt, Ramallah, Palestine<br>
                Email: contact@luxacar.com<br>
                Phone: +970599767544<br>
                <a href="contact.php">Contact Us</a>
            </div>
        </footer>
    </div>
</body>
</html>