<?php
require_once "config/database.php";

class ProductoModel
{
    public static function obtenerTodos()
    {
        $con = getConexion();
        $resultado = mysqli_query($con, "SELECT * FROM productos ORDER BY id ASC");
        $productos = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        return $productos;
    }

    public static function obtenerConBajoStock($limite = 5)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "SELECT * FROM productos WHERE stock <= ? ORDER BY stock ASC, nombre ASC");
        mysqli_stmt_bind_param($stmt, "i", $limite);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $productos = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        return $productos;
    }

    public static function obtenerPorId($id)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "SELECT * FROM productos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    public static function insertar($nombre, $descripcion, $precio, $stock, $imagen)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdis", $nombre, $descripcion, $precio, $stock, $imagen);
        return mysqli_stmt_execute($stmt);
    }

    public static function actualizar($id, $nombre, $descripcion, $precio, $stock, $imagen)
    {
        $con  = getConexion();
        if ($imagen !== null) {
            $stmt = mysqli_prepare($con, "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssdisi", $nombre, $descripcion, $precio, $stock, $imagen, $id);
        } else {
            $stmt = mysqli_prepare($con, "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssdii", $nombre, $descripcion, $precio, $stock, $id);
        }
        return mysqli_stmt_execute($stmt);
    }

    public static function eliminar($id)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "DELETE FROM productos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    // Resta stock de forma atómica; falla (sin afectar filas) si no alcanza
    public static function descontarStock($con, $id, $cantidad)
    {
        $stmt = mysqli_prepare($con, "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
        mysqli_stmt_bind_param($stmt, "iii", $cantidad, $id, $cantidad);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt) > 0;
    }
}
?>
