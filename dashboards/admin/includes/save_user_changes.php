<?php
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     // header("Location: ../auth/login.php");
//     header("Location: ../loginAd.php");
//     exit;
// }

// Get the form data from POST request
$userId = isset($_POST['id']) ? $_POST['id'] : '';
$userType = isset($_POST['type']) ? $_POST['type'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$info_bancaire = isset($_POST['info_bancaire']) ? $_POST['info_bancaire'] : '';  // For receptionist
$disponibilite = isset($_POST['disponibilite']) ? $_POST['disponibilite'] : '';  // For doctor
$specialite = isset($_POST['specialite']) ? $_POST['specialite'] : '';  // For nurse
$date_entree = isset($_POST['date_entree']) ? $_POST['date_entree'] : '';  // For patient
$historique_medical = isset($_POST['historique_medical']) ? $_POST['historique_medical'] : '';  // For patient
$status = isset($_POST['status']) ? $_POST['status'] : '';  // For patient
$ordonnance = isset($_POST['ordonnance']) ? $_POST['ordonnance'] : '';  // For patient
$mutuel = isset($_POST['mutuel']) ? $_POST['mutuel'] : '';  // For patient

// Initialize database connection
// require '../../config/config.php';
require '../includes/dbconnexion.php';
require '../includes/AdminController.php';

$conn = connect();
$controller = new AdminController($conn);

// Prepare data array for update
$data = [
    'user_id' => $userId,
    'user_type' => $userType,
    'name' => $name,
    'surname' => $surname,
    'phone' => $phone,
    'address' => $address,
    'info_bancaire' => $info_bancaire,
    'disponibilite' => $disponibilite,
    'specialite' => $specialite,
    'date_entree' => $date_entree,
    'historique_medical' => $historique_medical,
    'status' => $status,
    'ordonnance' => $ordonnance,
    'mutuel' => $mutuel
];

// Call update method based on user type
$updateSuccess = $controller->updateUser($data);

if ($updateSuccess) {
    // Redirect back to the user list page in the dashboard
    header("Location: allUsers.php?user_type=" . urlencode($userType));
    exit;
} else {
    // Show error message if update fails
    echo "Error updating user.";
}
?>
