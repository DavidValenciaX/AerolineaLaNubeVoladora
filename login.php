<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Protege contra inyección SQL
    $usuario = $conexion->real_escape_string($usuario);

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = $conexion->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row["contrasena"])) {
            $_SESSION["usuario_ID"] = $row["ID"];
            $_SESSION["usuario_nombre"] = $row["nombre"];
            $_SESSION["usuario_rol"] = $row["rol_ID"];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Usuario:</label>
        <input type="text" name="usuario" required><br>
        <label>Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>
