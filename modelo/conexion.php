<?php
// Archivo: conexion.php

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "proyecto_mta";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    echo("Error en la conexión: " . $conexion->connect_error);
}
?>
