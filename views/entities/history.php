<?php use App\Http\View; ?>
<h1>Histórico de vencimiento de vacunas</h1>
<div class="responsive-table"><table class="table"><thead><tr><th>Mascota</th><th>Vencimiento</th><th>Propietario</th><th>Teléfono</th></tr></thead><tbody>
<?php foreach($rows as $row): ?><tr><td><?= View::escape($row['MascotaNombre']) ?></td><td><?= View::escape($row['VacunaFchVenc']) ?><?php if($row['VacunaFchVenc']<date('Y-m-d')): ?> <span class="badge text-bg-danger">Vencida</span><?php endif; ?></td><td><?= View::escape($row['PropietarioNombre'].' '.$row['PropietarioApellido']) ?></td><td><?= View::escape($row['PropietarioTelefono']) ?></td></tr><?php endforeach; ?>
</tbody></table></div>
