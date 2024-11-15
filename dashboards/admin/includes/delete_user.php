<?php
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../auth/login.php");
//     exit;
// }
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../loginAd.php");
//     exit;
// }

// include_once '../../config/config.php';
require '../includes/AdminController.php'; // Include the controller
require '../includes/dbconnexion.php';

$conn = connect();
$controller = new AdminController($conn);

// Check if the necessary parameters are set
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    // Perform deletion based on user type
    if ($type == 'receptionist') {
        $controller->deleteReceptionist($id);
    } elseif ($type == 'doctor') {
        $controller->deleteDoctor($id);
    } elseif ($type == 'nurse') {
        $controller->deleteNurse($id);
    } elseif ($type == 'patient') {
        $controller->deletePatient($id);
    }

    // Redirect back to the user list after deletion (user type is preserved)
    header("Location: allUsers.php?user_type=$type");
    exit;
} else {
    // If no id or type is set, show an error message
    echo "Error: Invalid parameters.";
}
?>
