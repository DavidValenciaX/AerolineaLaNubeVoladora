<!DOCTYPE html>
<html>

<head>
    <title>Buscar Vuelo</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
  

    <h2>Buscar Vuelo</h2>
    <form action="descargar_informacion_vuelo.php" method="post">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha"><br><br>

        <label for="origen">Origen:</label>
        <select id="origen" name="origen">
            <option value="">Seleccione el origen</option>
            <?php
            $sql = "SELECT DISTINCT Origen FROM Vuelo";
            $result = $conexion->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["Origen"] . '">' . $row["Origen"] . '</option>';
                }
            } else {
                echo '<option value="">No hay orígenes disponibles</option>';
            }
            ?>
        </select><br><br>

        <label for="destino">Destino:</label>
        <select id="destino" name="destino">
            <option value="">Seleccione el destino</option>
            <?php
            $sql = "SELECT DISTINCT Destino FROM Vuelo";
            $result = $conexion->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["Destino"] . '">' . $row["Destino"] . '</option>';
                }
            } else {
                echo '<option value="">No hay destinos disponibles</option>';
            }
            ?>
        </select><br><br>

        <input type="submit" name="buscar" value="Buscar">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buscar"])) {
        $fecha = $_POST["fecha"];
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];

        $sql = "SELECT v.*, a.Modelo, a.Capacidad - COALESCE(p.pasajeros, 0) AS asientos_disponibles
                FROM Vuelo v
                JOIN Avion a ON v.Avion_ID = a.ID
                LEFT JOIN (
                    SELECT Vuelo_ID, COUNT(*) AS pasajeros
                    FROM PasajeroEnVuelo
                    GROUP BY Vuelo_ID
                ) p ON v.ID = p.Vuelo_ID
                WHERE 1=1";

        if (!empty($fecha)) {
            $sql .= " AND v.Fecha = '$fecha'";
        }
        if (!empty($origen)) {
            $sql .= " AND v.Origen = '$origen'";
        }
        if (!empty($destino)) {
            $sql .= " AND v.Destino = '$destino'";
        }

        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Resultados de la búsqueda:</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Avión</th><th>Fecha</th><th>Origen</th><th>Destino</th><th>Precio</th><th>Hora</th><th>Asientos Disponibles</th><th>Estado</th><th>Acciones</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Modelo"] . "</td>";
                echo "<td>" . $row["Fecha"] . "</td>";
                echo "<td>" . $row["Origen"] . "</td>";
                echo "<td>" . $row["Destino"] . "</td>";
                echo "<td>" . $row["Precio"] . "</td>";
                echo "<td>" . $row["Hora"] . "</td>";
                echo "<td>" . $row["asientos_disponibles"] . "</td>";
                if ($row["asientos_disponibles"] == 0) {
                    echo "<td>El vuelo está lleno</td>";
                } else {
                    echo "<td>Disponible</td>";
                }
                echo "<td><a href='descargar_csv.php?vuelo_id=" . $row["ID"] . "'>Descargar información de vuelo</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron vuelos que coincidan con los criterios de búsqueda.</p>";
        }

        $conexion->close();
    }
    ?>
    <br>
    <a href="../index.html" class="button">Volver al Dashboard</a>
</body>

</html>