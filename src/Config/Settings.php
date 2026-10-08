<?php
namespace App\Config;
final class Settings
{
    public static function all(): array
    {
        static $settings;
        if($settings===null) {
            $file=dirname(__DIR__,2).'/config/local.php';
            $settings=is_file($file)?require $file:[];
            if(!is_array($settings)) throw new \RuntimeException('config/local.php debe devolver un array.');
        }
        return $settings;
    }
}
