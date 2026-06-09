<?php
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php?option=Nosotros');
    exit;
}
?>
<h3 class="mb-4">Reportes</h3>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Catálogo de productos</h5>
                <p class="card-text text-muted small">Listado completo de productos con precio y stock.</p>
                <a href="reportes/inventario_pdf.php" target="_blank" class="btn text-white" style="background:rgb(177,12,12);">
                    Ver PDF
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Productos con bajo stock</h5>
                <p class="card-text text-muted small">Productos cuyo stock está por debajo del umbral indicado.</p>
                <form method="get" action="reportes/bajo_stock_pdf.php" target="_blank" class="d-flex gap-2 align-items-end">
                    <div>
                        <label class="form-label small mb-0">Umbral de stock</label>
                        <input type="number" name="limite" value="5" min="1" class="form-control form-control-sm" style="width:90px;">
                    </div>
                    <button type="submit" class="btn text-white" style="background:rgb(177,12,12);">
                        Ver PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Ventas por rango de fechas</h5>
                <p class="card-text text-muted small">Listado de ventas y total recaudado en el periodo.</p>
                <form method="get" action="reportes/ventas_pdf.php" target="_blank" class="d-flex gap-2 align-items-end flex-wrap">
                    <div>
                        <label class="form-label small mb-0">Desde</label>
                        <input type="date" name="desde" value="<?= date('Y-m-01') ?>" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Hasta</label>
                        <input type="date" name="hasta" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn text-white" style="background:rgb(177,12,12);">
                        Ver PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Productos más vendidos</h5>
                <p class="card-text text-muted small">Ranking de productos por unidades vendidas e ingresos.</p>
                <form method="get" action="reportes/mas_vendidos_pdf.php" target="_blank" class="d-flex gap-2 align-items-end">
                    <div>
                        <label class="form-label small mb-0">Cantidad (top)</label>
                        <input type="number" name="limite" value="10" min="1" class="form-control form-control-sm" style="width:90px;">
                    </div>
                    <button type="submit" class="btn text-white" style="background:rgb(177,12,12);">
                        Ver PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
