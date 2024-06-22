<?php
session_start();
include 'protected/db.php.inc';
if (isset($_SESSION['ManagerID'])) {
    $isManager = true;
}
$locationID = $_GET['ref'] ?? null;
$locationDetails = null;
if ($locationID) {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE locationID = ?");
        $stmt->execute([$locationID]);
        $locationDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["Name"];
        $address = $_POST["Address"];
        $telephone = $_POST["Telephone"];
        try{
            $stmt = $pdo->prepare("UPDATE locations SET Name = ?, Address = ?, Telephone = ? WHERE LocationID = ?");
            $stmt->execute([$name, $address, $telephone, $locationID]);
            echo "<div class='confirmation-message'>Location details have been successfully updated.</div>";
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE locationID = ?");
        $stmt->execute([$locationID]);
        $locationDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        
    }

} else {
    echo "Location reference number is required.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Location Details</title>
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
            <form method="post">
                <label for="LocationID">Location ID:</label><br>
                <input type="text" name="LocationID" value="<?php echo $locationDetails['LocationID']; ?>" readonly>
                <label for="Name">Name:</label><br>
                <input type="text" id="Name" name="Name" value="<?php echo $locationDetails['Name']; ?>"><br>
                <label for="Address">Address:</label><br>
                <input type="text" id="Address" name="Address" value="<?php echo $locationDetails['Address']; ?>"><br>
                <label for="Telephone">Telephone:</label><br>
                <input type="text" id="Telephone" name="Telephone" value="<?php echo $locationDetails['Telephone']; ?>"><br><br>
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
    </div>
</body>
</html>