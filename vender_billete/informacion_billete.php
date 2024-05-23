<!DOCTYPE html>
<html>

<head>
    <title>Información del Billete</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../styles.css">

</head>

<body>
    <?php

    require 'validar_permiso_vender_billete.php';



    if (isset($_GET['billete'])) {
        $billete = $_GET['billete'];

        // Consultar la información del billete
        $sql = "SELECT p.Nombre, p.Apellidos, v.Fecha, v.Hora, v.Origen, v.Destino, a.Modelo, pe.Asiento, pe.Billete 
            FROM PasajeroEnVuelo pe
            INNER JOIN Pasajero p ON pe.Pasajero_ID = p.ID
            INNER JOIN Vuelo v ON pe.Vuelo_ID = v.ID
            INNER JOIN Avion a ON v.Avion_ID = a.ID
            WHERE pe.Billete = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $billete);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='card'>";
            echo "<h1>Información del Billete</h1>";
            echo "<p><strong>Número de Billete:</strong> " . $row["Billete"] . "</p>";
            echo "<p><strong>Nombre:</strong> " . $row["Nombre"] . " " . $row["Apellidos"] . "</p>";
            echo "<p><strong>Fecha de viaje:</strong> " . $row["Fecha"] . "</p>";
            echo "<p><strong>Hora de viaje:</strong> " . $row["Hora"] . "</p>";
            echo "<p><strong>Origen:</strong> " . $row["Origen"] . "</p>";
            echo "<p><strong>Destino:</strong> " . $row["Destino"] . "</p>";
            echo "<p><strong>Avión:</strong> " . $row["Modelo"] . "</p>";
            echo "<p><strong>Asiento:</strong> " . $row["Asiento"] . "</p>";
            echo "</div>";
            echo "<a href='../index.html' class='button'>Volver al Dashboard</a>";
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'No se encontró la información del billete.',
                icon: 'error'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../index.html';
                }
            });
        </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'No se proporcionó un código de billete.',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../index.html';
            }
        });
    </script>";
    }

    $conexion->close();
    ?>
</body>

</html>