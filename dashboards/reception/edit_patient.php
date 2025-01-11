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

// Get the patient Id from the URL
$id = $_GET['id'] ?? '';

// Fetch patient data by Id
$patient = $controller->getPatientById($id);

// Check if patient data was successfully retrieved
if (!$patient) {
    echo "Patient not found.";
    exit; // Stop the script if no patient is found
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated patient data from the form
    $cin = $_POST['CIN'];
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $tel = $_POST['Tel'];
    $adresse = $_POST['adresse'];
    $date_entree = $_POST['date_entree'];
    $historique_medical = $_POST['historique_medical'];
    $status = $_POST['status'];

    // Update the patient details
    $prescription = $_POST['prescription']; // Get prescription from the form
    $updateStatus = $controller->updatePatient($id, $cin, $nom, $prenom, $tel, $adresse, $date_entree, $historique_medical, $prescription, $status);

    // $updateStatus = $controller->updatePatient($id, $cin, $nom, $prenom, $tel, $adresse, $date_entree, $historique_medical, $status);
    if ($updateStatus) {
        header('Location: allPatients.php');
        exit;
    } else {
        echo "Error updating patient.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - Massira Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebarAd.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Patient</h1>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="CIN" class="block">CIN</label>
                    <input type="text" name="CIN" value="<?php echo htmlspecialchars($patient['CIN']); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="Nom" class="block">Nom</label>
                    <input type="text" name="Nom" value="<?php echo htmlspecialchars($patient['Nom']); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="Prenom" class="block">Prénom</label>
                    <input type="text" name="Prenom" value="<?php echo htmlspecialchars($patient['Prenom']); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="Tel" class="block">Téléphone</label>
                    <input type="text" name="Tel" value="<?php echo htmlspecialchars($patient['Tel']); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="adresse" class="block">Adresse</label>
                    <input type="text" name="adresse" value="<?php echo htmlspecialchars($patient['adresse']); ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label for="date_entree" class="block">Date d'entrée</label>
                    <input type="date" name="date_entree" value="<?php echo htmlspecialchars($patient['date_entree']); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="historique_medical" class="block">Diagnostic</label>
                    <textarea name="historique_medical" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($patient['historique_medical']); ?></textarea>
                </div>
                <div>
                    <label for="status" class="block">Statut</label>
                    <input type="text" name="status" value="<?php echo htmlspecialchars($patient['status']); ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="form-group mb-4">
                    <label for="prescription" class="text-gray-700">Prescription</label>
                    <input type="text" value="<?php echo htmlspecialchars($patient['ordonnance']); ?>" class="form-control w-full p-3 border border-gray-300 rounded-md" id="prescription" name="prescription">
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Mettre à jour</button>
                    <a href="allPatients.php" class="px-4 py-2 bg-gray-300 text-black rounded-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
