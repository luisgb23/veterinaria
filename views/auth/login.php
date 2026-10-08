<?php use App\Http\View; use App\Http\Csrf; ?>
<section class="login-card" aria-labelledby="login-title">
    <div class="login-brand">
        <img class="login-logo" src="<?= App\Http\View::escape(App\Http\Url::to('img/logo.webp')) ?>" alt="" width="88" height="88">
        <p class="login-eyebrow">VETERINARIA ITAPEBÍ</p>
        <h1 id="login-title">Bienvenid@</h1>
        <p class="login-description">Ingresa para gestionar tu veterinaria.</p>
    </div>
    <?php if ($error): ?>
        <p class="login-error" id="login-error" role="alert"><?= View::escape($error) ?></p>
    <?php endif; ?>
    <form class="login-form" method="post" action="<?= View::escape(View::url('login.submit')) ?>"<?= $error ? ' aria-describedby="login-error"' : '' ?>>
        <input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>">
        <div class="login-field">
            <label for="usuario">Usuario</label>
            <input id="usuario" name="txtUser" autocomplete="username" placeholder="Tu nombre de usuario" required>
        </div>
        <div class="login-field">
            <label for="clave">Contraseña</label>
            <div class="login-password">
                <input type="password" id="clave" name="txtPwd" autocomplete="current-password" placeholder="Tu contraseña" required>
                <button class="password-toggle" type="button" aria-controls="clave" aria-pressed="false" hidden>Mostrar</button>
            </div>
        </div>
        <button class="login-submit" type="submit">Iniciar sesión</button>
    </form>
    <p class="login-footer">El cuidado empieza con una buena gestión.</p>
</section>
