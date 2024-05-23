document.addEventListener("DOMContentLoaded", () => {
  // Verificar si el usuario está logueado
  fetch("http://localhost/user-service/get_user_info.php")
    .then((response) => {
      if (response.status === 401) {
        Swal.fire({
          title: "Alerta",
          text: "No has iniciado sesión. Redirigiendo al login...",
          icon: "warning",
          confirmButtonText: "Aceptar",
        }).then(() => {
          window.location.href = "login.html";
        });
      } else {
        return response.json();
      }
    })
    .then((data) => {
      if (data) {
        document.getElementById(
          "welcome"
        ).innerText = `Bienvenido, ${data.usuario_nombre}`;

        // Fetch rol info
        fetch("http://localhost/user-service/get_info_rol.php")
          .then((response) => response.json())
          .then((roleData) => {
            document.getElementById(
              "role"
            ).innerText = `Rol: ${roleData.usuario_rol}`;

            // Fetch permissions info
            fetch("http://localhost/user-service/get_permissions_by_user.php")
              .then((response) => response.json())
              .then((permissionsData) => {
                const navbar = document.getElementById("navbar");
                let permisosHtml = "";

                // Convertir los valores de permissionsData.permisos a un arreglo
                const permisosArray = Object.values(permissionsData.permisos);

                if (permisosArray.includes("registrar vuelos")) {
                  permisosHtml += `
                    <a href='agregar_avion.html'>Agregar Avión</a>
                    <a href='registrar_vuelo.html'>Registrar Vuelo</a>
                  `;
                }

                if (permisosArray.includes("Buscar Vuelo")) {
                  permisosHtml += `
                    <a href='buscar_vuelo.html'>Buscar Vuelo</a>
                    <a href='buscar_billete.html'>Buscar billete</a>
                    <a href='descargar_informacion_vuelo.html'>Descargar informacion de vuelo</a>
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

                document
                  .getElementById("logoutBtn")
                  .addEventListener("click", () => {
                    fetch("http://localhost/user-service/logout.php", {
                      method: "POST",
                    })
                      .then((response) => response.json())
                      .then((data) => {
                        Swal.fire({
                          title: "Cerrando sesión",
                          icon: "success",
                          text: data.message,
                          showConfirmButton: false,
                          timer: 1000,
                        }).then(() => {
                          window.location.href = "login.html";
                        });
                      });
                  });
              });
          });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Swal.fire({
        title: "Error",
        text: "Ocurrió un error al procesar la información.",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    });
});
