<?php
include "modelo/conexion.php";
include "controlador/controlador_login.php";
if (empty($_SESSION["id"])) {
    header("location: alumnos.php");
}

    $fechaSeleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="logos/mta_accma/Isotipo.svg" rel="shortcut icon">
    <title>Dashboard ACCMA</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
                <div class="container-fluid">
                    <a class="navbar-brand" href="dashboard.php">Dashboard mta_accma</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa-solid fa-bars"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mobileNavbar">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php
                            $menuItems = [
                                ["name" => "Dashboard", "icon" => "fas fa-tachometer-alt", "link" => "dashboard.php"],
                                ["name" => "Alumnos", "icon" => "fas fa-user-graduate", "link" => "alumnos.php"],
                                ["name" => "Exportar", "icon" => "fa-solid fa-file-export", "link" => "exportar.php"],
                                ["name" => "Configuración", "icon" => "fas fa-cogs", "link" => "configuracion.php"],
                                ["name" => "CERRAR SESIÓN", "icon" => "fa-solid fa-right-from-bracket", "link" => "controlador/logout.php"],
                            ];

                            foreach ($menuItems as $item) {
                                echo '<li class="nav-item">';
                                if ($item['name'] == 'CERRAR SESIÓN') {
                                    echo '<a class="nav-link cerrar-sesion" href="' . htmlspecialchars($item['link']) . '">'; 
                                } else {
                                    echo '<a class="nav-link" href="' . htmlspecialchars($item['link']) . '">';
                                }
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
            <nav class="col-md-2 col-lg-2 d-none d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <div class="isotipo-centrado">
                        <img src="logos/mta_accma/LogoPrincipal.svg" alt="">
                    </div>
                    <ul class="nav flex-column">
                        <?php
                        foreach ($menuItems as $item) {
                            echo '<li class="nav-item">';
                            if ($item['name'] == 'CERRAR SESIÓN') {
                                echo '<a class="nav-link cerrar-sesion" href="' . htmlspecialchars($item['link']) . '">';
                            } else {
                                echo '<a class="nav-link" href="' . htmlspecialchars($item['link']) . '">';
                            }
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
                        Estos son los registros del día
                        <strong>
                            <?php
                                echo $fechaSeleccionada;
                            ?>
                            <strong>
                    </h1>
                </div>

                <div class="container">
                    <h2>Filtrar por fecha</h2>
                    <input type="date" id="fechaSeleccionada" class="form-control" onchange="filtrarPorFecha()" value="<?php echo $fechaSeleccionada; ?>">
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="table-contenido table-responsive">
                            <h1>Ingresos</h1>
                            <?php
                            $sql = "SELECT * FROM registrodeingreso WHERE FechaIngreso = '$fechaSeleccionada' ORDER BY IdRegistro DESC limit 50";
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
                                    echo '<th scope="col">' . htmlspecialchars($row["IdRegistro"]) . '</th>';
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
                            <h1>Egresos</h1>
                            <?php
                            $sql = "SELECT * FROM registrodeegreso WHERE FechaEgreso = '$fechaSeleccionada' ORDER BY IdRegistro DESC limit 50";
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
                                    echo '<th scope="col">' . htmlspecialchars($row["IdRegistro"]) . '</th>';
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
    
    <script>
    function filtrarPorFecha() {
        var fecha = document.getElementById('fechaSeleccionada').value;
        if (fecha) {
            window.location.href = "alumnos.php?fecha=" + fecha;
        }
    }
    </script>
</body>

</html>
