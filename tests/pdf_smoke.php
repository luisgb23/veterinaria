<?php
// Verify that dependency warnings cannot silently contaminate PDF output.
require dirname(__DIR__).'/vendor/autoload.php';
error_reporting(E_ALL);
set_error_handler(static function(int $severity,string $message,string $file,int $line): bool {
    if(error_reporting() & $severity) throw new ErrorException($message,0,$severity,$file,$line);
    return false;
});
try {
    $pdf=new Dompdf\Dompdf();
    $pdf->loadHtml('<html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans}h1{font-weight:bold}</style></head><body><h1>Veterinaria Itapebí</h1><p>Diagnóstico y tratamiento</p></body></html>','UTF-8');
    $pdf->render();
    if(!str_starts_with($pdf->output(),'%PDF-')) throw new RuntimeException('Invalid PDF output');
    echo "PASS PDF with all PHP warnings enabled\n";
} finally { restore_error_handler(); }
