<?php
session_start();

require_once "controller/auth_controller.php";
require_once "controller/carrito_controller.php";
require_once "controller/productos_controller.php";

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'login':
        AuthController::login();
        break;
    case 'logout':
        AuthController::logout();
        break;
    case 'agregar_carrito':
        CarritoController::agregar();
        break;
    case 'actualizar_carrito':
        CarritoController::actualizar();
        break;
    case 'eliminar_carrito':
        CarritoController::eliminar();
        break;
    case 'confirmar_compra':
        CarritoController::confirmarCompra();
        break;
    case 'guardar_producto':
        ProductosController::guardar();
        break;
    case 'actualizar_producto':
        ProductosController::actualizar();
        break;
    case 'eliminar_producto':
        ProductosController::eliminar();
        break;
    default:
        header('Location: index.php');
        exit;
}
?>
