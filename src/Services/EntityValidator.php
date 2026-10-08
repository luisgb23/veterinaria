<?php
namespace App\Services;
use App\Models\Entity;
/** Validates canonical database fields; both HTML and API map their inputs here. */
final class EntityValidator
{
    public function validate(string $entity, array $config, Entity $model, array $input, ?array $row = null): array
    {
        $values=[]; $errors=[];
        foreach($config['fields'] as $f) {
            $raw=$input[$f['column']] ?? '';
            $v=is_string($raw)||is_int($raw)?trim((string)$raw):'';
            $values[$f['column']]=$v===''?null:$v;
            $accepted=is_string($raw)||$raw===null||(is_int($raw)&&in_array($f['type'],['select','number'],true));
            $invalid=(!$accepted) || ($f['required'] && $v==='');
            if($v!=='') {
                if(in_array($f['type'],['text','email','textarea'],true) && mb_strlen($v)>$f['limit']) $invalid=true;
                if($f['type']==='date') { $d=\DateTimeImmutable::createFromFormat('!Y-m-d',$v); $invalid=$invalid || !$d || $d->format('Y-m-d')!==$v; }
                elseif($f['type']==='email') $invalid=$invalid || !filter_var($v,FILTER_VALIDATE_EMAIL);
                elseif($f['type']==='number') $invalid=$invalid || !ctype_digit($v) || strlen($v)>18;
                elseif($f['type']==='select') {
                    $options=$model->options($f['relation'],isset($row[$f['column']])?(int)$row[$f['column']]:null);
                    $ids=array_column($options,$f['relation'][1].'Id');
                    $invalid=$invalid || !ctype_digit($v) || !in_array((int)$v,$ids,true);
                }
                else $invalid=$invalid || mb_strlen($v)>$f['limit'];
            }
            if($invalid) $errors[$f['column']]='Revisa el campo '.$f['label'].'.';
        }
        if($entity==='vacunas' && !$errors && $values['VacunaFchVenc']<$values['VacunaFchIngreso']) $errors['VacunaFchVenc']='El vencimiento no puede ser anterior al ingreso.';
        if($entity==='mascotas' && !$errors && $values['MascotaFchNac']>date('Y-m-d')) $errors['MascotaFchNac']='La fecha de nacimiento no puede ser futura.';
        return [$values,$errors];
    }
}
