<?php
session_start();
include 'protected/db.php.inc';
if (isset($_SESSION['ManagerID'])) {
    $isManager = true;
}

$queryParams = $_GET;

$queryString = http_build_query($queryParams);

$fromDate = $toDate = $carType = $pickUpLocation = $minPrice = $maxPrice = null;

if (!empty($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
}
if (!empty($_GET['toDate'])) {
    $toDate = $_GET['toDate'];
}
if (!empty($_GET['carType'])) {
    $carType = $_GET['carType'];
}
if (!empty($_GET['pickUpLocation'])) {
    $pickUpLocation = $_GET['pickUpLocation'];
}
if (!empty($_GET['minPrice'])) {
    $minPrice = $_GET['minPrice'];
}
if (!empty($_GET['maxPrice'])) {
    $maxPrice = $_GET['maxPrice'];
}

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

$params = [];
$sql = "SELECT * FROM cars WHERE 1=1";

if (!empty($carType)) {
    $sql .= " AND Type = ?";
    $params[] = $carType;
}
if (!empty($minPrice) && !empty($maxPrice)) {
    $sql .= " AND PricePerDay BETWEEN ? AND ?";
    $params[] = $minPrice;
    $params[] = $maxPrice;
} elseif (!empty($minPrice)) {
    $sql .= " AND PricePerDay >= ?";
    $params[] = $minPrice;
} elseif (!empty($maxPrice)) {
    $sql .= " AND PricePerDay <= ?";
    $params[] = $maxPrice;
}
if (isset($_GET['sortBy'])) {
    $sortBy = $_GET['sortBy'];
    setcookie('sortPreference', $sortBy, time() + (86400 * 30), "/"); 
    $queryParams['sortBy'] = $sortBy;
} elseif (isset($_COOKIE['sortPreference'])) {
    $sortBy = $_COOKIE['sortPreference'];
    $queryParams['sortBy'] = $sortBy;
} else {
    $sortBy = 'PricePerDay'; 
}


$validSortColumns = ['PricePerDay', 'Type', 'FuelType'];
if (!in_array($sortBy, $validSortColumns)) {
    $sortBy = 'PricePerDay'; 
}

$sql .= " ORDER BY $sortBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $key => $car) {
    $stmt = $pdo->prepare("SELECT ImageFilename FROM carimages WHERE CarID = ?");
    $stmt->execute([$car['CarID']]);
    $result[$key]['image'] = $stmt->fetchColumn();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Search</title>
    <link rel="stylesheet" href="styles\styles.css">
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
                    <h1>Search for a Car</h1>
                    <form action="searchCar.php" method="get">
                        <input type="date" name="fromDate" placeholder="From Date">
                        <input type="date" name="toDate" placeholder="To Date">
                        <input type="text" name="carType" placeholder="Car Type">
                        <input type="text" name="pickUpLocation" placeholder="Pick-up Location">
                        <input type="number" name="minPrice" placeholder="Min Price">
                        <input type="number" name="maxPrice" placeholder="Max Price">
                        <input type="submit" value="Search">
                    </form>

                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th><a href=<?php echo "searchCar.php?" . $queryString . "&sortBy=PricePerDay"?>>Price per Day</a></th>
                                <th><a href=<?php echo "searchCar.php?" . $queryString . "&sortBy=Type"?>>Car Type</a></th>
                                <th><a href=<?php echo "searchCar.php?" . $queryString . "&sortBy=FuelType"?>>Fuel Type</a></th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $car): ?>
                            <tr class="<?php echo $car['FuelType']; ?>">
                                <td><input type="checkbox" name="selectedCars[]" value="<?php echo $car['CarID']; ?>"></td>
                                <td><?php echo $car['PricePerDay']; ?></td>
                                <td><?php echo $car['Type']; ?></td>
                                <td><?php echo $car['FuelType']; ?></td>
                                <td><img src="carsImages/<?php echo $car['image']; ?>" alt="Car Image"></td>
                                <td><a href="rentCar.php?ref=<?php echo $car['CarID']; ?>">Rent</a></td>
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