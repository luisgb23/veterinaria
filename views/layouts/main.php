<?php use App\Http\View; use App\Http\Csrf; ?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Veterinaria Itapebí</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><nav class="navbar bg-light"><div class="container">
<a class="navbar-brand" href="<?= View::escape(View::url('especies')) ?>">Veterinaria Itapebí</a>
<?php if (isset($_SESSION['nombre'])): ?>
<a href="/dashboard.php">Inicio</a><a href="/propietarios.php">Propietarios</a><a href="/mascotas.php">Mascotas</a><a href="/consultas.php">Consultas</a><a href="/cuotas.php">Cuotas</a><a href="/vacunas.php">Vacunas</a>
<form method="post" action="<?= View::escape(View::url('logout')) ?>"><input type="hidden" name="_token" value="<?= View::escape(Csrf::token()) ?>"><button class="btn btn-outline-secondary">Cerrar sesión</button></form>
<?php endif; ?></div></nav><main class="container py-4">
<?php if (isset($_SESSION['nombre'])): ?><p>Bienvenid@ <?= View::escape($_SESSION['nombre']) ?></p><?php endif; ?>
<?php require $content; ?>
</main></body></html>
