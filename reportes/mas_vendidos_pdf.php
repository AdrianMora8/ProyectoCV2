<?php
session_start();
chdir(dirname(__DIR__));

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/pedido_model.php";
require_once __DIR__ . '/../fpdf186/fpdf.php';

$limite   = isset($_GET['limite']) ? max(1, (int)$_GET['limite']) : 10;
$ranking  = PedidoModel::obtenerMasVendidos($limite);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Productos Mas Vendidos', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Top ' . $limite . '   |   Generado: ' . date('Y-m-d H:i'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(177, 12, 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(15, 8, '#', 1, 0, 'C', true);
$pdf->Cell(80, 8, 'Producto', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Unidades vendidas', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Ingresos generados', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$puesto = 1;
foreach ($ranking as $r) {
    $pdf->Cell(15, 8, $puesto, 1, 0, 'C');
    $pdf->Cell(80, 8, mb_strimwidth($r['nombre'], 0, 48, '...'), 1, 0);
    $pdf->Cell(40, 8, $r['total_unidades'], 1, 0, 'C');
    $pdf->Cell(45, 8, '$' . number_format($r['total_ingresos'], 2), 1, 1, 'R');
    $puesto++;
}

if (empty($ranking)) {
    $pdf->Cell(180, 8, 'Aun no hay ventas registradas.', 1, 1, 'C');
}

$pdf->Output('I', 'mas_vendidos.pdf');
