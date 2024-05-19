<?php
require_once 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
session_start();
if (!isset($_SESSION["usuario_rol"]) || $_SESSION["usuario_rol"] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST["permisos"] as $rol_ID => $permisos) {
        // Eliminar permisos anteriores del rol
        $sql = "DELETE FROM roles_permisos WHERE rol_ID = $rol_ID";
        $conexion->query($sql);

        // Insertar nuevos permisos del rol
        foreach ($permisos as $permiso_ID) {
            $sql = "INSERT INTO roles_permisos (rol_ID, permiso_ID) VALUES ($rol_ID, $permiso_ID)";
            $conexion->query($sql);
        }
    }
    echo "Permisos actualizados.";
}

$sql_roles = "SELECT * FROM roles";
$result_roles = $conexion->query($sql_roles);

$sql_permisos = "SELECT * FROM permisos";
$result_permisos = $conexion->query($sql_permisos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Permisos</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Tabla de Permisos</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <table>
            <tr>
                <th>Rol</th>
                <?php while ($permiso = $result_permisos->fetch_assoc()) { ?>
                    <th><?php echo $permiso["nombre"]; ?></th>
                <?php } ?>
            </tr>
            <?php while ($rol = $result_roles->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $rol["nombre"]; ?></td>
                    <?php
                    $sql_rol_permisos = "SELECT permiso_ID FROM roles_permisos WHERE rol_ID = " . $rol["ID"];
                    $result_rol_permisos = $conexion->query($sql_rol_permisos);
                    $rol_permisos = array();
                    while ($rol_permiso = $result_rol_permisos->fetch_assoc()) {
                        $rol_permisos[] = $rol_permiso["permiso_ID"];
                    }

                    $result_permisos->data_seek(0);
                    while ($permiso = $result_permisos->fetch_assoc()) {
                        $checked = in_array($permiso["ID"], $rol_permisos) ? "checked" : "";
                        echo "<td><input type='checkbox' name='permisos[" . $rol["ID"] . "][]' value='" . $permiso["ID"] . "' $checked></td>";
                    }
                    ?>
                </tr>
            <?php } ?>
        </table>
        <input type="submit" value="Guardar permisos">
    </form>
    <p><a href="dashboard.php" class="button">Volver al dashboard</a></p>
</body>
</html>