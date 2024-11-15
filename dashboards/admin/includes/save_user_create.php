<?php
// Include necessary files
// require '../../config/config.php';
require '../includes/AdminController.php';
require '../includes/dbconnexion.php';

// Start the session to ensure the admin is logged in
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../auth/login.php");
//     exit;
// }

$conn = connect();
$controller = new AdminController($conn);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['type'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $cin = $_POST['cin'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_entree = $_POST['date_entree'];  // Added date_entree

    // Handle specific fields based on the user type
    $info_bancaire = isset($_POST['info_bancaire']) ? $_POST['info_bancaire'] : '';
    $disponibilite = isset($_POST['disponibilite']) ? $_POST['disponibilite'] : '';
    $specialite = isset($_POST['specialite']) ? $_POST['specialite'] : '';
    $historique_medical = isset($_POST['historique_medical']) ? $_POST['historique_medical'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $ordonnance = isset($_POST['ordonnance']) ? $_POST['ordonnance'] : '';
    $mutuel = isset($_POST['mutuel']) ? $_POST['mutuel'] : '';

    // Prepare data for user creation
    $data = [
        'name' => $name,
        'surname' => $surname,
        'cin' => $cin,
        'phone' => $phone,
        'address' => $address,
        'user_type' => $userType,
        'date_entree' => $date_entree   // Added date_entree
    ];

    // Add specific data based on user type
    if ($userType == 'receptionist') {
        $data['info_bancaire'] = $info_bancaire;
    } elseif ($userType == 'doctor') {
        $data['disponibilite'] = $disponibilite;
    } elseif ($userType == 'nurse') {
        $data['specialite'] = $specialite;
    } elseif ($userType == 'patient') {
        $data['historique_medical'] = $historique_medical;
        $data['status'] = $status;
        $data['ordonnance'] = $ordonnance;
        $data['mutuel'] = $mutuel;
    }

    // Call the function to create the user
    if ($controller->createUser($data)) {
        header("Location: allUsers.php?user_type=" . $userType);
        exit;
    } else {
        echo "<p class='text-danger'>There was an error creating the user. Please try again.</p>";
    }
}
?>
