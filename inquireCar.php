<?php
session_start();
include 'protected/db.php.inc';
if (!isset($_SESSION['ManagerID'])) {
    header('Location: index.php');
    exit();
}
$queryParams = $_GET;
$queryString = http_build_query($queryParams);
$fromDate = $toDate = $pickUpLocation = $returnDate = $returnLocation = $status = null;
if (!empty($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
}
if (!empty($_GET['toDate'])) {
    $toDate = $_GET['toDate'];
}
if (!empty($_GET['pickUpLocation'])) {
    $pickUpLocation = $_GET['pickUpLocation'];
}
if (!empty($_GET['returnDate'])) {
    $returnDate = $_GET['returnDate'];
}
if (!empty($_GET['returnLocation'])) {
    $returnLocation = $_GET['returnLocation'];
}
if (!empty($_GET['status'])) {
    $status = $_GET['status'];
}
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM locations");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$params = [];
$sql = "SELECT *
        FROM cars
        Join rentals on cars.CarID = rentals.CarID
        Join locations ploc on rentals.PickupLocationID = ploc.LocationID 
        Join locations rloc on rentals.ReturnLocationID = rloc.LocationID
        WHERE 1=1";
if (!empty($fromDate) && !empty($toDate)) {
    // $sql .= " AND AvailableFromDate <= ? AND AvailableToDate >= ?";
    // $params[] = $fromDate;
    // $params[] = $toDate;
}
if (!empty($pickUpLocation)) {
    $sql .= " AND ploc.Name = ?";
    $params[] = $pickUpLocation;
}
if (!empty($returnDate)) {
    $sql .= " AND rentals.ReturnDate = ?";
    $params[] = $returnDate;
}
if (!empty($returnLocation)) {
    $sql .= " AND rloc.Name = ?";
    $params[] = $returnLocation;
}
if (!empty($status)) {
    $sql .= " AND cars.CarState = ?";
    $params[] = $status;
}
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
    <title>Cars Inquire</title>
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
            <h1>Inquire about Cars</h1>
            <form action="inquireCar.php" method="get">
                <label for="fromDate">From Date:</label>
                <input type="date" id="fromDate" name="fromDate">
                <label for="toDate">To Date:</label>
                <input type="date" id="toDate" name="toDate">
                <label for="pickUpLocation">Pick-Up Location:</label>
                <select id="pickUpLocation" name="pickUpLocation">
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $location) : ?>
                        <option value="<?= $location['LocationID'] ?>"><?= $location['Name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="returnDate">Return Date:</label>
                <input type="date" id="returnDate" name="returnDate">
                <label for="returnLocation">Return Location:</label>
                <select id="returnLocation" name="returnLocation">
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $location) : ?>
                        <option value="<?= $location['LocationID'] ?>"><?= $location['Name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="">Select Status</option>
                    <option value="available">Available</option>
                    <option value="repair">In Repair</option>
                    <option value="damaged">Damaged</option>
                </select>
                <input type="submit" value="Search">
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Car ID</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>Fuel Type</th>
                        <th>Status</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $car): ?>
                    <tr>
                        <td><?php echo $car['CarID']; ?></td>
                        <td><?php echo $car['Type']; ?></td>
                        <td><?php echo $car['Model']; ?></td>
                        <td><?php echo $car['Description']; ?></td>
                        <td><?php echo $car['FuelType']; ?></td>
                        <td><?php echo $car['CarState']; ?></td>
                        <td><img src="carsImages/<?php echo $car['image']; ?>" alt="Car Image"></td>
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
    </div>
</body>
</html>