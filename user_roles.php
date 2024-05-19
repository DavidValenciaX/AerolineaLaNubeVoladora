<?php
require_once 'conexion.php';

session_start();
if (!isset($_SESSION["usuario_ID"]) || $_SESSION["usuario_rol"] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST["user_roles"] as $usuario_ID => $rol_ID) {
        $sql = "UPDATE usuarios SET rol_ID = $rol_ID WHERE ID = $usuario_ID";
        $conexion->query($sql);
    }
    echo "Roles de usuario actualizados exitosamente.";
}

$sql_usuarios = "SELECT u.ID, u.nombre, u.apellido, u.email, r.nombre AS rol 
                 FROM usuarios u
                 INNER JOIN roles r ON u.rol_ID = r.ID";
$result_usuarios = $conexion->query($sql_usuarios);

$sql_roles = "SELECT * FROM roles";
$result_roles = $conexion->query($sql_roles);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asignar Roles a Usuarios</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Asignar Roles a Usuarios</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <table>
            <tr>
                <th>Usuario</th>
                <th>Rol Actual</th>
                <th>Nuevo Rol</th>
            </tr>
            <?php while ($usuario = $result_usuarios->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $usuario["nombre"] . " " . $usuario["apellido"] . " (" . $usuario["email"] . ")"; ?></td>
                    <td><?php echo $usuario["rol"]; ?></td>
                    <td>
                        <select name="user_roles[<?php echo $usuario["ID"]; ?>]">
                            <?php
                            $result_roles->data_seek(0);
                            while ($rol = $result_roles->fetch_assoc()) {
                                $selected = ($usuario["rol"] == $rol["nombre"]) ? "selected" : "";
                                echo "<option value='" . $rol["ID"] . "' $selected>" . $rol["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <input type="submit" value="Guardar Roles">
    </form>
    <p><a href="dashboard.php" class="button">Volver al dashboard</a></p>
</body>
</html>