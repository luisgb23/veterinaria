<?php
namespace App\Services;
final class ArchivoService
{
    private const TYPES=['application/pdf'=>'pdf','image/jpeg'=>'jpg','image/png'=>'png'];
    private function directory(): string { return dirname(__DIR__,2).'/storage/uploads'; }
    public function store(array $files): array
    {
        $validated=[]; $stored=[];
        for($i=1;$i<=3;$i++) {
            $file=$files['archivo'.$i] ?? null;
            if(!$file || ($file['error'] ?? null)===UPLOAD_ERR_NO_FILE) continue;
            if(!is_array($file) || ($file['error'] ?? null)!==UPLOAD_ERR_OK || !is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])) throw new \InvalidArgumentException('No se pudo recibir el adjunto.');
            if(($file['size'] ?? 0)>5*1024*1024) throw new \InvalidArgumentException('Cada adjunto debe ocupar como máximo 5 MB.');
            $mime=(new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
            if(!isset(self::TYPES[$mime])) throw new \InvalidArgumentException('Solo se admiten PDF, JPG y PNG.');
            $validated[$i]=[$file['tmp_name'],bin2hex(random_bytes(16)).'.'.self::TYPES[$mime]];
        }
        if($validated && !is_dir($this->directory()) && !mkdir($this->directory(),0700,true)) throw new \RuntimeException('No se pudo preparar el almacenamiento.');
        try {
            foreach($validated as $i=>[$temporary,$name]) {
                if(!move_uploaded_file($temporary,$this->directory().'/'.$name)) throw new \RuntimeException('No se pudo guardar el adjunto.');
                $stored['ConsultaArchivo'.$i]=$name;
            }
        } catch(\Throwable $e) { $this->remove($stored); throw $e; }
        return $stored;
    }
    public function remove(array $names): void
    {
        foreach($names as $name) if(is_string($name) && preg_match('/^[a-f0-9]{32}\.(pdf|jpg|png)$/',$name)) @unlink($this->directory().'/'.$name);
    }
    public function path(string $name): ?string
    {
        if($name!==basename($name) || $name==='' || str_contains($name,"\0")) return null;
        // Existing repository attachments remain readable only through an authenticated route.
        foreach([$this->directory(),dirname(__DIR__,2).'/archivos'] as $dir) {
            $path=realpath($dir.'/'.$name); $root=realpath($dir);
            if($path && $root && str_starts_with($path,$root.DIRECTORY_SEPARATOR) && is_file($path)) return $path;
        }
        return null;
    }
}
