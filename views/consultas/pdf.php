<?php use App\Http\View; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#23382c}h1{color:#224c3d}dt{font-weight:bold;margin-top:16px}dd{margin:6px 0}</style></head><body>
<h1>Veterinaria Itapebí</h1><h2>Resumen de historia clínica</h2><dl>
<?php foreach($config['fields'] as $field): ?><dt><?= View::escape($field['label']) ?></dt><dd><?php require dirname(__DIR__).'/entities/value.php'; ?></dd><?php endforeach; ?></dl>
</body></html>
