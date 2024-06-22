<?php
session_start();
$isManager = true;
if (!isset($_SESSION['ManagerID'])) {
    header('Location: index.php');
    exit();
}

include 'protected/db.php.inc';

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference = $_POST["reference"];
    $model = $_POST["model"];
    $type = $_POST["type"];
    $make = $_POST["make"];
    $year = $_POST["year"];
    $color = $_POST["color"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $capacity = $_POST["capacity"];
    $suitcases = $_POST["suitcases"];
    $fuel = $_POST["fuel"];
    $consumption = $_POST["consumption"];
    $horsepower = $_POST["horsepower"];
    $length = $_POST["length"];
    $width = $_POST["width"];
    $gear = $_POST["gear"];
    $conditions = $_POST["conditions"];
    $restrictions = $_POST["restrictions"];

    $stmt = $pdo->prepare("INSERT INTO cars (ReferenceNumber, Model, Type, Make, RegistrationYear, Color, Description, PricePerDay, CapacityPeople, CapacitySuitcases, FuelType, AvgConsumption, Horsepower, Length, Width, GearType, Conditions, Restrictions) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([$reference, $model, $type, $make, $year, $color, $description, $price, $capacity, $suitcases, $fuel, $consumption, $horsepower, $length, $width, $gear, $conditions, $restrictions]);
    $carID = $pdo->lastInsertId();

    $images = $_FILES['images'];
    $counter = 1;
    foreach ($images['tmp_name'] as $key => $tmpName) {
        
        $imageType = exif_imagetype($tmpName);
        if ($imageType !== IMAGETYPE_JPEG && $imageType !== IMAGETYPE_PNG) {
            die('Please upload a JPEG or PNG image.');
        }
        if ($imageType === IMAGETYPE_PNG) {
            $imageName = 'car' . $carID . 'img' . $counter . '.png';
        } else { 
            $imageName = 'car' . $carID . 'img' . $counter . '.jpeg';
        }
        move_uploaded_file($tmpName, 'carsImages/' . $imageName);
        
        $stmt = $pdo->prepare("INSERT INTO carimages (CarID, ImageFilename) VALUES (?, ?)");
        $stmt->execute([$carID, $imageName]);
        $counter++;
    }
    echo "<div class='confirmation-message'>Car has been successfully added to the database. Car ID: <a href='carDetails.php?ref=" . $carID . "'>" . $carID . "</a></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Car</title>
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
                <h1>Add a Car</h1>
                <form method="post" enctype="multipart/form-data">
                    <label for="reference">Reference Number:</label> <br>
                    <input type="text" id="reference" name="reference" required> <br>
                    <label for="model">Car Model:</label> <br>
                    <input type="text" id="model" name="model" required> <br>
                    <label for="type">Car Type:</label> <br>
                    <input type="text" id="type" name="type" required> <br>
                    <label for="make">Car Make:</label> <br>
                    <select id="make" name="make" required>
                        <option value="BMW">BMW</option>
                        <option value="VW">VW</option>
                        <option value="Volvo">Volvo</option>
                        <option value="Audi">Audi</option>
                        <option value="KIA">KIA</option>
                    </select> <br>
                    <label for="year">Registration Year:</label> <br>
                    <input type="number" id="year" name="year" required> <br>
                    <label for="color">Car Color:</label> <br>
                    <input type="text" id="color" name="color" required> <br>
                    <label for="description">Description:</label> <br>
                    <textarea id="description" name="description" required></textarea> <br>
                    <label for="price">Price per Day:</label> <br>
                    <input type="number" id="price" name="price" required> <br>
                    <label for="capacity">Capacity (People):</label> <br>
                    <input type="number" id="capacity" name="capacity" required> <br>
                    <label for="suitcases">Capacity (Suitcases):</label> <br>
                    <input type="number" id="suitcases" name="suitcases" required> <br>
                    <label for="fuel">Fuel Type:</label> <br> 
                    <select id="fuel" name="fuel" required>
                        <option value="petrol">petrol</option>
                        <option value="diesel">diesel</option>
                        <option value="electric">electric</option>
                        <option value="hybrid">hybrid</option>
                    </select> <br>
                    <label for="consumption">Average Consumption:</label> <br>
                    <input type="number" id="consumption" name="consumption" required> <br>
                    <label for="horsepower">Horsepower:</label> <br>
                    <input type="number" id="horsepower" name="horsepower" required> <br>
                    <label for="length">Length:</label> <br>
                    <input type="number" id="length" name="length" step="0.01" required> <br>
                    <label for="width">Width:</label> <br>
                    <input type="number" id="width" name="width" step="0.01" required> <br>
                    <label for="gear">Gear Type:</label> <br>
                    <input type="text" id="gear" name="gear" required> <br>
                    <label for="conditions">Conditions:</label> <br>
                    <textarea id="conditions" name="conditions"></textarea> <br>
                    <label for="restrictions">Restrictions:</label> <br>
                    <textarea id="restrictions" name="restrictions"></textarea> <br>

                    <label for="images">Upload Images:</label>
                    <input type="file" id="images" name="images[]" accept=".jpeg,.jpg,.png" multiple required> <br>

                    <input type="submit" value="Add Car">
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