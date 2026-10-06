<?php use App\Http\View; use App\Http\Csrf; ?>
<h1>Especies</h1><a class="btn btn-success" href="<?= View::escape(View::url('especies.create')) ?>">Agregar especie</a>
<table class="table"><thead><tr><th>Código</th><th>Descripción</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($especies as $especie): ?><tr><td><?= (int) $especie['EspecieId'] ?></td><td><?= View::escape($especie['EspecieNombre']) ?></td><td>
<a class="btn btn-warning" href="<?= View::escape(View::url('especies.edit', ['id' => $especie['EspecieId']])) ?>">Editar</a>
<form class="d-inline" method="post" action="<?= View::escape(View::url('especies.destroy')) ?>" onsubmit="return confirm('¿Eliminar esta especie?')">
<input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>"><input type="hidden" name="txtId" value="<?= (int) $especie['EspecieId'] ?>"><button class="btn btn-danger">Eliminar</button></form>
</td></tr><?php endforeach; ?>
</tbody></table>
