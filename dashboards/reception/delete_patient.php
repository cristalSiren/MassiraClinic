<?php
session_start();
include 'db2.php';
require 'AdminController.php';

// Check if the user is authenticated
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'receptionists') {
    header('Location: login.php');
    exit;
}

$conn = connect();
$controller = new AdminController($conn);

// Get the patient CIN from the URL
$cin = $_GET['id'] ?? '';

// Delete the patient record
$deleteStatus = $controller->deletePatient($cin);

if ($deleteStatus) {
    header('Location: allPatients.php');
    exit;
} else {
    echo "Error deleting patient.";
}
?>
