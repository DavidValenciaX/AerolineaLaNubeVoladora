<!DOCTYPE html>
<html>
<head>
    <title>Seleccionar Asiento</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .asiento-disponible {
            background-color: lightgreen;
        }
        .asiento-ocupado {
            background-color: lightcoral;
        }
        table {
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Seleccionar Asiento</h2>
    <?php
    require_once 'conexion.php';
    if (isset($_GET["vuelo_id"])) {
        $vuelo_id = $_GET["vuelo_id"];

        // Obtener detalles del vuelo
        $sql = "SELECT v.*, a.Modelo, a.Capacidad, a.Filas, a.Columnas
                FROM Vuelo v
                JOIN Avion a ON v.Avion_ID = a.ID
                WHERE v.ID = $vuelo_id";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $vuelo = $result->fetch_assoc();
            echo "<h3>Vuelo ID: " . $vuelo["ID"] . "</h3>";
            echo "<h3>Avión: " . $vuelo["Modelo"] . "</h3>";
            echo "<h3>Fecha: " . $vuelo["Fecha"] . "</h3>";
            echo "<h3>Origen: " . $vuelo["Origen"] . "</h3>";
            echo "<h3>Destino: " . $vuelo["Destino"] . "</h3>";
            echo "<h3>Hora: " . $vuelo["Hora"] . "</h3>";

            // Obtener asientos ocupados
            $sql = "SELECT Asiento FROM PasajeroEnVuelo WHERE Vuelo_ID = $vuelo_id";
            $result = $conexion->query($sql);
            $asientos_ocupados = array();
            while ($row = $result->fetch_assoc()) {
                $asientos_ocupados[] = $row["Asiento"];
            }

            // Mostrar asientos disponibles en una grilla de checkbox
            echo "<form action='procesar_pago.php' method='post' class='seleccion_asiento'>";
            echo "<input type='hidden' name='vuelo_id' value='$vuelo_id'>";
            echo "<label for='asiento'>Seleccione un asiento:</label><br>";

            echo "<table style='width: 100%;'>";
            for ($i = 1; $i <= $vuelo["Columnas"]; $i++) {
                echo "<tr>";
                for ($j = 1; $j <= $vuelo["Filas"]; $j++) {
                    $asiento = chr(64 + $i) . $j;
                    $checked = in_array($asiento, $asientos_ocupados) ? "disabled" : "";
                    $class = in_array($asiento, $asientos_ocupados) ? "asiento-ocupado" : "asiento-disponible";
                    echo "<td class='$class'><label><input type='radio' name='asiento' value='$asiento' $checked> $asiento</label></td>";
                }
                echo "</tr>";
            }
            echo "</table><br>";

            echo "<label for='nombre'>Nombre:</label>";
            echo "<input type='text' id='nombre' name='nombre' required><br><br>";

            echo "<label for='apellidos'>Apellidos:</label>";
            echo "<input type='text' id='apellidos' name='apellidos' required><br><br>";

            echo "<label for='fecha_nac'>Fecha de Nacimiento:</label>";
            echo "<input type='date' id='fecha_nac' name='fecha_nac' required><br><br>";

            echo "<label for='sexo'>Sexo:</label>";
            echo "<select id='sexo' name='sexo' required>";
            echo "<option value='M'>Masculino</option>";
            echo "<option value='F'>Femenino</option>";
            echo "</select><br><br>";

            echo "<input type='submit' value='Pagar'>";
            echo "</form>";
        } else {
            echo "<p>Vuelo no encontrado.</p>";
        }
    } else {
        echo "<p>ID de vuelo no especificado.</p>";
    }
    ?>
    <br>
    <a href="buscar_vuelo.php" class="button">Volver a Buscar Vuelo</a>
</body>
</html>
