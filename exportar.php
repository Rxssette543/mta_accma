<?php
require('libs/fpdf186/fpdf.php');
include "modelo/conexion.php";

class PDF extends FPDF {
    var $fechaSeleccionada;

    function SetFecha($fecha) {
        $this->fechaSeleccionada = $fecha;
    }

    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Registros de Entrada y Salida - Fecha: ' . $this->fechaSeleccionada, 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fechaIngreso = $_POST['fechaIngreso'];

    $pdf = new PDF();
    $pdf->SetFecha($fechaIngreso);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    $sql = "
        SELECT ri.FechaIngreso, ri.HoraIngreso, re.FechaEgreso, re.HoraEgreso, ri.NumeroDeControl
        FROM registrodeingreso ri
        LEFT JOIN registrodeegreso re ON ri.NumeroDeControl = re.NumeroDeControl
        WHERE ri.FechaIngreso = '$fechaIngreso' OR re.FechaEgreso = '$fechaIngreso'
        ORDER BY ri.HoraIngreso";
    
    $resultado = $conexion->query($sql);

    $pdf->Cell(35, 10, 'Numero de Control', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fecha de Ingreso', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Hora de Ingreso', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fecha de Egreso', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Hora de Egreso', 1, 1, 'C');

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $pdf->Cell(35, 10, $row["NumeroDeControl"], 1, 0, 'C');
            $pdf->Cell(40, 10, $row["FechaIngreso"], 1, 0, 'C');
            $pdf->Cell(30, 10, $row["HoraIngreso"], 1, 0, 'C');
            $pdf->Cell(40, 10, $row["FechaEgreso"], 1, 0, 'C');
            $pdf->Cell(30, 10, $row["HoraEgreso"], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para la fecha seleccionada.', 1, 1, 'C');
    }

    $pdf->Output();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar Registros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Exportar Registros de Entrada y Salida</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="fechaIngreso" class="form-label">Selecciona una fecha de ingreso:</label>
                <input type="text" class="form-control" id="fechaIngreso" name="fechaIngreso" readonly="readonly" required>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function() {
            $("#fechaIngreso").datepicker({
                dateFormat: "yy-mm-dd"
            });
        });
    </script>
</body>
</html>
