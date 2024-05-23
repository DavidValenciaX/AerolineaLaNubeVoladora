<?php
session_start();
require_once '../conexion.php';

// Función para mostrar alertas con SweetAlert
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

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_ID'])) {
    showAlert("No has iniciado sesión. Redirigiendo al login...", "../login.php");
    exit();
}

// Obtiene el ID del usuario desde la sesión
$usuario_ID = $_SESSION['usuario_ID'];

// Consulta para obtener el rol del usuario
$sql = "SELECT rol_ID FROM usuarios WHERE ID = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rol_ID = $row['rol_ID'];
    // Consulta para verificar si el rol tiene el permiso 'vender billete'
    $sqlPermiso = "SELECT p.nombre 
                       FROM roles_permisos rp 
                       JOIN permisos p ON rp.permiso_ID = p.ID 
                       WHERE rp.rol_ID = ? AND p.nombre = 'registrar vuelos'";
    $stmtPermiso = $conexion->prepare($sqlPermiso);
    $stmtPermiso->bind_param("i", $rol_ID);
    $stmtPermiso->execute();
    $resultPermiso = $stmtPermiso->get_result();

    if ($resultPermiso->num_rows == 0) {
        showAlert("No tienes permiso para acceder a esta página.", "../index.html");
        exit();
    }
} else {
    showAlert("Usuario no encontrado.");
    exit();
}
?>