<?php
$logueado = isset($_SESSION['usuario_id']);
$es_admin = $logueado && $_SESSION['rol'] === 'admin';
$es_cliente = $logueado && $_SESSION['rol'] === 'cliente';
$opcion_actual = $_GET['option'] ?? 'Inicio';
$opciones_nosotros = ['Login', 'Nosotros', 'Tienda', 'Carrito', 'Factura'];
$nosotros_activo = in_array($opcion_actual, $opciones_nosotros);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuarto Visual</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css?v=4">
</head>
<body>
    <header>
        <img src="image/banner.jpeg" alt="banner" width="100%" height="100px" style="object-fit:cover;">
    </header>
    <nav class="navbar navbar-expand-lg" style="background:rgb(177,12,12);">
        <div class="container-fluid justify-content-center">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold <?= $opcion_actual === 'Inicio' ? 'active-tab' : '' ?>"
                       href="index.php?option=Inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold <?= $nosotros_activo ? 'active-tab' : '' ?>"
                       href="index.php?option=Nosotros">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold <?= $opcion_actual === 'Servicios' ? 'active-tab' : '' ?>"
                       href="index.php?option=Servicios">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold <?= $opcion_actual === 'Contactanos' ? 'active-tab' : '' ?>"
                       href="index.php?option=Contactanos">Contáctanos</a>
                </li>

                <?php if ($es_admin): ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold <?= $opcion_actual === 'AdminProductos' ? 'active-tab' : '' ?>"
                       href="index.php?option=AdminProductos"
                       style="color:#ffd700;">Gestión Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold <?= $opcion_actual === 'AdminReportes' ? 'active-tab' : '' ?>"
                       href="index.php?option=AdminReportes"
                       style="color:#ffd700;">Reportes</a>
                </li>
                <?php endif; ?>

                <?php if ($logueado): ?>
                <li class="nav-item d-flex align-items-center ms-2 gap-2">
                    <span class="text-white small" style="opacity:0.75;">
                        <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                        <span class="badge ms-1" style="background:rgba(255,255,255,0.2);font-size:0.65rem;letter-spacing:0.03em;">
                            <?= $es_admin ? 'Admin' : 'Cliente' ?>
                        </span>
                    </span>
                    <form method="post" action="action.php" class="d-inline">
                        <input type="hidden" name="accion" value="logout">
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <article class="container my-4">
        <?php
        $mvc = new controllersEnlaces();
        $mvc->controladorEnlaces();
        ?>
    </article>

    <footer class="text-center py-3" style="background:rgb(177,12,12);color:azure;">
        <p class="mb-0">Derechos reservados &copy; 2024</p>
    </footer>
</body>
</html>
