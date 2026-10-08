<?php
namespace App\Controllers\Api;
use App\Http\ApiResponse as Response;
use App\Http\Csrf;
use App\Models\Database;
use App\Models\Usuario;
final class AuthController
{
    public static function current(): array
    {
        $id=$_SESSION['usuario_id'] ?? null;
        if(!is_int($id) && !(is_string($id)&&ctype_digit($id))) Response::error('Debes iniciar sesión.',401);
        $db=Database::connect();
        $s=$db->prepare('SELECT UsuarioId,UsuarioNombre,UsuarioUser FROM usuario WHERE UsuarioId=? AND UsuarioEstado=1');
        $s->execute([$id]); $user=$s->get_result()->fetch_assoc();
        if(!$user) Response::error('Debes iniciar sesión.',401);
        return ['id'=>(int)$user['UsuarioId'],'nombre'=>$user['UsuarioNombre'],'usuario'=>$user['UsuarioUser']];
    }
    public function csrf(): never { Response::json(['data'=>['csrf_token'=>Csrf::token()]]); }
    public function me(): never { Response::json(['data'=>self::current()]); }
    public function login(array $body): never
    {
        if(!is_string($body['usuario'] ?? null) || !is_string($body['password'] ?? null)) Response::error('Usuario y contraseña son obligatorios.',422);
        $user=(new Usuario(Database::connect()))->authenticate($body['usuario'],$body['password']);
        if(!$user) Response::error('Usuario o contraseña incorrectos.',401);
        session_regenerate_id(true);
        $_SESSION['usuario_id']=(int)$user['UsuarioId']; $_SESSION['nombre']=$user['UsuarioNombre']; unset($_SESSION['csrf']);
        Response::json(['data'=>self::current(),'csrf_token'=>Csrf::token()]);
    }
    public function logout(): never
    {
        $_SESSION=[]; session_destroy(); Response::empty();
    }
}
