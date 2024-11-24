

<?php
require_once 'db2.php';
require_once 'AdminController.php';

$db = connect(); // Assuming `connect()` establishes and returns a PDO connection
if ($db) {
    echo "Database connected successfully.";
}

$adminController = new AdminController($db);
if ($adminController) {
    echo "AdminController instantiated successfully.";
}
try {
    // $adminController = new AdminController(); // No arguments needed if Solution 2 is applied

    // Save the patient data
    $message = $adminController->createPatient($_POST);
    // Redirect or display success message
    echo $message;
    header("Location: allPatients.php");
    exit;
} catch (Exception $e) {
    // Handle errors
    echo "Failed to create patient: " . $e->getMessage();
}
?>


