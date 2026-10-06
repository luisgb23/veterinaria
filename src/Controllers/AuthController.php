<?php
namespace App\Controllers;
use App\Http\View;
use App\Models\Database;
use App\Models\Usuario;
final class AuthController
{
    public function form(): void { View::render('auth/login', ['error' => null]); }
    public function login(): void
    {
        $username = $_POST['txtUser'] ?? null;
        $password = $_POST['txtPwd'] ?? null;
        $user = is_string($username) && is_string($password) ? (new Usuario(Database::connect()))->authenticate($username, $password) : null;
        if (!$user) { View::render('auth/login', ['error' => 'Usuario o contraseña incorrectos.'], 422); return; }
        session_regenerate_id(true);
        unset($_SESSION['csrf']);
        $_SESSION['nombre'] = $user['UsuarioNombre'];
        $_SESSION['usuario_id'] = $user['UsuarioId'];
        View::redirect('especies');
    }
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        View::redirect('login');
    }
}
