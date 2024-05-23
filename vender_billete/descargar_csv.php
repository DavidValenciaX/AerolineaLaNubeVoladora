<?php


// Verifica si se ha pasado un ID de vuelo
if (!isset($_GET['vuelo_id'])) {
    echo "ID de vuelo no especificado.";
    exit();
}

$vuelo_id = $_GET['vuelo_id'];

// Consulta para obtener la información del vuelo
$sqlVuelo = "SELECT Fecha, Hora, Origen, Destino, Precio FROM Vuelo WHERE ID = ?";
$stmtVuelo = $conexion->prepare($sqlVuelo);
$stmtVuelo->bind_param("i", $vuelo_id);
$stmtVuelo->execute();
$resultVuelo = $stmtVuelo->get_result();

if ($resultVuelo->num_rows === 0) {
    echo "Vuelo no encontrado.";
    exit();
}

$vuelo = $resultVuelo->fetch_assoc();

// Consulta para obtener la información de los pasajeros
$sqlPasajeros = "
    SELECT p.Nombre, p.Apellidos, p.Fecha_Nac, p.Sexo, pev.Billete, pev.Asiento
    FROM PasajeroEnVuelo pev
    JOIN Pasajero p ON pev.Pasajero_ID = p.ID
    WHERE pev.Vuelo_ID = ?
";
$stmtPasajeros = $conexion->prepare($sqlPasajeros);
$stmtPasajeros->bind_param("i", $vuelo_id);
$stmtPasajeros->execute();
$resultPasajeros = $stmtPasajeros->get_result();

// Configura las cabeceras para la descarga del archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="informacion_vuelo_' . $vuelo_id . '.csv"');

// Abre el archivo en modo de escritura
$output = fopen('php://output', 'w');

// Añade el BOM al principio del archivo para soportar UTF-8
fwrite($output, "\xEF\xBB\xBF");

// Escribe la cabecera del archivo CSV
fputcsv($output, ['Fecha', 'Hora', 'Origen', 'Destino', 'Precio']);
fputcsv($output, [$vuelo['Fecha'], $vuelo['Hora'], $vuelo['Origen'], $vuelo['Destino'], $vuelo['Precio']]);

// Escribe una línea vacía para separar la información del vuelo de la de los pasajeros
fputcsv($output, []);
fputcsv($output, ['Nombre', 'Apellidos', 'Fecha de Nacimiento', 'Sexo', 'Billete', 'Asiento']);

// Escribe la información de los pasajeros en el archivo CSV
while ($row = $resultPasajeros->fetch_assoc()) {
    fputcsv($output, [
        $row['Nombre'],
        $row['Apellidos'],
        $row['Fecha_Nac'],
        $row['Sexo'],
        $row['Billete'],
        $row['Asiento']
    ]);
}

// Cierra el archivo
fclose($output);

// Cierra las conexiones
$stmtVuelo->close();
$stmtPasajeros->close();
$conexion->close();