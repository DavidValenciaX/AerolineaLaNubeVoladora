document
  .getElementById("login-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch("http://localhost/user-service/login.php", {
      method: "POST",
      body: formData,
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.href = "/frontend/index.html";
        } else {
          document.getElementById("error-message").textContent = data.message;
        }
      })
      .catch((error) => {
        document.getElementById("error-message").textContent =
          "Error en la solicitud. Intente de nuevo.";
      });
  });
