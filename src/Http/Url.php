<?php
namespace App\Http;
use App\Config\Settings;
final class Url
{
    public static function base(): string
    {
        $configured=getenv('APP_BASE_PATH');
        if($configured===false) $configured=Settings::all()['base_path'] ?? null;
        if($configured!==null) {
            if(!is_string($configured) || ($configured!=='' && (!str_starts_with($configured,'/') || preg_match('/[?#\\\\\r\n]/',$configured)))) throw new \RuntimeException('APP_BASE_PATH debe ser una ruta local, por ejemplo /veterinaria.');
            return rtrim($configured,'/');
        }
        $root=str_replace('\\','/',realpath(dirname(__DIR__,2)));
        $document=realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
        if($document) {
            $document=rtrim(str_replace('\\','/',$document),'/');
            if($root===$document) return '';
            if(str_starts_with($root,$document.'/')) return substr($root,strlen($document));
        }
        return '';
    }
    public static function to(string $path): string { return self::base().'/'.ltrim($path,'/'); }
}
