<?php include "conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualización</title>
</head>
<body>
    <nav style="background-color: #f4f4f4; padding: 10px; margin-bottom: 20px;">
        <a href="select.php">Ver Estudiantes</a> | 
        <a href="../post.html">Ingresar</a> | 
        <a href="../update.html">Actualizar</a> | 
        <a href="../delete.html">Eliminar</a>
    </nav>
    <h2>Resultado de Actualización</h2>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Sentencia para actualizar
    $sqlUpdate = "UPDATE estudiantes SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion' WHERE cedula='$cedula'";

    if (mysqli_query($conexion, $sqlUpdate)) {
        echo "<p>Estudiante con cédula $cedula actualizado correctamente.</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conexion) . "</p>";
    }
} else {
    echo "<p>Por favor envíe los datos desde el formulario.</p>";
}
?>
</body>
</html>
