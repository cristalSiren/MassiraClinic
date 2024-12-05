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
        // Clinic name on the left
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(0, 128, 0); // Set text color to green
        $this->Cell(0, 5, 'Polyclinique Marrakech El Massira', 0, 1, 'L', 0, '', 0);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'Pluridisciplinaire', 0, 1, 'L', 0, '', 0);

        // Arabic name on the right
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(0, -10, 'مصحة مراكش', 0, 1, 'R', 0, '', 0);
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 5, 'مصحة متعددة الاختصاصات', 0, 1, 'R', 0, '', 0);

        // Centered date
        $currentDate = date('d/m/Y');
        $this->SetY(25); // Position slightly below
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 10, 'Marrakech, le ' . $currentDate . ' : مراكش في', 0, 1, 'C', 0, '', 0);

        $this->Ln(10); // Add a line break
    }

    // Custom footer
    public function Footer() {
        $this->SetY(-30); // Position 30mm from bottom
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(0, 128, 0); // Set text color to green
        $footerContent = 'Hay El Massira 1 <<D>> N8 - Marrakech - حي المسيرة 1 حرف «د» رقم 8 مراكش';
        $this->Cell(0, 10, $footerContent, 0, 1, 'C', 0, '', 0);

        $contactInfo = 'Tel: 05 24 49 82 88 - 06 61 08 99 63 - Tel/Fax: 05 24 49 82 83 - E-mail: Polyclinique.elmassira@gmail.com';
        $this->Cell(0, 10, $contactInfo, 0, 0, 'C', 0, '', 0);
    }
}

$pdf = new CustomTCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Massira Clinic');
$pdf->SetTitle('Prescriptions');

// Set margins and add a page
$pdf->SetMargins(15, 50, 15); // Adjust margins to fit the header and footer
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(0, 128, 0); // Set text color to green

// Extract the ordonnance of the first patient
$ordonnance = $patient['ordonnance'] ?? '';

// Write prescription content
$pdf->SetFont('helvetica', 'B', 14); // Larger font for ordonnance content
$html = '<h2 style="text-align:center;">Ordonnance</h2>';
$html .= '<p>' . nl2br(htmlspecialchars($ordonnance)) . '</p>';

// Write the HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Clean the buffer and output the PDF
ob_end_clean(); // Clean the buffer to ensure no previous output
$pdf->Output('prescription.pdf', 'D');
