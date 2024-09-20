?php
session_start();

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "nutrifit";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el número de control desde el formulario
$control_num = $_POST['control_num'];

// Consulta para verificar si el usuario existe en la base de datos
$sql = "SELECT * FROM usuarios WHERE username='$control_num'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si el usuario es válido, redirige a principal.html
    header("Location: principal.html");
    exit();
} else {
    // Si el usuario no es válido, redirige de nuevo al login con un mensaje de error
    header("Location: login.html?error=1");
    exit();
}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="fotos/logo.jpeg" alt="Logo" class="logo">
            <h2>Bienvenido a NutriBits</h2>
            <form action="login.php" method="POST">
                <div class="input-container">
                    <input type="text" name="control_num" placeholder="Num. De Control" required>
                    <i class="fas fa-user"></i>
                </div>
                <button type="submit">Acceso</button>
            </form>
        </div>
    </div>
</body>
</html>