<?php
session_start();
chdir(dirname(__DIR__));

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/pedido_model.php";
require_once __DIR__ . '/../fpdf186/fpdf.php';

$hoy   = date('Y-m-d');
$desde = $_GET['desde'] ?? $hoy;
$hasta = $_GET['hasta'] ?? $hoy;

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) $desde = $hoy;
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) $hasta = $hoy;
if ($desde > $hasta) [$desde, $hasta] = [$hasta, $desde];

$ventas = PedidoModel::obtenerVentasPorRango($desde, $hasta);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Periodo: ' . $desde . ' a ' . $hasta, 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(177, 12, 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(20, 8, 'N. Venta', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Cliente', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$gran_total = 0;
foreach ($ventas as $v) {
    $pdf->Cell(20, 8, '#' . str_pad($v['id'], 6, '0', STR_PAD_LEFT), 1, 0, 'C');
    $pdf->Cell(40, 8, $v['fecha'], 1, 0, 'C');
    $pdf->Cell(65, 8, mb_strimwidth($v['cliente'], 0, 38, '...'), 1, 0);
    $pdf->Cell(35, 8, '$' . number_format($v['total'], 2), 1, 1, 'R');
    $gran_total += $v['total'];
}

if (empty($ventas)) {
    $pdf->Cell(160, 8, 'No se registraron ventas en este periodo.', 1, 1, 'C');
} else {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(125, 9, 'TOTAL DEL PERIODO (' . count($ventas) . ' venta(s))', 1, 0, 'R');
    $pdf->Cell(35, 9, '$' . number_format($gran_total, 2), 1, 1, 'R');
}

$pdf->Output('I', 'ventas_' . $desde . '_a_' . $hasta . '.pdf');
