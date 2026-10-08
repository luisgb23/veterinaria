<?php
namespace App\Controllers\Api;
use App\Http\ApiResponse as Response;
use App\Models\Database;
use App\Models\Entity;
use App\Services\EntityValidator;
use App\Services\ArchivoService;
final class EntityController
{
    private \mysqli $db;
    private array $config;
    private array $fields;
    private Entity $model;
    public function __construct(private string $entity)
    {
        $this->db=Database::connect();
        $entities=require dirname(__DIR__,3).'/config/entities.php';
        $entities['especies']=['prefix'=>'Especie','fields'=>[['column'=>'EspecieNombre','label'=>'Nombre','type'=>'text','limit'=>100,'relation'=>null,'required'=>true]]];
        $this->config=$entities[$entity];
        $this->fields=(require dirname(__DIR__,3).'/config/api_fields.php')[$entity];
        $this->model=new Entity($this->db,$entity,$this->config);
    }
    private function idColumn(): string { return $this->config['prefix'].'Id'; }
    private function selected(int $id): array
    {
        $row=$this->model->find($id);
        if(!$row) Response::error('Registro no encontrado.',404);
        return $row;
    }
    private function serialize(array $row): array
    {
        $data=['id'=>(int)$row[$this->idColumn()]];
        foreach($this->fields as $public=>$column) {
            $v=$row[$column] ?? null;
            $data[$public]=str_ends_with($public,'_id')&&$v!==null?(int)$v:$v;
            if($this->entity==='cuotas' && $public==='valor' && $v!==null) $data[$public]=(string)$v;
        }
        foreach($this->config['fields'] as $f) if($f['relation'] && !empty($row[$f['column']])) {
            [$table,$prefix]=$f['relation'];
            $s=$this->db->prepare("SELECT * FROM $table WHERE {$prefix}Id=?"); $s->execute([$row[$f['column']]]); $related=$s->get_result()->fetch_assoc();
            $public=array_search($f['column'],$this->fields,true);
            if($related && $public) $data[substr($public,0,-3)]=['id'=>(int)$related[$prefix.'Id'],'nombre'=>$related[$prefix.'Nombre']]+(isset($related[$prefix.'Apellido'])?['apellido'=>$related[$prefix.'Apellido']]:[]);
        }
        if($this->entity==='consultas') {
            $data['adjuntos']=[];
            for($i=1;$i<=3;$i++) if(!empty($row['ConsultaArchivo'.$i])) $data['adjuntos'][]=['slot'=>$i,'url'=>\App\Http\Url::to('api/v1/consultas/'.$data['id'].'/adjuntos/'.$i)];
        }
        return $data;
    }
    private function positive(mixed $v,string $name,int $max=PHP_INT_MAX): int
    {
        $n=is_scalar($v)?filter_var($v,FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>$max]]):false;
        if($n===false) Response::error('Filtro inválido.',422,[$name=>['Debe ser un entero positivo.']]);
        return $n;
    }
    public function index(array $query): never
    {
        $page=$this->positive($query['page'] ?? 1,'page',1000000);
        $per=$this->positive($query['per_page'] ?? 20,'per_page',100);
        $where=[$this->config['prefix'].'Estado=1']; $args=[];
        $allowed=['page','per_page','buscar','desde','hasta','vencidas'];
        foreach($this->fields as $public=>$col) if(str_ends_with($public,'_id')) {
            $allowed[]=$public;
            if(isset($query[$public])) { $where[]="$col=?"; $args[]=$this->positive($query[$public],$public); }
        }
        foreach($query as $key=>$value) if(!in_array($key,$allowed,true)) Response::error('Filtro desconocido.',422,[$key=>['Filtro no admitido.']]);
        if(isset($query['buscar'])) {
            if(!is_string($query['buscar']) || mb_strlen($query['buscar'])>100) Response::error('Búsqueda inválida.',422);
            $search=[];
            foreach($this->config['fields'] as $f) if(in_array($f['type'],['text','email','textarea'],true)) { $search[]=$f['column'].' LIKE ?'; $args[]='%'.$query['buscar'].'%'; }
            if(!$search) Response::error('Esta entidad no admite búsqueda de texto.',422);
            $where[]='('.implode(' OR ',$search).')';
        }
        $dateColumn=['cuotas'=>'CuotaFecha','consultas'=>'ConsultaFecha','vacunas'=>'VacunaFchVenc'][$this->entity] ?? null;
        foreach(['desde'=>'>=','hasta'=>'<='] as $key=>$operator) if(isset($query[$key])) {
            $value=$query[$key]; $date=is_string($value)?\DateTimeImmutable::createFromFormat('!Y-m-d',$value):false;
            if(!$dateColumn||!$date||$date->format('Y-m-d')!==$value) Response::error('Filtro de fecha inválido.',422,[$key=>['Usa YYYY-MM-DD en cuotas, consultas o vacunas.']]);
            $where[]="$dateColumn $operator ?"; $args[]=$value;
        }
        if(isset($query['vencidas'])) {
            if($this->entity!=='vacunas' || !in_array($query['vencidas'],['0','1'],true)) Response::error('Filtro vencidas inválido.',422);
            $where[]='VacunaFchVenc '.($query['vencidas']==='1'?'<':'>=').' ?'; $args[]=date('Y-m-d');
        }
        $condition=implode(' AND ',$where);
        $s=$this->db->prepare("SELECT COUNT(*) n FROM {$this->entity} WHERE $condition"); $s->execute($args); $total=(int)$s->get_result()->fetch_assoc()['n'];
        $s=$this->db->prepare("SELECT * FROM {$this->entity} WHERE $condition ORDER BY {$this->idColumn()} DESC LIMIT ? OFFSET ?");
        $s->execute([...$args,$per,($page-1)*$per]);
        $rows=$s->get_result()->fetch_all(MYSQLI_ASSOC);
        Response::json(['data'=>array_map(fn($r)=>$this->serialize($r),$rows),'meta'=>['page'=>$page,'per_page'=>$per,'total'=>$total,'last_page'=>max(1,(int)ceil($total/$per))]]);
    }
    public function show(int $id): never { Response::json(['data'=>$this->serialize($this->selected($id))]); }
    public function save(array $body,?int $id=null): never
    {
        $row=$id?$this->selected($id):null; $input=[]; $errors=[];
        foreach($body as $key=>$v) if(!isset($this->fields[$key])) $errors[$key]=['Campo no admitido.'];
        if(!$body && $id) $errors['body']=['Envía al menos un campo.'];
        foreach($this->fields as $public=>$column) $input[$column]=array_key_exists($public,$body)?$body[$public]:($row[$column] ?? null);
        [$values,$invalid]=(new EntityValidator())->validate($this->entity,$this->config,$this->model,$input,$row);
        foreach($invalid as $column=>$message) $errors[array_search($column,$this->fields,true)]=[$message];
        if($errors) Response::error('Revisa los datos enviados.',422,$errors);
        $newId=$this->model->save($values,$id);
        if(!$id) header('Location: '.\App\Http\Url::to('api/v1/'.$this->entity.'/'.$newId));
        Response::json(['data'=>$this->serialize($this->selected($newId))],$id?200:201);
    }
    public function delete(int $id): never { $this->selected($id); $this->model->delete($id); Response::empty(); }
    public function pdf(int $id): void
    {
        $row=$this->selected($id); $config=$this->config; $options=[];
        foreach($config['fields'] as $f) if($f['relation']) $options[$f['column']]=$this->model->options($f['relation'],(int)$row[$f['column']]);
        ob_start(); require dirname(__DIR__,3).'/views/consultas/pdf.php'; $html=ob_get_clean();
        (new \App\Services\PdfService())->output($html);
    }
    public function upload(int $id): never
    {
        $this->selected($id);
        if(!str_starts_with(strtolower($_SERVER['CONTENT_TYPE'] ?? ''),'multipart/form-data')) Response::error('Envía multipart/form-data.',415);
        $slot=$this->positive($_POST['slot'] ?? null,'slot',3);
        if(!isset($_FILES['archivo'])) Response::error('Adjunto obligatorio.',422,['archivo'=>['Envía un archivo.']]);
        $service=new ArchivoService(); $uploaded=[];
        try {
            $uploaded=$service->store(['archivo'.$slot=>$_FILES['archivo']]);
            if(!$uploaded) Response::error('Adjunto obligatorio.',422);
            $this->model->save($uploaded,$id);
        } catch(\InvalidArgumentException $e) { $service->remove($uploaded); Response::error($e->getMessage(),422,['archivo'=>[$e->getMessage()]]); }
        catch(\Throwable $e) { $service->remove($uploaded); throw $e; }
        Response::json(['data'=>$this->serialize($this->selected($id))],201);
    }
    public function attachment(int $id,int $slot,bool $delete): void
    {
        $row=$this->selected($id); $column='ConsultaArchivo'.$slot;
        if(empty($row[$column])) Response::error('Adjunto no encontrado.',404);
        if($delete) { $this->model->save([$column=>null],$id); Response::empty(); }
        $path=(new ArchivoService())->path($row[$column]);
        if(!$path) Response::error('Adjunto no encontrado.',404);
        $ext=strtolower(pathinfo($path,PATHINFO_EXTENSION)); if(!in_array($ext,['pdf','jpg','jpeg','png'],true)) $ext='bin';
        header('Content-Type: application/octet-stream'); header('Cache-Control: no-store'); header('X-Content-Type-Options: nosniff');
        header('Content-Disposition: attachment; filename="adjunto-'.$slot.'.'.$ext.'"'); readfile($path);
    }
    public function history(array $query): never
    {
        // Paginated vaccines include patient/owner contact details for this report.
        $page=$this->positive($query['page'] ?? 1,'page',1000000); $per=$this->positive($query['per_page'] ?? 20,'per_page',100);
        $where=['v.VacunaEstado=1']; $args=[];
        foreach($query as $key=>$v) if(!in_array($key,['page','per_page','mascota_id','desde','hasta','vencidas'],true)) Response::error('Filtro desconocido.',422);
        if(isset($query['mascota_id'])) { $where[]='v.MascotaId=?'; $args[]=$this->positive($query['mascota_id'],'mascota_id'); }
        foreach(['desde'=>'>=','hasta'=>'<='] as $key=>$operator) if(isset($query[$key])) {
            $v=$query[$key]; $d=is_string($v)?\DateTimeImmutable::createFromFormat('!Y-m-d',$v):false;
            if(!$d||$d->format('Y-m-d')!==$v) Response::error('Fecha inválida.',422);
            $where[]='v.VacunaFchVenc '.$operator.' ?'; $args[]=$v;
        }
        if(isset($query['vencidas'])) { if(!in_array($query['vencidas'],['0','1'],true)) Response::error('Filtro vencidas inválido.',422); $where[]='v.VacunaFchVenc '.($query['vencidas']==='1'?'<':'>=').' ?'; $args[]=date('Y-m-d'); }
        $condition=implode(' AND ',$where);
        $s=$this->db->prepare("SELECT COUNT(*) n FROM vacunas v JOIN mascotas m ON v.MascotaId=m.MascotaId LEFT JOIN propietarios p ON m.PropietarioId=p.PropietarioId WHERE $condition");$s->execute($args);$total=(int)$s->get_result()->fetch_assoc()['n'];
        $s=$this->db->prepare("SELECT v.*,p.PropietarioTelefono FROM vacunas v JOIN mascotas m ON v.MascotaId=m.MascotaId LEFT JOIN propietarios p ON m.PropietarioId=p.PropietarioId WHERE $condition ORDER BY v.VacunaFchVenc,v.VacunaId LIMIT ? OFFSET ?");
        $s->execute([...$args,$per,($page-1)*$per]);$data=[];
        foreach($s->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $item=$this->serialize($row);$item['vencida']=$row['VacunaFchVenc']<date('Y-m-d'); $item['telefono_propietario']=$row['PropietarioTelefono']; $data[]=$item;
        }
        Response::json(['data'=>$data,'meta'=>['page'=>$page,'per_page'=>$per,'total'=>$total,'last_page'=>max(1,(int)ceil($total/$per))]]);
    }
}
