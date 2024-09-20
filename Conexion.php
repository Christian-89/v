<?php
header('Content-Type: application/json');

// Datos de conexión a la base de datos
$user = "root";
$pass = "";
$host = "localhost";
$dbname = "nutrifit";

// Conectar a la base de datos
$connection = mysqli_connect($host, $user, $pass, $dbname);

// Verificar si la conexión fue exitosa
if (!$connection) {
    echo json_encode(["success" => false, "message" => "No se ha podido conectar con el servidor."]);
    exit();
}

// Verificar si los datos del formulario han sido enviados y no están vacíos
if (!empty($_POST['correo']) && !empty($_POST['contrasena']) && !empty($_POST['nombre']) && !empty($_POST['edad'])) {
    $email = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    $nombre = $_POST["nombre"];
    $edad = $_POST["edad"];

    // Validaciones
    $errores = [];

    // Validar correo: debe contener "@" y ".com"
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@.+\.com$/', $email)) {
        $errores['correo'] = "El correo debe contener un formato válido, como ejemplo@dominio.com.";
    }

    // Validar contraseña: solo letras y números
    if (!preg_match('/^[a-zA-Z0-9]+$/', $contrasena)) {
        $errores['contrasena'] = "La contraseña solo debe contener letras y números.";
    }

    // Validar nombre: solo letras, espacios, acentos y la letra Ñ
    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
        $errores['nombre'] = "El nombre solo debe contener letras, acentos, espacios y la letra Ñ.";
    }

    // Validar edad: solo valores enteros y mayor que 0
    if (!filter_var($edad, FILTER_VALIDATE_INT) || $edad <= 0) {
        $errores['edad'] = "La edad debe ser un número entero válido y mayor que 0.";
    }

    // Si hay errores, devolver los mensajes y no guardar
    if (!empty($errores)) {
        echo json_encode(["success" => false, "errors" => $errores]);
    } else {
        // Si no hay errores, proceder a sanitizar e insertar los datos
        $correo = mysqli_real_escape_string($connection, $email);
        $contrasena = mysqli_real_escape_string($connection, $contrasena);
        $nombre = mysqli_real_escape_string($connection, $nombre);
        $edad = (int)$edad;

        // Insertar los datos en la base de datos
        $instruccion_SQL = "INSERT INTO usuarios (correo, nombre, contrasena, edad) VALUES ('$correo', '$nombre', '$contrasena', $edad)";
        $resultado = mysqli_query($connection, $instruccion_SQL);

        if ($resultado) {
            echo json_encode(["success" => true, "message" => "Usuario guardado correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar los datos: " . mysqli_error($connection)]);
        }

        // Cerrar la conexión
        mysqli_close($connection);
    }
} else {
    echo json_encode(["success" => false, "message" => "Faltan datos en el formulario."]);
}
?>
