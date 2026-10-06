<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<script src="js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function (){
            console.log("Funciona")
            alert("Funciona")
        })
    </script>
</body>
</html>



<?php

$fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
$fecha_entrada = strtotime("19-11-2008 21:00:00");

if($fecha_actual > $fecha_entrada) {
    echo date("d-m-Y", $fecha_actual).": ". "La fecha actual es mayor a la comparada.";
} else {
    echo date("d-m-Y", $fecha_entrada).": "."La fecha comparada es igual o menor";
}