<?php use App\Http\View; use App\Http\Csrf; ?>
<h1><?= $especie ? 'Editar' : 'Agregar' ?> especie</h1>
<?php if ($error): ?><p class="alert alert-danger" role="alert"><?= View::escape($error) ?></p><?php endif; ?>
<form method="post" action="<?= View::escape(View::url($especie ? 'especies.update' : 'especies.store')) ?>">
<input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>">
<?php if ($especie): ?><input type="hidden" name="txtId" value="<?= (int) $especie['EspecieId'] ?>"><?php endif; ?>
<label for="nombre">Descripción</label><input class="form-control mb-3" id="nombre" name="txtNombre" value="<?= View::escape($name) ?>" maxlength="100" required>
<button class="btn btn-success">Guardar</button><a class="btn btn-secondary" href="<?= View::escape(View::url('especies')) ?>">Cancelar</a>
</form>
