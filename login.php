<?php
session_start();
require_once 'conexion.php';

$response = ["success" => false, "message" => ""];

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
            $response["success"] = true;
            $response["message"] = "Inicio de sesión exitoso.";
        } else {
            $response["message"] = "Contraseña incorrecta.";
        }
    } else {
        $response["message"] = "Usuario no encontrado.";
    }

    // Devuelve la respuesta en formato JSON
    echo json_encode($response);
    exit();
}