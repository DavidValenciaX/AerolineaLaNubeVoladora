document.addEventListener("DOMContentLoaded", async () => {
  try {
    // Verificar si el usuario está logueado
    const userInfoResponse = await fetch(
      "http://localhost/user-service/get_user_info.php"
    );
    if (userInfoResponse.status === 401) {
      window.location.href = "login.html";
      return;
    }

    const userData = await userInfoResponse.json();
    document.getElementById(
      "welcome"
    ).innerText = `Bienvenido, ${userData.usuario_nombre}`;

    // Fetch rol info
    const roleResponse = await fetch(
      "http://localhost/user-service/get_info_rol.php"
    );
    const roleData = await roleResponse.json();
    document.getElementById("role").innerText = `Rol: ${roleData.usuario_rol}`;
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      title: "Error",
      text: "Ocurrió un error al procesar la información.",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
  }
});
