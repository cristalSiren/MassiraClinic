<?php
require 'vendor/autoload.php';
require 'AdminController.php';
require 'db2.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Suppress output before headers
if (ob_get_length()) {
    ob_end_clean();
}

// Establish database connection
$conn = connect();
$controller = new AdminController($conn);

// Retrieve search parameters from the POST request (as these come from the form submission)
$searchTerm = $_POST['search'] ?? '';
$filter = $_POST['date_entree'] ?? '';

// Fetch patient data based on search and filter
$patients = $controller->getPatientsBySearchXlsx($searchTerm, $filter);

if (empty($patients)) {
    die("Aucune donnée trouvée pour l'exportation.");
}

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add header row
$sheet->setCellValue('A1', 'CIN')
      ->setCellValue('B1', 'Nom')
      ->setCellValue('C1', 'Prenom')
      ->setCellValue('D1', 'Tel')
      ->setCellValue('E1', 'Date d\'entrée')
      ->setCellValue('F1', 'Adresse')
      ->setCellValue('G1', 'Status');

// Add data rows
$row = 2; // Start from the second row
foreach ($patients as $patient) {
    $sheet->setCellValue("A{$row}", $patient['CIN'])
          ->setCellValue("B{$row}", $patient['Nom'])
          ->setCellValue("C{$row}", $patient['Prenom'])
          ->setCellValue("D{$row}", $patient['Tel'])
          ->setCellValue("E{$row}", $patient['date_entree'])
          ->setCellValue("F{$row}", $patient['adresse'])
          ->setCellValue("G{$row}", $patient['status']);
    $row++;
}

// Set correct headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="patients.xlsx"');
header('Cache-Control: max-age=0');

// Stream the file to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
