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

    // Fetch flight data
    const flightResponse = await fetch(
      "http://localhost/flight-service/index.php?action=read_vuelos"
    );
    const flightData = await flightResponse.json();
    const flightList = document.getElementById("flightList");

    // Crear la tabla y agregarla al contenedor
    const table = document.createElement("table");

    flightData.forEach((flight) => {
      const formattedDate = new Date(flight.Fecha).toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${flight.Origen}</td>
        <td>${flight.Destino}</td>
        <td>${formattedDate}</td>
        <td>${flight.Hora}</td>
        <td>$${parseFloat(flight.Precio).toLocaleString("es-ES")}</td>
      `;
      table.appendChild(row);
    });

    // Duplicar los elementos para un scroll continuo
    const tableClone = table.cloneNode(true);
    flightList.appendChild(table);
    flightList.appendChild(tableClone);
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
