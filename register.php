<?php
session_start();
require 'protected/db.php.inc';

$phase = $_SESSION['phase'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($phase === 1) {
        // Validate and store customer information
        // TODO: Add more specific validation for each field

        $_SESSION['customerInfo'] = $_POST;
        $_SESSION['phase'] = 2;
    } elseif ($phase === 2) {
        // Validate and store account information
        // TODO: Add more specific validation for each field

        if ($_POST['password'] !== $_POST['passwordConfirm']) {
            die('Passwords do not match');
        }

        $_SESSION['accountInfo'] = $_POST;
        $_SESSION['phase'] = 3;
    } elseif ($phase === 3) {
        // Confirm and store all information
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);

            // Insert into customers table
            $stmt = $pdo->prepare("INSERT INTO customers (Name, Address, DateOfBirth, NationalID, Email, Telephone, CreditCardNumber, CreditCardExpiration, CreditCardName, CreditCardBank) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array_values($_SESSION['customerInfo']));

            $customerId = $pdo->lastInsertId();

            // Insert into customeraccounts table
            $stmt = $pdo->prepare("INSERT INTO customeraccounts (CustomerID, Username, Password) VALUES (?, ?, ?)");
            $stmt->execute([$customerId, $_SESSION['accountInfo']['username'], $_SESSION['accountInfo']['password']]);

            // Store customer ID in session
            $_SESSION['customerId'] = $customerId;

            // Reset phase and temporary data
            $_SESSION['phase'] = 1;
            unset($_SESSION['customerInfo'], $_SESSION['accountInfo']);

            // Display confirmation message
            echo "Registration successful! Your customer ID is $customerId.";
            exit;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <?php if ($phase === 1): ?> 
        <!-- Phase 1: Customer information fields -->
        <fieldset>
            <legend>Customer Information</legend>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="flatHouseNo" placeholder="Flat/House No" required>
            <input type="text" name="street" placeholder="Street" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="country" placeholder="Country" required>
            <input type="date" name="dob" placeholder="Date of Birth" required>
            <input type="text" name="idNumber" placeholder="ID Number" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="telephone" placeholder="Telephone" required>
            <input type="text" name="creditCardNumber" placeholder="Credit Card Number" required>
            <input type="date" name="creditCardExpiration" placeholder="Credit Card Expiration Date" required>
            <input type="text" name="creditCardName" placeholder="Name on Credit Card" required>
            <input type="text" name="creditCardBank" placeholder="Bank Issuing Credit Card" required>
            <input type="submit" value="Next">
        </fieldset>
        <?php elseif ($phase === 2): ?>
        <!-- Phase 2: Account creation fields -->
        <fieldset>
            <legend>E-account Information</legend>
            <input type="text" name="username" placeholder="Username" required minlength="6" maxlength="13">
            <input type="password" name="password" placeholder="Password" required minlength="8" maxlength="12">
            <input type="password" name="passwordConfirm" placeholder="Confirm Password" required minlength="8" maxlength="12">
            <input type="submit" value="Next">
        </fieldset>

        <?php elseif ($phase === 3): ?>
        <!-- Phase 3: Confirmation -->
        <fieldset>
            <legend>Confirmation</legend>
            <!-- Display all stored information for review -->
            <p>Name: <?php echo $_SESSION['name']; ?></p>
            <p>Flat/House No: <?php echo $_SESSION['flatHouseNo']; ?></p>
            <p>Street: <?php echo $_SESSION['street']; ?></p>
            <p>City: <?php echo $_SESSION['city']; ?></p>
            <p>Country: <?php echo $_SESSION['country']; ?></p>
            <p>Date of Birth: <?php echo $_SESSION['dob']; ?></p>
            <p>ID Number: <?php echo $_SESSION['idNumber']; ?></p>
            <p>Email Address: <?php echo $_SESSION['email']; ?></p>
            <p>Telephone: <?php echo $_SESSION['telephone']; ?></p>
            <p>Credit Card Number: <?php echo $_SESSION['creditCardNumber']; ?></p>
            <p>Credit Card Expiration Date: <?php echo $_SESSION['creditCardExpiration']; ?></p>
            <p>Name on Credit Card: <?php echo $_SESSION['creditCardName']; ?></p>
            <p>Bank Issuing Credit Card: <?php echo $_SESSION['creditCardBank']; ?></p>
            <p>Username: <?php echo $_SESSION['username']; ?></p>
            <input type="submit" value="Confirm">
        </fieldset>

        <?php endif; ?>
    </form>
</body>
</html>