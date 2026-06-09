<?php
session_start();
chdir(dirname(__DIR__));

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/producto_model.php";
require_once __DIR__ . '/../fpdf186/fpdf.php';

$productos = ProductoModel::obtenerTodos();

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Catalogo de Productos', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Generado: ' . date('Y-m-d H:i'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(177, 12, 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(55, 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(70, 8, 'Descripcion', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Stock', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
foreach ($productos as $p) {
    $pdf->Cell(15, 8, $p['id'], 1, 0, 'C');
    $pdf->Cell(55, 8, mb_strimwidth($p['nombre'], 0, 35, '...'), 1, 0);
    $pdf->Cell(70, 8, mb_strimwidth((string)$p['descripcion'], 0, 48, '...'), 1, 0);
    $pdf->Cell(25, 8, '$' . number_format($p['precio'], 2), 1, 0, 'R');
    $pdf->Cell(25, 8, $p['stock'], 1, 1, 'C');
}

if (empty($productos)) {
    $pdf->Cell(190, 8, 'No hay productos registrados.', 1, 1, 'C');
}

$pdf->Output('I', 'inventario.pdf');
