<!DOCTYPE html>
<html>

<head>
    <title>Buscar Billete</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require 'validar_permiso_vender_billete.php'; ?>

    <h2>Buscar Billete</h2>
    <form action="informacion_billete.php" method="get">
        <label for="billete">Número de Billete:</label>
        <input type="text" id="billete" name="billete" required><br><br>
        <input type="submit" name="buscar" value="Buscar">
    </form>

    <br>
    <a href="../index.html" class="button">Volver al Dashboard</a>
</body>

</html>
