<?php use App\Http\View; use App\Http\Csrf; ?>
<div class="cabezal mb-4"><h1><?= View::escape($config['title']) ?></h1><a class="btn btn-success" href="<?= View::escape(View::url($entity.'.create')) ?>">Agregar</a></div>
<?php if(!$rows): ?><p>No hay registros activos.</p><?php endif; ?>
<div class="responsive-table"><table class="table table-hover"><thead><tr><th>Código</th>
<?php foreach($config['fields'] as $field): ?><th><?= View::escape($field['label']) ?></th><?php endforeach; ?><th>Acciones</th></tr></thead><tbody>
<?php foreach($rows as $row): $id=(int)$row[$config['prefix'].'Id']; ?><tr><td><?= $id ?></td>
<?php foreach($config['fields'] as $field): ?><td><?php require __DIR__.'/value.php'; ?></td><?php endforeach; ?>
<td><div class="entity-actions"><a class="btn btn-light" href="<?= View::escape(View::url($entity.'.show',['id'=>$id])) ?>">Detalle</a><a class="btn btn-warning" href="<?= View::escape(View::url($entity.'.edit',['id'=>$id])) ?>">Editar</a>
<?php if($entity==='consultas'): ?><a class="btn btn-secondary" href="<?= View::escape(View::url('consultas.pdf',['id'=>$id])) ?>" target="_blank" rel="noopener">PDF</a><?php endif; ?>
<form method="post" action="<?= View::escape(View::url($entity.'.destroy')) ?>" onsubmit="return confirm('¿Eliminar este registro?')"><input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>"><input type="hidden" name="txtId" value="<?= $id ?>"><button class="btn btn-danger">Eliminar</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></div>
