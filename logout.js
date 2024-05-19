document.getElementById("logoutBtn").addEventListener("click", function () {
  fetch("http://localhost/user-service/logout.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = "login.html";
      } else {
        alert("Error al cerrar sesión.");
      }
    })
    .catch((error) => console.error("Error:", error));
});
