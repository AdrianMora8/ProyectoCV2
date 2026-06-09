<?php
require_once "config/database.php";

class PedidoModel
{
    public static function crearVenta($con, $usuario_id, $total)
    {
        $fecha = date('Y-m-d H:i:s');
        $stmt  = mysqli_prepare($con, "INSERT INTO ventas (usuario_id, fecha, total) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isd", $usuario_id, $fecha, $total);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($con);
    }

    public static function insertarDetalle($con, $venta_id, $producto_id, $cantidad, $precio_unitario)
    {
        $subtotal = $cantidad * $precio_unitario;
        $stmt     = mysqli_prepare($con, "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiidd", $venta_id, $producto_id, $cantidad, $precio_unitario, $subtotal);
        return mysqli_stmt_execute($stmt);
    }

    public static function obtenerVentasPorRango($desde, $hasta)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "
            SELECT v.id, v.fecha, v.total, u.nombre AS cliente, u.email
            FROM ventas v
            JOIN usuarios u ON u.id = v.usuario_id
            WHERE DATE(v.fecha) BETWEEN ? AND ?
            ORDER BY v.fecha ASC
        ");
        mysqli_stmt_bind_param($stmt, "ss", $desde, $hasta);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $filas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $filas[] = $fila;
        }
        return $filas;
    }

    public static function obtenerMasVendidos($limite = 10)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "
            SELECT pr.id, pr.nombre,
                   SUM(dv.cantidad)  AS total_unidades,
                   SUM(dv.subtotal)  AS total_ingresos
            FROM detalle_venta dv
            JOIN productos pr ON pr.id = dv.producto_id
            GROUP BY pr.id, pr.nombre
            ORDER BY total_unidades DESC
            LIMIT ?
        ");
        mysqli_stmt_bind_param($stmt, "i", $limite);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $filas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $filas[] = $fila;
        }
        return $filas;
    }

    public static function obtenerVentaConDetalles($venta_id)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "
            SELECT v.id AS venta_id, v.fecha, v.total,
                   u.nombre AS cliente, u.email,
                   dv.cantidad, dv.precio_unitario, dv.subtotal,
                   pr.id AS producto_id, pr.nombre AS producto
            FROM ventas v
            JOIN usuarios u  ON u.id  = v.usuario_id
            JOIN detalle_venta dv ON dv.venta_id = v.id
            JOIN productos pr ON pr.id = dv.producto_id
            WHERE v.id = ?
            ORDER BY dv.id ASC
        ");
        mysqli_stmt_bind_param($stmt, "i", $venta_id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $filas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $filas[] = $fila;
        }
        return $filas;
    }
}
?>
