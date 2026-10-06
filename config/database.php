<?php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_NAME') ?: 'itapebi',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'socket' => getenv('DB_SOCKET') ?: (ini_get('mysqli.default_socket') ?: null),
];
