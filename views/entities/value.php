<?php
use App\Http\View;
$value=$row[$field['column']] ?? '';
if($field['relation']) {
    $prefix=$field['relation'][1];
    foreach($options[$field['column']] as $option) if((string)$option[$prefix.'Id']===(string)$value) { $value=$option[$prefix.'Nombre'].' '.($option[$prefix.'Apellido'] ?? ''); break; }
}
echo nl2br(View::escape($value));
