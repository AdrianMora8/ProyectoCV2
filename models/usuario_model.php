<?php
require_once "config/database.php";

class UsuarioModel
{
    public static function buscarPorEmail($email)
    {
        $con  = getConexion();
        $stmt = mysqli_prepare($con, "SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }
}
?>
