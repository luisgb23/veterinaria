<?php
// Regression test using a temporary connection-local table; existing data stays untouched.
require dirname(__DIR__).'/vendor/autoload.php';
$db=App\Models\Database::connect();
$config=(require dirname(__DIR__).'/config/entities.php')['vacunas'];
foreach([false,true] as $withModification) {
    $db->query('DROP TEMPORARY TABLE IF EXISTS vacunas');
    // Temporary tables shadow permanent tables only on this test connection.
    $db->query('CREATE TEMPORARY TABLE vacunas (
        VacunaId INT AUTO_INCREMENT PRIMARY KEY,
        MascotaId INT NOT NULL,
        VacunaFchIngreso DATE NOT NULL,
        VacunaFchVenc DATE NOT NULL,
        VacunaEstado SMALLINT NOT NULL DEFAULT 1'.($withModification?', VacunaFchModificacion DATETIME NULL':'').')');
    $model=new App\Models\Entity($db,'vacunas',$config);
    $values=['MascotaId'=>1,'VacunaFchIngreso'=>'2026-01-01','VacunaFchVenc'=>'2026-12-01'];
    $id=$model->save($values,null);
    $row=$model->find($id);
    if(!$row || $row['VacunaFchVenc']!=='2026-12-01') throw new RuntimeException('Legacy insert failed');
    $values['VacunaFchVenc']='2027-01-01';
    $model->save($values,$id);
    $row=$model->find($id);
    if($row['VacunaFchVenc']!=='2027-01-01') throw new RuntimeException('Legacy update failed');
    if($withModification && !$row['VacunaFchModificacion']) throw new RuntimeException('Timestamp not updated');
    $model->delete($id);
    if($model->find($id)!==null) throw new RuntimeException('Legacy soft delete failed');
    echo 'PASS vacunas without creation timestamp'.($withModification?' with':' without')." modification timestamp\n";
}
$db->query('DROP TEMPORARY TABLE vacunas');
