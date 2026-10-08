<?php use App\Http\View; use App\Http\Csrf; ?>
<h1><?= $row?'Editar':'Agregar' ?>: <?= View::escape($config['title']) ?></h1>
<?php if($errors): ?><div class="alert alert-danger" role="alert"><ul><?php foreach($errors as $message): ?><li><?= View::escape($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="entity-form" method="post" enctype="multipart/form-data" action="<?= View::escape(View::url($entity.($row?'.update':'.store'))) ?>">
<input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>">
<?php if($row): ?><input type="hidden" name="txtId" value="<?= (int)$row[$config['prefix'].'Id'] ?>"><?php endif; ?>
<?php foreach($config['fields'] as $field): $value=$values[$field['column']] ?? ''; ?>
<div class="mb-3"><label class="form-label" for="<?= View::escape($field['input']) ?>"><?= View::escape($field['label']) ?></label>
<?php if($field['type']==='select'): ?>
<select class="form-select" name="<?= View::escape($field['input']) ?>" id="<?= View::escape($field['input']) ?>" required><option value="">Selecciona una opción</option>
<?php $prefix=$field['relation'][1]; foreach($options[$field['column']] as $option): ?><option value="<?= (int)$option[$prefix.'Id'] ?>"<?= (string)$value===(string)$option[$prefix.'Id']?' selected':'' ?>><?= View::escape($option[$prefix.'Nombre'].' '.($option[$prefix.'Apellido'] ?? '')) ?></option><?php endforeach; ?></select>
<?php elseif($field['type']==='textarea'): ?>
<textarea class="form-control" rows="4" name="<?= View::escape($field['input']) ?>" id="<?= View::escape($field['input']) ?>" maxlength="<?= $field['limit'] ?>"<?= $field['required']?' required':'' ?>><?= View::escape($value) ?></textarea>
<?php else: ?>
<input class="form-control" type="<?= View::escape($field['type']) ?>" name="<?= View::escape($field['input']) ?>" id="<?= View::escape($field['input']) ?>" value="<?= View::escape($value) ?>" maxlength="<?= $field['limit'] ?>"<?= $field['type']==='number'?' min="0" step="1"':'' ?><?= $field['required']?' required':'' ?>>
<?php endif; ?></div><?php endforeach; ?>
<?php if($entity==='consultas'): ?><fieldset class="mb-4"><legend>Adjuntos</legend><p>Opcionales: PDF, JPG o PNG, hasta 5 MB cada uno. Dejar vacío conserva el archivo actual.</p>
<?php for($i=1;$i<=3;$i++): ?><label class="form-label" for="archivo<?= $i ?>">Archivo <?= $i ?></label><input class="form-control mb-2" type="file" id="archivo<?= $i ?>" name="archivo<?= $i ?>" accept="application/pdf,image/jpeg,image/png"><?php if(!empty($row['ConsultaArchivo'.$i])): ?><p>Ya hay un archivo guardado en esta posición.</p><?php endif; ?><?php endfor; ?></fieldset><?php endif; ?>
<button class="btn btn-success">Guardar</button><a class="btn btn-secondary" href="<?= View::escape(View::url($entity)) ?>">Cancelar</a>
</form>
