<?php
require_once "models/producto_model.php";
$logueado_nosotros = isset($_SESSION['usuario_id']);
$es_cliente = isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';

if (!$logueado_nosotros):
    $error = $_SESSION['error_login'] ?? '';
    unset($_SESSION['error_login']);
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header text-white text-center" style="background:rgb(177,12,12);">
                <h4 class="mb-0">Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post" action="action.php">
                    <input type="hidden" name="accion" value="login">
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100 text-white" style="background:rgb(177,12,12);">
                        Entrar
                    </button>
                </form>
                <hr>
                <p class="text-muted small text-center mb-0">
                    Admin: admin@tienda.com / admin123<br>
                    Cliente: cliente@tienda.com / cliente123
                </p>
            </div>
        </div>
    </div>
</div>
<?php else:
    $productos = ProductoModel::obtenerTodos();
    $total_items = 0;
    if (!empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $item) {
            $total_items += $item['cantidad'];
        }
    }
    $mensaje = $_SESSION['mensaje_carrito'] ?? '';
    unset($_SESSION['mensaje_carrito']);
    $aviso = $_SESSION['aviso_carrito'] ?? '';
    unset($_SESSION['aviso_carrito']);
?>
<?php if ($mensaje): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>
<?php if ($aviso): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($aviso) ?></div>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Tienda</h3>
    <?php if ($es_cliente): ?>
    <a href="index.php?option=Carrito" class="btn text-white position-relative" style="background:rgb(177,12,12);">
        Carrito
        <?php if ($total_items > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                <?= $total_items ?>
            </span>
        <?php endif; ?>
    </a>
    <?php endif; ?>
</div>

<?php if (empty($productos)): ?>
    <p class="text-muted">No hay productos disponibles.</p>
<?php else: ?>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($productos as $p): ?>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <?php if (!empty($p['imagen'])): ?>
                <img src="image/productos/<?= htmlspecialchars($p['imagen']) ?>"
                     class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>"
                     style="height:200px;object-fit:cover;">
            <?php else: ?>
                <div class="bg-secondary d-flex align-items-center justify-content-center"
                     style="height:200px;">
                    <span class="text-white">Sin imagen</span>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
                <p class="card-text text-muted small"><?= htmlspecialchars($p['descripcion']) ?></p>
                <p class="fw-bold fs-5 mb-1" style="color:rgb(177,12,12);">
                    $<?= number_format($p['precio'], 2) ?>
                </p>
                <p class="small text-secondary mb-0">Stock: <?= (int)$p['stock'] ?> unidades</p>
            </div>

            <?php if ($es_cliente && $p['stock'] > 0): ?>
            <div class="card-footer bg-white border-top-0">
                <form method="post" action="action.php">
                    <input type="hidden" name="accion" value="agregar_carrito">
                    <input type="hidden" name="producto_id" value="<?= $p['id'] ?>">
                    <div class="input-group input-group-sm mb-1">
                        <input type="number"
                               name="cantidad"
                               value="1"
                               min="1"
                               max="<?= (int)$p['stock'] ?>"
                               class="form-control input-cantidad-tienda"
                               data-precio="<?= $p['precio'] ?>">
                        <button type="submit" class="btn btn-sm text-white"
                                style="background:rgb(177,12,12);">
                            Agregar al carrito
                        </button>
                    </div>
                    <small class="subtotal-tienda text-success fw-semibold"></small>
                </form>
            </div>
            <?php elseif ($p['stock'] == 0): ?>
            <div class="card-footer bg-white">
                <span class="badge bg-secondary">Sin stock</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script src="js/carrito.js"></script>
<?php endif; ?>
