<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/pedido_model.php";

$venta_id = $_SESSION['ultimo_pedido'] ?? 0;

if (!$venta_id) {
    header('Location: index.php?option=Nosotros');
    exit;
}

$detalles = PedidoModel::obtenerVentaConDetalles($venta_id);

if (empty($detalles)) {
    header('Location: index.php?option=Nosotros');
    exit;
}

$info  = $detalles[0];
$total = $info['total'];
?>
<div class="row justify-content-center">
    <div class="col-md-9" id="factura-imprimible">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background:rgb(177,12,12);">
                <h4 class="mb-0">Factura de Compra</h4>
            </div>
            <div class="card-body">

                <!-- Encabezado factura -->
                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong>N° de Venta:</strong>
                            <span class="badge fs-6" style="background:rgb(177,12,12);">#<?= str_pad($venta_id, 6, '0', STR_PAD_LEFT) ?></span>
                        </p>
                        <p class="mb-1"><strong>Cliente:</strong> <?= htmlspecialchars($info['cliente']) ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1"><strong>Fecha:</strong> <?= htmlspecialchars($info['fecha']) ?></p>
                    </div>
                </div>

                <!-- Detalle de productos -->
                <table class="table table-bordered table-sm">
                    <thead class="text-white text-center" style="background:rgb(177,12,12);">
                        <tr>
                            <th>ID Prod.</th>
                            <th>Nombre del Producto</th>
                            <th class="text-end">Precio Unit.</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td class="text-center"><?= (int)$d['producto_id'] ?></td>
                            <td><?= htmlspecialchars($d['producto']) ?></td>
                            <td class="text-end">$<?= number_format($d['precio_unitario'], 2) ?></td>
                            <td class="text-center"><?= (int)$d['cantidad'] ?></td>
                            <td class="text-end">$<?= number_format($d['subtotal'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-bold fs-5">TOTAL GENERAL</td>
                            <td class="text-end fw-bold fs-5" style="color:rgb(177,12,12);">
                                $<?= number_format($total, 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="alert alert-success text-center mt-3 mb-3">
                    ¡Compra registrada exitosamente! Gracias por tu pedido.
                </div>

                <!-- Botones -->
                <div class="text-center no-print d-flex justify-content-center gap-2">
                    <a href="index.php?option=Nosotros" class="btn btn-outline-secondary">
                        Volver a la tienda
                    </a>
                    <a href="reportes/factura_pdf.php" target="_blank" class="btn text-white" style="background:rgb(177,12,12);">
                        Descargar PDF
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
