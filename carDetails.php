<?php
session_start();
include 'protected/db.php.inc';

if (isset($_SESSION['ManagerID'])) {
    $isManager = true;
}

$carID = $_GET['ref'] ?? null;
$carDetails = null;
$carImages = [];

if ($carID) {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE carID = ?");
        $stmt->execute([$carID]);
        $carDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT ImageFilename FROM carimages WHERE CarID = ?");
        $stmt->execute([$carDetails['CarID']]);
        $carImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
} else {
    echo "Car reference number is required.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Details</title>
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
                <div class="car-details-container">
                    <div class="car-images">
                        <?php foreach ($carImages as $image): ?>
                            <img src="carsImages/<?php echo $image ?>" alt="Car Image">
                        <?php endforeach; ?>
                    </div>
                    <div class="car-info">
                        <ul>
                            <li>Reference Number: <?php echo $carDetails['ReferenceNumber'] ?></li>
                            <li>Model: <?php echo $carDetails['Model'] ?></li>
                            <li>Type: <?php echo $carDetails['Type'] ?></li>
                            <li>Make: <?php echo $carDetails['Make'] ?></li>
                            <li>Year: <?php echo $carDetails['RegistrationYear'] ?></li>
                            <li>Color: <?php echo $carDetails['Color'] ?></li>
                            <li>Description: <?php echo $carDetails['Description'] ?></li>
                            <li>Price per Day: <?php echo $carDetails['PricePerDay'] ?></li>
                            <li>Capacity (People): <?php echo $carDetails['CapacityPeople'] ?></li>
                            <li>Capacity (Suitcases): <?php echo $carDetails['CapacitySuitcases'] ?></li>
                            <li>Fuel Type: <?php echo $carDetails['FuelType'] ?></li>
                            <li>Average Consumption: <?php echo $carDetails['AvgConsumption'] ?></li>
                            <li>Horsepower: <?php echo $carDetails['Horsepower'] ?></li>
                            <li>Length: <?php echo $carDetails['Length'] ?></li>
                            <li>Width: <?php echo $carDetails['Width'] ?></li>
                            <li>Gear: <?php echo $carDetails['GearType'] ?></li>
                            <li>Conditions: <?php echo $carDetails['Conditions'] ?></li>
                            <li>Restrictions: <?php echo $carDetails['Restrictions'] ?></li>
                        </ul>
                        <a href="rentCar.php?ref=<?php echo $carDetails['carID'] ?>" class="rent-car-button">Rent-a-Car</a>
                    </div>
                    <div class="marketing-info">
                        <h2>Why rent with us?</h2>
                        <p>Our cars are the best in the market. We offer the best prices and the best customer service.</p>
                    </div>
                </div>
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