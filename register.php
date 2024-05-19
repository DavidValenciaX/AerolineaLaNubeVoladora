<?php
require_once 'conexion.php';

// Función para obtener todos los roles de la base de datos
function obtenerRoles($conexion) {
    $sql = "SELECT ID, nombre FROM roles WHERE nombre != 'Superusuario'";
    $result = $conexion->query($sql);
    $roles = [];
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
    return $roles;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $usuario = $_POST["usuario"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $rol_ID = $_POST["rol"]; // Obtener el rol seleccionado del formulario

    $sql = "INSERT INTO usuarios (nombre, apellido, email, usuario, contrasena, rol_ID) VALUES ('$nombre', '$apellido', '$email', '$usuario', '$contrasena', '$rol_ID')";

    if ($conexion->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}

$roles = obtenerRoles($conexion); // Obtener los roles para el formulario
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Registrar usuario</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Apellido:</label>
        <input type="text" name="apellido" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Usuario:</label>
        <input type="text" name="usuario" required><br>
        <label>Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        <label>Rol:</label>
        <select name="rol" required>
            <?php foreach ($roles as $rol): ?>
                <option value="<?php echo $rol['ID']; ?>"><?php echo $rol['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Registrar usuario">
    </form>
    <a href="dashboard.php" class="button">Volver al Dashboard</a>
</body>
</html>
