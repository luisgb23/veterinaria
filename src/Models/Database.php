<?php
namespace App\Models;
final class Database
{
    public static function connect(): \mysqli
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $db = new \mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port'], $config['socket']);
        $db->set_charset('utf8mb4');
        return $db;
    }
}
