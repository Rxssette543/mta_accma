<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link rel="stylesheet" href="css/all.min.css">
   <link rel="stylesheet" href="css/fontawesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
   <link href="logos/mta_accma/Isotipo.svg" rel="shortcut icon">
   <title>Inicio de sesión ACCMAA</title>
</head>

<body>
   <div class="container">
      <div class="left-side">
         <div class="img animate__animated animate__backInUp">
            <img src="logos/mta_accma/LogoPrincipal.svg">
         </div>
      </div>
      <div class="right-side">
         <div class="login-content animate__animated animate__backInUp">
            <form method="post" action="">
               <img src="logos/mta_accma/IsotipoNegativo.svg">
               <h2 class="title">BIENVENIDO</h2>
               <?php
               include "modelo/conexion.php";
               include "controlador/controlador_login.php";
               ?>
               <div class="input-div one">
                  <div class="i">
                     <i class="fas fa-user"></i>
                  </div>
                  <div class="div">
                     <h5>Usuario</h5>
                     <input id="usuario" type="text" class="input" name="usuario" autocomplete="">
                  </div>
               </div>
               <div class="input-div pass">
                  <div class="i">
                     <i class="fas fa-lock"></i>
                  </div>
                  <div class="div">
                     <h5>Contraseña</h5>
                     <input type="password" id="input" class="input" name="password">
                  </div>
               </div>
               <div class="view">
                  <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
               </div>

               <div class="text-center">
               </div>
               <input name="btningresar" class="btn" type="submit" value="INICIAR SESION">
            </form>
         </div>
      </div>
   </div>

   <script src="js/fontawesome.js"></script>
   <script src="js/main.js"></script>
   <script src="js/main2.js"></script>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script src="js/bootstrap.bundle.js"></script>
</body>

</html>