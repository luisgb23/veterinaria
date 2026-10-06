<?php
namespace App\Models;
final class Especie
{
    public function __construct(private \mysqli $db) {}
    public function all(): array
    {
        return $this->db->query('SELECT * FROM especies WHERE EspecieEstado = 1 ORDER BY EspecieId')->fetch_all(MYSQLI_ASSOC);
    }
    public function find(int $id): ?array
    {
        $s = $this->db->prepare('SELECT * FROM especies WHERE EspecieId = ? AND EspecieEstado = 1');
        $s->bind_param('i', $id);
        $s->execute();
        return $s->get_result()->fetch_assoc();
    }
    public function create(string $name): void
    {
        $s = $this->db->prepare('INSERT INTO especies (EspecieNombre, EspecieFchCreacion, EspecieEstado) VALUES (?, NOW(), 1)');
        $s->bind_param('s', $name);
        $s->execute();
    }
    public function update(int $id, string $name): void
    {
        $s = $this->db->prepare('UPDATE especies SET EspecieNombre = ?, EspecieFchModificacion = NOW() WHERE EspecieId = ? AND EspecieEstado = 1');
        $s->bind_param('si', $name, $id);
        $s->execute();
    }
    public function delete(int $id): void
    {
        $s = $this->db->prepare('UPDATE especies SET EspecieEstado = 0 WHERE EspecieId = ? AND EspecieEstado = 1');
        $s->bind_param('i', $id);
        $s->execute();
    }
}
