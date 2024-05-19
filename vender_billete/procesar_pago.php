<!DOCTYPE html>
<html>

<head>
    <title>Procesar Pago</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php

    require 'validar_permiso_vender_billete.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $vuelo_id = $_POST["vuelo_id"];

        // Verificar si se seleccionó un asiento
        if (isset($_POST["asiento"])) {
            $asiento = $_POST["asiento"];
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $fecha_nac = $_POST["fecha_nac"];
            $sexo = $_POST["sexo"];

            // Insertar pasajero
            $sql = "INSERT INTO Pasajero (Nombre, Apellidos, Fecha_Nac, Sexo) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $apellidos, $fecha_nac, $sexo);

            if ($stmt->execute()) {
                $pasajero_id = $stmt->insert_id;

                // Generar el código de billete único
                $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellidos, 0, 1));
                $timestamp = time();
                $billete = $vuelo_id . '-' . $pasajero_id . '-' . $iniciales . '-' . $asiento . '-' . $timestamp;

                // Insertar pasajero en vuelo
                $sql = "INSERT INTO PasajeroEnVuelo (Vuelo_ID, Pasajero_ID, Billete, Asiento) VALUES (?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("iiss", $vuelo_id, $pasajero_id, $billete, $asiento);

                if ($stmt->execute()) {
                    echo "<script>
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Pago realizado y asiento reservado exitosamente. Su código de billete es: $billete',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'informacion_billete.php?billete=$billete';                        }
                    });
                </script>";
                } else {
                    $error = $stmt->error;
                    echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al reservar el asiento: $error',
                        icon: 'error'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'seleccionar_asiento.php?vuelo_id=$vuelo_id';
                        }
                    });
                </script>";
                }
            } else {
                $error = $stmt->error;
                echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar al pasajero: $error',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'seleccionar_asiento.php?vuelo_id=$vuelo_id';
                    }
                });
            </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar un asiento.',
                icon: 'error'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'seleccionar_asiento.php?vuelo_id=$vuelo_id';
                }
            });
        </script>";
        }

        $conexion->close();
    }
    ?>
</body>

</html>