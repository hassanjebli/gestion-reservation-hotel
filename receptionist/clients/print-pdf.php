<?php
require_once '../../config/database.php';
require_once "../../assets/PDF/tcpdf.php";

// Fetch data from the database
$query = "SELECT * FROM reservation
          JOIN client ON reservation.id_client = client.id_client
          JOIN chambre ON reservation.id_chambre = chambre.id_chambre
          JOIN tarif_chambre ON tarif_chambre.id_tarif = chambre.id_tarif";
$stmt = $conn->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Hotel Name');
$pdf->SetTitle('Liste Des Réservations - Export PDF');
$pdf->SetSubject('Réservations Export');
$pdf->SetKeywords('TCPDF, PDF, export, réservations, liste');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 15, 15);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 11);

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Liste Des Réservations', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Généré le ' . date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Ln(10);

// Table
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(220, 220, 220);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('helvetica', 'B', 10);

// Define column widths
$w = array(15, 40, 15, 15, 30, 30, 25, 25);
$header = array('ID', 'Nom Complet', 'Sexe', 'Age', "Date d'arrivée", 'Date de départ', 'N° Chambre', 'Prix');

// Calculate the width of the table
$table_width = array_sum($w);

// Calculate left margin to center the table
$left_margin = ($pdf->GetPageWidth() - $table_width) / 2;

// Set the left margin
$pdf->SetLeftMargin($left_margin);

// Table header
for($i = 0; $i < count($header); ++$i) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
}
$pdf->Ln();

// Table rows
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(255, 255, 255);

foreach($reservations as $row) {
    $pdf->Cell($w[0], 6, $row['id_client'], 'LR', 0, 'C', true);
    $pdf->Cell($w[1], 6, $row['nom_complet'], 'LR', 0, 'L', true);
    $pdf->Cell($w[2], 6, $row['sexe'], 'LR', 0, 'C', true);
    $pdf->Cell($w[3], 6, $row['age'], 'LR', 0, 'C', true);
    $pdf->Cell($w[4], 6, date('d/m/Y', strtotime($row['date_arrivee'])), 'LR', 0, 'C', true);
    $pdf->Cell($w[5], 6, date('d/m/Y', strtotime($row['date_depart'])), 'LR', 0, 'C', true);
    $pdf->Cell($w[6], 6, $row['numero_chambre'], 'LR', 0, 'C', true);
    $pdf->Cell($w[7], 6, number_format($row['n_prix_nuit'], 2) . ' DH', 'LR', 0, 'R', true);
    $pdf->Ln();
}

// Closing line
$pdf->Cell(array_sum($w), 0, '', 'T');

// Reset left margin
$pdf->SetLeftMargin(15);

// Output the PDF
$pdf->Output('liste_reservations.pdf', 'D');
