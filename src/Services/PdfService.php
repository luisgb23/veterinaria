<?php
namespace App\Services;
final class PdfService
{
    public function output(string $html): void
    {
        $pdf=new \Dompdf\Dompdf();
        $pdf->loadHtml($html,'UTF-8'); $pdf->setPaper('letter'); $pdf->render();
        $pdf->stream('reporte_consulta.pdf',['Attachment'=>false]);
    }
}
