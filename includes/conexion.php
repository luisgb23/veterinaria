<?php
$host = "localhost";
$user = "root";
$paswd = "";
$db = "itapebi";
$mysqli = new mysqli($host, $user, $paswd, $db);
if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
return $mysqli;
?>
