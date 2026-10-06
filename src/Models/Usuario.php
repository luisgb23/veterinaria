<?php
namespace App\Models;
final class Usuario
{
    public function __construct(private \mysqli $db) {}
    public function authenticate(string $username, string $password): ?array
    {
        $s = $this->db->prepare('SELECT UsuarioId, UsuarioNombre, UsuarioPwd FROM usuario WHERE UsuarioUser = ? AND UsuarioEstado = 1 LIMIT 1');
        $s->bind_param('s', $username);
        $s->execute();
        $user = $s->get_result()->fetch_assoc();
        if (!$user) return null;
        $hash = $user['UsuarioPwd'];
        $legacy = preg_match('/^[a-f0-9]{40}$/i', $hash) === 1;
        if (!($legacy ? hash_equals(strtolower($hash), sha1($password)) : password_verify($password, $hash))) return null;
        if ($legacy || password_needs_rehash($hash, PASSWORD_BCRYPT)) {
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $s = $this->db->prepare('UPDATE usuario SET UsuarioPwd = ? WHERE UsuarioId = ?');
            $s->bind_param('si', $newHash, $user['UsuarioId']);
            $s->execute();
        }
        return $user;
    }
}
