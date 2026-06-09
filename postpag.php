<?php
$nombre=$_POST["valor"];
echo "hola $nombre";
echo "<br>";
$n1=$_POST["n1"];
$n2=$_POST["n2"];
$op=$_POST["op"];
$resultado;

switch ($op) {
    case 'suma':
        $resultado = $n1 + $n2;
        echo "El resultado de la suma es: $resultado";
        break;
    
    default:
        echo "Operación no válida";
        break;
}
?>