<?php
include 'protected/db.php.inc';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

try { 
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);

    
    $stmt = $pdo->prepare('SELECT * FROM customeraccounts WHERE Username = ?');
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($user['ManagerID'])){
        header('Location: index.php');
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $stmt = $pdo->prepare('UPDATE customers SET Name = ?, Address = ?, DateOfBirth = ?, NationalID = ?, Email = ?, Telephone = ?, CreditCardNumber = ?, CreditCardExpiration = ?, CreditCardName = ?, CreditCardBank = ? WHERE CustomerID = ?');
        $stmt->execute([$_POST['name'], $_POST['address'], $_POST['dob'], $_POST['id_number'], $_POST['email'], $_POST['telephone'], $_POST['cc_number'], $_POST['cc_expiry'], $_POST['cc_name'], $_POST['cc_bank'], $_POST['customerID']]);
    }
    $stmt = $pdo->prepare('SELECT customers.* FROM customers 
                           JOIN customeraccounts ON customers.CustomerID = customeraccounts.CustomerID 
                           WHERE customeraccounts.username = ?');
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e){
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
                <h1>Profile</h1>
                <p>Welcome to your profile page, <?php echo $_SESSION['username']; ?>.</p>
                <form method="post">
                <label for="customerID">Customer ID:</label> <br>
                <input type="text" id="customerID" name="customerID" value="<?php echo $user['CustomerID']; ?>" readonly> <br>
                <label for="name">Name:</label> <br>
                <input type="text" id="name" name="name" value="<?php echo $user['Name']; ?>" required> <br>
                <label for="address">Address:</label> <br>
                <input type="text" id="address" name="address" value="<?php echo $user['Address']; ?>" required> <br>
                <label for="dob">Date of Birth:</label> <br>
                <input type="date" id="dob" name="dob" value="<?php echo $user['DateOfBirth']; ?>" required> <br>
                <label for="id_number">ID Number:</label> <br>
                <input type="text" id="id_number" name="id_number" value="<?php echo $user['NationalID']; ?>" required> <br>
                <label for="email">Email:</label> <br>
                <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required> <br>
                <label for="telephone">Telephone:</label> <br>
                <input type="tel" id="telephone" name="telephone" value="<?php echo $user['Telephone']; ?>" required> <br>
                <label for="cc_number">Credit Card Number:</label> <br>
                <input type="text" id="cc_number" name="cc_number" value="<?php echo $user['CreditCardNumber']; ?>" required> <br>
                <label for="cc_expiry">Credit Card Expiry Date:</label> <br>
                <input type="date" id="cc_expiry" name="cc_expiry" value="<?php echo $user['CreditCardExpiration']; ?>" required> <br>
                <label for="cc_name">Name on Credit Card:</label> <br>
                <input type="text" id="cc_name" name="cc_name" value="<?php echo $user['CreditCardName']; ?>" required> <br>
                <label for="cc_bank">Bank Issuing Credit Card:</label> <br>
                <input type="text" id="cc_bank" name="cc_bank" value="<?php echo $user['CreditCardBank']; ?>" required><br>
                <input type="submit" value="Update">
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
    
</body>
</html>