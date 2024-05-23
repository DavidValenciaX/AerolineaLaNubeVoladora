document.addEventListener("DOMContentLoaded", function () {
  fetch("http://localhost/user-service/get_roles.php")
    .then((response) => response.json())
    .then((data) => {
      const roles = data.roles;
      const rolSelect = document.getElementById("rol");
      roles.forEach((rol) => {
        const option = document.createElement("option");
        option.value = rol.ID;
        option.textContent = rol.nombre;
        rolSelect.appendChild(option);
      });
    })
    .catch((error) => console.error("Error:", error));

  document
    .getElementById("registerForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = {
        nombre: document.getElementsByName("nombre")[0].value,
        apellido: document.getElementsByName("apellido")[0].value,
        email: document.getElementsByName("email")[0].value,
        usuario: document.getElementsByName("usuario")[0].value,
        contrasena: document.getElementsByName("contrasena")[0].value,
        rol: document.getElementsByName("rol")[0].value,
      };

      fetch("http://localhost/user-service/register.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              title: "¡Registro exitoso!",
              text: "El usuario ha sido registrado exitosamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              window.location.href = "login.html";
            });
          } else {
            Swal.fire({
              title: "Error",
              text: "Error al registrar: " + data.message,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        })
        .catch((error) => console.error("Error:", error));
    });
});
