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

    // Fetch permissions info
    const permissionsResponse = await fetch(
      "http://localhost/user-service/get_permissions_by_user.php"
    );
    const permissionsData = await permissionsResponse.json();
    console.log("Permissions data received:", permissionsData); // Debugging line

    const navbar = document.getElementById("navbar");
    let permisosHtml = "";

    // Convertir los valores de permissionsData.permisos a un arreglo
    const permisosArray = Object.values(permissionsData.permisos);
    console.log("Permissions array:", permisosArray); // Debugging line

    if (permisosArray.includes("registrar vuelos")) {
      permisosHtml += `
        <a href='registrar_vuelos/agregar_avion.html'>Agregar Avión</a>
        <a href='registrar_vuelos/registrar_vuelo.html'>Registrar Vuelo</a>
      `;
    }

    if (permisosArray.includes("vender billete")) {
      permisosHtml += `
        <a href='vender_billete/buscar_vuelo.html'>Buscar Vuelo</a>
        <a href='vender_billete/buscar_billete.html'>Buscar billete</a>
        <a href='vender_billete/descargar_informacion_vuelo.html'>Descargar informacion de vuelo</a>
      `;
    }

    navbar.innerHTML = `
      <a href='index.html'>Dashboard</a>
      ${
        roleData.usuario_rol === "Superusuario"
          ? `
      <a href='register.html'>Registrar Usuarios</a>
      <a href='administracion_roles/permission_table.html'>Ir a la tabla de permisos</a>
      <a href='administracion_roles/asignar_roles.html'>Asignar roles a usuarios</a>
      `
          : ""
      }
      ${permisosHtml}
      <button id='logoutBtn'>Cerrar Sesión</button>
    `;

    document.getElementById("logoutBtn").addEventListener("click", async () => {
      const logoutResponse = await fetch(
        "http://localhost/user-service/logout.php",
        {
          method: "POST",
        }
      );
      const logoutData = await logoutResponse.json();
      await Swal.fire({
        title: "Cerrando sesión",
        icon: "success",
        text: logoutData.message,
        showConfirmButton: false,
        timer: 1000,
      });
      window.location.href = "login.html";
    });
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
