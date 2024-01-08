<?php

function obtenerEdad($fechaNac)
{
    $nacimiento = new DateTime($fechaNac);
    $hoy = new DateTime(date("Y-m-d"));
    $edad = $hoy->diff($nacimiento);
    return $edad->format("%y");
}