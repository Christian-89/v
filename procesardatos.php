<?php
// Conexión a la base de datos (ejemplo con MySQL)
$host = "localhost";
$dbname = "recetas_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

// Obtener los datos del formulario
$sexo = $_POST['sexo'];
$edad = $_POST['edad'];
$peso = $_POST['peso'];
$estatura = $_POST['estatura'];
$actividad = $_POST['actividad'];
$enfermedad = isset($_POST['enfermedad']) ? $_POST['enfermedad'] : '';
$objetivo = isset($_POST['objetivo']) ? $_POST['objetivo'] : '';

// Consultar recetas según las condiciones
$query = "SELECT * FROM recetas WHERE 1=1";

// Filtrar por enfermedades
if ($enfermedad == 'Diabetes') {
    $query .= " AND apto_para_diabetes = 1";
} elseif ($enfermedad == 'Colesterol') {
    $query .= " AND apto_para_colesterol = 1";
} elseif ($enfermedad == 'Hipertensión') {
    $query .= " AND apto_para_hipertension = 1";
}

// Filtrar por objetivo
if (!empty($objetivo)) {
    $query .= " AND objetivo = :objetivo";
}

$stmt = $conn->prepare($query);
if (!empty($objetivo)) {
    $stmt->bindParam(':objetivo', $objetivo);
}
$stmt->execute();
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas Recomendadas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('fondoRegis1.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.7); /* Más transparente */
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            padding: 20px;
            margin: 20px auto;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e65100;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        footer img {
            width: 30px;
            height: 30px;
            cursor: pointer; /* Agregado para que los botones sean clicables */
        }

        /* Adaptar a pantallas más pequeñas (móviles) */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                max-width: 100%;
                height: auto;
            }

            table, th, td {
                font-size: 14px;
            }

            footer img {
                width: 24px;
                height: 24px;
            }
        }

        /* Pantallas más grandes (computadoras) */
        @media (min-width: 769px) {
            .container {
                width: 60%;
                max-width: 800px;
            }
        }
    </style>
    <script>
        // Funcionalidad para los botones del footer
        function goBack() {
            window.history.back(); // Regresa a la página anterior
        }

        function goHome() {
            window.location.href = 'login.php'; // Redirige a la página de login
        }

        function goSettings() {
            window.location.href = 'ajustes.php'; // Redirige a la página de ajustes
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Recetas Recomendadas</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Calorías</th>
                    <th>Objetivo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recetas as $receta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($receta['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($receta['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($receta['calorias']); ?></td>
                    <td><?php echo htmlspecialchars($receta['objetivo']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <footer>
            <img src="atras.png" alt="Atrás" onclick="goBack()">
            <img src="Home.png" alt="Home" onclick="goHome()">
            <img src="ajustes.png" alt="Configuración" onclick="goSettings()">
        </footer>
    </div>
</body>
</html>
