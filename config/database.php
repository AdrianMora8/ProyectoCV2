<?php
date_default_timezone_set('America/Guayaquil');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'cv');

function getConexion()
{
    $port = (int)(getenv('DB_PORT') ?: 3306);
    $conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, $port);
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    if (getenv('DB_SSL') === 'true') {
        mysqli_ssl_set($conexion, null, null, null, null, null);
    }
    mysqli_set_charset($conexion, 'utf8');
    return $conexion;
}
?>
