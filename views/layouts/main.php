<?php use App\Http\View; $isLogin = $template === 'auth/login'; ?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Veterinaria Itapebí</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/styles.css" rel="stylesheet">
<script src="/js/sidebar.js" defer></script>
<?php if ($isLogin): ?><script src="/js/login.js" defer></script><?php endif; ?>
</head><body<?= $isLogin ? ' class="login-page"' : (isset($_SESSION['nombre']) ? ' class="has-sidebar"' : '') ?>>
<?php if (!$isLogin && isset($_SESSION['nombre'])) require dirname(__DIR__) . '/partials/sidebar.php'; ?>
<main class="<?= $isLogin ? 'login-main' : (isset($_SESSION['nombre']) ? 'app-main' : 'container py-4') ?>" id="main-content" tabindex="-1">
<?php if (!$isLogin && isset($_SESSION['nombre'])): ?><p class="app-welcome">Bienvenid@ <?= View::escape($_SESSION['nombre']) ?></p><?php endif; ?>
<?php require $content; ?>
</main></body></html>
