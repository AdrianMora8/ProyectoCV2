<?php
session_start();
chdir(dirname(__DIR__));

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?option=Nosotros');
    exit;
}

$venta_id = $_SESSION['ultimo_pedido'] ?? 0;
if (!$venta_id) {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/pedido_model.php";
require_once __DIR__ . '/../fpdf186/fpdf.php';

$detalles = PedidoModel::obtenerVentaConDetalles($venta_id);
if (empty($detalles)) {
    header('Location: index.php?option=Nosotros');
    exit;
}

$info = $detalles[0];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Factura de Compra', 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'N. de Venta: #' . str_pad($venta_id, 6, '0', STR_PAD_LEFT), 0, 1);
$pdf->Cell(0, 7, 'Cliente: ' . $info['cliente'], 0, 1);
$pdf->Cell(0, 7, 'Email: ' . $info['email'], 0, 1);
$pdf->Cell(0, 7, 'Fecha: ' . $info['fecha'], 0, 1);
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(177, 12, 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(20, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(70, 8, 'Producto', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Precio Unit.', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Subtotal', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
foreach ($detalles as $d) {
    $pdf->Cell(20, 8, $d['producto_id'], 1, 0, 'C');
    $pdf->Cell(70, 8, $d['producto'], 1, 0);
    $pdf->Cell(30, 8, '$' . number_format($d['precio_unitario'], 2), 1, 0, 'R');
    $pdf->Cell(25, 8, $d['cantidad'], 1, 0, 'C');
    $pdf->Cell(35, 8, '$' . number_format($d['subtotal'], 2), 1, 1, 'R');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(145, 9, 'TOTAL GENERAL', 1, 0, 'R');
$pdf->Cell(35, 9, '$' . number_format($info['total'], 2), 1, 1, 'R');

$pdf->Output('I', 'factura_' . $venta_id . '.pdf');
