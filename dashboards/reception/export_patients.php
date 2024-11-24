<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet if you're using Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
include 'db2.php';
require 'AdminController.php';

// Establish database connection
$conn = connect();
$controller = new AdminController($conn);

// Fetch all patients
$patients = $controller->getPatientsBySearchXlsx('', ''); // Adjust search parameters as needed

// Create a new spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the headers
$sheet->setCellValue('A1', 'CIN');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Prénom');
$sheet->setCellValue('D1', 'Téléphone');
$sheet->setCellValue('E1', 'Date d\'entrée');
$sheet->setCellValue('F1', 'Adresse');
$sheet->setCellValue('G1', 'Status');

// Add patient data to the sheet
$row = 2;
foreach ($patients as $patient) {
    $sheet->setCellValue('A' . $row, $patient['CIN']);
    $sheet->setCellValue('B' . $row, $patient['Nom']);
    $sheet->setCellValue('C' . $row, $patient['Prenom']);
    $sheet->setCellValue('D' . $row, $patient['Tel']);
    $sheet->setCellValue('E' . $row, $patient['date_entree']);
    $sheet->setCellValue('F' . $row, $patient['adresse']);
    $sheet->setCellValue('G' . $row, $patient['status']);
    $row++;
}

// Write the file to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="patients.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
