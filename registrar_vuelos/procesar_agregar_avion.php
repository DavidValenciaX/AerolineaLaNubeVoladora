<!DOCTYPE html>
<html>
<head>
    <title>Procesar Agregar Avión</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
<?php
    require 'validar_permiso_registrar_vuelo.php';
    ?>
    
<?php
require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = $_POST["modelo"];
    $capacidad = $_POST["capacidad"];
    $filas = $_POST["filas"];
    $columnas = $_POST["columnas"];

    $sql = "INSERT INTO Avion (Modelo, Capacidad, Filas, Columnas) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("siii", $modelo, $capacidad, $filas, $columnas);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: 'Avión agregado exitosamente.',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'agregar_avion.php';
                }
            });
        </script>";
    } else {
        $error = $stmt->error;
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Error: $error',
                icon: 'error'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'agregar_avion.php';
                }
            });
        </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
</body>
</html>
