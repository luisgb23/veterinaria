<?php
namespace App\Http;
use App\Controllers\Api\AuthController;
use App\Controllers\Api\EntityController;
use App\Models\Database;
final class ApiRouter
{
    private function cors(): void
    {
        $origin=$_SERVER['HTTP_ORIGIN'] ?? '';
        if(!$origin) return;
        $scheme=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http';
        $same=$scheme.'://'.($_SERVER['HTTP_HOST'] ?? '');
        $origins=getenv('CORS_ALLOWED_ORIGINS');
        $allowed=$origins===false?(\App\Config\Settings::all()['cors_allowed_origins'] ?? []):array_filter(array_map('trim',explode(',',$origins)));
        if($origin!==$same && !in_array($origin,$allowed,true)) ApiResponse::error('Origen no permitido.',403);
        if($origin!==$same) {
            header('Access-Control-Allow-Origin: '.$origin); header('Vary: Origin');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
            header('Access-Control-Expose-Headers: Location');
        }
    }
    private function body(): array
    {
        $type=strtolower(trim(explode(';',$_SERVER['CONTENT_TYPE'] ?? '')[0]));
        if($type!=='application/json') ApiResponse::error('Envía Content-Type: application/json.',415);
        $raw=file_get_contents('php://input',false,null,0,1048577);
        if(strlen($raw)>1048576) ApiResponse::error('El cuerpo supera 1 MB.',413);
        try { $object=json_decode($raw,false,32,JSON_THROW_ON_ERROR); }
        catch(\JsonException $e) { ApiResponse::error('JSON inválido.',400); }
        if(!$object instanceof \stdClass) ApiResponse::error('Envía un objeto JSON.',400);
        return (array)$object;
    }
    public function dispatch(string $path,string $method): void
    {
        $this->cors();
        if($method==='OPTIONS') ApiResponse::empty();
        $path=trim($path,'/');
        $action=null; $entity=null; $id=null; $slot=null; $methods=[];
        if(preg_match('#^auth/(csrf|login|me|logout)$#',$path,$m)) {
            $action='auth.'.$m[1]; $methods=[in_array($m[1],['csrf','me'],true)?'GET':'POST'];
        } elseif($path==='dashboard/resumen') { $action='dashboard';$methods=['GET']; }
        elseif($path==='vacunas/vencimientos') { $action='history';$entity='vacunas';$methods=['GET']; }
        elseif(preg_match('#^(especies|propietarios|mascotas|consultas|vacunas|cuotas)(?:/([1-9][0-9]*))?(?:/(pdf|adjuntos)(?:/([1-3]))?)?$#',$path,$m)) {
            $entity=$m[1]; $id=isset($m[2])&&$m[2]!==''?filter_var($m[2],FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]):null;
            if($id===false) ApiResponse::error('Identificador inválido.',404);
            $sub=$m[3] ?? ''; $slot=isset($m[4])?(int)$m[4]:null;
            if($sub && ($entity!=='consultas'||!$id)) ApiResponse::error('Endpoint no encontrado.',404);
            if($sub==='pdf') { if($slot) ApiResponse::error('Endpoint no encontrado.',404); $action='pdf';$methods=['GET']; }
            elseif($sub==='adjuntos') { $action=$slot?'attachment':'upload'; $methods=$slot?['GET','DELETE']:['POST']; }
            else { $action=$id?'item':'collection';$methods=$id?['GET','PATCH','DELETE']:['GET','POST']; }
        } else ApiResponse::error('Endpoint no encontrado.',404);
        if(!in_array($method,$methods,true)) { header('Allow: '.implode(', ',$methods)); ApiResponse::error('Método no permitido.',405); }
        if(!in_array($action,['auth.csrf','auth.login'],true)) AuthController::current();
        if(in_array($method,['POST','PATCH','DELETE'],true)) {
            $token=$_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if(!isset($_SESSION['csrf'])||!is_string($token)||!hash_equals($_SESSION['csrf'],$token)) ApiResponse::error('Token CSRF inválido.',403);
        }
        $auth=new AuthController();
        if($action==='auth.csrf') $auth->csrf();
        if($action==='auth.me') $auth->me();
        if($action==='auth.login') $auth->login($this->body());
        if($action==='auth.logout') $auth->logout();
        if($action==='dashboard') {
            $db=Database::connect();$data=[];
            foreach(['especies'=>'Especie','propietarios'=>'Propietario','mascotas'=>'Mascota','consultas'=>'Consulta','vacunas'=>'Vacuna','cuotas'=>'Cuota'] as $table=>$prefix) $data[$table]=(int)$db->query("SELECT COUNT(*) n FROM $table WHERE {$prefix}Estado=1")->fetch_assoc()['n'];
            $s=$db->prepare('SELECT COUNT(*) n FROM vacunas WHERE VacunaEstado=1 AND VacunaFchVenc<?');
            $s->execute([date('Y-m-d')]);
            $data['vacunas_vencidas']=(int)$s->get_result()->fetch_assoc()['n'];
            ApiResponse::json(['data'=>$data]);
        }
        $controller=new EntityController($entity);
        if($action==='history') $controller->history($_GET);
        if($action==='pdf') { $controller->pdf($id); return; }
        if($action==='upload') $controller->upload($id);
        if($action==='attachment') { $controller->attachment($id,$slot,$method==='DELETE'); return; }
        if($action==='collection') {
            if($method==='GET') $controller->index($_GET);
            $controller->save($this->body());
        }
        if($method==='GET') $controller->show($id);
        if($method==='DELETE') $controller->delete($id);
        $controller->save($this->body(),$id);
    }
}
