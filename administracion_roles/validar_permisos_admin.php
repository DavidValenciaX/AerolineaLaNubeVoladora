<?php    
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
session_start();
if (!isset($_SESSION['usuario_ID'])) {
    showAlert("No has iniciado sesión. Redirigiendo al login...", "../login.php");
    exit();
}

if ($_SESSION["usuario_rol"] != 1) {
    showAlert("No tienes permiso para acceder a esta página.", "../dashboard.php");
    exit();
}
?>