<?php
require_once "models/producto_model.php";

class ProductosController
{
    private static function subirImagen()
    {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE || $_FILES['imagen']['name'] === '') {
            return null;
        }

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $codigos = [
                UPLOAD_ERR_INI_SIZE   => 'Imagen demasiado grande (límite php.ini: 2MB).',
                UPLOAD_ERR_FORM_SIZE  => 'Imagen demasiado grande (límite formulario).',
                UPLOAD_ERR_PARTIAL    => 'La imagen se subió parcialmente.',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal del servidor.',
                UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir en disco.',
                UPLOAD_ERR_EXTENSION  => 'Una extensión PHP bloqueó la subida.',
            ];
            $code = $_FILES['imagen']['error'];
            $_SESSION['error_producto'] = $codigos[$code] ?? "Error de subida (código PHP: $code).";
            return false;
        }

        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensiones_permitidas)) {
            $_SESSION['error_producto'] = 'Solo se permiten imágenes JPG, PNG, GIF o WEBP.';
            return false;
        }

        $nombre_archivo = 'prod_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $dir_destino    = dirname(__DIR__) . '/image/productos/';
        $destino        = $dir_destino . $nombre_archivo;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $writable = is_writable($dir_destino) ? 'sí' : 'no';
            $tmp      = $_FILES['imagen']['tmp_name'];
            $_SESSION['error_producto'] = "move_uploaded_file falló. Carpeta escribible: $writable. Tmp: $tmp";
            return false;
        }

        return $nombre_archivo;
    }

    public static function guardar()
    {
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);

        if ($nombre === '' || $precio <= 0) {
            $_SESSION['error_producto'] = 'Nombre y precio son obligatorios.';
            header('Location: index.php?option=AdminProductos');
            exit;
        }

        $imagen = self::subirImagen();
        if ($imagen === false) {
            header('Location: index.php?option=AdminProductos');
            exit;
        }

        ProductoModel::insertar($nombre, $descripcion, $precio, $stock, $imagen);
        header('Location: index.php?option=AdminProductos');
        exit;
    }

    public static function actualizar()
    {
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $id          = (int)($_POST['id'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);

        if ($id <= 0 || $nombre === '' || $precio <= 0) {
            $_SESSION['error_producto'] = 'Datos inválidos.';
            header('Location: index.php?option=AdminProductos');
            exit;
        }

        $imagen = self::subirImagen();
        if ($imagen === false) {
            header('Location: index.php?option=AdminProductos');
            exit;
        }

        // Si no se subió imagen nueva, $imagen es null → no sobreescribe la existente
        ProductoModel::actualizar($id, $nombre, $descripcion, $precio, $stock, $imagen);
        header('Location: index.php?option=AdminProductos');
        exit;
    }

    public static function eliminar()
    {
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            ProductoModel::eliminar($id);
        }

        header('Location: index.php?option=AdminProductos');
        exit;
    }
}
?>
