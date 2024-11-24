<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet if you're using Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection
include 'db2.php';
require 'AdminController.php';

$conn = connect();
$controller = new AdminController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
    $file = $_FILES['import_file']['tmp_name'];

    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();

    // Loop through rows and insert patient data into the database
    foreach ($sheet->getRowIterator(2) as $row) { // Start at row 2 to skip the header
        $cin = $sheet->getCell('A' . $row->getRowIndex())->getValue();
        $nom = $sheet->getCell('B' . $row->getRowIndex())->getValue();
        $prenom = $sheet->getCell('C' . $row->getRowIndex())->getValue();
        $tel = $sheet->getCell('D' . $row->getRowIndex())->getValue();
        $date_entree = $sheet->getCell('E' . $row->getRowIndex())->getValue();
        $adresse = $sheet->getCell('F' . $row->getRowIndex())->getValue();
        $historique_medical = $sheet->getCell('G' . $row->getRowIndex())->getValue();
        $status = $sheet->getCell('H' . $row->getRowIndex())->getValue();

        // Insert into the database
        $controller->addPatient($cin, $nom, $prenom, $tel, $adresse, $date_entree, $historique_medical, $status);
    }

    echo "Importation réussie!";
    header('Location: allPatients.php'); // Redirect after successful import
    exit;
} else {
    echo "Erreur de téléchargement de fichier.";
}
?>
