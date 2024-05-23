<!DOCTYPE html>
<html>

<head>
    <title>Agregar Avión</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php
    require 'validar_permiso_registrar_vuelo.php';
    ?>

    <h2>Agregar Avión</h2>
    <form action="procesar_agregar_avion.php" method="post">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required><br><br>
        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required><br><br>
        <label for="filas">Filas:</label>
        <input type="number" id="filas" name="filas" required><br><br>
        <label for="columnas">Columnas:</label>
        <input type="number" id="columnas" name="columnas" required><br><br>
        <input type="submit" value="Agregar Avión">
    </form>
    <br>
    <a href="../index.html" class="button">Volver al Dashboard</a>
</body>

</html>