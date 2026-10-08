<?php use App\Http\View; ?>
<h1><?= View::escape($config['title']) ?>: detalle</h1><dl>
<?php foreach($config['fields'] as $field): ?><dt><?= View::escape($field['label']) ?></dt><dd><?php require __DIR__.'/value.php'; ?></dd><?php endforeach; ?></dl>
<?php if($entity==='consultas'): ?>
<?php for($i=1;$i<=3;$i++): if(!empty($row['ConsultaArchivo'.$i])): ?><p><a href="<?= View::escape(View::url('consultas.file',['id'=>$row['ConsultaId'],'slot'=>$i])) ?>">Descargar adjunto <?= $i ?></a></p><?php endif; endfor; ?>
<a class="btn btn-secondary" href="<?= View::escape(View::url('consultas.pdf',['id'=>$row['ConsultaId']])) ?>">Ver PDF</a>
<?php endif; ?><a class="btn btn-light" href="<?= View::escape(View::url($entity)) ?>">Volver</a>
