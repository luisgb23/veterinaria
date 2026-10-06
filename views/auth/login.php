<?php use App\Http\View; use App\Http\Csrf; ?>
<h1>Iniciar sesión</h1>
<?php if ($error): ?><p class="alert alert-danger" role="alert"><?= View::escape($error) ?></p><?php endif; ?>
<form method="post" action="<?= View::escape(View::url('login.submit')) ?>">
<input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>">
<label for="usuario">Usuario</label><input class="form-control" id="usuario" name="txtUser" autocomplete="username" required>
<label for="clave">Contraseña</label><input class="form-control mb-3" type="password" id="clave" name="txtPwd" autocomplete="current-password" required>
<button class="btn btn-success">Ingresar</button></form>
