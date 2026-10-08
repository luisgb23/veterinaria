<?php
// Copy to config/local.php. This local file is ignored by Git.
return [
    // null: automatically detect htdocs/veterinaria; '' for a root virtual host.
    'base_path' => null,
    'database' => [
        'host' => '127.0.0.1',
        'port' => 8889, // Check the MySQL port in MAMP preferences.
        'user' => 'root',
        'password' => '', // Enter your own MAMP MySQL password here.
        'database' => 'itapebi',
        'socket' => null, // TCP avoids using another PHP installation's socket.
    ],
    'cors_allowed_origins' => ['http://localhost:5173'],
];
