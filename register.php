<?php
include 'protected\db.php.inc';

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

session_start();
$phase = 1;     
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["name"])) {
        $_SESSION["name"] = $_POST["name"];
        $_SESSION["address"] = $_POST["h_num"] . " " . $_POST["street"] . ", " . $_POST["city"] . ", " . $_POST["country"];
        $_SESSION["dob"] = $_POST["dob"];
        $_SESSION["id_number"] = $_POST["id_number"];
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["telephone"] = $_POST["telephone"];
        $_SESSION["cc_number"] = $_POST["cc_number"];
        $_SESSION["cc_expiry"] = $_POST["cc_expiry"];
        $_SESSION["cc_name"] = $_POST["cc_name"];
        $_SESSION["cc_bank"] = $_POST["cc_bank"];
        $phase = 2;
    } elseif (isset($_POST["username"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST["password"] == $_POST["confirm_password"]) {
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["password"] = $_POST["password"];
                $phase = 3;
            } else {
                echo "Passwords do not match.";
                $phase = 2;
            }
        }
    } elseif (isset($_POST["back"])) { 
        $phase = 1;
    } elseif (isset($_POST["confirm"])) {

        $stmt = $pdo->prepare("INSERT INTO customers (Name, Address, DateOfBirth, NationalID, Email, Telephone, CreditCardNumber, CreditCardExpiration, CreditCardName, CreditCardBank) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION["name"], $_SESSION["address"], $_SESSION["dob"], $_SESSION["id_number"], $_SESSION["email"], $_SESSION["telephone"], $_SESSION["cc_number"], $_SESSION["cc_expiry"], $_SESSION["cc_name"], $_SESSION["cc_bank"]]);
        $customerId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO customeraccounts (CustomerID, Username, Password) VALUES (?, ?, ?)");
        $stmt->execute([$customerId, $_SESSION["username"], $_SESSION["password"]]);
        $customerId = $pdo->lastInsertId();

        $phase = 4;
    }
} else {
    $phase = 1;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styles/form.css">
    <title>Register</title>
</head>

<body>
    <?php if ($phase == 1) : ?>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <div class="address-row">
                <div class="address-field">
                    <label for="h_num">House Number:</label>
                    <input type="text" id="h_num" name="h_num" required>
                </div>

                <div class="address-field">
                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street" required>
                </div>
            </div>

            <div class="address-row">
                <div class="address-field">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="address-field">
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" required>
                </div>
            </div>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br>

            <label for="id_number">ID Number:</label>
            <input type="text" id="id_number" name="id_number" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="telephone">Telephone:</label>
            <input type="tel" id="telephone" name="telephone" required><br><br>

            <label for="cc_number">Credit Card Number:</label>
            <input type="text" id="cc_number" name="cc_number" required><br>

            <label for="cc_expiry">Credit Card Expiry Date:</label>
            <input type="date" id="cc_expiry" name="cc_expiry" required><br>

            <label for="cc_name">Name on Credit Card:</label>
            <input type="text" id="cc_name" name="cc_name" required><br>

            <label for="cc_bank">Bank Issuing Credit Card:</label>
            <input type="text" id="cc_bank" name="cc_bank" required><br>

            <input type="submit" value="Next">
        </form>
    <?php elseif ($phase == 2) : ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required pattern=".{6,13}" title="Username should be between 6-13 characters"><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required pattern=".{8,12}" title="Password should be between 8-12 characters"><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required pattern=".{8,12}" title="Password should be between 8-12 characters"><br>

            <input type="submit" value="Next">
        </form>
    <?php elseif ($phase == 3) : ?>
        <?php
        $username = $_SESSION['username'];
        $stmt = $pdo->prepare('SELECT * FROM customeraccounts WHERE Username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Username already exists";
            $phase = 2;  
        }
        ?>

        <?php if (isset($error)): ?>
            <form method="post">
                <div id="error">
                    <?php echo $error ?>
                </div>
                <input type="submit" value="Next">
            </form>
        <?php else: ?>
            <form method="post">
            <p>Please confirm your details:</p>
            <p>Name: <?php echo $_SESSION["name"]; ?></p>
            <p>Address: <?php echo $_SESSION["address"];?></p>
            <p>Date of Birth: <?php echo $_SESSION["dob"]; ?></p>
            <p>ID Number: <?php echo $_SESSION["id_number"]; ?></p>
            <p>Email: <?php echo $_SESSION["email"]; ?></p>
            <p>Telephone: <?php echo $_SESSION["telephone"]; ?></p>
            <p>Credit Card Number: <?php echo $_SESSION["cc_number"]; ?></p>
            <p>Credit Card Expiry Date: <?php echo $_SESSION["cc_expiry"]; ?></p>
            <p>Name on Credit Card: <?php echo $_SESSION["cc_name"]; ?></p>
            <p>Bank Issuing Credit Card: <?php echo $_SESSION["cc_bank"]; ?></p>
            <p>Username: <?php echo $_SESSION["username"]; ?></p>
            <p>Password: <?php echo $_SESSION["password"]; ?></p>
            <input type="submit" name="confirm" value="Confirm">
            <input type="submit" name="back" value="Back">
            </form>
        <?php endif; ?>
    <?php elseif ($phase == 4) : ?>
        <?php
        echo "Thank you for registering. Your customer ID is " . $customerId;
        session_destroy();
        ?>
        <br><br><p><a href="index.php">Return to home page</a></p>
    <?php endif; ?>
</body>

</html>