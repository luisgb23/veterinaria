<?php
namespace App\Controllers;
use App\Http\View;
use App\Models\Database;
use App\Models\Especie;
final class EspecieController
{
    private Especie $model;
    public function __construct() { $this->model = new Especie(Database::connect()); }
    public function index(): void { View::render('especies/index', ['especies' => $this->model->all()]); }
    public function create(): void { View::render('especies/form', ['especie' => null, 'name' => '', 'error' => null]); }
    private function selected(mixed $value): ?array
    {
        $id = is_scalar($value) ? filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : false;
        $row = $id ? $this->model->find($id) : null;
        if (!$row) { http_response_code(404); echo 'Especie no encontrada'; }
        return $row;
    }
    public function edit(): void
    {
        if ($row = $this->selected($_GET['id'] ?? null)) View::render('especies/form', ['especie' => $row, 'name' => $row['EspecieNombre'], 'error' => null]);
    }
    private function save(?array $row): void
    {
        $name = is_string($_POST['txtNombre'] ?? null) ? trim($_POST['txtNombre']) : '';
        if ($name === '' || mb_strlen($name) > 100) {
            View::render('especies/form', ['especie' => $row, 'name' => $name, 'error' => 'La descripción debe tener entre 1 y 100 caracteres.'], 422);
            return;
        }
        if ($row) $this->model->update((int) $row['EspecieId'], $name);
        else $this->model->create($name);
        View::redirect('especies');
    }
    public function store(): void { $this->save(null); }
    public function update(): void
    {
        if ($row = $this->selected($_POST['txtId'] ?? null)) $this->save($row);
    }
    public function destroy(): void
    {
        if ($row = $this->selected($_POST['txtId'] ?? null)) {
            $this->model->delete((int) $row['EspecieId']);
            View::redirect('especies');
        }
    }
}
