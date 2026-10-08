<?php
$defaults=[
    'host'=>'localhost', 'user'=>'root', 'password'=>'', 'database'=>'itapebi',
    'port'=>3306, 'socket'=>ini_get('mysqli.default_socket') ?: null,
];
$local=App\Config\Settings::all()['database'] ?? [];
$config=array_replace($defaults,$local);
foreach(['host'=>'DB_HOST','user'=>'DB_USER','password'=>'DB_PASSWORD','database'=>'DB_NAME','port'=>'DB_PORT','socket'=>'DB_SOCKET'] as $key=>$name) {
    $value=getenv($name);
    if($value!==false) $config[$key]=$value;
}
$config['port']=(int)$config['port'];
return $config;
