<?php
require 'vendor/autoload.php';
require 'db2.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Suppress output before headers
if (ob_get_length()) {
    ob_end_clean();
}

// Establish database connection
$conn = connect();

try {
    // Fetch stock data
    $query = $conn->query("SELECT * FROM stock");
    $stockData = $query->fetchAll(PDO::FETCH_ASSOC);

    if (empty($stockData)) {
        die("Aucune donnée trouvée pour l'exportation.");
    }

    // Create a new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add header row
    $headers = array_keys($stockData[0]); // Get column names
    $column = 'A';

    foreach ($headers as $header) {
        $sheet->setCellValue("{$column}1", ucfirst($header));
        $column++;
    }

    // Add data rows
    $row = 2; // Start from the second row
    foreach ($stockData as $data) {
        $column = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue("{$column}{$row}", $value);
            $column++;
        }
        $row++;
    }

    // Set correct headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="stock_data.xlsx"');
    header('Cache-Control: max-age=0');

    // Stream the file to the browser
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
