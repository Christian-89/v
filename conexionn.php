<?php
$servername = "localhost";  // Servidor de la base de datos (puede cambiar dependiendo de tu servidor)
$username = "root";         // Usuario de la base de datos
$password = "";             // Contraseña del usuario (si tienes contraseña, colócala aquí)
$dbname = "datos";       // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    // echo "Conexión exitosa";  // Esto es opcional, para confirmar si la conexión fue exitosa.
}
?>
