<?php
include "modelo/conexion.php";
include "controlador/controlador_login.php";

if (empty($_SESSION["id"])) {
    header("location: configuracion.php");
    exit();
}

// Actualizar registros
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tabla = $_POST['tabla']; // Identificar tabla
    $nombre = $_POST['nombre'];
    $apPaterno = $_POST['apPaterno'];
    $apMaterno = $_POST['apMaterno'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $numero_identificador = "";

    if ($tabla == "empleado") {
        $numero_identificador = "IdEmpleado";
    } elseif ($tabla == "profesor") {
        $numero_identificador = "NumeroProfesor";
    } else {
        $numero_identificador = "NumeroDeControl";
    }

    $sql = "UPDATE $tabla SET 
                Nombre='$nombre', 
                ApPaterno='$apPaterno', 
                ApMaterno='$apMaterno', 
                $numero_identificador='{$_POST['numero_identificador']}', 
                IdTarjeta='$numero_tarjeta' 
            WHERE id=$id";
    if (!$conexion->query($sql)) {
        echo "Error: " . mysqli_error($conexion);
    } else {
        header("Location: configuracion.php");
        exit();
    }
}

$search = "";
$tablas = ["alumno", "profesor", "empleado", "visitante"];
$resultados = [];

if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $search = $conexion->real_escape_string(trim($_POST['search']));
    foreach ($tablas as $tabla) {
        if ($tabla == "empleado") {
            $query = "SELECT * FROM $tabla WHERE 
                        Nombre LIKE '%$search%' OR 
                        ApPaterno LIKE '%$search%' OR 
                        ApMaterno LIKE '%$search%' OR 
                        IdEmpleado LIKE '%$search%' OR 
                        IdTarjeta LIKE '%$search%'";
        } elseif ($tabla == "profesor") {
            $query = "SELECT * FROM $tabla WHERE 
                        Nombre LIKE '%$search%' OR 
                        ApPaterno LIKE '%$search%' OR 
                        ApMaterno LIKE '%$search%' OR 
                        NumeroProfesor LIKE '%$search%' OR 
                        IdTarjeta LIKE '%$search%'";
        } elseif ($tabla == "alumno") {
            $query = "SELECT * FROM $tabla WHERE 
                        Nombre LIKE '%$search%' OR 
                        ApPaterno LIKE '%$search%' OR 
                        ApMaterno LIKE '%$search%' OR 
                        NumeroDeControl LIKE '%$search%' OR 
                        IdTarjeta LIKE '%$search%'";
        } else { // Tabla visitante
            $query = "SELECT * FROM $tabla WHERE IdTarjeta LIKE '%$search%'";
        }

        $resultados[$tabla] = $conexion->query($query);

        if (!$resultados[$tabla]) {
            echo "Error en consulta de la tabla '$tabla': " . $conexion->error;
        }
    }
} else {
    foreach ($tablas as $tabla) {
        $resultados[$tabla] = $conexion->query("SELECT * FROM $tabla");
        if (!$resultados[$tabla]) {
            echo "Error en consulta de la tabla '$tabla': " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="logos/mta_accma/Isotipo.svg" rel="shortcut icon">
    <title>Administración de Registros</title>
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
                <h1 class="text-center mb-4 titulo-admin">Administración de Registros</h1>
                <form method="POST" class="d-flex mb-4">
                    <input class="form-control me-2" type="search" name="search" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    <a href="configuracion.php" class="btn btn-secondary ms-2">Borrar</a>
                </form>
                <?php foreach ($resultados as $tabla => $resultado): ?>
                    <h2 class="mt-4 text-capitalize"><?= $tabla ?></h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th><?= $tabla == 'empleado' ? 'ID Empleado' : ($tabla == 'profesor' ? 'Número Profesor' : 'Número de Control') ?></th>
                                    <th>Número de Tarjeta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['Nombre'] . ' ' . $row['ApPaterno'] . ' ' . $row['ApMaterno']) ?></td>
                                        <td>
                                            <?= $tabla == 'empleado' ? htmlspecialchars($row['IdEmpleado']) : ($tabla == 'profesor' ? htmlspecialchars($row['NumeroProfesor']) : htmlspecialchars($row['NumeroDeControl'])) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['IdTarjeta']) ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal<?= $tabla . $row['IdTarjeta'] ?>">Editar</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>