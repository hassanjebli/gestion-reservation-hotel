<?php
session_start();
require_once "../config/database.php";
require_once "../assets/PDF/tcpdf.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("location:payments.php");
    exit();
}

$id_reservation = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM reservation 
                        INNER JOIN client ON reservation.id_client=client.id_client 
                        INNER JOIN chambre ON chambre.id_chambre=reservation.id_chambre
                        WHERE reservation.id_reservation = ?");
$stmt->execute([$id_reservation]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("location:payments.php");
    exit();
}

class PDF extends TCPDF
{
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'FACTURE', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Image('../assets/images/logo.png', 10, 10, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Ln(20);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Hotel Name');
$pdf->SetTitle('Facture de Réservation');
$pdf->SetSubject('Facture de Réservation Hôtelière');
$pdf->SetKeywords('TCPDF, PDF, facture, reservation, hotel');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Nom de l\'hôtel', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Adresse de l\'hôtel', 0, 1, 'L');
$pdf->Cell(0, 5, 'Téléphone: +212617940675', 0, 1, 'L');
$pdf->Cell(0, 5, 'Email: hassan@jebli.com', 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Détails de la facture', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(90, 7, 'Numéro de facture: INV-' . str_pad($reservation['id_reservation'], 6, '0', STR_PAD_LEFT), 0, 0);
$pdf->Cell(0, 7, 'Date: ' . date('d/m/Y'), 0, 1);
$pdf->Cell(90, 7, 'Code de réservation: ' . $reservation['code_reservation'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Informations du client', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Nom: ' . $reservation['nom_complet'], 0, 1);
$pdf->Cell(0, 7, 'Téléphone: ' . $reservation['telephone'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Détails de la réservation', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(90, 7, 'Date d\'arrivée: ' . date('d/m/Y', strtotime($reservation['date_arrivee'])), 0, 0);
$pdf->Cell(0, 7, 'Date de départ: ' . date('d/m/Y', strtotime($reservation['date_depart'])), 0, 1);
$pdf->Cell(0, 7, 'Chambre: ' . $reservation['numero_chambre'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Détail des frais', 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(90, 7, 'Description', 1, 0, 'C');
$pdf->Cell(30, 7, 'Quantité', 1, 0, 'C');
$pdf->Cell(0, 7, 'Montant', 1, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(90, 7, 'Séjour - Chambre ' . $reservation['numero_chambre'], 1, 0);
$pdf->Cell(30, 7, '1', 1, 0, 'C');
$pdf->Cell(0, 7, $reservation['montant_total'] . ' DH', 1, 1, 'R');

// Add total
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(120, 10, 'Total', 0, 0, 'R');
$pdf->Cell(0, 10, $reservation['montant_total'] . ' DH', 0, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Mode de paiement: ________________', 0, 1);
$pdf->Cell(0, 7, 'Date de paiement: ________________', 0, 1);

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 7, 'Nous vous remercions pour votre séjour et espérons vous revoir bientôt!', 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Conditions générales:', 0, 1);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(0, 5, 'Veuillez noter que cette facture est due à réception. Des frais de retard peuvent être appliqués pour tout paiement effectué après la date d\'échéance. Pour toute question concernant cette facture, veuillez contacter notre service client.', 0, 'L');

$pdf->Ln(15);
$pdf->Line(20, $pdf->GetY(), 80, $pdf->GetY());
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 5, 'Signature du client', 0, 1, 'C');

$pdf->Output('Facture_' . $reservation['code_reservation'] . '.pdf', 'D');