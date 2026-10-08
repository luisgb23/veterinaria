<?php
namespace App\Controllers;
use App\Http\View;
use App\Models\Database;
use App\Models\Entity;
use App\Services\ArchivoService;
abstract class EntityController
{
    protected string $entity;
    private array $config;
    private Entity $model;
    public function __construct()
    {
        $this->config=(require dirname(__DIR__,2).'/config/entities.php')[$this->entity];
        $this->model=new Entity(Database::connect(),$this->entity,$this->config);
    }
    private function render(string $view,array $data=[],int $status=200): void
    {
        View::render('entities/'.$view,['entity'=>$this->entity,'config'=>$this->config]+$data,$status);
    }
    private function selected(mixed $value): ?array
    {
        $id=is_scalar($value)?filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]):false;
        $row=$id?$this->model->find($id):null;
        if(!$row) { http_response_code(404); echo 'Registro no encontrado'; }
        return $row;
    }
    private function options(?array $row): array
    {
        $options=[];
        foreach($this->config['fields'] as $f) if($f['relation']) $options[$f['column']]=$this->model->options($f['relation'],isset($row[$f['column']])?(int)$row[$f['column']]:null);
        return $options;
    }
    public function index(): void { $this->render('index',['rows'=>$this->model->all(),'options'=>$this->options(null)]); }
    public function create(): void { $this->form(null); }
    private function form(?array $row,array $values=[],array $errors=[],int $status=200): void
    {
        $this->render('form',['row'=>$row,'values'=>$values ?: ($row ?? []),'errors'=>$errors,'options'=>$this->options($row)],$status);
    }
    public function edit(): void { if($row=$this->selected($_GET['id'] ?? null)) $this->form($row); }
    public function show(): void { if($row=$this->selected($_GET['id'] ?? null)) $this->render('show',['row'=>$row,'options'=>$this->options($row)]); }
    public function store(): void { $this->save(null); }
    public function update(): void { if($row=$this->selected($_POST['txtId'] ?? null)) $this->save($row); }
    private function save(?array $row): void
    {
        $values=[]; $errors=[]; $options=$this->options($row);
        foreach($this->config['fields'] as $f) {
            $raw=$_POST[$f['input']] ?? ''; $v=is_string($raw)?trim($raw):'';
            $values[$f['column']]=$v===''?null:$v;
            $invalid=(!is_string($raw)) || ($f['required'] && $v==='');
            if($v!=='') {
                if(in_array($f['type'],['text','email','textarea'],true) && mb_strlen($v)>$f['limit']) $invalid=true;
                if($f['type']==='date') { $d=\DateTimeImmutable::createFromFormat('!Y-m-d',$v); $invalid=$invalid || !$d || $d->format('Y-m-d')!==$v; }
                elseif($f['type']==='email') $invalid=$invalid || !filter_var($v,FILTER_VALIDATE_EMAIL);
                elseif($f['type']==='number') $invalid=$invalid || !ctype_digit($v) || strlen($v)>18;
                elseif($f['type']==='select') {
                    $ids=array_column($options[$f['column']],$f['relation'][1].'Id');
                    $invalid=$invalid || !ctype_digit($v) || !in_array((int)$v,$ids);
                }
                else $invalid=$invalid || mb_strlen($v)>$f['limit'];
            }
            if($invalid) $errors[$f['column']]='Revisa el campo '.$f['label'].'.';
        }
        if($this->entity==='vacunas' && !$errors && $values['VacunaFchVenc']<$values['VacunaFchIngreso']) $errors['VacunaFchVenc']='El vencimiento no puede ser anterior al ingreso.';
        if($this->entity==='mascotas' && !$errors && $values['MascotaFchNac']>date('Y-m-d')) $errors['MascotaFchNac']='La fecha de nacimiento no puede ser futura.';
        if($errors) { $this->form($row,$values,$errors,422); return; }
        $uploaded=[]; $files=new ArchivoService();
        try {
            if($this->entity==='consultas') { $uploaded=$files->store($_FILES); $values+=$uploaded; }
            $this->model->save($values,$row?(int)$row[$this->config['prefix'].'Id']:null);
        } catch(\InvalidArgumentException $e) { $files->remove($uploaded); $this->form($row,$values,['attachments'=>$e->getMessage()],422); return; }
        catch(\Throwable $e) { $files->remove($uploaded); throw $e; }
        View::redirect($this->entity);
    }
    public function destroy(): void
    {
        if($row=$this->selected($_POST['txtId'] ?? null)) { $this->model->delete((int)$row[$this->config['prefix'].'Id']); View::redirect($this->entity); }
    }
    public function history(): void { $this->render('history',['rows'=>$this->model->history()]); }
    public function pdf(): void
    {
        if(!$row=$this->selected($_GET['id'] ?? null)) return;
        $options=$this->options($row); $config=$this->config;
        ob_start(); require dirname(__DIR__,2).'/views/consultas/pdf.php'; $html=ob_get_clean();
        (new \App\Services\PdfService())->output($html);
    }
    public function download(): void
    {
        if(!$row=$this->selected($_GET['id'] ?? null)) return;
        $slot=$_GET['slot'] ?? '';
        if(!is_string($slot) || !in_array($slot,['1','2','3'],true) || !$path=(new ArchivoService())->path($row['ConsultaArchivo'.$slot] ?? '')) { http_response_code(404); echo 'Adjunto no encontrado'; return; }
        header('Content-Type: application/octet-stream'); header('X-Content-Type-Options: nosniff');
        $extension=strtolower(pathinfo($path,PATHINFO_EXTENSION));
        if(!in_array($extension,['pdf','png','jpg','jpeg'],true)) $extension='bin';
        header('Content-Disposition: attachment; filename="adjunto-'.(int)$slot.'.'.$extension.'"');
        readfile($path);
    }
}
