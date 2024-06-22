<?php
session_start();
include 'protected/db.php.inc';

if (!isset($_SESSION['username']) || !isset($_SESSION['isManager']) || $_SESSION['isManager'] !== true) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

if (isset($_POST['update_car_status'])) {
    $carID = $_POST['car_id'];
    $newStatus = $_POST['car_status'];
    $newLocation = $_POST['pickup_location'];
    $stmt = $pdo->prepare("UPDATE cars SET CarState = ? WHERE CarID = ?");
    $stmt->execute([$newStatus, $carID]);
    header("Location: returnCarMan.php");
    exit();
}

$stmt = $pdo->prepare("SELECT rentals.*, cars.*, customeraccounts.Username FROM rentals JOIN cars ON rentals.CarID = cars.CarID JOIN customeraccounts ON rentals.CustomerID = customeraccounts.CustomerID WHERE cars.CarState = 'Returning'");
$stmt->execute();
$carsReturning = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Car Returns</title>
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
                <h1>Manage Car Returns</h1>
                <table>
                    <tr>
                        <th>CarID</th>
                        <th>Make</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Return Location</th>
                        <th>Customer Name</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($carsReturning as $car): ?>
                    <?php
                        $stmt = $pdo->prepare("SELECT Name FROM locations WHERE LocationID = ?");
                        $stmt->execute([$car['PickupLocationID']]);
                        $location = $stmt->fetchColumn();
                    ?>
                    <tr>
                        <td><?= $car['CarID'] ?></td>
                        <td><?= $car['Make'] ?></td>
                        <td><?= $car['Type'] ?></td>
                        <td><?= $car['Model'] ?></td>
                        <td><?= $car['RentalDate'] ?></td>
                        <td><?= $car['ReturnDate'] ?></td>
                        <td><?= $location ?></td>
                        <td><?= $car['Username'] ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="car_id" value="<?= $car['CarID'] ?>">
                                <input type="hidden" name="pickup_location" value="<?= $car['PickupLocationID'] ?>">
                                <select name="car_status">
                                    <option value="Available">Available</option>
                                    <option value="Damaged">Damaged</option>
                                    <option value="Repair">Repair</option>
                                </select>
                                <input type="submit" name="update_car_status" value="Update">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
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
    
</body>
</html>