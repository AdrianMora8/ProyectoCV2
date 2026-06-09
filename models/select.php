<?php include "conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudiantes</title>
</head>
<body>
    <nav style="background-color: #f4f4f4; padding: 10px; margin-bottom: 20px;">
        <a href="select.php">Ver Estudiantes</a> | 
        <a href="../post.html">Ingresar</a> | 
        <a href="../update.html">Actualizar</a> | 
        <a href="../delete.html">Eliminar</a>
    </nav>
    <h2>Lista de Estudiantes</h2>
<?php
$sqlSelect = "SELECT * FROM estudiantes";
$ejecutar = mysqli_query($conexion, $sqlSelect);

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr style='background-color: #e0e0e0; color: #1a5276;'>
        <th>cedula</th>
        <th>nombre</th>
        <th>apellido</th>
        <th>telefono</th>
        <th>direccion</th>
      </tr>";

while($resultado = mysqli_fetch_assoc($ejecutar)){
    echo "<tr>";
    foreach ($resultado as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
</body>
</html>