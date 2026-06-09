<?php
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: index.php?option=Nosotros');
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];
$total   = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$aviso = $_SESSION['aviso_carrito'] ?? '';
unset($_SESSION['aviso_carrito']);
$error = $_SESSION['error_carrito'] ?? '';
unset($_SESSION['error_carrito']);
?>
<h3 class="mb-4">Mi Carrito</h3>

<?php if ($aviso): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($aviso) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (empty($carrito)): ?>
    <div class="alert alert-info">
        Tu carrito está vacío. <a href="index.php?option=Nosotros">Ir a la tienda</a>
    </div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="text-white" style="background:rgb(177,12,12);">
            <tr>
                <th>Producto</th>
                <th class="text-end">Precio unit.</th>
                <th class="text-center">Cantidad</th>
                <th class="text-end">Subtotal</th>
                <th class="text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($carrito as $prod_id => $item): ?>
            <tr data-precio="<?= $item['precio'] ?>">
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td class="text-end">$<?= number_format($item['precio'], 2) ?></td>
                <td class="text-center" style="width:150px;">
                    <form method="post" action="action.php" class="d-flex gap-1 justify-content-center">
                        <input type="hidden" name="accion" value="actualizar_carrito">
                        <input type="hidden" name="producto_id" value="<?= $prod_id ?>">
                        <input type="number"
                               name="cantidad"
                               value="<?= $item['cantidad'] ?>"
                               min="1" max="99"
                               class="form-control form-control-sm input-cantidad"
                               style="width:65px;">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">OK</button>
                    </form>
                </td>
                <td class="text-end celda-subtotal">
                    $<?= number_format($item['precio'] * $item['cantidad'], 2) ?>
                </td>
                <td class="text-center">
                    <form method="post" action="action.php">
                        <input type="hidden" name="accion" value="eliminar_carrito">
                        <input type="hidden" name="producto_id" value="<?= $prod_id ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="table-light">
                <td colspan="3" class="text-end fw-bold fs-5">TOTAL</td>
                <td class="text-end fw-bold fs-5" id="total-general">
                    $<?= number_format($total, 2) ?>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="d-flex justify-content-between mt-3">
    <a href="index.php?option=Nosotros" class="btn btn-outline-secondary">Seguir comprando</a>
    <form method="post" action="action.php">
        <input type="hidden" name="accion" value="confirmar_compra">
        <button type="submit" class="btn text-white" style="background:rgb(177,12,12);">
            Confirmar compra
        </button>
    </form>
</div>
<?php endif; ?>

<script src="js/carrito.js"></script>
