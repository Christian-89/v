<?php
// Iniciar sesión
session_start();

// Verificar si se ha iniciado sesión y se tiene el ID del usuario
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirigir al login si no hay sesión activa
    exit();
}

// Datos de conexión a la base de datos
$user = "root";
$pass = "";
$host = "localhost";
$dbname = "nutrifit";

// Conectar a la base de datos
$connection = mysqli_connect($host, $user, $pass, $dbname);

// Verificar si la conexión fue exitosa
if (!$connection) {
    die("No se ha podido conectar con el servidor.");
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Variable para el mensaje
$mensaje = "";

// Si el formulario fue enviado, se procesan los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados desde el formulario
    $correo = mysqli_real_escape_string($connection, $_POST['correo']);
    $contrasena = mysqli_real_escape_string($connection, $_POST['contrasena']);
    $nombre = mysqli_real_escape_string($connection, $_POST['nombre']);
    $edad = mysqli_real_escape_string($connection, $_POST['edad']);

    // Actualizar los datos del usuario en la base de datos
    $consulta_SQL = "UPDATE usuarios SET correo='$correo', contrasena='$contrasena', nombre='$nombre', edad='$edad' WHERE id='$user_id'";

    if (mysqli_query($connection, $consulta_SQL)) {
        $mensaje = "Datos guardados correctamente.";
    } else {
        $mensaje = "Error al guardar los datos.";
    }
}

// Consultar los datos del usuario si no se ha enviado el formulario
$consulta_SQL = "SELECT * FROM usuarios WHERE id = '$user_id'";
$resultado = mysqli_query($connection, $consulta_SQL);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $usuario = mysqli_fetch_assoc($resultado);
} else {
    die("No se pudo obtener la información del usuario.");
}

// Cerrar la conexión
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes NutriFit</title>
    <style>
        /* Reset general */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
        }

        .background-container {
            width: 100vw; /* Ocupa el 100% del ancho del viewport */
            height: 100vh; /* Ocupa el 100% de la altura del viewport */
            background-image: url('Iconos/fondoRegis1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            position: relative; /* Necesario para el posicionamiento absoluto de los elementos */
        }

        .GuardarBTN{
            background-color: #D24D25; /* Color de fondo del botón */
            color: #fff; /* Color del texto */
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px; /* Espacio superior para separar del contenido anterior */
            width: 100%; /* Ancho completo para el botón */
        }

        .container {
            width: 100%;
            max-width: 360px;
            height: 800px;
            display: flex;
            justify-content: center;
            align-items: center;
            
            
        }

        .login-box {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 20px;
            width: 90%;
            max-width: 320px;
            text-align: center;
            margin-top: 200px; /*Alejar el logo y el contenedor*/
            position: relative; /* Necesario para posicionar mensajes de error dentro de los campos */
        }

         /* Estilo para la línea verde y los botones */
         .footer {
            width: 100%;
            position: fixed; /* Fija el footer al final de la pantalla */
            bottom: 0;
            text-align: center;
            padding-bottom: 0px; /* Ajuste del espacio inferior */
        }

        .boton {
            width: 50px; /* Ancho más grande para los botones */
            height: 50px; /* Altura más grande para los botones */
            border: none;
            background-color: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* Posicionamiento relativo dentro de su contenedor */
        }

        .boton img {
            width: 50px; /* Aumenta el tamaño de la imagen dentro del botón */
            height: 50px;
        }

        .botones-container {
            display: flex;
            justify-content: space-evenly; /* Mueve los botones más hacia el centro */
            align-items: flex-end;
            position: absolute;
            bottom: 5px; /* Aumenta este valor para bajar más los botones */
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 360px; /* Límite del contenedor */
        }


        .linea-horizontal {
            width: 100%;
            max-width: 360px;
            height: 60px;
            background-color: #28a745;
            position: absolute;
            bottom: 0; /* Mantén este valor si deseas que la línea verde quede en el fondo */
            left: 50%;
            transform: translateX(-50%);
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .background-container {
        width: 100vw; /* Asegura el ancho completo en móviles */
        height: 100vh; /* Asegura la altura completa en móviles */
    }

            .login-box {
                max-width: 280px; /* Ajuste para pantallas más pequeñas */
            }

            
        }


        .login-box h1 {
            margin-bottom: 20px;
            color: #333;
        }

    </style>
</head>
<body>
    <div class="background-container">
        <div class="login-box">
            <h1>Editar Información</h1>
            <form id="settingsForm" method="POST" action="">

                <div>
                <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electrónico" 
           value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
    <small style="color: red; display: none;" id="correo-error">El correo debe ser de Gmail, por ejemplo: usuario@gmail.com</small>
                </div>


                <div>
                    <input type="password" name="contrasena" placeholder="Ingrese nueva contraseña" required>
                </div>
                <div>
                <div>
                    <input type="text" name="nombre" placeholder="Ingrese su nombre" 
                    value="<?php echo htmlspecialchars($usuario['nombre']); ?>" 
                    required pattern="[A-Za-zÀ-ÿ\u00f1\u00d1\s]+" 
                    title="El nombre solo debe contener letras y espacios">
                    <small style="color: red; display: none;" id="error-message">El nombre de usuario no es válido</small>
                </div>
                </div>
                <div>
                    <input type="number" name="edad" placeholder="Ingrese su edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
                </div>
                <button class="GuardarBTN" type="submit">Guardar Cambios</button>
            </form>

            <!-- Sección para mostrar el mensaje de éxito o error -->
            <?php if (!empty($mensaje)): ?>
                <p style="color: green;"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>
        </div>

        <!-- Nueva sección con la línea horizontal y los botones sobre la línea -->
        <div class="footer">
            <div class="linea-horizontal"></div>
            <div class="botones-container">
                <button class="boton" id="back">
                    <img src="Iconos/atras.png" alt="Icono 1">
                </button>
                <button class="boton" id="home">
                    <img src="Iconos/Home.png" alt="Icono 2">
                </button>
                <button class="boton" id="settings">
                    <img src="Iconos/ajustes.png" alt="Icono 3">
                </button>
            </div>
        </div>
        </div>
    </div>

    <script>
        // Redirigir al login al hacer clic en Home
        document.getElementById('home').addEventListener('click', function() {
            window.location.href = 'inicio.html';
        });

        // Redirigir a nosotros.html al hacer clic en Ajustes
        document.getElementById('settings').addEventListener('click', function() {
            window.location.href = 'Ajustes.php';
        });

        // Redirigir a la página anterior al hacer clic en Atrás
        document.getElementById('back').addEventListener('click', function() {
            window.history.back();
        });


    // Captura el evento de envío del formulario
    document.querySelector('form').addEventListener('submit', function (event) {
        const correoInput = document.getElementById('correo');
        const correoError = document.getElementById('correo-error');
        
        // Validación del dominio de correo Gmail
        const correoValido = correoInput.value.endsWith('@gmail.com');

        if (!correoValido) {
            event.preventDefault();  // Evita el envío si el correo no es válido
            correoError.style.display = 'block';  // Muestra el mensaje de error
        } else {
            correoError.style.display = 'none';  // Oculta el mensaje de error si es válido
        }
    });
    </script>
</body>
</html>
