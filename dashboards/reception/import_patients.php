<?php 
require 'vendor/autoload.php'; // Include PhpSpreadsheet if you're using Composer

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Database connection
include 'db2.php';
require 'AdminController.php';

$conn = connect();
$controller = new AdminController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['import_file']['tmp_name'];

    try {
        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();

        // Get the highest row number in the sheet
        $highestRow = $sheet->getHighestRow();
        
        // Loop through rows and insert patient data into the database
        for ($row = 2; $row <= $highestRow; $row++) { // Start at row 2 to skip the header
            $cin = $sheet->getCellByColumnAndRow(1, $row)->getValue(); // Column A
            $nom = $sheet->getCellByColumnAndRow(2, $row)->getValue(); // Column B
            $prenom = $sheet->getCellByColumnAndRow(3, $row)->getValue(); // Column C
            $tel = $sheet->getCellByColumnAndRow(4, $row)->getValue(); // Column D
            $date_entree = $sheet->getCellByColumnAndRow(5, $row)->getFormattedValue(); // Column E
            $adresse = $sheet->getCellByColumnAndRow(6, $row)->getValue(); // Column F
            $historique_medical = $sheet->getCellByColumnAndRow(7, $row)->getValue(); // Column G
            $status = $sheet->getCellByColumnAndRow(8, $row)->getValue(); // Column H

            // Validate the required fields
            if (!empty($cin) && !empty($nom) && !empty($prenom)) {
                // Insert into the database
                $controller->addPatient($cin, $nom, $prenom, $tel, $adresse, $date_entree, $historique_medical, $status);
            }
        }

        echo "Importation réussie!";
        header('Location: allPatients.php'); // Redirect after successful import
        exit;

    } catch (Exception $e) {
        echo "Erreur lors de l'importation : " . $e->getMessage();
    }
} else {
    echo "Erreur de téléchargement de fichier.";
}
?>
