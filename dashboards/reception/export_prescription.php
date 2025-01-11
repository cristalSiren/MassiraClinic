<?php

ob_start(); // Start output buffering

require_once('../../TCPDF/tcpdf.php'); // Include the TCPDF library
include 'db2.php'; // Include your database connection

$conn = connect();

// Fetch prescription data
$search = $_POST['search'] ?? '';
$date_entree = $_POST['date_entree'] ?? '';

$sql = "SELECT * FROM patients 
        WHERE (CIN LIKE :search OR Nom LIKE :search OR Prenom LIKE :search) 
          AND (date_entree LIKE :date_entree OR :date_entree = '')
        LIMIT 1"; // Fetch only the first patient
$stmt = $conn->prepare($sql);

// Bind parameters
$likeSearch = "%$search%";
$stmt->bindValue(':search', $likeSearch, PDO::PARAM_STR);
$stmt->bindValue(':date_entree', $date_entree, PDO::PARAM_STR);

// Execute the statement
$stmt->execute();
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// If no result is found, display an error
if (!$patient) {
    die("No prescription found for the given search criteria.");
}

// Initialize TCPDF
class CustomTCPDF extends TCPDF {
    // Custom header
    public function Header() {
        // Background image
        $imgFile = '../assets/images/ordo.jpg'; // Adjust the path as needed
        if (file_exists($imgFile)) {
            $this->Image($imgFile, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        }
    }

    // Custom footer
    public function Footer() {
        // No footer needed for this design
    }
}

$pdf = new CustomTCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Massira Clinic');
$pdf->SetTitle('Prescriptions');

// Set margins and add a page
$pdf->SetMargins(15, 15, 15); // Adjust margins to fit the header and footer
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(0, 128, 0); // Set text color to green

// Extract the ordonnance of the first patient
$ordonnance = $patient['ordonnance'] ?? '';
$currentDate = date('d/m/Y');

// Position the current date
$pdf->SetY(100); // Position the date (2cm above the text)
$htmlDate = '<p style="text-align:center; font-size:14px; color:#008000;">Marrakech, le ' . htmlspecialchars($currentDate) . '</p>';
$pdf->writeHTML($htmlDate, true, false, true, false, '');

// Position the prescription text
$pdf->SetY(130); // Position text below the date
$html = '<h1 style="text-align:center; color:#008000;">Ordonnance</h1>';
$html .= '<p style="text-align:center; font-size:16px; color:#008000;">' . nl2br(htmlspecialchars($ordonnance)) . '</p>';

// Write the prescription content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Clean the buffer and output the PDF
ob_end_clean(); // Clean the buffer to ensure no previous output
$pdf->Output('prescription.pdf', 'D');

?>
