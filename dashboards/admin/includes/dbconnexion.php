<?php
// Database credentials
$host = 'localhost'; // Database host
$dbname = 'clinic-elmassira'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (empty for XAMPP by default)

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
