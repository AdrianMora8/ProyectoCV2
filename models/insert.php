<?php include "conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso</title>
</head>
<body>
    <nav style="background-color: #f4f4f4; padding: 10px; margin-bottom: 20px;">
        <a href="select.php">Ver Estudiantes</a> | 
        <a href="../post.html">Ingresar</a> | 
        <a href="../update.html">Actualizar</a> | 
        <a href="../delete.html">Eliminar</a>
    </nav>
    <h2>Resultado de Ingreso</h2>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sqlInsert = "INSERT INTO estudiantes (cedula, nombre, apellido, telefono, direccion) VALUES ('$cedula', '$nombre', '$apellido', '$telefono', '$direccion')";

    if (mysqli_query($conexion, $sqlInsert)) {
        echo "<p>Registro insertado correctamente.</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conexion) . "</p>";
    }
} else {
    echo "<p>Por favor, envíe los datos a través de un formulario (POST).</p>";
}
?>
</body>
</html>
