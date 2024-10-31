<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
require('tfpdf.php');

if (!isset($_GET['devis_id'])) {
    die("Erreur : Aucun devis spécifié.");
}
$devis_id = $_GET['devis_id'];

// Récupérer les paramètres de l'entreprise avec le logo
$entreprise_stmt = $pdo->prepare("SELECT * FROM Entreprises WHERE entreprise_id = (SELECT entreprise_id FROM Devis WHERE devis_id = :devis_id)");
$entreprise_stmt->execute([':devis_id' => $devis_id]);
$settings = $entreprise_stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    die("Erreur : Les paramètres de l'entreprise sont introuvables.");
}

// Récupérer les informations du devis et du client, y compris le SIRET
$devis = $pdo->prepare("SELECT Devis.*, Clients.nom AS client_nom, Clients.adresse, Clients.telephone, Clients.email, Clients.siret
                        FROM Devis 
                        JOIN Clients ON Devis.client_id = Clients.client_id 
                        WHERE devis_id = :devis_id");
$devis->execute([':devis_id' => $devis_id]);
$devis_info = $devis->fetch(PDO::FETCH_ASSOC);

if (!$devis_info) {
    die("Erreur : Les informations du devis sont introuvables.");
}

// Récupérer les items du devis
$items = $pdo->prepare("SELECT Devis_Items.*, Items.description, Items.prix_unitaire 
                        FROM Devis_Items 
                        JOIN Items ON Devis_Items.item_id = Items.item_id 
                        WHERE devis_id = :devis_id");
$items->execute([':devis_id' => $devis_id]);
$items_list = $items->fetchAll(PDO::FETCH_ASSOC);

// Création du PDF
$pdf = new tFPDF();
$pdf->AddPage();

// Charger les variantes de police DejaVu
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$pdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);

if (!empty($settings['logo']) && file_exists($settings['logo'])) {
    $pdf->Image($settings['logo'], 160, 10, 30);
}

// Informations de l'entreprise (à gauche)
$pdf->SetFont('DejaVu', 'B', 12);
$pdf->Cell(0, 10, $settings['nom_entreprise'], 0, 1, 'L');
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(0, 6, $settings['adresse'], 0, 1, 'L');
$pdf->Cell(0, 6, 'Téléphone : ' . $settings['telephone'], 0, 1, 'L');
$pdf->Cell(0, 6, 'Email : ' . $settings['email'], 0, 1, 'L');
$pdf->Ln(10);

// Informations du devis (à droite)
$pdf->SetXY(150, 40);
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(0, 6, 'Devis ID : ' . $devis_info['devis_id'], 0, 1, 'R');
$pdf->Cell(0, 6, 'Date : ' . $devis_info['date_creation'], 0, 1, 'R');
$pdf->Ln(15);

// Informations du client (à gauche sous l'entreprise)
$pdf->SetFont('DejaVu', 'B', 12);
$pdf->SetXY(10, 60);
$pdf->Cell(0, 10, 'Client', 0, 1, 'L');
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(0, 6, $devis_info['client_nom'], 0, 1, 'L');
$pdf->Cell(0, 6, $devis_info['adresse'], 0, 1, 'L');
$pdf->Cell(0, 6, 'Téléphone : ' . $devis_info['telephone'], 0, 1, 'L');
$pdf->Cell(0, 6, 'Email : ' . $devis_info['email'], 0, 1, 'L');

// Afficher le SIRET s'il est présent
if (!empty($devis_info['siret'])) {
    $pdf->Cell(0, 6, 'SIRET : ' . $devis_info['siret'], 0, 1, 'L');
}
$pdf->Ln(10);

// Tableau des items
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(100, 10, 'Description', 1, 0, 'L', true);
$pdf->Cell(30, 10, 'Quantité', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Prix Unitaire', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

$pdf->SetFont('DejaVu', '', 10);
$total_ht = 0;

foreach ($items_list as $item) {
    $prix_unitaire = $item['prix_unitaire'] * (1 + $settings['marge'] / 100);
    $prix_total = $prix_unitaire * $item['quantite'];
    $pdf->Cell(100, 10, $item['description'], 1);
    $pdf->Cell(30, 10, $item['quantite'], 1, 0, 'C');
    $pdf->Cell(30, 10, number_format($prix_unitaire, 2, ',', ' ') . ' €', 1, 0, 'R');
    $pdf->Cell(30, 10, number_format($prix_total, 2, ',', ' ') . ' €', 1, 1, 'R');
    $total_ht += $prix_total;
}

$tva = $total_ht * ($settings['taux_tva'] / 100);
$total_ttc = $total_ht + $tva;

// Afficher la TVA et le total général
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(160, 10, 'TVA (' . $settings['taux_tva'] . '%)', 1, 0, 'R');
$pdf->Cell(30, 10, number_format($tva, 2, ',', ' ') . ' €', 1, 1, 'R');
$pdf->Cell(160, 10, 'Total Général', 1, 0, 'R');
$pdf->Cell(30, 10, number_format($total_ttc, 2, ',', ' ') . ' €', 1, 1, 'R');
$pdf->Ln(20);

// Pied de page avec les informations bancaires
$pdf->SetFont('DejaVu', 'I', 8);

// Récupérer et afficher les détails bancaires s'ils existent
$bank_stmt = $pdo->prepare("SELECT * FROM BankDetails WHERE user_id = :user_id");
$bank_stmt->execute([':user_id' => $settings['user_id']]);
$bank_details = $bank_stmt->fetch(PDO::FETCH_ASSOC);

if ($bank_details) {
    $pdf->Ln(10);
    $pdf->SetFont('DejaVu', 'B', 10);
    $pdf->Cell(0, 10, 'Détails bancaires :', 0, 1, 'L');
    $pdf->SetFont('DejaVu', '', 10);
    $pdf->Cell(0, 6, 'Banque : ' . $bank_details['bank_name'], 0, 1, 'L');
    $pdf->Cell(0, 6, 'IBAN : ' . $bank_details['iban'], 0, 1, 'L');
    $pdf->Cell(0, 6, 'SWIFT/BIC : ' . $bank_details['swift_bic'], 0, 1, 'L');
}

// Section signature
$pdf->SetFillColor(230, 230, 230); // Couleur de fond gris clair
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(0, 10, ' ', 0, 1); // Espace avant l'encadré
$pdf->Cell(0, 10, 'Signature du client (précédée de la mention "Bon pour accord")', 1, 1, 'C', true);
$pdf->Ln(20); // Espace après l'encadré


$pdf->Output('I', 'Devis_' . $devis_id . '.pdf');
?>
