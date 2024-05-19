<!DOCTYPE html>
<html>

<head>
    <title>Procesar Registro Vuelo</title>
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
        $avion_id = $_POST["avion_id"];
        $fecha = $_POST["fecha"];
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $precio = $_POST["precio"];
        $hora = $_POST["hora"];

        $sql = "INSERT INTO Vuelo (Avion_ID, Fecha, Origen, Destino, Precio, Hora) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isssds", $avion_id, $fecha, $origen, $destino, $precio, $hora);

        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: 'Vuelo registrado exitosamente.',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'registrar_vuelo.php';
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
                    window.location.href = 'registrar_vuelo.php';
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