<?php
include "modelo/conexion.php";
include "controlador/controlador_login.php";
if (empty($_SESSION["id"])) {
    header("location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
</head>
<body>
    
</body>
</html>