<?php
require_once "models/producto_model.php";
require_once "models/pedido_model.php";
require_once "config/database.php";

class CarritoController
{
    public static function agregar()
    {
        $producto_id = (int)($_POST['producto_id'] ?? 0);
        $cantidad    = (int)($_POST['cantidad'] ?? 1);

        if ($producto_id <= 0 || $cantidad <= 0) {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $producto = ProductoModel::obtenerPorId($producto_id);
        if (!$producto) {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $actual  = $_SESSION['carrito'][$producto_id]['cantidad'] ?? 0;
        $deseada = $actual + $cantidad;
        $final   = min($deseada, $producto['stock']);

        if (isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id]['cantidad'] = $final;
        } else {
            $_SESSION['carrito'][$producto_id] = [
                'nombre'   => $producto['nombre'],
                'precio'   => $producto['precio'],
                'cantidad' => $final,
            ];
        }

        if ($final < $deseada) {
            $_SESSION['aviso_carrito'] = "Solo se agregaron {$final} unidad(es) de \"{$producto['nombre']}\" por disponibilidad de stock.";
        } else {
            $_SESSION['mensaje_carrito'] = "\"{$producto['nombre']}\" se agregó al carrito.";
        }

        header('Location: index.php?option=Nosotros');
        exit;
    }

    public static function actualizar()
    {
        $producto_id = (int)($_POST['producto_id'] ?? 0);
        $cantidad    = (int)($_POST['cantidad'] ?? 0);

        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$producto_id]);
        } else {
            $producto = ProductoModel::obtenerPorId($producto_id);
            if ($producto) {
                $final = min($cantidad, $producto['stock']);
                $_SESSION['carrito'][$producto_id]['cantidad'] = $final;

                if ($final < $cantidad) {
                    $_SESSION['aviso_carrito'] = "Solo se ajustó a {$final} unidad(es) de \"{$producto['nombre']}\" por disponibilidad de stock.";
                }
            }
        }

        header('Location: index.php?option=Nosotros');
        exit;
    }

    public static function eliminar()
    {
        $producto_id = (int)($_POST['producto_id'] ?? 0);
        unset($_SESSION['carrito'][$producto_id]);
        header('Location: index.php?option=Nosotros');
        exit;
    }

    public static function confirmarCompra()
    {
        if (empty($_SESSION['carrito'])) {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        // Revalida cada ítem contra el producto actual: precio y stock pueden
        // haber cambiado desde que se agregó al carrito.
        $items = [];
        $total = 0;
        $hubo_ajustes = false;

        foreach ($_SESSION['carrito'] as $producto_id => $item) {
            $producto = ProductoModel::obtenerPorId($producto_id);
            if (!$producto) {
                $hubo_ajustes = true;
                continue;
            }

            $cantidad = min($item['cantidad'], $producto['stock']);
            if ($cantidad <= 0) {
                $hubo_ajustes = true;
                continue;
            }
            if ($cantidad < $item['cantidad'] || $producto['precio'] != $item['precio']) {
                $hubo_ajustes = true;
            }

            $items[] = [
                'producto_id' => $producto_id,
                'cantidad'    => $cantidad,
                'precio'      => $producto['precio'],
            ];
            $total += $producto['precio'] * $cantidad;
        }

        if (empty($items)) {
            $_SESSION['carrito']       = [];
            $_SESSION['error_carrito'] = 'Los productos de tu carrito ya no están disponibles.';
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $con = getConexion();
        mysqli_begin_transaction($con);

        try {
            $venta_id = PedidoModel::crearVenta($con, $_SESSION['usuario_id'], $total);

            foreach ($items as $item) {
                if (!ProductoModel::descontarStock($con, $item['producto_id'], $item['cantidad'])) {
                    throw new RuntimeException('Stock insuficiente.');
                }
                PedidoModel::insertarDetalle($con, $venta_id, $item['producto_id'], $item['cantidad'], $item['precio']);
            }

            mysqli_commit($con);
        } catch (Throwable $e) {
            mysqli_rollback($con);
            $_SESSION['error_carrito'] = 'No se pudo completar la compra, intenta de nuevo.';
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $_SESSION['carrito']       = [];
        $_SESSION['ultimo_pedido'] = $venta_id;

        if ($hubo_ajustes) {
            $_SESSION['aviso_carrito'] = 'Algunos productos se ajustaron por cambios de precio o stock antes de confirmar tu compra.';
        }

        $_SESSION['abrir_factura_pdf'] = true;
        header('Location: index.php?option=Nosotros');
        exit;
    }
}
?>
