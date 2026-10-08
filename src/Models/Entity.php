<?php
namespace App\Models;
/** Metadata is trusted configuration, never supplied by the request. */
final class Entity
{
    public function __construct(private \mysqli $db, private string $table, private array $config) {}
    private function id(): string { return $this->config['prefix'] . 'Id'; }
    private function state(): string { return $this->config['prefix'] . 'Estado'; }
    public function all(): array
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->state()}=1 ORDER BY {$this->id()} DESC")->fetch_all(MYSQLI_ASSOC);
    }
    public function find(int $id): ?array
    {
        $s=$this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->id()}=? AND {$this->state()}=1");
        $s->execute([$id]); return $s->get_result()->fetch_assoc();
    }
    public function options(array $relation, ?int $existing = null): array
    {
        [$table,$prefix]=$relation;
        // Preserve existing inactive relations when editing; disallow new ones in the controller.
        $s=$this->db->prepare("SELECT * FROM $table WHERE {$prefix}Estado=1 OR {$prefix}Id=? ORDER BY {$prefix}Nombre");
        $s->execute([$existing ?? 0]); return $s->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function save(array $values, ?int $id): int
    {
        $columns=array_keys($values); $args=array_values($values);
        if ($id) {
            $sets=implode(', ',array_map(fn($c)=>"$c=?",$columns));
            $timestamp=$this->config['prefix'].'FchModificacion';
            $s=$this->db->prepare("UPDATE {$this->table} SET $sets, $timestamp=NOW() WHERE {$this->id()}=? AND {$this->state()}=1");
            $args[]=$id;
        } else {
            $columns[]=$this->config['prefix'].'FchCreacion'; $columns[]=$this->state();
            $sqlColumns=implode(', ',$columns); $marks=implode(', ',array_fill(0,count($values),'?'));
            $s=$this->db->prepare("INSERT INTO {$this->table} ($sqlColumns) VALUES ($marks, NOW(), 1)");
        }
        $s->execute($args); return $id ?? (int)$this->db->insert_id;
    }
    public function delete(int $id): void
    {
        $s=$this->db->prepare("UPDATE {$this->table} SET {$this->state()}=0 WHERE {$this->id()}=?"); $s->execute([$id]);
    }
    public function history(): array
    {
        return $this->db->query('SELECT v.*, m.MascotaNombre, p.PropietarioNombre, p.PropietarioApellido, p.PropietarioTelefono FROM vacunas v JOIN mascotas m ON v.MascotaId=m.MascotaId JOIN propietarios p ON m.PropietarioId=p.PropietarioId WHERE v.VacunaEstado=1 ORDER BY v.VacunaFchVenc')->fetch_all(MYSQLI_ASSOC);
    }
}
