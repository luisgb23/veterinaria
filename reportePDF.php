<?php
ob_start();
require ('frmReportePDF.php');
$html = ob_get_clean();

require './vendor/autoload.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream("reporte_consulta.pdf", array('Attachment' => false));
?>