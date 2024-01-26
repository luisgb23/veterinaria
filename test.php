<?php

$fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
$fecha_entrada = strtotime("19-11-2008 21:00:00");

if($fecha_actual > $fecha_entrada) {
    echo date("d-m-Y", $fecha_actual).": ". "La fecha actual es mayor a la comparada.";
} else {
    echo date("d-m-Y", $fecha_entrada).": "."La fecha comparada es igual o menor";
}