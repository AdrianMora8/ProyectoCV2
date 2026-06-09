<?php
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}

require_once "models/producto_model.php";

$error   = $_SESSION['error_producto'] ?? '';
unset($_SESSION['error_producto']);

$editar  = null;
$edit_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
if ($edit_id > 0) {
    $editar = ProductoModel::obtenerPorId($edit_id);
}

$productos = ProductoModel::obtenerTodos();
?>
<h3 class="mb-4">Gestión de Productos</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Formulario alta / edición -->
<div class="card mb-4 shadow-sm">
    <div class="card-header text-white" style="background:rgb(177,12,12);">
        <?= $editar ? 'Editar producto' : 'Nuevo producto' ?>
    </div>
    <div class="card-body">
        <form method="post" action="action.php" enctype="multipart/form-data">
            <?php if ($editar): ?>
                <input type="hidden" name="accion" value="actualizar_producto">
                <input type="hidden" name="id" value="<?= $editar['id'] ?>">
            <?php else: ?>
                <input type="hidden" name="accion" value="guardar_producto">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required
                           value="<?= htmlspecialchars($editar['nombre'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio *</label>
                    <input type="number" name="precio" class="form-control" step="0.01" min="0.01" required
                           value="<?= htmlspecialchars($editar['precio'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" min="0"
                           value="<?= htmlspecialchars($editar['stock'] ?? '0') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">
                        Imagen <?= $editar ? '(dejar vacío para mantener actual)' : '' ?>
                    </label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <?php if ($editar && !empty($editar['imagen'])): ?>
                        <img src="image/productos/<?= htmlspecialchars($editar['imagen']) ?>"
                             class="mt-2 rounded" style="height:60px;object-fit:cover;"
                             alt="imagen actual">
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"><?= htmlspecialchars($editar['descripcion'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn text-white" style="background:rgb(177,12,12);">
                        <?= $editar ? 'Guardar cambios' : 'Agregar producto' ?>
                    </button>
                    <?php if ($editar): ?>
                        <a href="index.php?option=AdminProductos" class="btn btn-outline-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de productos -->
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="text-white" style="background:rgb(177,12,12);">
            <tr>
                <th>#</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-end">Precio</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($productos)): ?>
            <tr><td colspan="7" class="text-center text-muted">No hay productos registrados.</td></tr>
            <?php else: ?>
            <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td>
                    <?php if (!empty($p['imagen'])): ?>
                        <img src="image/productos/<?= htmlspecialchars($p['imagen']) ?>"
                             style="height:50px;object-fit:cover;border-radius:4px;"
                             alt="<?= htmlspecialchars($p['nombre']) ?>">
                    <?php else: ?>
                        <span class="text-muted small">Sin imagen</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td class="small"><?= htmlspecialchars($p['descripcion']) ?></td>
                <td class="text-end">$<?= number_format($p['precio'], 2) ?></td>
                <td class="text-center"><?= (int)$p['stock'] ?></td>
                <td class="text-center">
                    <a href="index.php?option=AdminProductos&edit_id=<?= $p['id'] ?>"
                       class="btn btn-sm btn-warning">Editar</a>
                    <form method="post" action="action.php" class="d-inline"
                          onsubmit="return confirm('¿Eliminar este producto?');">
                        <input type="hidden" name="accion" value="eliminar_producto">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
