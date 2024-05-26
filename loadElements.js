document.addEventListener("DOMContentLoaded", async () => {
  try {
    const userInfoResponse = await fetch(
      "http://localhost/user-service/get_user_info.php"
    );
    if (userInfoResponse.status === 401) {
      window.location.href = "/login.html";
      return;
    }

    const permissionsResponse = await fetch(
      "http://localhost/user-service/get_permissions_by_user.php"
    );
    const permissionsData = await permissionsResponse.json();

    const navbar = document.getElementById("navbar");
    let permisosHtml = "";

    const permisosArray = Object.values(permissionsData.permisos);

    const baseUrl = window.location.origin + `/frontend`;

    if (permisosArray.includes("Asignar permisos")) {
      permisosHtml += `
              <a href='${baseUrl}/administracion_roles/permission_table.html'>Asignar permisos</a>
          `;
    }

    if (permisosArray.includes("Asignar roles")) {
      permisosHtml += `
              <a href='${baseUrl}/administracion_roles/asignar_roles.html'>Asignar roles</a>
          `;
    }

    if (permisosArray.includes("Registrar vuelos")) {
      permisosHtml += `
              <a href='${baseUrl}/registrar_vuelos/agregar_avion.html'>Agregar Avión</a>
              <a href='${baseUrl}/registrar_vuelos/registrar_vuelo.html'>Registrar Vuelo</a>
              <a href='${baseUrl}/registrar_vuelos/descargar_informacion_vuelo.html'>Descargar información de vuelo</a>
          `;
    }

    if (permisosArray.includes("Comprar billete")) {
      permisosHtml += `
              <a href='${baseUrl}/comprar_billete/buscar_vuelo.html'>Buscar Vuelo</a>
              <a href='${baseUrl}/comprar_billete/buscar_billete.html'>Buscar billete</a>
          `;
    }

    navbar.innerHTML = `
          <a href='${baseUrl}/index.html'>Dashboard</a>
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
      window.location.href = `${baseUrl}/login.html`;
    });

    const footer = document.getElementById("footer");
    footer.innerHTML =
      "&copy; 2024 Aerolínea La Nube Voladora. Todos los derechos reservados.";
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
