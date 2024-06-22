<?php
include 'protected/db.php.inc';

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: login.php');
    exit;
}


$carID = $_GET['ref'] ?? null;

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE CarID = ?");
    $stmt->execute([$carID]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($car['CarState'] != "Available") {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error Message</title>
            <link rel="stylesheet" href="styles/styles.css">
        </head>
        <body>
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
            </div>
        </header>
        <div class="main-container">
            <nav class="nav">
                <a href="searchCar.php">Search a Car</a>
            </nav>
                <main class="main">
                    <div class="error-message">
                        <p>Car is not available for rent.</p>
                    </div>
                    <p><a href="index.php">Return to home page</a></p>
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
        ';
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM locations");
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT customers.* FROM customers JOIN customeraccounts ON customers.CustomerID = customeraccounts.CustomerID WHERE customeraccounts.Username = ?");
    $stmt->execute([$_SESSION['username']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die($e->getMessage());
}

$phase = $_SESSION['phase'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($phase) {
        case 1:
            $_SESSION['carID'] = $_POST['carID'];
            $_SESSION['model'] = $_POST['model'];
            $_SESSION['description'] = $_POST['description'];
            $_SESSION['RentalDate'] = $_POST['RentalDate'];
            $_SESSION['ReturnDate'] = $_POST['ReturnDate'];
            $_SESSION['pickup_location'] = $_POST['pickup_location'];
            $_SESSION['return_location'] = $_POST['return_location'];
            $_SESSION['special_requirements'] = $_POST['special_requirements'] ?? [];

            $pricePerDay = $car['PricePerDay'];
            $rentalDays = (strtotime($_SESSION['ReturnDate']) - strtotime($_SESSION['RentalDate'])) / 86400;
            $totalCost = $pricePerDay * $rentalDays;
            if (in_array("Child Seat", $_SESSION['special_requirements'])) {
                $totalCost += 10;
            }
            $_SESSION['totalCost'] = $totalCost;

            $phase = 2;
            break;
        case 2:
            $_SESSION['cc_number'] = $_POST['cc_number'];
            $_SESSION['cc_expiry'] = $_POST['cc_expiry'];
            $_SESSION['cc_name'] = $_POST['cc_name'];
            $_SESSION['cc_bank'] = $_POST['cc_bank'];
            $_SESSION['cc_type'] = $_POST['cc_type'];
            $_SESSION['accept_terms'] = $_POST['accept_terms'] ?? false;
            $_SESSION['signature'] = $_POST['signature'];
            $_SESSION['signature_date'] = $_POST['signature_date'];
            if ($_SESSION['accept_terms']) {
                $phase = 3;
            } else {
                $error = "You must accept the terms and conditions.";
                $phase = 2;
            }
            break;
        case 3:

            $stmt = $pdo->prepare("INSERT INTO rentals (CustomerID, CarID, RentalDate, ReturnDate, TotalCost, PickupLocationID, ReturnLocationID, SpecialRequirements) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$customer['CustomerID'], $_SESSION['carID'], $_SESSION['RentalDate'], $_SESSION['ReturnDate'], $_SESSION['totalCost'], $_SESSION['pickup_location'], $_SESSION['return_location'], implode(", ", $_SESSION['special_requirements'])]);
            $booking_ref = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO invoices (RentalID, CustomerID, InvoiceDate, TotalAmount, CreditCardType) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$booking_ref, $customer['CustomerID'], date('Y-m-d'), $_SESSION['totalCost'], $_SESSION['cc_type']]);
            $invoiceID = $pdo->lastInsertId();

            $stmt = $pdo->prepare("UPDATE cars SET CarState = 'Rented' WHERE CarID = ?");
            $stmt->execute([$_SESSION['carID']]);


            $_SESSION['booking_ref'] = $booking_ref;
            $phase = 4;
    }
    $_SESSION['phase'] = $phase;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles/styles.css">
    <title>Rent a Car</title>
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
                <h1>Rent a Car</h1>
                <?php if ($phase == 1) : ?>
                    <form method="post">
                        <label for="carID">Car ID:</label> <br>
                        <input type="text" id="carID" name="carID" value="<?= $car['CarID'] ?>" readonly> <br>
                        <label for="model">Model:</label> <br>
                        <input type="text" id="model" name="model" value="<?= $car['Model'] ?>" readonly> <br>
                        <label for="description">Description:</label> <br>
                        <textarea id="description" name="description" readonly><?= $car['Description'] ?></textarea> <br>
                        <label for="RentalDate">Rental Date:</label> <br>
                        <input type="date" id="RentalDate" name="RentalDate" required> <br>
                        <label for="ReturnDate">Return Date:</label> <br>
                        <input type="date" id="ReturnDate" name="ReturnDate" required> <br>
                        <label for="pickup_location">Pickup Location:</label> <br>
                        <select id="pickup_location" name="pickup_location" required>
                            <?php foreach ($locations as $location) : ?>
                                <option value="<?= $location['LocationID'] ?>"><?= $location['Name'] ?></option>
                            <?php endforeach; ?>
                        </select> <br>
                        <label for="return_location">Return Location:</label> <br>
                        <select id="return_location" name="return_location" required>
                            <?php foreach ($locations as $location) : ?>
                                <option value="<?= $location['LocationID'] ?>"><?= $location['Name'] ?></option>
                            <?php endforeach; ?>
                        </select> <br>
                        <label for="special_requirements">Special Requirements:</label> <br>
                        <input type="checkbox" id="special_requirements" name="special_requirements[]" value="Child Seat"> Child Seat <br> <br>
                        <input type="submit" value="Next">
                    </form>
                <?php elseif ($phase == 2) : ?>
                    <form method="post">
                        <label for="totalCost">Total Cost:</label> <br>
                        <input type="text" id="totalCost" name="totalCost" value="<?= $_SESSION['totalCost'] ?>" readonly> <br>
                        <label for="cc_number">Credit Card Number:</label> <br>
                        <input type="text" id="cc_number" name="cc_number" value="<?= $customer["CreditCardNumber"] ?>" readonly title="Edit credit card info from the user profile"> <br>
                        <label for="cc_expiry">Credit Card Expiry Date:</label> <br>
                        <input type="date" id="cc_expiry" name="cc_expiry" value="<?= $customer["CreditCardExpiration"] ?>" readonly> <br>
                        <label for="cc_name">Name on Credit Card:</label> <br>
                        <input type="text" id="cc_name" name="cc_name" value="<?= $customer["CreditCardName"] ?>"> <br>
                        <label for="cc_bank">Bank Issuing Credit Card:</label> <br>
                        <input type="text" id="cc_bank" name="cc_bank" value="<?= $customer["CreditCardBank"] ?>"> <br>
                        <label for="cc_type">Credit Card Type:</label> <br>
                        <select id="cc_type" name="cc_type" required>
                            <option value="Visa">Visa</option>
                            <option value="Mastercard">Mastercard</option>
                        </select> <br>
                        <label for="accept_terms">I accept the terms and conditions:</label> <br>
                        <input type="checkbox" id="accept_terms" name="accept_terms" required> <br>
                        <label for="signature">Signature:</label> <br>
                        <input type="text" id="signature" name="signature" required> <br>
                        <label for="signature_date">Date:</label> <br>
                        <input type="date" id="signature_date" name="signature_date" value="<?php echo date('Y-m-d'); ?>" required> <br>

                        <?php if (isset($error)) echo "<p>$error</p>"; ?>
                        <input type="submit" value="Confirm Rent">
                    </form>
                <?php elseif ($phase == 3) : ?>
                    <p>Please confirm your details:</p>
                    <p>Car ID: <?= $_SESSION['carID'] ?></p>
                    <p>Model: <?= $_SESSION['model'] ?></p>
                    <p>Description: <?= $_SESSION['description'] ?></p>
                    <p>Rental Date: <?= $_SESSION['RentalDate'] ?></p>
                    <p>Return Date: <?= $_SESSION['ReturnDate'] ?></p>
                    <p>Pickup Location: <?= $_SESSION['pickup_location'] ?></p>
                    <p>Return Location: <?= $_SESSION['return_location'] ?></p>
                    <p>Special Requirements: <?= implode(", ", $_SESSION['special_requirements']) ?></p>
                    <p>Total Cost: <?= $_SESSION['totalCost'] ?></p>
                    <p>Credit Card Number: <?= $_SESSION['cc_number'] ?></p>
                    <p>Credit Card Expiry Date: <?= $_SESSION['cc_expiry'] ?></p>
                    <p>Name on Credit Card: <?= $_SESSION['cc_name'] ?></p>
                    <p>Bank Issuing Credit Card: <?= $_SESSION['cc_bank'] ?></p>
                    <p>Credit Card Type: <?= $_SESSION['cc_type'] ?></p>
                    <p>Signature: <?= $_SESSION['signature'] ?></p>
                    <p>Date: <?= $_SESSION['signature_date'] ?></p>
                    <form method="post">
                        <input type="submit" value="Confirm">
                    </form>
                <?php elseif ($phase == 4) : ?>
                    <p>Thank you for renting a car with us. Your booking reference number is: <?= $_SESSION['booking_ref'] ?></p>
                    <?php unset($_SESSION['phase']); ?>
                    <p><a href="index.php">Return to home page</a></p>
                <?php endif; ?>
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