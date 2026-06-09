<?php
session_start();
chdir(dirname(__DIR__));

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/producto_model.php";
require_once __DIR__ . '/../fpdf186/fpdf.php';

$limite    = isset($_GET['limite']) ? max(1, (int)$_GET['limite']) : 5;
$productos = ProductoModel::obtenerConBajoStock($limite);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Productos con Bajo Stock', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Umbral: stock <= ' . $limite . '   |   Generado: ' . date('Y-m-d H:i'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(177, 12, 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(85, 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Stock actual', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
foreach ($productos as $p) {
    $pdf->Cell(15, 8, $p['id'], 1, 0, 'C');
    $pdf->Cell(85, 8, mb_strimwidth($p['nombre'], 0, 50, '...'), 1, 0);
    $pdf->Cell(35, 8, '$' . number_format($p['precio'], 2), 1, 0, 'R');

    if ((int)$p['stock'] === 0) {
        $pdf->SetTextColor(177, 12, 12);
        $pdf->SetFont('Arial', 'B', 10);
    }
    $pdf->Cell(30, 8, $p['stock'], 1, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
}

if (empty($productos)) {
    $pdf->Cell(165, 8, 'No hay productos con stock bajo este umbral.', 1, 1, 'C');
}

$pdf->Output('I', 'bajo_stock.pdf');
