<?php
require_once "models/usuario_model.php";

class AuthController
{
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['error_login'] = 'Completa todos los campos.';
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $usuario = UsuarioModel::buscarPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
            header('Location: index.php?option=Nosotros');
            exit;
        }

        $_SESSION['usuario_id']  = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['rol']         = $usuario['rol'];
        unset($_SESSION['error_login']);

        header('Location: index.php?option=Nosotros');
        exit;
    }

    public static function logout()
    {
        session_destroy();
        header('Location: index.php?option=Inicio');
        exit;
    }
}
?>
