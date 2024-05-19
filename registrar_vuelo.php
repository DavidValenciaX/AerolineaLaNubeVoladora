<!DOCTYPE html>
<html>
<head>
    <title>Registrar Vuelo</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Registrar Vuelo</h2>
    <form action="procesar_registro_vuelo.php" method="post">
        <label for="avion_id">Avión:</label>
        <select id="avion_id" name="avion_id" required>
            <option value="">Seleccione un avión</option>
            <?php
            require_once 'conexion.php';
            $sql = "SELECT ID, Modelo FROM Avion";
            $result = $conexion->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row["ID"].'">'.$row["Modelo"].'</option>';
                }
            } else {
                echo '<option value="">No hay aviones disponibles</option>';
            }

            $conexion->close();
            ?>
        </select><br><br>
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>
        <label for="origen">Origen:</label>
        <input type="text" id="origen" name="origen" required><br><br>
        <label for="destino">Destino:</label>
        <input type="text" id="destino" name="destino" required><br><br>
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" required><br><br>
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required><br><br>
        <input type="submit" value="Registrar Vuelo">
    </form>
    <br>
    <a href="dashboard.php" class="button">Volver al Dashboard</a>
</body>
</html>
