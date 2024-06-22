<?php
session_start();
if (!isset($_SESSION['ManagerID'])) {
    header('Location: index.php');
    exit();
}
include 'protected/db.php.inc';
$isManager = true;
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $propertyNumber = $_POST["propertyNumber"];
    $streetName = $_POST["streetName"];
    $city = $_POST["city"];
    $postalCode = $_POST["postalCode"];
    $country = $_POST["country"];
    $address = $propertyNumber . " " . $streetName . ", " . $city . ", " . $country;
    $telephone = $_POST["telephone"];

    $stmt = $pdo->prepare("INSERT INTO locations (Name, Address, Telephone) 
                            VALUES (?, ?, ?)");
    $stmt->execute([$name, $address, $telephone]);
    $locationID = $pdo->lastInsertId();

    echo "<div class='confirmation-message'>Location has been successfully added to the database. Location ID: <a href='locationDetails.php?ref=" . $locationID . "'>" . $locationID . "</a></div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Location</title>
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
                <h1>Add a Location</h1>
                <form method="post">
                    <label for="name">Location Name:</label> <br>
                    <input type="text" id="name" name="name" required> <br>
                    <label for="propertyNumber">Property Number:</label> <br>
                    <input type="text" id="propertyNumber" name="propertyNumber" required> <br>
                    <label for="streetName">Street Name:</label> <br>
                    <input type="text" id="streetName" name="streetName" required> <br>
                    <label for="city">City:</label> <br>
                    <input type="text" id="city" name="city" required> <br>
                    <label for="postalCode">Postal Code:</label> <br>
                    <input type="text" id="postalCode" name="postalCode" required> <br>
                    <label for="country">Country:</label> <br>
                    <input type="text" id="country" name="country" required> <br>
                    <label for="telephone">Telephone Number:</label> <br>
                    <input type="text" id="telephone" name="telephone" required> <br>
                    <input type="submit" value="Add Location">
                </form>
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