document.addEventListener("DOMContentLoaded", function () {
  fetch("http://localhost/user-service/get-roles.php")
    .then((response) => response.json())
    .then((roles) => {
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
            window.location.href = "login.html";
          } else {
            alert("Error al registrar: " + data.message);
          }
        })
        .catch((error) => console.error("Error:", error));
    });
});
