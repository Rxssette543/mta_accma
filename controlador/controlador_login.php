<?php
session_start();
if (!empty($_POST["btningresar"])) {
   if (!empty($_POST["usuario"]) && !empty($_POST["password"])) {
      $usuario = $_POST["usuario"];
      $password = $_POST["password"];  

      $sql = $conexion->query("SELECT * FROM usuario WHERE Usuario='$usuario' AND Clave='$password'");

      if ($datos = $sql->fetch_object()) {
         $_SESSION["id"] = $datos->Id;
         $_SESSION["nombre"] = $datos->Nombre;
         $_SESSION["apPaterno"] = $datos->ApPaterno;

         header("location: dashboard.php");
      } else {
         echo "<div class='alert alert-danger'>Acceso denegado</div>";
      }
   } else {
      echo "<div class='alert alert-warning'>Campos vacíos</div>";
   }
}
