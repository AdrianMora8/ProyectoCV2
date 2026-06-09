<?php include "conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminación</title>
</head>
<body>
    <nav style="background-color: #f4f4f4; padding: 10px; margin-bottom: 20px;">
        <a href="select.php">Ver Estudiantes</a> | 
        <a href="../post.html">Ingresar</a> | 
        <a href="../update.html">Actualizar</a> | 
        <a href="../delete.html">Eliminar</a>
    </nav>
    <h2>Resultado de Eliminación</h2>
<?php
if (isset($_REQUEST['cedula'])) {
    $cedula = $_REQUEST['cedula'];

    $sqlDelete = "DELETE FROM estudiantes WHERE cedula = '$cedula'";

    if (mysqli_query($conexion, $sqlDelete)) {
        echo "<p>Estudiante con cédula $cedula eliminado correctamente.</p>";
    } else {
        echo "<p>Error al eliminar: " . mysqli_error($conexion) . "</p>";
    }
} else {
    echo "<p>Por favor, envíe la cédula del estudiante que desea eliminar.</p>";
}
?>
</body>
</html>
