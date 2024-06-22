<?php
session_start();
include 'protected/db.php.inc'; 

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    
    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT CustomerID FROM customeraccounts WHERE Username = ?");
    $stmt->execute([$_SESSION['username']]);
    $customerID = $stmt->fetchColumn();

    $sql = "SELECT invoices.InvoiceID, invoices.InvoiceDate, cars.Type, cars.Model, rentals.RentalDate, rentals.ReturnDate, pickup.Name AS PickupLocation, returnLoc.Name AS ReturnLocation
            FROM rentals
            JOIN cars ON rentals.CarID = cars.CarID
            JOIN invoices ON rentals.RentalID = invoices.RentalID
            JOIN locations AS pickup ON rentals.PickupLocationID = pickup.LocationID
            JOIN locations AS returnLoc ON rentals.ReturnLocationID = returnLoc.LocationID
            WHERE rentals.CustomerID = ?
            ORDER BY rentals.RentalDate DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customerID]);
    $rentals = $stmt->fetchAll();
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Rented Car</title>
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
                <h1>View Rented Cars</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Invoice Date</th>
                            <th>Type</th>
                            <th>Model</th>
                            <th>Pick-up Date</th>
                            <th>Pick-up Location</th>
                            <th>Return Date</th>
                            <th>Return Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rentals as $rental): ?>
                            <?php if ($rental['RentalDate'] > date('Y-m-d')) {
                                $RentalStatus = 'future';
                            } elseif ($rental['ReturnDate'] < date('Y-m-d')) {
                                $RentalStatus = 'past';
                            } else {
                                $RentalStatus = 'current';
                            } ?>
                        <tr class="<?= $RentalStatus ?>">
                            <td><?= $rental['InvoiceID'] ?></td>
                            <td><?= $rental['InvoiceDate'] ?></td>
                            <td><?= $rental['Type'] ?></td>
                            <td><?= $rental['Model'] ?></td>
                            <td><?= $rental['RentalDate'] ?></td>
                            <td><?= $rental['PickupLocation'] ?></td>
                            <td><?= $rental['ReturnDate'] ?></td>
                            <td><?= $rental['ReturnLocation'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
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