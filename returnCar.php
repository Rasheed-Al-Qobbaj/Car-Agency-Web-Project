<?php
session_start();
include 'protected/db.php.inc';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

if (isset($_POST['return_car_id'])) {
    $carID = $_POST['return_car_id'];
    $stmt = $pdo->prepare("UPDATE cars SET CarState = 'Returning' WHERE CarID = ?");
    $stmt->execute([$carID]);
    header("Location: returnCar.php");
    exit();
}

$stmt = $pdo->prepare("SELECT CustomerID FROM customeraccounts WHERE Username = ?");
$stmt->execute([$_SESSION['username']]);
$_SESSION['CustomerID'] = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT rentals.*, cars.CarState FROM rentals JOIN cars ON rentals.CarID = cars.CarID WHERE rentals.CustomerID = ? AND cars.CarState = 'Rented'");
$stmt->execute([$_SESSION['CustomerID']]);
$rents = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Return a Car</title>
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
                <h1>Return a Car</h1>
                <table>
                    <tr>
                        <th>Reference Number</th>
                        <th>Make</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Return Location</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($rents as $rent): ?>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM cars WHERE CarID = ?");
                    $stmt->execute([$rent['CarID']]);
                    $car = $stmt->fetch(PDO::FETCH_ASSOC);

                    $stmt = $pdo->prepare("SELECT * FROM locations WHERE LocationID = ?");
                    $stmt->execute([$rent['ReturnLocationID']]);
                    $location = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                        <td><?= $rent['CarID'] ?></td>
                        <td><?= $car['Make'] ?></td>
                        <td><?= $car['Type'] ?></td>
                        <td><?= $car['Model'] ?></td>
                        <td><?= $rent['RentalDate'] ?></td>
                        <td><?= $rent['ReturnDate'] ?></td>
                        <td><?= $location['Name'] ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="return_car_id" value="<?= $rent['CarID'] ?>">
                                <input type="submit" value="Return">
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