<?php
class modeloEnlaces
{
    private static $paginas_publicas = ['Inicio', 'Nosotros', 'Servicios', 'Contactanos'];
    private static $paginas_auth     = [];
    private static $paginas_admin    = ['AdminProductos', 'AdminReportes'];

    public static function EnlacesPaginas($opcion)
    {
        if (in_array($opcion, ['Login', 'Tienda', 'Carrito', 'Factura'])) {
            return "views/nosotros.php";
        }

        if (in_array($opcion, self::$paginas_publicas)) {
            return "views/" . strtolower($opcion) . ".php";
        }

        if (in_array($opcion, self::$paginas_auth)) {
            if (!isset($_SESSION['usuario_id'])) {
                return "views/nosotros.php";
            }
            return "views/" . strtolower($opcion) . ".php";
        }

        if (in_array($opcion, self::$paginas_admin)) {
            if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
                return "views/nosotros.php";
            }
            return "views/" . strtolower($opcion) . ".php";
        }

        return "views/inicio.php";
    }
}
?>
