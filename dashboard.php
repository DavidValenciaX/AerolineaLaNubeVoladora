<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aerolínea La Nube Voladora Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php
    require_once 'conexion.php';

    session_start();
    function showAlert($message, $redirect = null)
    {
        echo "<script type='text/javascript'>
            Swal.fire({
                title: 'Alerta',
                text: '$message',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed && '$redirect') {
                    window.location.href = '$redirect';
                }
            });
          </script>";
    }

    if (!isset($_SESSION['usuario_ID'])) {
        showAlert("No has iniciado sesión. Redirigiendo al login...", "login.php");
        exit();
    }

    $usuario_ID = $_SESSION["usuario_ID"];
    $usuario_nombre = $_SESSION["usuario_nombre"];
    $usuario_rol = $_SESSION["usuario_rol"];

    // Función para obtener el nombre del rol a partir del ID
    function obtenerNombreRol($rol_ID, $conexion)
    {
        $sql = "SELECT nombre FROM roles WHERE ID = $rol_ID";
        $result = $conexion->query($sql);
        $row = $result->fetch_assoc();
        return $row["nombre"];
    }

    // Función para obtener los permisos de un usuario
    function obtenerPermisosUsuario($usuario_ID, $conexion)
    {
        $sql = "SELECT p.ID, p.nombre FROM permisos p
            INNER JOIN roles_permisos rp ON p.ID = rp.permiso_ID
            INNER JOIN usuarios u ON rp.rol_ID = u.rol_ID
            WHERE u.ID = $usuario_ID";
        $result = $conexion->query($sql);
        $permisos = array();
        while ($row = $result->fetch_assoc()) {
            $permisos[$row["ID"]] = $row["nombre"];
        }
        return $permisos;
    }

    // Verificar permisos del usuario
    $permisos = obtenerPermisosUsuario($usuario_ID, $conexion);

    echo "<nav>";
    echo "<a href='dashboard.php'>Dashboard</a>";
    if ($_SESSION["usuario_rol"] == 1) {
        echo "<a href='register.php'>Registrar Usuarios</a>";
    }
    if (array_key_exists(1, $permisos)) {
        echo "<a href='registrar_vuelos/registrar_vuelo.php'>Registrar Vuelo</a>";
        echo "<a href='registrar_vuelos/agregar_avion.php'>Agregar Avión</a>";
    }
    if (array_key_exists(2, $permisos)) {
        echo "<a href='vender_billete/buscar_vuelo.php'>Buscar Vuelo</a>";
    }
    echo "<a href='logout.php'>Cerrar Sesión</a>";
    echo "</nav>";

    echo "<h1>Bienvenido, $usuario_nombre<br></h1>";
    echo "<h2>Rol: " . obtenerNombreRol($usuario_rol, $conexion) . "<br></h2>";
    ?>

    <footer>
        <p>&copy; 2024 Aerolínea La Nube Voladora. Todos los derechos reservados.</p>
    </footer>
</body>

</html>