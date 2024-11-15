<?php
// dbconnection.php

$host = 'localhost'; // Database host
$dbname = 'clinic-elmassira'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (empty for XAMPP by default)

function connect() {
    global $host, $dbname, $username, $password;
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Error handling
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>

