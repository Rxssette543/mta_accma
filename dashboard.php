<?php
include "modelo/conexion.php";
include "controlador/controlador_login.php";
if (empty($_SESSION["id"])) {
    header("location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="logos/mta_accma/Isotipo.svg" rel="shortcut icon">
    <title>Dashboard ACCMA</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Barra de navegación móvil -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
                <div class="container-fluid">
                    <a class="navbar-brand" href="dashboard.php">Dashboard mta_accma</a>

                    <div class="collapse navbar-collapse" id="mobileNavbar">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php
                            $menuItems = [
                                ["name" => "Dashboard", "icon" => "fas fa-tachometer-alt", "link" => "dashboard.php"],
                                ["name" => "Alumnos", "icon" => "fas fa-user-graduate", "link" => "alumnos.php"],
                                ["name" => "Configuración", "icon" => "fas fa-cogs", "link" => "configuracion.php"],
                                ["name" => "CERRAR SESIÓN", "icon" => "fas fa-cogs", "link" => "logout.php"]
                            ];

                            foreach ($menuItems as $item) {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link" href="' . htmlspecialchars($item['link']) . '">';
                                echo '<i class="' . htmlspecialchars($item['icon']) . '"></i> ';
                                echo htmlspecialchars($item['name']);
                                echo '</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Barra lateral para pantallas grandes -->
            <nav class="col-md-2 col-lg-2 d-none d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <div class="isotipo-centrado">
                        <img src="logos/mta_accma/LogoPrincipal.svg" alt="">
                    </div>
                    <ul class="nav flex-column">
                        <?php
                        foreach ($menuItems as $item) {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="' . htmlspecialchars($item['link']) . '">';
                            echo '<i class="' . htmlspecialchars($item['icon']) . '"></i> ';
                            echo htmlspecialchars($item['name']);
                            echo '</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </nav>

            <div class="col-md-9 mx-auto col-lg-9">
                <div class="container-welcome">
                    <h1 class="mt-4 mt-5">
                        Bienvenido <?php echo $_SESSION["nombre"] . " " . $_SESSION["apPaterno"]; ?>
                    </h1>
                    <p>Esta es la vista de el día de hoy <?php echo date('Y-m-d'); ?></p>

                </div>
                <div class="row">
                    <!-- Para pantallas grandes y medianas (laptops) se dividirán en 4 columnas, y en pantallas pequeñas se apilarán en 12 columnas -->
                    <div class="col-12 col-sm-6 col-md-6 mb-3 h-100">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total de personas dentro del ITMA</h5>
                                <p class="numeros">
                                    <?php
                                    $consultatotal = "SELECT COUNT(*) AS total FROM registrodeingreso";
                                    $resultadoTotal = $conexion->query($consultatotal);
                                    $row = $resultadoTotal->fetch_assoc();
                                    echo $row['total'];
                                    ?>
                                <p class="subnumeros">/ 999 activos</p>
                                </p>
                                <a href="#" class="btn btn-primary">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Profesores y personal</h5>
                                <div class="numeros-end">
                                    <p class="numeros">
                                        <?php
                                        $consultatotalpp = "SELECT COUNT(NumeroProfesor) AS totalProfesor FROM registrodeingreso";
                                        $resultadoTotalpp = $conexion->query($consultatotalpp);
                                        $row = $resultadoTotalpp->fetch_assoc();
                                        echo $row['totalProfesor'];
                                        ?>
                                    <p class="subnumeros">/ 58 activos</p>
                                    </p>
                                    <a href="#" class="btn btn-primary">Ver detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="table-contenido table-responsive">
                            <h1>Últimos 10 ingresos</h1>
                            <?php
                            $sql = "SELECT * FROM registrodeingreso ORDER BY IdRegistro DESC limit 10";
                            $resultado = $conexion->query($sql);

                            if ($resultado->num_rows > 0) {
                                echo '<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Carrera</th>
                                    <th scope="col">IdTarjeta</th>
                                </tr>
                            </thead>
                            <tbody>';
                                while ($row = $resultado->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<th scope="col">'
                                        . htmlspecialchars($row["IdRegistro"]) . '</th>';
                                    echo '<td>' . htmlspecialchars($row["FechaIngreso"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["HoraIngreso"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["NumeroDeControl"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["NumeroProfesor"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["IdEmpleado"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["IdVisitante"]) . '</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';
                            } else {
                                echo "No hay resultados.";
                            }
                            $resultado->free();
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="table-contenido table-responsive">
                            <h1>Últimos 10 egresos</h1>
                            <?php
                            $sql = "SELECT * FROM registrodeegreso ORDER BY IdRegistro DESC limit 10";
                            $resultado = $conexion->query($sql);

                            if ($resultado->num_rows > 0) {
                                echo '<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Carrera</th>
                                    <th scope="col">IdTarjeta</th>
                                </tr>
                            </thead>
                            <tbody>';
                                while ($row = $resultado->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<th scope="col">'
                                        . htmlspecialchars($row["IdRegistro"]) . '</th>';
                                    echo '<td>' . htmlspecialchars($row["FechaEgreso"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["HoraEgreso"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["NumeroDeControl"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["NumeroProfesor"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["IdEmpleado"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["IdVisitante"]) . '</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';
                            } else {
                                echo "No hay resultados.";
                            }
                            $resultado->free();
                            $conexion->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>