<?php
// Conexión a la base de datos (ajusta las credenciales según tu entorno)
$host = 'localhost';
$db = 'NutriFit';
$user = 'root';
$pass = '';

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Seleccionar un mensaje aleatorio de la base de datos
$sql = "SELECT mensaje FROM mensajes_bienvenida ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si hay un mensaje, lo mostramos
    $row = $result->fetch_assoc();
    echo nl2br($row['mensaje']); // nl2br convierte las nuevas líneas (\n) en <br> para HTML
} else {
    echo "No hay mensajes disponibles";
}

$conn->close();
?>
