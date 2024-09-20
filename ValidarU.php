<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Obtener los datos del formulario
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validar que los datos no estén vacíos
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    exit();
}

error_log("Intento de inicio de sesión: Email: $email, Password: $password");

try {
    // Conectar a la base de datos
    $pdo = new PDO('mysql:host=localhost;dbname=NutriFit', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    error_log("Conexión a la base de datos exitosa");

    // Preparar la consulta para verificar el usuario
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE correo = :email AND contrasena = :password');
    $stmt->execute(['email' => $email, 'password' => $password]);

    // Verificar si se encontró un usuario
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
        error_log("Inicio de sesión exitoso");
    } else {
        echo json_encode(['success' => false, 'error' => 'Credenciales incorrectas']);
        error_log("Credenciales incorrectas");
    }
} catch (PDOException $e) {
    error_log("Error en la base de datos: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
