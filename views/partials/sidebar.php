<?php
use App\Http\View;
use App\Http\Csrf;
$script = basename($_SERVER['SCRIPT_NAME'] ?? '');
$currentRoute = $mvcRoute ?? ($_GET['route'] ?? '');
$links = [
    ['Inicio', '/dashboard.php', 'dashboard.php', null],
    ['Especies', View::url('especies'), 'Especie', 'especies'],
    ['Propietarios', '/propietarios.php', 'Propietario', null],
    ['Mascotas', '/mascotas.php', 'Mascota', null],
    ['Vacunas', '/vacunas.php', 'Vacuna', null],
    ['Consultas', '/consultas.php', 'Consulta', null],
    ['Cuotas', '/cuotas.php', 'Cuota', null],
    ['Histórico de vacunas', '/vencimientos.php', 'vencimientos.php', null],
];
?>
<a class="skip-link" href="#main-content">Ir al contenido</a>
<button class="sidebar-toggle" type="button" aria-controls="app-sidebar" aria-expanded="false"><span aria-hidden="true">☰</span> Menú</button>
<button class="sidebar-backdrop" type="button" tabindex="-1" aria-label="Cerrar menú" hidden></button>
<aside class="app-sidebar" id="app-sidebar" aria-label="Menú principal">
    <a class="sidebar-brand" href="/dashboard.php"><img src="/img/logo.webp" alt="" width="48" height="48"><span>Veterinaria<small>Itapebí</small></span></a>
    <p class="sidebar-label">GESTIÓN</p>
    <nav class="sidebar-nav" aria-label="Accesos">
        <?php foreach ($links as [$label, $url, $match, $prefix]):
            $active = ($prefix && is_string($currentRoute) && str_starts_with($currentRoute, $prefix))
                || stripos($script, $match) !== false
                || ($label === 'Especies' && $script === 'especies.php');
        ?>
        <a class="sidebar-link<?= $active ? ' is-active' : '' ?>" href="<?= View::escape($url) ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= View::escape($label) ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-account">
        <span class="sidebar-label">SESIÓN ACTIVA</span>
        <strong><?= View::escape($_SESSION['nombre']) ?></strong>
        <form method="post" action="<?= View::escape(View::url('logout')) ?>">
            <input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>">
            <button class="sidebar-logout" type="submit">Cerrar sesión</button>
        </form>
    </div>
</aside>
