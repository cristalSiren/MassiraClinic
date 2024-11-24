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

// Get the patient Id from the URL (for fetching data)
$id = $_GET['id'] ?? '';

// Fetch patient data by Id
$patient = $controller->getPatientById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated patient data from the form
    $cin = $_POST['CIN'];  // CIN can be updated if needed, but ID is now used for updates
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $tel = $_POST['Tel'];
    $adresse = $_POST['adresse'];
    $date_entree = $_POST['date_entree'];
    $historique_medical = $_POST['historique_medical'];
    $prescription = $_POST['prescription'];
    $status = $_POST['status'];

    // Update the patient details
    $updateStatus = $controller->updatePatient($id, $cin, $nom, $prenom, $tel, $adresse, $date_entree, $historique_medical, $prescription, $status);
    
    if ($updateStatus) {
        header('Location: allPatients.php');
        exit;
    } else {
        echo "Error updating patient.";
    }
}
?>
